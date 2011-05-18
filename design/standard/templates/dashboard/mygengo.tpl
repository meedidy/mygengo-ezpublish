{set-block scope=global variable=cache_ttl}0{/set-block}
{ezscript(array('ezjsc::jquery'))} 
<h2>{"myGengo jobs"|i18n}</h2>
<a href={"mygengo/translate"|ezurl}>{"New translation"|i18n}</a> | <a href={"mygengo/poll"|ezurl}>{"Rebuild database"|i18n}</a> | {"Credits: "|i18n}<b>{fetch('mygengo','balance',hash())|wash}</b>
<div class="block">
	<table class="list">
		<thead><tr><th>{"Job"|i18n}</th><th>{"Status"|i18n}</th><th>{"Language"|i18n}</th><th>{"Comments"|i18n}</th><th>{"Updated"|i18n}</th><th>{"Command"|i18n}</th></tr></thead>
		<tbody>
			{foreach fetch('mygengo','allJobs') as $i}
				{include uri="design:mygengo/job.tpl" obj=$i}
			{/foreach}
		</tbody>
	</table>
	<span style="float: right; font-size: small;">powered by <a href="mygengo.com">myGengo</a></span>
</div>
