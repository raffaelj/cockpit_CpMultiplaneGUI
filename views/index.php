<div>
    <ul class="uk-breadcrumb">
        <li class="uk-active"><span>@lang('Multiplane')</span></li>
    </ul>
@hasaccess?('cpmultiplanegui', 'create')
<div class="uk-float-right">
    <a class="uk-button uk-button-large uk-button-primary uk-width-1-1" href="@route('/multiplane/profile')">@lang('Add Profile')</a>
</div>
@end
</div>


<div riot-view>

    <style>
        .panel-footer-aside {
            display: inline-block;
            min-width: 50px;
        }
        .panel-teaser-description {
            padding: 0 1em;
            white-space: pre-line;
            /* max-height: 4em;
            overflow: hidden; */
        }
    </style>

    <div class="uk-container uk-container-center">

        <div if="{ !hasProfiles }">
            <div class="uk-text-center">
                @lang('No profiles')
                <a class="uk-button uk-button-link uk-button-primary" href="@route('/multiplane/profile')">@lang('Add one')</a>
            </div>
        </div>

        <div if="{ hasProfiles }" class="uk-grid uk-grid-match uk-grid-gutter uk-grid-width-medium-1-2 uk-grid-width-xlarge-1-3 uk-margin-top">

            <div each="{ profile, idx in profiles }">
                <div class="uk-panel uk-panel-box uk-panel-card uk-panel-card-hover">

                    <div class="uk-panel-teaser uk-position-relative">
                        <canvas width="600" height="350"></canvas>

                        <a aria-label="{ profile.label }" href="@route('/multiplane/profile')/{ profile.name }" class="uk-position-cover uk-text-center uk-link-muted uk-vertical-align">
                            <div class="uk-width-1-1 uk-vertical-align-middle">
                            <div class="uk-width-1-4 uk-svg-adjust uk-display-inline-block" style="color:{ profile.color };">
                                <img riot-src="{ profile.icon ? '@url('assets:app/media/icons/')'+profile.icon : '@url('cpmultiplanegui:icon.svg')' }" alt="icon" data-uk-svg>
                            </div>
                            <div class="panel-teaser-description uk-margin-small-top" if="{ profile.description }">{ profile.description }</div>
                            </div>
                        </a>
                        <div class="uk-panel-badge uk-badge uk-badge-success" if="{ profile.name == currentProfile }">@lang('active')</div>

                    </div>

                    <div class="uk-grid uk-grid-small">

                        <div data-uk-dropdown="mode:'click'">

                            <a class="panel-footer-aside uk-icon-cog" style="color:{ profile.color }"></a>

                            <div class="uk-dropdown">
                                <ul class="uk-nav uk-nav-dropdown">
                                    <li class="uk-nav-header">@lang('Actions')</li>
                                    <li><a href="@route('/multiplane/profile')/{ profile.name }">@lang('Edit')</a></li>
                                    @hasaccess?('cpmultiplanegui', 'delete')
                                    <li class="uk-nav-item-danger"><a class="uk-dropdown-close" onclick="{ parent.remove }">@lang('Delete')</a></li>
                                    @end
                                </ul>
                            </div>
                        </div>

                        <div class="uk-flex-item-1 uk-text-center uk-text-truncate">
                            <a class="uk-text-bold uk-link-muted" href="@route('/multiplane/profile')/{ profile.name }">{ profile.label || profile.name }</a>
                        </div>

                        <div class="panel-footer-aside uk-text-right"></div>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <script type="view/script">

        var $this = this;

        this.profiles       = {{ empty($profiles) ? '{}' : json_encode($profiles, true) }};
        this.hasProfiles    = {{ empty($profiles) ? 'false' : 'true' }};
        this.currentProfile = '{{ $currentProfile }}';

        // this.on('mount', function() {
            // this.update();
        // });

        remove(e, profile) {

            if (e) e.preventDefault();

            profile = e.item.profile;

            App.ui.confirm("Are you sure?", function() {

                App.callmodule('cpmultiplanegui:removeProfile', profile.name).then(function(data) {

                    App.ui.notify("Profile removed", "success");

                    delete $this.profiles[e.item.idx];

                    if (!Object.keys($this.profiles).length) $this.hasProfiles = false;

                    $this.update();

                }).catch(function(e) {console.log(e);});;
            });
        }

    </script>

</div>
