<div class="uk-hidden" id="add-mpgetimage-to-editor-formats">
<?php // toolbar option ?>
{ toolbar.indexOf("mpgetimage") === -1 ? toolbar.push("mpgetimage") : "" }
<?php // plugin option ?>
{ format.plugins.mpgetimage = format.plugins.mpgetimage || false }
</div>';
<?php // move div inside riot view to update the variables ?>
<script>App.$("#account-form").prepend(App.$("#add-mpgetimage-to-editor-formats"));</script>
