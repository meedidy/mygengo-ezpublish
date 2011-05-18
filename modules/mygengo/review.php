<?php

require_once('kernel/common/template.php');

$tpl = templateInit();
$api = new MyGengoApi();

$jid = $Params['Parameters'][0];
$job = MyGengoJob::fetch($jid);

if(count($job) > 0)
	$tpl->setVariable('job',$job[0]);

$tpl->setVariable('preview-img',$api->preview($jid));

$http =& eZHTTPTool::instance();

if($http->hasPostVariable('rating'))
{
	$api = new MyGengoApi();

	try
	{
		if($api->approve($jid,
										 $http->postVariable('rating','3'),
										 $http->postVariable('for_trans',''),
										 $http->postVariable('for_mygengo',''),
										 $http->hasPostVariable('public') ? 1 : 0))
		{
			
			$tpl->setVariable('msg',ezpI18n::tr('mygengo','Job approved'));
		}
	}
	catch(Exception $e)
	{
		$tpl->setVariable('error',ezpI18n::tr('mygengo','Approve request failed: ' . $e));
	}
}


$Result = array(
	'content' => $tpl->fetch('design:mygengo/review.tpl'),
	'path' => array(
		array(
			'url' => 'mygengo',
			'text' => 'myGengo'),
		array(
			'url' => false,
			'text' => ezpI18n::tr('mygengo','Review'))));
?>
