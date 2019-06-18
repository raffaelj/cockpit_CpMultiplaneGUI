
<div>
    <ul class="uk-breadcrumb">
        <li><a href="@route('/settings')">@lang('Settings')</a></li>
        <li class="uk-active"><span>@lang('CpMultiplane')</span></li>
    </ul>
</div>


<div class="uk-margin-top" riot-view>

    <p>to do...</p>

    <p>{{ $app->module('cpmultiplanegui')->findMultiplaneDir() }}</p>

</div>
