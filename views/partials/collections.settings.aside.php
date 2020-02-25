<div class="uk-margin">
    <div class="uk-flex uk-flex-middle">
        <i class="uk-icon-justify" title="{{ htmlspecialchars($c['label']) }}" data-uk-tooltip="pos:'bottom'">
            <img src="@url('cpmultiplanegui:icon.svg')" alt="icon" data-uk-svg width="16px" height="16px" />
        </i>
        <span>CpMultiplane</span>
    </div>
    <div class="uk-margin">
        <field-boolean bind="collection.multiplane.sidebar" title="@lang('Enable sidebar')" label="@lang('Enable sidebar')"></field-boolean>
    </div>
    <div class="uk-margin">
        <label class="uk-text-small">@lang('Type')</label>
        <select class="uk-width-1-1" bind="collection.multiplane.type" bind-event="input">
            <option value=""></option>
            @foreach(['pages', 'subpages'] as $v)
            <option value="{{ $v }}">@lang(ucfirst($v))</option>
            @endforeach
        </select>
    </div>
    <div class="uk-margin">
        <field-boolean bind="collection.multiplane.gui_in_header" title="@lang('Show in header menu')" label="@lang('Show in header menu')"></field-boolean>
    </div>
</div>
