<?php

include_once("classes/ezinifile.php");

class MyGengoConfig
{
	private $ini;

	function __construct()
	{	
		$this->ini = eZINI::instance('mygengo.ini');
	}

	function MyGengoConfig()
	{
		return;
	}

	private function setting($g,$k,$v = "")
	{
		if($v === "")
		{
			$ret = "";
			$this->ini->assign($g,$k,&$ret);
			return $ret;
		}
		else
		{
			$this->ini->setVariable($g,$k,$v);
			$this->ini->save();
			return $this->setting($g,$k);
		}
	}

	function apiKey($v = "")
	{
		return $this->setting('General','ApiKey',$v);
	}

	function tplApiKey($v = "")
	{
		return array('result' => $this->apiKey($v));
	}

	function privateKey($v = "")
	{
		return $this->setting('General','PrivateKey',$v);
	}
	
	function tplPrivateKey($v = "")
	{
		return array('result' => $this->privateKey($v));
	}

	function autoApprove($v = "")
	{
		return $this->setting('General','AutoApprove',$v);
	}

	function tplAutoApprove($v = "")
	{
		return array('result' => $this->autoApprove($v));
	}

}

