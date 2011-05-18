<?php

require_once('kernel/common/template.php');

$msg = array();

// kill db
foreach(MyGengoJob::all() as $job)
{
	foreach(MyGengoComment::thread($job->jid) as $cmnt)
		$cmnt->remove();
	$job->remove();
	$msg[] = sprintf(ezpI18n::tr('mygengo','Removed job #%s'),$job->jid);
}

$api = new MyGengoApi();
$langs = $api->languages();

// fetch jobs and their comment threads
foreach($api->jobs() as $job)
{
	// job
	$full = $api->get_job($job->job_id);
	$jrow = new MyGengoJob();
	$jrow->from_json($full);
	$jrow->setAttribute('lang_src',$langs[$jrow->lc_src]->language);
	$jrow->setAttribute('lang_tgt',$langs[$jrow->lc_tgt]->language);
	$jrow->sync();

	// comments
	$cmnts = $api->get_comments($job->job_id);
	foreach($cmnts as &$c)
	{
		$crow = new MyGengoComment();
		$crow->from_json($c,$job->job_id);
		$crow->sync();
	}

	$msg[] = sprintf(ezpI18n::tr('mygengo','Merged job #%s (%s), %d comments'),$full->job_id,ucwords($full->status),count($cmnts));
}

$tpl = templateInit();
$tpl->setVariable('msg',$msg);

$Result = array(
	'content' => $tpl->fetch('design:mygengo/poll.tpl'),
	'path' => array(
		array(
			'url' => 'mygengo',
			'text' => 'myGengo'),
		array(
			'url' => false,
			'text' => ezpI18n::tr('mygengo','Poll'))));
?>
