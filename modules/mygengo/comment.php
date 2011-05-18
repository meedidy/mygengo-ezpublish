<?php

require_once('kernel/common/template.php');

$jid = $Params['Parameters'][0];
$tpl = templateInit();

$http =& eZHTTPTool::instance();

if($http->postVariable('body','') != '')
{
	$api = new MyGengoApi();
	$ret = $api->comment($jid,$http->postVariable('body',''));
	if($ret === true)
		$tpl->setVariable('msg',ezpI18n::tr('mygengo','Comment posted'));
	else
		$tpl->setVariable('error',ezpI18n::tr('mygengo','Comment failed: ' . $ret));
}

MyGengoComment::read($jid);

$tpl->setVariable('jid',$jid);
$tpl->setVariable('thread',MyGengoComment::thread($jid));

$Result = array(
	'content' => $tpl->fetch('design:mygengo/thread.tpl'),
	'path' => array(
		array(
			'url' => 'mygengo',
			'text' => 'myGengo'),
		array(
			'url' => false,
			'text' => ezpI18n::tr('mygengo','Comments'))));
?>
