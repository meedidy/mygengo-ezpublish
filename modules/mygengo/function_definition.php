<?php

$FunctionList = array(
	'apiKey' => array(
		'name' => 'apiKey',
		'operation_types' => array('read'),
		'call_method' => array(
			'class' => 'MyGengoConfig',
			'method' => 'tplApiKey'),
		'parameter_type' => 'standard',
		'parameters' => array(
			array(
				'name' => 'v',
				'type'     => 'string',
				'required' => false,
				'default'  => ""))),

	'privateKey' => array(
		'name' => 'tplPrivateKey',
		'operation_types' => array('read'),
		'call_method' => array(
			'class' => 'MyGengoConfig',
			'method' => 'tplPrivateKey'),
		'parameter_type' => 'standard',
		'parameters' => array(
			array(
				'name' => 'v',
				'type'     => 'string',
				'required' => false,
				'default'  => ""))),
	
	'autoApprove' => array(
		'name' => 'tplAutoApprove',
		'operation_types' => array('read'),
		'call_method' => array(
			'class' => 'MyGengoConfig',
			'method' => 'autoApprove'),
		'parameter_type' => 'standard',
		'parameters' => array(
			array(
				'name' => 'v',
				'type'     => 'integer',
				'required' => false,
				'default'  => ""))),
	
	'balance' => array(
		'name' => 'balance',
		'operation_types' => array('read'),
		'call_method' => array(
			'class' => 'MyGengoApi',
			'method' => 'tplBalance'),
		'parameter_type' => 'standard',
		'parameters' => array()),
	
	'allJobs' => array(
		'name' => 'allJobs',
		'operation_types' => array('read'),
		'call_method' => array(
			'class' => 'MyGengoJobTI',
			'method' => 'all'),
		'parameter_type' => 'standard',
		'parameters' => array()),

	'unreadCount' => array(
		'name' => 'unreadCount',
		'operation_types' => array('read'),
		'call_method' => array(
			'class' => 'MyGengoCommentTI',
			'method' => 'unread_count'),
		'parameter_type' => 'standard',
		'parameters' => array(
			array(
				'name' => 'jid',
				'type'     => 'string',
				'required' => true,
				'default'  => ""))),

	'threadCount' => array(
		'name' => 'threadCount',
		'operation_types' => array('read'),
		'call_method' => array(
			'class' => 'MyGengoCommentTI',
			'method' => 'thread_count'),
		'parameter_type' => 'standard',
		'parameters' => array(
			array(
				'name' => 'jid',
				'type'     => 'string',
				'required' => true,
				'default'  => ""))));
