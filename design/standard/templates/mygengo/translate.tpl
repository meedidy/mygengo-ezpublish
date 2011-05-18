{ezscript(array('ezjsc::jquery'))} 
<script lang="javascript">
{include uri="design:mygengo/script.tpl"}
</script>

<script lang="javascript">
	mygengo = new Object();
	mygengo.langs = eval('({$langs})');
	mygengo.default_lang = '{$default_lang}';
</script>

<div class='box-header'>
<h1>{"Submit content for translation"|i18n}</h1>
</div>

{if ne("",$error)}
<div class="message-error">
<h2>{$error}</h2>
</div>
{/if}


{if ne("",$msg)}
<div class="message-feedback">
<h2>{$msg}</h2>
</div>
{/if}

<noscript>
<div class="message-error">
<h2>{"Please activate JavaScript"|i18n}</h2>
</div>
</noscript>

<div class='box-content'>
<form action={"/mygengo/translate"|ezurl(,'full')} method='POST'>

	<fieldset>
	<legend>{"Content"|i18n}</legend>
		<div class="block">
			<label for="summary">{"Summary"|i18n}</label>
			<input type="text" class="box" id="summary" name="summary" value="{$summary}" />
		</div>

		<div class="block">
			<label for="body">{"Body"|i18n}</label>
			<textarea rows="10" class="box" id="body" name="body">{$body}</textarea>
		</div>

		<div class="block">
			<input type="submit" class="button-default button" name="browse" value='{"Add article"|i18n}' />
		</div>

		<div class="block">
			<label for="src-lang">{"Source language"|i18n}</label>
			<select id="src-lang" name="src-lang">
			</select>
		</div>
	</fieldset>

	<fieldset>
	<legend>{"Target languages"|i18n}</legend>
		<div class="block">
			<label for="tgt-tbl">{"Selected languages"|i18n}</label>
			<table name="tgt-tbl" id="tgt-tbl" class="list">
				<thead><tr>
					<th>{"Language"|i18n}</th>
					<th>{"Tier"|i18n}</th>
					<th>{"Cost"|i18n}</th>
					<th>{"Command"|i18n}</th>
				</tr></thead>
				<tbody><tr class"tgt-tbl-filler"><td colspan="4">{"None"|i18n}</td></tr></tbody>
			</table>
		</div>

		<div class="block">
			<label for="tgt-lang">{"Target language"|i18n}</label>
			<select id="tgt-lang" name="tgt-lang">
			</select>
		</div>

		<div class="block">
			<label for="tgt-tier">{"Tier"|i18n}</label>
			<table style="width: 80%;" name="tgt-tier"><tr>
			<td width="25%"><input type="radio" value="machine" id="tier-machine" name="tgt-tier">{"Machine"|i18n}</input></td>
			<td width="25%"><input type="radio" value="standard" id="tier-standard" name="tgt-tier">{"Standard"|i18n}</input></td>
			<td width="25%"><input type="radio" value="pro" id="tier-pro" name="tgt-tier">{"Pro"|i18n}</input></td>
			<td width="25%"><input type="radio" value="ultra" id="tier-ultra" name="tgt-tier">{"Ultra"|i18n}</input></td>
			</tr></table>
		</div>
		

		<input type="button" class="button" onclick="javascript:add_language();" value='{"Add language"|i18n}' />
	</fieldset>

	<div class="block">
		<label for="comment">{"Comment"|i18n}</label>
		<textarea rows="8" class="box" name="comment" id="comment">{ezhttp('comment','POST')}</textarea>
	</div>

	<div class="block">
		<label for="auto-approve">{"Auto approve"|i18n}</label>
<input type="checkbox" id=auto-approve" name="auto-approve" {if eq(fetch('mygengo','autoApprove',hash()),"true")}checked="checked" {/if}/>
	</div>

	<div class="block">
		<span style="font-size: large;">{"Cost: "|i18n}<span id="cost">0.00</span></span>
	</div>

	<input type="hidden" name="tgt-json" id="tgt-json" value="" />
	<span style="float: right;">powered by <a href="mygengo.com">myGengo</a></span>
	<input type="submit" class="button-default button" name="submit" value='{"Submit request"|i18n}' />
</form>
</div>
