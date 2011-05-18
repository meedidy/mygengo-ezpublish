{if ne("",$msg)}
<div class="message-feedback">
<h2>{$msg|wash}</h2>
</div>
{/if}

<div class='box-header'>
<h1>{"myGengo account settings"|i18n}</h1>
</div>

<div class='box-content'>
<form action={"/mygengo/config"|ezurl(,'full')} method='POST'>

	<fieldset>
	<legend>{"Credentials"|i18n}</legend>
		<div class="block">
			<label for="apikey">{"API Key: "|i18n}</label>
			<input type="text" class="box" name="apikey" value="{fetch('mygengo','apiKey',hash())}" />
		</div>
		
		<div class="block">
			<label for="privatekey">{"Private Key: "|i18n}</label>
			<input type="text" class="box" name="privatekey" value="{fetch('mygengo','privateKey',hash())}" />
		</div>
	</fieldset>

	<fieldset>
	<legend>{"Preferences"|i18n}</legend>
		<div class="block">
			<input type="checkbox" name="autoapprove" {if eq(fetch('mygengo','autoApprove',hash()),"true")}checked="checked" {/if}>{"Auto approve"|i18n}</input>
		</div>
	</fieldset>

	<input type="submit" class="button-default button" value='{"Save settings"|i18n}' />
</form>
</div>
