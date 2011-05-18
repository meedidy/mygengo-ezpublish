{set-block scope=global variable=cache_ttl}0{/set-block}

<div class='box-header'>
<h1>{concat("Comment thread of job #",$jid)|i18n}</h1>
</div>

{if ne("",$msg)}
<div class="message-feedback">
<h2>{$msg}</h2>
</div>
{/if}

<div class='box-content'>
	{foreach $thread as $comment}
		<div class="block" style="background: {if eq($comment.author,"customer")}#fcfcbc;{else}#f8f8f8;{/if} border: 1px solid #cacaca; margin: 4px; padding: 4px; width: 80%;">
			<p>{$comment.body|wash}</p>
			<hr />
			<div style="font-size: 85%;">{$comment.author|wash|upword|i18n} | {$comment.ctime|l10n("datetime")}</div>
		</div>
	{/foreach}

	<form action={concat("/mygengo/comment/",$jid)|ezurl} method='POST'>
		<fieldset>
		<legend>{"New comment"|i18n}</legend>
			<div class="block">
				<label for="body">{"Body"|i18n}</label>
				<textarea rows="8" class="box" name="body" id="body">{ezhttp('body','POST')}</textarea>
			</div>
			<input type="submit" class="button-default button" value='{"Post comment"|i18n}' />
		</fieldset>
	</form>
</div>
