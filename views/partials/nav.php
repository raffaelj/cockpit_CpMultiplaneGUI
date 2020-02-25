
<li id="custom_nav">
    <ul class="uk-list">
        @foreach($collections as $c)
        <li class="uk-float-left uk-margin-small-right">
            <a class="uk-svg-adjust {{ $c['active'] ? 'uk-active' : 'uk-text-muted' }}" href="@route('/collections/entries/'.$c['name'])">
                <i class="uk-icon-justify" title="{{ htmlspecialchars($c['label']) }}" data-uk-tooltip="pos:'bottom'">
                    <img src="@base(!empty($c['icon']) ? 'assets:app/media/icons/'.$c['icon'] : 'collections:icon.svg')" alt="@lang($c['label'])" data-uk-svg width="20px" height="20px" style="color:{{ $c['color'] }}" />
                </i>
                <span class="custom_nav_label uk-hidden-medium uk-text-small uk-text-bolder">@lang($c['label'])</span>
            </a>
            @if($app->module('collections')->hasaccess($c['name'], 'entries_create'))
            <a class="uk-svg-adjust uk-text-muted" href="@route('/collections/entry')/{{ $c['name'] }}" title="@lang('Add entry')" data-uk-tooltip="pos:'right'">
                <img src="@base('assets:app/media/icons/plus-circle.svg')" width="16px" height="16px" data-uk-svg />
            </a>
            @endif
        </li>
        @endforeach

        @foreach($singletons as $s)
        @if($app->module('singletons')->hasaccess($s['name'], 'singleton_edit'))
        <li class="uk-float-left uk-margin-small-right">
            <a class="uk-svg-adjust {{ $s['active'] ? 'uk-active' : 'uk-text-muted' }}" href="@route('/singletons/form/'.$s['name'])">
                <i class="uk-icon-justify" title="{{ htmlspecialchars($s['label']) }}" data-uk-tooltip="pos:'bottom'">
                    <img src="@base(!empty($s['icon']) ? 'assets:app/media/icons/'.$s['icon'] : 'singletons:icon.svg')" alt="@lang($s['label'])" data-uk-svg width="20px" height="20px" style="color:{{ $s['color']}}" />
                </i>
                <span class="custom_nav_label uk-hidden-medium uk-text-small uk-text-bolder">@lang($s['label'])</span>
            </a>
        </li>
        @endif
        @endforeach

        @foreach($other as $o)
        <li class="uk-float-left uk-margin-small-right">
            <a class="uk-svg-adjust {{ $o['active'] ? 'uk-active' : 'uk-text-muted' }}" href="@route($o['route'])">
                <i class="uk-icon-justify" title="{{ htmlspecialchars($o['label']) }}" data-uk-tooltip="pos:'bottom'">
                    <img src="@base('assets:app/media/icons/'.$o['icon'])" alt="@lang($o['label'])" data-uk-svg width="20px" height="20px" style="color:{{ $o['color'] ?? '' }}" />
                </i>
                <span class="custom_nav_label uk-hidden-medium uk-text-small uk-text-bolder">@lang($o['label'])</span>
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
