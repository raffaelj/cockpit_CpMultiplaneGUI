
<style>
    field-boolean.small {
        font-size: .75em;
        line-height: 1.5em;
    }
    field-boolean.small .uk-form-switch input[type="checkbox"] + label > span {
       font-size: 14px;
    }
    #multiplane_sidebar .uk-form-switch input[type="checkbox"]:not(:focus) + label:before {
        box-shadow: 0 0 1px rgba(0,0,0,0.5);
    }
    .label-left .uk-form-switch input[type="checkbox"] + label {
        padding-left: 0;
        padding-right: 3.5em;
    }
    .label-left .uk-form-switch input[type="checkbox"] + label:before {
        left: auto;
        right: 0;
    }
    .label-left .uk-form-switch input[type="checkbox"] + label:after {
        left: auto;
        right: 1.75em;
    }
</style>

<div id="multiplane_sidebar">

    <div class="uk-panel uk-margin">

        <div class="uk-margin">
            <div class="uk-margin-small-top">
                <field-boolean bind="entry.{{ $fieldNames['published'] }}" label="@lang('Published')"></field-boolean>
            </div>
        </div>

    </div>

</div>

<script>

    // move Multiplane sidebar to top
    this.on('mount', function() { // moves on mount
        var sidebar = App.$('#multiplane_sidebar');
        sidebar.prependTo(sidebar.parent());
        delete sidebar;
    });
</script>
