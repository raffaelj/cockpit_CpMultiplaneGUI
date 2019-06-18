<?php

$pageTypeDetection = $app->retrieve('multiplane/pageTypeDetection', 'collections');

$pageTypes = $app->retrieve('multiplane/pageTypes', ['page', 'post']);

?>

<div id="multiplane_sidebar">

    <div class="uk-panel uk-panel-box uk-margin">

        <div class="uk-margin">
            <div class="uk-margin-small-top">
                <field-boolean bind="entry.published" label="@lang('Published')"></field-boolean>
            </div>
        </div>
        
        @if($pageTypeDetection == 'type')
        <div class="uk-margin-small-top">
            <label class="uk-text-small">@lang('Type')</label>
            <span class="uk-hidden">{ entry.type = entry.type || 'page' }</span>
            <select bind="entry.type" bind-event="input">
                @foreach($pageTypes as $type)
                <option value="{{ $type }}">{{ $type }}</option>
                @endforeach
            </select>
        </div>
        @endif

    </div>

    <div class="uk-panel uk-panel-box uk-margin">

        <div class="uk-margin">
            <label class="uk-text-small">@lang('Startpage')</label>
            <span class="uk-text-small"> (To do: check, if other page is startpage)</span>
            <div class="uk-margin-small-top">
                <field-boolean bind="entry.startpage"></field-boolean>
            </div>
        </div>

        <div class="uk-margin">
            <label class="uk-text-small">@lang('Navigation')</label>
            <div class="uk-margin-small-top">
                <field-multipleselect bind="entry.nav" options="main, footer"></field-multipleselect>
            </div>
        </div>

        <div class="uk-margin">
            <label class="uk-text-small">@lang('Contact form')</label>
            <span class="uk-hidden">{ entry.contactform = entry.contactform || {} }</span>
            <div class="uk-margin-small-top">
                <field-boolean bind="entry.contactform.active" label="@lang('Contact form')" options="main, footer"></field-boolean>
            </div>
            <div class="uk-margin-small-top" if="{ entry.contactform.active }">
                <label class="uk-text-small">@lang('Id')</label>
                <i class="uk-icon uk-icon-info" title="@lang('If you have multiple forms on the same page, they must have different ids.')" data-uk-tooltip></i>
                <field-text bind="entry.contactform.id"></field-text>
            </div>
        </div>

        <div class="uk-margin">

            <span class="uk-hidden">{ entry.subpagemodule = entry.subpagemodule || {} }</span>

            <label class="uk-text-small">@lang('Subpage Module')</label>

            <div class="uk-margin-small-top">
                <div class="uk-margin-small-top">
                    <field-boolean bind="entry.subpagemodule.active"></field-boolean>
                </div>
            </div>
            
            @if($pageTypeDetection == 'collections')
            <div class="uk-margin-small-top" if="{ entry.subpagemodule.active }">
                <label class="uk-text-small">@lang('Collection')</label>
                <field-text bind="entry.subpagemodule.collection"></field-text>
            </div>

            <div class="uk-margin-small-top" if="{ entry.subpagemodule.active }">
                <label class="uk-text-small">@lang('Route')</label>
                <field-text bind="entry.subpagemodule.route{lang ? '_'+lang : ''}"></field-text>
            </div>

            <div class="uk-margin-small-top" if="{ entry.subpagemodule.active }">
                <div class="uk-panel uk-panel-box">
                    <div class="uk-margin-small-top">
                        <label class="uk-text-small">@lang('Limit')</label>
                        <field-text bind="entry.subpagemodule.limit"></field-text>
                    </div>
                    <div class="uk-margin-small-top">
                        <field-boolean bind="entry.subpagemodule.sort" label="@lang('Reverse order')"></field-boolean>
                        <i class="uk-icon uk-icon-info" title="@lang('Default: Display latest entries first')" data-uk-tooltip></i>
                    </div>
                    <div class="uk-margin-small-top">
                        <field-boolean bind="entry.subpagemodule.pagination" label="@lang('Pagination')"></field-boolean>
                    </div>
                </div>
            </div>

            @elseif($pageTypeDetection == 'type')
            <div class="uk-margin-small-top" if="{ entry.subpagemodule.active }">
                <label class="uk-text-small">@lang('Type')</label>
                <field-text bind="entry.subpagemodule.type"></field-text>
            </div>
            @endif

        </div>

        <div class="uk-margin">
            <div class="uk-margin-small-top">
                <field-boolean bind="entry.privacypage" label="@lang('is privacy notice')"></field-boolean>
                <i class="uk-icon uk-icon-info" title="@lang('Enable, if this is your privacy page. This info is needed to link to this page from dynamic content, e. g. cookie banner or contact form.')" data-uk-tooltip></i>
            </div>
        </div>

    </div>

</div>

<script>
    // Multiplane sidebar to top
    App.$('#multiplane_sidebar').prependTo(App.$('#multiplane_sidebar').parent());
</script>
