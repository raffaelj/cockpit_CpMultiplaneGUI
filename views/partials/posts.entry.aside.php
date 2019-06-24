
<div id="multiplane_sidebar">

    <div class="uk-panel uk-panel-box uk-margin">

        <div class="uk-margin">
            <div class="uk-margin-small-top">
                <field-boolean bind="entry.published" label="@lang('Published')"></field-boolean>
            </div>
        </div>

    </div>

</div>

<script>
    // Multiplane sidebar to top
    App.$('#multiplane_sidebar').prependTo(App.$('#multiplane_sidebar').parent());
</script>
