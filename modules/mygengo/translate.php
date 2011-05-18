<?php

require_once('kernel/common/template.php');

$tpl = templateInit();

$api = new MyGengoApi();
$langs = $api->languages();

$tpl->setVariable('langs',json_encode($langs));
$tpl->setVariable('default_lang',eZContentObject::defaultLanguage());

$http =& eZHTTPTool::instance();
$tpl->setVariable('summary',$http->postVariable('summary',''));
$tpl->setVariable('body',$http->postVariable('body',''));

if($http->hasPostVariable('browse'))			// add article
{
	eZContentBrowse::browse(array(
		'action_name' => 'MyGengoBrowseAction',
		'from_page' => "/mygengo/translate/" . $jid)
	,$Params['Module']);

	return;
}
elseif($http->hasPostVariable('submit'))			// submit translation request
{
	if($http->postVariable('summary','') == '')
		$tpl->setVariable('error',ezpI18n::tr('mygengo','No summary given'));
	elseif($http->postVariable('body','') == '')
		$tpl->setVariable('error',ezpI18n::tr('mygengo','No content to translate'));
	elseif($http->postVariable('src-lang','') == '')
		$tpl->setVariable('error',ezpI18n::tr('mygengo','No source langnguage picked'));
	elseif(count(json_decode($http->postVariable('tgt-json','{}'))) == 0)
		$tpl->setVariable('error',ezpI18n::tr('mygengo','No target languages'));
	else
	{
		$tgts = json_decode($http->postVariable('tgt-json','{}'));
		foreach($tgts as &$j)
		{
			try
			{
				$new = $api->post_job($http->postVariable('summary',''),
															$http->postVariable('body',''),
														 	$http->postVariable('src-lang',''),
											 				$j->lc,
											 				$j->tier,
										 					$http->postVariable('comment',''),
										 					$http->hasPostVariable('auto-approve') ? 1 : 0);
				$new->setAttribute('lang_src',$langs[$new->lc_src]->language);
				$new->setAttribute('lang_tgt',$langs[$new->lc_tgt]->language);
				$new->sync();
			}
			catch(Exception $e)
			{
				$tpl->setVariable('error',ezpI18n::tr('mygengo',$e));
			}	
		}

		$tpl->setVariable('msg',ezpI18n::tr('mygengo','Translation submitted'));
	}
}
elseif($http->hasPostVariable('SelectButton'))
{
	$oid = $http->postVariable('SelectedObjectIDArray','');
	if(count($oid) > 0)
	{
		$obj = eZContentObject::fetch($oid[0]);
		$obj_dmap = $obj->dataMap();
		$tpl->setVariable('summary',$obj_dmap['title']->toString());

		// turn xml into readable plaintext - (nested) <paragraph> tags are replaced w/ new lines, all other tags are stripped
		$tmp = strip_tags($obj_dmap['intro']->toString() . $obj_dmap['body']->toString(),'<paragraph>');
		$tmp = preg_replace("/<paragraph.*?>/","\n",$tmp);
		$tmp = preg_replace("/<\/paragraph>/","",$tmp);
		$tpl->setVariable('body',$tmp);
	}
}


$Result = array(
	'content' => $tpl->fetch('design:mygengo/translate.tpl'),
	'path' => array(
		array(
			'url' => 'mygengo',
			'text' => 'myGengo'),
		array(
			'url' => false,
			'text' => ezpI18n::tr('mygengo','Translate'))));
?>
