<div class='box-header'>
<h1>{"Request corrections"|i18n}</h1>
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
		<p>{"If you find a few small mistakes in the translation - or the translator has not fully responded to your comment requests, please select the request corrections option and explain what changes need to be done.<br/>
				If you think that corrections will not be enough, then please reject the translation and either choose to pass the job onto another translator, or request a refund. If you do choose to reject a job, please give a detailed explanation of the reason. This information will be helpful for us in improving our services, as well as good feedback for the translator.<br/>
				Please read the <a href='http://mygengo.com/help/faqs'>FAQ</a> for more informations."|i18n}</p>
	</div>

	<form action={concat("/mygengo/correct/",$jid)|ezurl} method='POST'>
		<div class="block">
			<label for="comment">{"Use this space to make a formal correction request"|i18n}</label>
			<textarea name="comment" id="comment" rows="9" cols="80"></textarea>
		</div>

		<input type="submit" class="button-default button" value='{"Submit correction request"|i18n}' />
	</form>
</div>
