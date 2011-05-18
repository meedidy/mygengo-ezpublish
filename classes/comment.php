<?php

class MyGengoComment extends eZPersistentObject
{
	public $cid;
	public $jid;
	public $body;
	public $author;
	public $ctime;
	public $new;

	function __construct($row = false)
	{
		parent::__construct($row);
		MyGengoComment::ensure_schema();
	}

	static function ensure_schema()
	{
		$db = eZDB::instance();
		if(count($db->arrayQuery("SHOW TABLES LIKE 'mygengocomment'",array(),eZDBInterface::SERVER_SLAVE)) == 0)
		{
			$db->arrayQuery('DROP TABLE IF EXISTS mygengocomment');
			$db->arrayQuery('CREATE TABLE mygengocomment
											(
												cid 		INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
												jid			INT NOT NULL,
												body		MEDIUMTEXT,
												author	TINYTEXT,
												ctime		INT,
												new			TINYINT
											)');
		}
	}

	static function definition()
	{
		return array(
			'fields' => array(
				'cid' => 'cid',
				'jid' => 'jid',
				'body' => 'body',
				'author' => 'author',
				'ctime' => 'ctime',
				'new' => 'new'),
			'keys' => array('cid', 'ctime'),
			'increment_key' => 'cid',
			'class_name' => 'MyGengoComment',
			'sort' => array('ctime' => 'desc'),
			'name' => 'mygengocomment');
	}

	static function fetch($q)
	{
		MyGengoComment::ensure_schema();
		return eZPersistentObject::fetchObjectList(MyGengoComment::definition(),null,$q);
	}		

	static function thread($jid)
	{
		return MyGengoComment::fetch(array('jid' => $jid));
	}
	
	static function unread($jid)
	{
		return MyGengoComment::fetch(array('jid' => $jid,'new' => '1'));
	}

	static function ctime($jid,$ctime)
	{
		return MyGengoComment::fetch(array('jid' => $jid,'ctime' => $ctime));
	}

	static function read($jid)
	{
		MyGengoComment::ensure_schema();
		$db = eZDB::instance();
		$db->arrayQuery('UPDATE mygengocomment SET new=0 WHERE jid=\'' . $db->escapeString($jid) . '\';');
		return true;
	}

	function from_json($json,$jid)
	{
		$this->setAttribute('jid',$jid);
		$this->setAttribute('body',$json->body);
		$this->setAttribute('author',$json->author);
		$this->setAttribute('ctime',$json->ctime);
		$this->setAttribute('new',1);

		$this->setHasDirtyData(true);
	}
}

class MyGengoCommentTI
{
	function __construct()
	{
		return;
	}

	function MyGengoCommentTI()
	{
		return;
	}

	function thread_count($jid)
	{
		return array('result' => count(MyGengoComment::thread($jid)));
	}

	function unread_count($jid)
	{	
		return array('result' => count(MyGengoComment::unread($jid)));
	}
}

?>
