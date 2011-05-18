<div class='box-header'>
<h1>{"View translation"|i18n}</h1>
</div>

{if ne("",$error|wash)}
<div class="message-error">
<h2>{$error}</h2>
</div>
{/if}


{if ne("",$msg|wash)}
<div class="message-feedback">
<h2>{$msg}</h2>
</div>
{/if}

<div class='box-content'>
	<div class="block">
		<h2>{"Original text"|i18n}</h2>
		<p>{$job.body_src|wash}</p>
	</div>

	<div class="block">
		<h2>{"Translation"|i18n}</h2>
		<p>{$job.body_tgt|wash}</p>
	</div>

	<div class="block">
		<h2>{"Feedback"|i18n}</h2>
		<div class="block"><b>{"Rating: "|i18n}</b>{$feedback.rating|wash}<br/></div>
		<div class="block"><b>{"Comment: "|i18n}</b><p>{$feedback.for_translator|wash}</p></div>
	</div>

	<form action={concat("/mygengo/view/",$job.jid)|ezurl} method='POST'>
		<input type="hidden" name="publish" value="yes" />
		<input type="submit" class="button-default button" value='{"Publish translation"|i18n}' />
	</form>
</div>
