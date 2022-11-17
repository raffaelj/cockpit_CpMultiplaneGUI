<div class="uk-margin uk-panel-box uk-panel-card uk-panel-box-secondary">
    <div class="uk-panel-box-header">
        <img class="uk-margin-small-right" src="@url('cpmultiplanegui:icon.svg')" alt="icon" data-uk-svg width="20px" height="20px" />
        CpMultiplane
    </div>
    <div class="uk-margin uk-flex uk-flex-middle uk-flex-wrap">
        <div class="uk-flex-item-1 uk-text-{ __formsInUse.includes(form.name) ? 'success' : 'danger' }">
            @lang('Published'):
            <span if='{ __formsInUse.includes(form.name) }'>
                @lang('Yes')
            </span>
            <span if='{ !__formsInUse.includes(form.name) }'>
                @lang('No')
            </span>
        </div>

        @if($app->module('cockpit')->hasaccess('cpmultiplanegui', 'manage') || $app->module('cockpit')->hasaccess('cpmultiplanegui', 'edit_forms_in_use'))
        <button class="uk-button uk-button-primary" data-form="{ form.name }" data-use="1" if="{ !__formsInUse.includes(form.name) }" onclick="{ publish_form_to_mp_profile }">@lang('Publish')</a>
        <button class="uk-button uk-button-danger" data-form="{ form.name }" data-use="0" if="{ __formsInUse.includes(form.name) }" onclick="{ publish_form_to_mp_profile }">@lang('Unpublish')</a>
        @endif

    </div>
    <div class="uk-margin">
        <label class="uk-text-small">@lang('Language')</label>
        <select class="uk-width-1-1" bind="form.multiplane.language" bind-event="input">
            <option value=""></option>
            @foreach($app['languages'] as $k => $v)
            <option value="{{ $k != 'default' ? $k : $app('i18n')->locale }}">{{ $v }}</option>
            @endforeach
        </select>
    </div>
    @if($isMultilingual)
    <div class="uk-margin">
        @lang('Direct link:') <a href="{{ $siteUrl }}/{ form.multiplane.language ?? App.$data.locale }/form/{ form.name }" target="_blank">{{ $siteUrl }}/{ form.multiplane.language || App.$data.locale }/form/{ form.name }</a>
    </div>
    @else
    <div class="uk-margin">
        @lang('Direct link:') <a href="{{ $siteUrl }}/form/{ form.name }" target="_blank">{{ $siteUrl }}/form/{ form.name }</a>
    </div>
    @endif
    <div class="uk-margin">
        <field-boolean bind="form.multiplane.gui_in_header" label="@lang('Show in header menu')"></field-boolean>
    </div>
</div>
<script>
window.__formsInUse = {{ json_encode($formsInUse); }};

@if($app->module('cockpit')->hasaccess('cpmultiplanegui', 'manage') || $app->module('cockpit')->hasaccess('cpmultiplanegui', 'edit_forms_in_use'))

function publish_form_to_mp_profile(e) {

    if (e) e.preventDefault();

    var form = e.target.dataset.form
        use  = e.target.dataset.use;

    App.request('/multiplane/edit_forms_in_use', {form:form,use:use}).then(function(data) {

        if (data && data.success) {

            if (use == 1) {
                __formsInUse.push(form);
                App.ui.notify('Published form');
            } else {

                var index = __formsInUse.indexOf(form);
                if (index !== -1) {
                __formsInUse.splice(index, 1);
                }

                App.ui.notify('Unpublished form');
            }

            var riotContainer = e.target.closest('[data-is]');
            if (riotContainer && riotContainer._tag) {
                riotContainer._tag.update();
            }

        }
    }).catch(function(e) {
        console.log(e);
    });
}
@endif
</script>
