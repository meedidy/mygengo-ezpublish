<div class='box-header'>
<h1>{"Approve job"|i18n}</h1>
</div>

{if ne("",$error)}
<div class="message-error">
<h2>{$error|wash}</h2>
</div>
{/if}


{if ne("",$msg)}
<div class="message-feedback">
<h2>{$msg|wash}</h2>
</div>
{/if}

<div class='box-content'>
	<div class="block">
		<h2>{"Original text"|i18n}</h2>
		<p>{$job.body_src|wash}</p>
	</div>
	
	<div class="block">
		<h2>{"Translation"|i18n}</h2>
		<img src="{$preview-img|wash}"></img>
	</div>

	<form action={concat("/mygengo/review/",$job.jid)|ezurl} method='POST'>
		<div class="block">
			<label for="rating">{"Rating"|i18n}</label>
			<input type="radio" value="1" name="rating">{"1 Bad"|i18n}</input>
			<input type="radio" value="2" name="rating">2</input>
			<input type="radio" value="3" name="rating" checked="checked">3</input>
			<input type="radio" value="4" name="rating">4</input>
			<input type="radio" value="5" name="rating">{"5 Great"|i18n}</input>
		</div>

		<div class="block">
			<label for="for_trans">{"Feedback for original translator"|i18n}</label>
			<textarea name="for_trans" rows="8" cols="80">{ezhttp('for_trans','POST')}</textarea>
		</div>

		<div class="block">
			<label for="for_mygengo">{"Feedback for myGengo"|i18n}</label>
			<textarea name="for_mygengo" rows="8" cols="80">{ezhttp('for_mygengo','POST')}</textarea>
		</div>

		<div class="block">
			<label for="public">{"Can myGengo use this translation publicly in his examples?"|i18n}</label>
			<input type="checkbox" name="public" />{"Yes, you can use this translation as a public example of myGengo\'s service."|i18n}</input>
		</div>

		<span style="float: right;">
			<a href={concat("/mygengo/reject/",$job.jid)|ezurl}>{"Reject"|i18n}</a>
			&nbsp;|&nbsp;
			<a href={concat("/mygengo/correct/",$job.jid)|ezurl}>{"Request corrections"|i18n}</a>
		</span>
		<input type="submit" class="button-default button" value='{"Submit rejection"|i18n}' />
	</form>
</div>
