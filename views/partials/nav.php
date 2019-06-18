
<li id="custom_nav">
    <ul class="uk-list">
        @foreach($collections as $collection)
        <li class="uk-float-left uk-margin-small-right">
            <a class="uk-svg-adjust {{ $collection['active'] ? 'uk-active' : 'uk-text-muted' }}" href="@route('/collections/entries/'.$collection['name'])">
                <i class="uk-icon-justify" title="{{ htmlspecialchars($collection['label'] ? $collection['label'] : $collection['name']) }}" data-uk-tooltip="pos:'bottom'">
                    @if(!empty($collection['icon']))
                    <img src="@base('assets:app/media/icons/'.$collection['icon'])" alt="@lang($collection['label'] ?? $collection['name'])" data-uk-svg width="20px" height="20px" style="color:{{ $collection['color'] ?? '' }}" />
                    @else
                    <img src="@base('collections:icon.svg')" alt="@lang($collection['label'])" data-uk-svg width="20px" height="20px" style="color:{{ $collection['color'] ?? '' }}" />
                    @endif
                </i>
                <span class="custom_nav_label uk-hidden-medium uk-text-small uk-text-bolder">@lang($collection['label'] ?? $collection['name'])</span>
            </a>
            @if($app->module('collections')->hasaccess($collection['name'], 'entries_create'))
            <a class="uk-svg-adjust uk-text-muted" href="@route('/collections/entry')/{{ $collection['name'] }}" title="@lang('Add entry')" data-uk-tooltip="pos:'right'">
                <img src="@base('assets:app/media/icons/plus-circle.svg')" width="16px" height="16px" data-uk-svg />
            </a>
            @endif
        </li>
        @endforeach

        @foreach($singletons as $collection)
        @if($app->module('singletons')->hasaccess($collection['name'], 'singleton_edit'))
        <li class="uk-float-left uk-margin-small-right">
            <a class="uk-svg-adjust {{ $collection['active'] ? 'uk-active' : 'uk-text-muted' }}" href="@route('/singletons/forms/'.$collection['name'])">
                <i class="uk-icon-justify" title="{{ htmlspecialchars($collection['label'] ? $collection['label'] : $collection['name']) }}" data-uk-tooltip="pos:'bottom'">
                    @if(!empty($collection['icon']))
                    <img src="@base('assets:app/media/icons/'.$collection['icon'])" alt="@lang($collection['label'] ?? $collection['name'])" data-uk-svg width="20px" height="20px" style="color:{{ $collection['color'] ?? '' }}" />
                    @else
                    <img src="@base('singletons:icon.svg')" alt="@lang($collection['label'])" data-uk-svg width="20px" height="20px" style="color:{{ $collection['color'] ?? '' }}" />
                    @endif
                </i>
                <span class="custom_nav_label uk-hidden-medium uk-text-small uk-text-bolder">@lang($collection['label'] ?? $collection['name'])</span>
            </a>
        </li>
        @endif
        @endforeach

        @foreach($other as $collection)
        <li class="uk-float-left uk-margin-small-right">
            <a class="uk-svg-adjust {{ $collection['active'] ? 'uk-active' : 'uk-text-muted' }}" href="@route($collection['route'])">
                <i class="uk-icon-justify" title="{{ htmlspecialchars($collection['label'] ? $collection['label'] : $collection['name']) }}" data-uk-tooltip="pos:'bottom'">
                    @if(!empty($collection['icon']))
                    <img src="@base('assets:app/media/icons/'.$collection['icon'])" alt="@lang($collection['label'])" data-uk-svg width="20px" height="20px" style="color:{{ $collection['color'] ?? '' }}" />
                    @else
                    <img src="@base('singletons:icon.svg')" alt="@lang($collection['label'] ?? $collection['name'])" data-uk-svg width="20px" height="20px" style="color:{{ $collection['color'] ?? '' }}" />
                    @endif
                </i>
                <span class="custom_nav_label uk-hidden-medium uk-text-small uk-text-bolder">@lang($collection['label'] ?? $collection['name'])</span>
            </a>
        </li>
        @endforeach
    </ul>
</li>

<li id="custom_nav_toggle">
    <a id="toggleCockpitMenuItems" class="uk-text-muted"><i class="uk-icon uk-icon-navicon" title="@lang('Toggle Cockpit menu')" data-uk-tooltip="pos:'bottom'"></i></a>
</li>

<script>
// move list item to menu bar
App.$('.app-modulesbar').prepend(App.$('#custom_nav'));

// move tooggle list item to menu bar
App.$('.app-modulesbar').append(App.$('#custom_nav_toggle'));

// hide default menu items
App.$('.app-modulesbar > li:not([id])').addClass('uk-hidden');

// toggle default menu items
document.getElementById('toggleCockpitMenuItems').addEventListener('click', function(e) {
    if (e) e.preventDefault();
    toggleCockpitMenuItems();
});

function toggleCockpitMenuItems() {
    App.$('.app-modulesbar > li:not([id])').toggleClass('uk-hidden');
    App.$('.custom_nav_label').toggleClass('uk-hidden'); // toggle custom labels
}
</script>
