{set-block scope=global variable=cache_ttl}0{/set-block}
{def $unread=fetch('mygengo','unreadCount',hash(jid,$obj.jid))}
{def $thread=fetch('mygengo','threadCount',hash(jid,$obj.jid))}

<tr>
	<td><a onclick="javascript:$('#mygengo-job-{$obj.jid}').toggle();" href="#">{$obj.slug|wash|extract_left(9)}..</a></td>
	<td>{$obj.status|wash|upword|i18n}</td>
	<td>{$obj.lang_tgt|wash|upword|i18n}</td>
	<td><a href={concat("mygengo/comment/",$obj.jid)|ezurl} >{$thread|wash}{if ne($unread,0)} ({$unread} {"new"|i18n}){/if}</a></td>
	<td>{$obj.atime|l10n("shortdate")}</td>
	<td>
		{if eq($obj.status,"available")}
			<a href={concat("mygengo/cancel/",$obj.jid)|ezurl}>{"Cancel"|i18n}</a>
		{/if}
		{if eq($obj.status,"reviewable")}
			<a href={concat("mygengo/review/",$obj.jid)|ezurl}>{"Review"|i18n}</a>
		{/if}
		{if eq($obj.status,"approved")}
			<a href={concat("mygengo/view/",$obj.jid)|ezurl}>{"View"|i18n}</a>
		{/if}
	</td>
</tr>
<tr id="mygengo-job-{$obj.jid}" style="display: none;">
	<td colspan="6">
		<table class="list">
			<tr><td>{"Job ID"|i18n}</td><td>{$obj.jid|wash}</td>
			<tr><td>{"Summary"|i18n}</td><td>{$obj.slug|wash}</td>
			<tr><td>{"Status"|i18n}</td><td>{$obj.status|wash|upword|i18n}</td>
			<tr><td>{"Tier"|i18n}</td><td>{$obj.tier|wash|upword|i18n}</td>
			<tr><td>{"Comments"|i18n}</td><td>{$thread|wash}{if ne($unread,0)} ({$unread} {"new"|i18n}){/if}</td>
			<tr><td>{"Source language"|i18n}</td><td>{$obj.lang_src|wash|i18n}</td>
			<tr><td>{"Target language"|i18n}</td><td>{$obj.lang_tgt|wash|i18n}</td>
			<tr><td>{"Unit count"|i18n}</td><td>{$obj.unit_count|wash}</td>
			<tr><td>{"Ordered"|i18n}</td><td>{$obj.ctime|l10n("shortdate")}</td>
			<tr><td>{"Updated"|i18n}</td><td>{$obj.atime|l10n("shortdate")}</td>
		</table>
	</td>
</tr>
