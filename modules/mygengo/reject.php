<?php

require_once('kernel/common/template.php');

$tpl = templateInit();
$jid = $Params['Parameters'][0];
$api = new MyGengoApi();

$http =& eZHTTPTool::instance();

if($http->hasPostVariable('captcha'))
{
	if($api->reject($jid,
									$http->postVariable('follow_up','requeue'),
									$http->postVariable('reason','quality'),
									$http->postVariable('comment',''),
									$http->postVariable('captcha','')))
		$tpl->setVariable('msg','Rejection submitted');
	else
		$tpl->setVariable('error','Rejection failed');		
}

$job = MyGengoJob::fetch($jid);
if(count($job) > 0)
	$tpl->setVariable('job',$job[0]);

$Result = array(
	'content' => $tpl->fetch('design:mygengo/reject.tpl'),
	'path' => array(
		array(
			'url' => 'mygengo',
			'text' => 'myGengo'),
		array(
			'url' => false,
			'text' => ezpI18n::tr('mygengo','Reject'))));
?>
