<?php
if (!defined(ERROR_MISSING_HOST))
	define(ERROR_MISSING_HOST, "Did you forgot to specify the SolusVM host?");
if (!defined(ERROR_MISSING_FIELDS))
	define(ERROR_MISSING_FIELDS, "Did you forgot to pass the API key and secret?");
if (!defined(ERROR_MISSING_APIKEY))
	define(ERROR_MISSING_APIKEY, "Did you forgot to pass the API key?");
if (!defined(ERROR_MISSING_APISECRET))
	define(ERROR_MISSING_APISECRET, "Did you forgot to pass the API secret?");

class SolusVM_API {
	private $key 					= "";
	private $hash 					= "";
	private $url					= "";
	private $errors					= array();
	
	public function __construct($host='', $key='', $hash='') {
		if (trim($host == '') && trim($key == '') && trim($hash)=='') {
			$this->setError(ERROR_MISSING_FIELDS);
		} else if (trim($host == '')) {
			$this->setError(ERROR_MISSING_HOST);	
		} else if (trim($key == '')) {
			$this->setError(ERROR_MISSING_APIKEY);
		} else if (trim($hash == '')) {
			$this->setError(ERROR_MISSING_APISECRET);
		} else {
			$this->key 	= $key;
			$this->hash	= $hash;
			$this->url	= "https://".$host.'/api/client/command.php';
			$this->clearAllErrors();	
		}
	}
	
	private function setError($error) {
		if (trim($error)!='' && !in_array(ERROR_MISSING_FIELDS, $this->errors)) {
			$this->errors[] = $error;
		}
	}
	
	public function isError() {
		if (count($this->errors)>0)	{
			return true;
		} else {
			return false;	
		}
	}
	
	private function clearAllErrors() {
		$this->errors = array();
	}
	
	private function load($action) {
		
		$postfields = array();
		$postfields["key"] 		= $this->key;
		$postfields["hash"] 	= $this->hash;
		$postfields["action"] 	= $action;
		
		// Send the query to the solusvm master
		$ch = curl_init($this->url) or die('curl not enabled');
		curl_setopt($ch, CURLOPT_POST, 0);
		curl_setopt($ch, CURLOPT_TIMEOUT, 20);
		curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array( 'Expect:' ) );
		curl_setopt($ch, CURLOPT_VERBOSE, false);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
		$data = curl_exec($ch);
		curl_close($ch);
		
		// Parse the returned data and build an array
		 
		preg_match_all('/<(.*?)>([^<]+)<\/\\1>/i', $data, $match);
		$result = array();
		foreach ($match[1] as $x => $y) {
			$result[$y] = $match[2][$x];
		}
		
		return $data;
	}
	
	public function boot() {
		if ($this->isError()==false) {
			return $this->load("boot");
		}
	}
	
	public function reboot() {
		if ($this->isError()==false) {
			return $this->load("reboot");
		}
	}
	
	public function shutdown() {
		if ($this->isError()==false) {
			return $this->load("shutdown");
		}
	}
	
	public function status() {
		if ($this->isError()==false) {
			return $this->load("status");
		}
	}
	
	public function info() {
		if ($this->isError()==false) {
			return $this->load("info");
		} else {
			return $this->errors;	
		}
	}
	
    // candidate for deletion
	public function debug() {
		$tmp = array();
		if ($this->isError() == false) {
			$tmp[] = "Errors = No";	
		} else {
			$tmp[] = "Errors = Yes";		
		}
		
		$tmp[] = "URL = ".$this->url;
		$tmp[] = "KEY = ".$this->key;
		$tmp[] = "HASH = ".$this->hash;
		
		echo "<hr />";
		echo implode("<br \>", $tmp);
		echo "<br /><br />";
	}
}