<?php

require_once('kernel/common/template.php');

$tpl = templateInit();

$jid = $Params['Parameters'][0];
$job = MyGengoJob::fetch($jid);
$api = new MyGengoApi();

if(count($job) > 0)
	$job = $job[0];
else
	$job = null;

$tpl->setVariable('job',$job);
$tpl->setVariable('feedback',(array)$api->feedback($jid));


$http =& eZHTTPTool::instance();
$Result = array(
	'path' => array(
		array(
			'url' => 'mygengo',
			'text' => 'myGengo'),
		array(
			'url' => false,
			'text' => ezpI18n::tr('mygengo','View'))));

// from node selector?
if($http->hasPostVariable('SelectButton'))
{
	$oid = $http->postVariable('SelectedObjectIDArray','');
	if(count($oid) > 0)
	{
		$loc = $job->locale_tgt()->localeObject()->localeCode();
		$obj = eZContentObject::fetch($oid[0]);
		$obj_dmap = $obj->dataMap();
		$str = '';
		$new = $obj->createNewVersionIn($job->locale_tgt()->localeObject()->localeCode());

		foreach($new->contentObjectAttributes() as $v)
		{
			$tr = $v->translateTo($loc);

			switch($v->contentClassAttributeName())
			{
				case 'Title':
					$tr->fromString($job->slug);
					break;

				case 'Intro':
					$tr->fromString($job->slug);
					break;

				case 'Body':
					$tr->fromString("<section>" . preg_replace("/^(.+)$/","<paragraph>\\1</paragraph>",$job->body_tgt) . "</section>");
					break;

				default:
					$tr = $v->translateTo($loc);					
					$orig = $obj_dmap[$v->contentClassAttributeName()];
					if($orig)
						$tr->fromString($orig->toString());
			}
			
			$tr->store();
			$tr->storeData();
		}

		$new->store();
		$tpl->setVariable('msg',ezpI18n::tr('mygengo','Translation saved as draft'));
	}
	$Result['content'] = $tpl->fetch('design:mygengo/view.tpl');
}
elseif($http->hasPostVariable('BrowseCancelButton'))	
{
	$tpl->setVariable('error','Aborted');		
	$Result['content'] = $tpl->fetch('design:mygengo/view.tpl');
}
elseif($http->hasPostVariable('publish'))
{
	eZContentBrowse::browse(array(
		'action_name' => 'MyGengoBrowseAction',
		'from_page' => "/mygengo/view/" . $jid)
	,$Params['Module']);
}
else
{
	$Result['content'] = $tpl->fetch('design:mygengo/view.tpl');
}

?>
