<?php

class MyGengoJob extends eZPersistentObject
{
	public $jid;
	public $body_src;
	public $body_tgt;
	public $lc_src;
	public $lc_tgt;
	public $unit_count;
	public $tier;
	public $credits;
	public $status;
	public $captcha_url;
	public $preview_url;
	public $slug;
	public $ctime;
	public $atime;
	public $lang_src;
	public $lang_tgt;

	function __construct($row = false)
	{
		parent::__construct($row);
		MyGengoJob::ensure_schema();
	}

	static function ensure_schema()
	{
		$db = eZDB::instance();
		if(count($db->arrayQuery("SHOW TABLES LIKE 'mygengojob'",array(),eZDBInterface::SERVER_SLAVE)) == 0)
		{
			$db->arrayQuery('
				DROP TABLE IF EXISTS mygengojob');
			$db->arrayQuery('
				CREATE TABLE mygengojob 
				(
					jid 				INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
					body_src 		MEDIUMTEXT NOT NULL,
					body_tgt 		MEDIUMTEXT,
					lc_src 			TINYTEXT,
					lc_tgt 			TINYTEXT,
					lang_src 		TINYTEXT,
					lang_tgt 		TINYTEXT,
					unit_count	INT,
					tier				TINYTEXT,
					credits			FLOAT,
					status			TINYTEXT,
					captcha_url	TEXT,
					preview_url	TEXT,
					slug				TINYTEXT,
					ctime				INT,
					atime				INT
				)');
		}
	}

	static function definition()
	{
		return array(
			'fields' => array(
				'jid' => 'jid',
				'body_src' => 'body_src',
				'body_tgt' => 'body_tgt',
				'lc_src' => 'lc_src',
				'lc_tgt' => 'lc_tgt',
				'unit_count' => 'unit_count',
				'tier' => 'tier',
				'credits' => 'credits',
				'status' => 'status',
				'captcha_url' => 'captcha_url',
				'preview_url' => 'preview_url',
				'slug' => 'slug',
				'ctime' => 'ctime',
				'atime' => 'atime',
				'lang_src' => 'lang_src',
				'lang_tgt' => 'lang_tgt'),
			'keys' => array('jid'),
			'increment_key' => 'jid',
			'class_name' => 'MyGengoJob',
			'sort' => array('ctime' => 'desc'),
			'name' => 'mygengojob');
	}

	static function all()
	{
		MyGengoJob::ensure_schema();
		return eZPersistentObject::fetchObjectList(MyGengoJob::definition());
	}

	static function fetch($jid)
	{
		MyGengoJob::ensure_schema();
		return eZPersistentObject::fetchObjectList(MyGengoJob::definition(),null,array('jid' => $jid));
	}

	function from_json($json)
	{
		$this->setAttribute('jid',$json->job_id);
		$this->setAttribute('body_src',$json->body_src);
		$this->setAttribute('body_tgt',$json->body_tgt);
		$this->setAttribute('lc_src',$json->lc_src);
		$this->setAttribute('lc_tgt',$json->lc_tgt);
		$this->setAttribute('unit_count',$json->unit_count);
		$this->setAttribute('tier',$json->tier);
		$this->setAttribute('credits',$json->credits);
		$this->setAttribute('status',$json->status);
		$this->setAttribute('captcha_url',$json->captcha_url);
		$this->setAttribute('preview_url',$json->preview_url);
		$this->setAttribute('slug',$json->slug);
		$this->setAttribute('ctime',$json->ctime);
		$this->setAttribute('atime',gmdate('U'));

		$this->setHasDirtyData(true);
	}

	function locale_tgt()
	{
		$ini = eZINI::instance('mygengo.ini');
		foreach($ini->variableArray('Languages',$this->lc_tgt) as $l)
		{
			$ret = eZContentLanguage::fetchByLocale($l[0],true);
			if($ret)
				return $ret;
		}
		return false;
	}
}

class MyGengoJobTI
{
	function __construct()
	{
		return;
	}

	function MyGengoJobTI()
	{
		return;
	}

	function all()
	{
		$ret = array('result' => array());
		foreach(MyGengoJob::all() as $i)
			$ret['result'][] = $i;
		return $ret;
	}
}

?>
