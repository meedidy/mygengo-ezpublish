<div class='box-header'>
<h1>{"Reject job"|i18n}</h1>
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
		<p>{"Please use rejections sparingly, and only as a last resort.<br/>
		If you're not happy with a translation, you can reject and cancel the job. However, before you receive your full refund, the myGengo Quality Control team will review your request and determine whether or not it was a fair rejection. You also have the option to pass the translation along to another translator if you don't want to cancel the job.<br/>
		We're people too (and so are our translators). So please try to explain things as calmly as possible if things go wrong - as the Beatles say 'We can work it out' :)<br/>
		Please read the <a href='http://mygengo.com/help/faqs'>FAQ</a> for more informations."|i18n}</p>
	</div>

	<form action={concat("/mygengo/reject/",$job.jid)|ezurl} method='POST'>
		<div class="block">
			<label for="follow_up">{"Would you like to cancel the translation?"|i18n}</label>
			<input type="radio" value="cancel" name="follow_up" >{"Yes, please cancel and refund me"|i18n}</input>
			<input type="radio" value="requeue" name="follow_up" checked="checked" >{"No, please have another translator finish the job"|i18n}</input>
		</div>

		<div class="block">
			<label for="reason">{"Rejection reason"|i18n}</label>
			<input type="radio" value="quality" name="reason"  checked="checked" >{"Poor quality of the translation"|i18n}</input>
			<input type="radio" value="incomplete" name="reason">{"Missing or incomplete translation"|i18n}</input>
			<input type="radio" value="other" name="reason">{"Other (please describe below)"|i18n}</input>
		</div>

		<div class="block">
			<label for="comment">{"Feedback for original translator"|i18n}</label>
			<textarea name="comment" rows="8" cols="80">{ezhttp('comment','POST')}</textarea>
		</div>

		<div class="block">
			<img src={$job.captcha_url|wash} />
		</div>

		<div class="block">
			<label for="captcha">{"Please enter the text above (to confirm you\'re human)"|i18n}</label>
			<input type="text" name="captcha" />
		</div>

		<input type="submit" class="button-default button" value='{"Submit rejection"|i18n}' />
	</form>
</div>
