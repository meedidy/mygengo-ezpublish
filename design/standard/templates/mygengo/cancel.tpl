<div class='box-header'>
<h1>{"Cancel jobs"|i18n}</h1>
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
