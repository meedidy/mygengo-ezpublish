<?php

require_once('kernel/common/template.php');

$tpl = templateInit();
$jid = $Params['Parameters'][0];
$tpl->setVariable('jid',$jid);


$http =& eZHTTPTool::instance();
if($http->hasPostVariable('comment'))
{
	$api = new MyGengoApi();
	if($api->correct($jid,$http->postVariable('comment','')))
		$tpl->setVariable('msg','Correction request sent');
	else
		$tpl->setVariable('msg','Correction request failed');
}

$Result = array(
	'content' => $tpl->fetch('design:mygengo/correct.tpl'),
	'path' => array(
		array(
			'url' => 'mygengo',
			'text' => 'myGengo'),
		array(
			'url' => false,
			'text' => ezpI18n::tr('mygengo','Correct'))));
?>
