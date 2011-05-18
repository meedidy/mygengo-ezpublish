<?php

$http =& eZHTTPTool::instance();

if($http->hasPostVariable('job'))
{
	$json = json_decode($http->postVariable('job',''));
	$job = MyGengoJob::fetch($json->job_id);
	if(count($job) == 0)
		$job = new MyGengoJob();
	else
		$job = $job[0];

	$job->from_json($json);
	$job->sync();
}

if($http->postVariable('comment','') != '')
{
	// mygengo doesn't send author field
	// WORKAROUND is to recheck the whole thread
	$api = new MyGengoApi();
	$post = json_decode($http->postVariable('comment',''));
	foreach($api->get_comments($post->job_id) as $c)
	{
		$cmnt = MyGengoComment::ctime($post->job_id,$c->ctime);
		if(count($cmnt) == 0)
			$cmnt = new MyGengoComment();
		else
			$cmnt = $cmnt[0];

		$cmnt->from_json($c,$post->job_id);
		$cmnt->sync();
	}
}


$Result = array(
	'content' => '<http><body>nothing to see here</body></html>',
	'pagelayout' => false,
	'path' => array());
?>
