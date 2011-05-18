<?php

require_once('kernel/common/template.php');

$tpl = templateInit();
$jid = $Params['Parameters'][0];
$api = new MyGengoApi();

if($api->cancel($jid))
{
	$j = MyGengoJob::fetch($jid);

	if(count($j) > 0)
	{
		$j = $j[0];
		$j->from_json($api->get_job($jid));
		$j->sync();
		$tpl->setVariable('msg',ezpI18n::tr('mygengo',sprintf('Job #%s canceled',$jid)));
	}
}
else
	$tpl->setVariable('error',ezpI18n::tr('mygengo','Failed to cancel job'));

$Result = array(
	'content' => $tpl->fetch('design:mygengo/cancel.tpl'),
	'path' => array(
		array(
			'url' => 'mygengo',
			'text' => 'myGengo'),
		array(
			'url' => false,
			'text' => ezpI18n::tr('mygengo','Cancel'))));
?>
