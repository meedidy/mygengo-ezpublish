<?php

include_once('kernel/common/template.php');
include_once('lib/ezutils/classes/ezhttptool.php');


$tpl =& templateInit();
$http =& eZHTTPTool::instance();

if($http->hasPostVariable('apikey') && $http->hasPostVariable('privatekey'))
{
	$cfg = new MyGengoConfig();
	$cfg->MyGengoConfig();

	$cfg->apiKey($http->postVariable('apikey',''));
	$cfg->privateKey($http->postVariable('privatekey',''));
	if($http->hasPostVariable('autoapprove'))
		$cfg->autoApprove('true');
	else
		$cfg->autoApprove('false');
	$tpl->setVariable('msg', ezpI18n::tr('Settings saved'));		
}

$Result = array(
	'content' => $tpl->fetch('design:mygengo/config.tpl'),
	'path' => array(
		array(
			'url' => 'mygengo',
			'text' => 'myGengo'),
		array(
			'url' => false,
			'text' => ezpI18n::tr('mygengo','Configuration'))));
?>
