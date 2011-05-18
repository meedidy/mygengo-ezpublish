<?php

$Module = array(
	'name' => 'mygengo');

$ViewList = array(
	// translation submit form
	'translate' => array(
		'script' => 'translate.php',
		'param' => array(),
		'functions' => array('use')),

	// configuration (api/private)key, auto-approve
	'config' => array(
		'script' => 'config.php',
		'param' => array(),
		'functions' => array('admin')),

	// called by myGengo to push updates
	'callback' => array(
		'script' => 'callback.php',
		'param' => array(),
		'functions' => array('anonymous')),

	// rebuild database
	'poll' => array(
		'script' => 'poll.php',
		'param' => array(),
		'functions' => array('use')),

	// view the comment thread of a job
	'comment' => array(
		'script' => 'comment.php',
		'param' => array('jid'),
		'functions' => array('use')),

	// cancel job with 'avaliaible status
	'cancel' => array(
		'script' => 'cancel.php',
		'param' => array('jid'),
		'functions' => array('use')),

	// approve job
	'review' => array(
		'script' => 'review.php',
		'param' => array('jid'),
		'functions' => array('use')),

	// reject job
	'reject' => array(
		'script' => 'reject.php',
		'param' => array('jid'),
		'functions' => array('use')),

	// correct job
	'correct' => array(
		'script' => 'correct.php',
		'param' => array('jid'),
		'functions' => array('use')),
	
	// view and publish job
	'view' => array(
		'script' => 'view.php',
		'param' => array('jid'),
		'functions' => array('use')));

$FunctionList = array(
	'use' => array(),
	'admin' => array(),
	'anonymous' => array());
?>
