<div class="uk-margin uk-panel-box uk-panel-card uk-panel-box-secondary">
    <div class="uk-panel-box-header">
        <img class="uk-margin-small-right" src="@url('cpmultiplanegui:icon.svg')" alt="icon" data-uk-svg width="20px" height="20px" />
        CpMultiplane
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
    <div class="uk-margin">
        <field-boolean bind="form.multiplane.gui_in_header" label="@lang('Show in header menu')"></field-boolean>
    </div>
</div>
