<?php

$config = $app->module('cpmultiplanegui')->getConfig();

$pageTypeDetection = $config['pageTypeDetection'] ?? 'collections';
$pageTypes         = $config['pageTypes'] ?? ['page', 'post'];

$allowSubpages = true;

?>
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

<div id="multiplane_sidebar" class="uk-form uk-margin">

    <cp-fieldcontainer>
        <div class="uk-clearfix">
            <div class="uk-float-left">
                <label class="uk-text-small">@lang('Navigation')</label>
                <div class="uk-margin-small-top">
                    <field-multipleselect bind="entry.nav" options="{ {main:'@lang('Main')',footer:'@lang('Footer')'} }"></field-multipleselect>
                </div>
            </div>

            <div class="uk-float-right">
                <div class="uk-margin-small-top">
                    <field-boolean bind="entry.published" label="@lang('Published')" class="label-left"></field-boolean>
                </div>

                @if($pageTypeDetection == 'type')
                <div class="uk-margin-top">
                    <label class="uk-text-middle uk-text-small">@lang('Page type')</label>
                    <select bind="entry.type" bind-event="input">
                        <option value=""></option>
                        @foreach($pageTypes as $type)
                        <option value="{{ $type }}">@lang(ucfirst($type))</option>
                        @endforeach
                    </select>
                </div>
                @endif
            </div>

        </div>
    </cp-fieldcontainer>

    <div class="uk-panel uk-margin-small">

        @if(count($forms))
        <div class="uk-margin-small">
{ (entry.contactform = entry.contactform || {}) && '' }
            <cp-fieldcontainer>
                <field-boolean bind="entry.contactform.active" label="@lang('Add form')" class="uk-margin-small uk-text-middle small"></field-boolean>

                <select bind="entry.contactform.form" aria-label="@lang('Select form')" class="uk-margin-small" if="{ entry.contactform.active }">
                    <option value=""></option>
                    @foreach($forms as $form)
                    <option value="{{ $form['name'] }}">{{ $form['label'] }}</option>
                    @endforeach
                </select>
            <cp-fieldcontainer>
        </div>
        @endif

        @if($allowSubpages && (($pageTypeDetection == 'type') || ($pageTypeDetection == 'collections' && count($collections))))
        <div class="uk-margin uk-width-medium-1-1 uk-width-small-1-2">
{ (entry.subpagemodule = !entry.subpagemodule || Array.isArray(entry.subpagemodule) ? {} : entry.subpagemodule) && '' }
            <cp-fieldcontainer>

                <div class="{ entry.subpagemodule.active && 'uk-flex uk-flex-bottom' }">
                    <field-boolean bind="entry.subpagemodule.active" label="@lang('Sub pages')" class="uk-margin-small uk-text-middle small"></field-boolean>

                  @if($pageTypeDetection == 'collections')
                    <select bind="entry.subpagemodule.collection" aria-label="@lang('Select a collection for sub pages')" class="uk-margin-small uk-margin-small-left" title="@lang('Select collection')" if="{ entry.subpagemodule.active }">
                        <option value=""></option>
                        @foreach($collections as $col)
                        <option value="{{ $col['name'] }}">{{ $col['label'] }}</option>
                        @endforeach
                    </select>
                  @endif

                    <span class="uk-flex-item-1"></span>
                    <a class="uk-icon-chevron-circle-{ mp_subpageToggle ? 'up' : 'down' } uk-icon-hover uk-text-large uk-margin-small-left" aria-label="@lang('Toggle dropdown')" onclick="{ mp_toggleSubpage }" if="{ entry.subpagemodule.active }"></a>
                </div>

                <div if="{ mp_subpageToggle && entry.subpagemodule.active }">
                @if($pageTypeDetection == 'collections' && count($collections))
                    <div class="uk-margin-small-top">
                        <div class="">
                            <label class="uk-text-small">@lang('Route')</label>
                            <input type="text" class="uk-form-width-small" bind="entry.subpagemodule.route{lang ? '_'+lang : ''}" />
                            <i class="uk-icon uk-icon-info-circle uk-margin-small-left uk-text-muted" title="@lang('Default: slug of this page')" data-uk-tooltip></i>
                        </div>
                    </div>

                @elseif($pageTypeDetection == 'type')
                    <div class="uk-margin-small-top" if="{ entry.subpagemodule.active }">
                        <label class="uk-text-small">@lang('Type')</label>
                        <select bind="entry.subpagemodule.type" bind-event="input">
                            <option value=""></option>
                            @foreach($pageTypes as $type)
                            <option value="{{ $type }}">@lang(ucfirst($type))</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                    <div class="uk-margin-small-top">
                        <cp-fieldcontainer>
                            <div class="uk-panel">
                                <div class="uk-margin-small-top">
{ (entry.subpagemodule.display = typeof entry.subpagemodule.display == 'undefined' ? true : entry.subpagemodule.display) && '' }
                                    <field-boolean bind="entry.subpagemodule.display" label="@lang('Display block with sub pages')" class="small"></field-boolean>
                                </div>
                                <div class="uk-margin-small-top">
                                    <label class="uk-text-small">@lang('Limit')</label>
                                    <input type="number" bind="entry.subpagemodule.limit" placeholder="5" size="3" class="uk-form-width-mini" />
                                </div>
                                <div class="uk-margin-small-top">
                                    <field-boolean bind="entry.subpagemodule.sort" label="@lang('Reverse order')" class="small"></field-boolean>
                                    <i class="uk-icon-info-circle uk-text-muted uk-margin-small-left" title="@lang('Default: Display latest entries first')" data-uk-tooltip></i>
                                </div>
                                <div class="uk-margin-small-top">
                                    <field-boolean bind="entry.subpagemodule.pagination" label="@lang('Show pagination')" class="small"></field-boolean>
                                </div>
                                <div class="uk-margin-small-top">
                                    <label class="uk-text-small">@lang('Custom sort order')</label>
                                    <field-key-value-pair bind="entry.subpagemodule.customsort"></field-key-value-pair>
                                </div>
                            </div>
                        </cp-fieldcontainer>
                    </div>
                </div>

            <cp-fieldcontainer>
        </div>
        @endif

        <div class="uk-panel uk-margin-small uk-width-medium-1-1 uk-width-small-1-2">
            <cp-fieldcontainer>
                <a href="#" aria-label="@lang('Toggle dropdown to set special page types')" class="uk-flex uk-flex-bottom uk-link-muted" onclick="{ mp_toggleSpecialPageTypes }">
                    <span class="uk-text-small">@lang('Set special page types')</span>
                    <i class="uk-icon-home uk-margin-small-left uk-text-small uk-text-muted" if="{ entry.startpage }"></i>
                    <i class="uk-icon-shield uk-margin-small-left uk-text-small uk-text-muted" if="{ entry.privacypage }"></i>
                    <span class="uk-flex-item-1"></span>
                    <i class="uk-icon-chevron-circle-{ mp_specialPageTypeToggle ? 'up' : 'down' } uk-icon-hover uk-text-large uk-margin-small-left"></i>
                </a>

                <div class="uk-margin-small" if="{ mp_specialPageTypeToggle }">
                    <div class="uk-margin-small-top">
                        <field-boolean bind="entry.startpage" label="@lang('Home page')" class="small"></field-boolean>
                    </div>
                    <div class="uk-margin-small-top">
                        <field-boolean bind="entry.privacypage" label="@lang('Privacy page')" class="small"></field-boolean>
                        <i class="uk-icon-info-circle uk-margin-small-left" title="@lang('Enable, if this is your privacy page. This info is needed to link to this page from dynamic content, e. g. cookie banner or contact form.')" data-uk-tooltip></i>
                    </div>
                </div>
            </cp-fieldcontainer>

        </div>
    </div>

</div>

<script>

    this.mp_toggleSpecialPageTypes = function(e) {
        if (e) e.preventDefault();
        this.mp_specialPageTypeToggle = !this.mp_specialPageTypeToggle;
    };

    this.mp_toggleSubpage = function(e) {
        if (e) e.preventDefault();
        this.mp_subpageToggle = !this.mp_subpageToggle;
    };

    // move Multiplane sidebar to top
    this.on('mount', function() { // moves on mount
        var sidebar = App.$('#multiplane_sidebar');
        sidebar.prependTo(sidebar.parent());
        delete sidebar;
    });

</script>
