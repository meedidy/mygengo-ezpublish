<?php

require_once('kernel/common/template.php');

$tpl = templateInit();

$api = new MyGengoApi();
$langs = $api->languages();

$tpl->setVariable('langs',json_encode($langs));
$tpl->setVariable('default_lang',eZContentObject::defaultLanguage());

$http =& eZHTTPTool::instance();

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
