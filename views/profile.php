<?php

/**
 * options:
 * [x] pages
 * [x] posts
 * [x] siteSingleton
 * [x] slugName
 * [x] isMultilingual
 * [x] displayBreadcrumbs
 * [x] displaySearch
 * [ ] use (not sure, if I keep it)
 * [ ] sitemap --> defaults to "multiplane/use/collections"
 * [ ] search (needs to be less experimental before I build an user interface) --> defaults to "multiplane/use/collections"
 * [x] lexy
 * [x] theme
 * [ ] site_url --> not needed - $app->module('cpmultiplanegui')->getSiteUrl()
 * [ ] pagination for posts (subpages)
 * [x] maintenance
 * [x] preRenderFields
 * [x] forms
 * [x] preview
 * [ ] matomo
 * [ ] structure (pages, subpages)
 * [ ] map field names to default structure (e. g. use 'name' as 'title')
 * [ ] custom menus
 * [ ] 
 * [ ] 
 * [ ] 
 * [ ] 
 */


?>

<div class="" riot-view>

    <style>
        .panel-footer-aside {
            display: inline-block;
            min-width: 50px;
        }
        .uk-panel-teaser canvas {
            border-radius: 2px 2px 0 0;
        }
        .badge {
            display: inline-block;
            margin: 0 .2em .2em 0;
            padding: 0em 0.8em;
            font-size: 10px;
            letter-spacing: .2px;
            font-weight: bold;
            text-transform: uppercase;
            border-radius: 0.2em;
            color: #fff;
            background-color: #007fff;
        }
        .uk-form-width-small {
            width: 70px;
        }
    </style>
    <div>
        <ul class="uk-breadcrumb">
            <li><a href="@route('/multiplane')">@lang('Multiplane')</a></li>
            <li class="uk-active"><span>@lang('Profile')</span></li>
        </ul>
        <a class="uk-button uk-button-outline uk-text-warning uk-float-right" onclick="{ showEntryObject }">@lang('Show json')</a>
    </div>

    <div class="uk-margin-top" >
      <form class="uk-form" onsubmit="{ submit }">
        <div class="uk-grid" data-uk-grid-margin>

          <div class="uk-width-medium-1-4">
              <div class="uk-panel uk-panel-box uk-panel-card">

                  <div class="uk-margin">
                      <label class="uk-text-small">@lang('Name')</label>
                      <input aria-label="@lang('Name')" class="uk-width-1-1 uk-form-large" type="text" ref="name" bind="profile.name" pattern="[a-zA-Z0-9_]+" required>
                      <p class="uk-text-small uk-text-muted" if="{!profile._id}">
                          @lang('Only alpha nummeric value is allowed')
                      </p>
                  </div>

                  <div class="uk-margin">
                      <label class="uk-text-small">@lang('Label')</label>
                      <input aria-label="@lang('Label')" class="uk-width-1-1 uk-form-large" type="text" ref="label" bind="profile.label">
                  </div>

                   <div class="uk-margin">
                       <label class="uk-text-small">@lang('Icon')</label>
                       <div data-uk-dropdown="pos:'right-center', mode:'click'">
                           <a><img class="uk-display-block uk-margin uk-container-center" riot-src="{ profile.icon ? '@url('assets:app/media/icons/')'+profile.icon : '@url('cpmultiplanegui:icon.svg')'}" alt="icon" width="100"></a>
                           <div class="uk-dropdown uk-dropdown-scrollable uk-dropdown-width-2">
                                <div class="uk-grid uk-grid-gutter">
                                    <div>
                                        <a class="uk-dropdown-close" onclick="{ selectIcon }" icon=""><img src="@url('cpmultiplanegui:icon.svg')" width="30" icon=""></a>
                                    </div>
                                    @foreach($app->helper("fs")->ls('*.svg', 'assets:app/media/icons') as $icon)
                                    <div>
                                        <a class="uk-dropdown-close" onclick="{ selectIcon }" icon="{{ $icon->getFilename() }}"><img src="@url($icon->getRealPath())" width="30" icon="{{ $icon->getFilename() }}"></a>
                                    </div>
                                    @endforeach
                                </div>
                           </div>
                       </div>
                   </div>

                   <div class="uk-margin">
                       <label class="uk-text-small">@lang('Color')</label>
                       <div class="uk-margin-small-top">
                           <field-colortag bind="profile.color" title="@lang('Color')" size="20px"></field-colortag>
                       </div>
                   </div>

                  <div class="uk-margin">
                      <label class="uk-text-small">@lang('Description')</label>
                      <textarea aria-label="@lang('Description')" class="uk-width-1-1 uk-form-large" name="description" bind="profile.description" bind-event="input" rows="5"></textarea>
                  </div>

                  @trigger('cpmultiplanegui.settings.aside')

              </div>
          </div>

          <div class="uk-width-medium-3-4">

            <div class="uk-width-1-1 uk-margin-bottom">
                <ul class="uk-tab uk-margin-bottom">
                    <li class="{ tab=='main' && 'uk-active'}"><a class="uk-text-capitalize" onclick="{ toggleTab }" data-tab="main">@lang('Main')</a></li>
                    <li class="{ tab=='theme' && 'uk-active'}"><a class="uk-text-capitalize" onclick="{ toggleTab }" data-tab="theme">@lang('Theme')</a></li>
                    <!--<li class="{ tab=='nav' && 'uk-active'}"><a class="uk-text-capitalize" onclick="{ toggleTab }" data-tab="nav">@lang('Menus')</a></li>-->
                    <li class="{ tab=='other' && 'uk-active'}"><a class="uk-text-capitalize" onclick="{ toggleTab }" data-tab="other">@lang('Other')</a></li>
                    <li class="{ tab=='themes' && 'uk-active'}"><a class="uk-text-capitalize" onclick="{ toggleTab }" data-tab="themes">@lang('Themes')</a></li>
                </ul>
            </div>



            <div class="uk-width-1-1" show="{tab=='main'}">

                <div class="uk-grid uk-grid-match" data-uk-grid-margin>

                  <div class="uk-width-medium-1-2 uk-width-large-1-3">
                      <div class="uk-panel-box uk-panel-card">

                          <div class="uk-margin">
                              <div class="uk-flex uk-flex-middle uk-margin-small">
                                  <label class="uk-text-small">
                                      @lang('Select pages collection')
                                  </label>
                                  <span class="uk-flex-item-1"></span>
                                  <!--<i class="uk-icon-info-circle uk-margin-small-left" title="@lang('Default: pages')" data-uk-tooltip></i>-->
                              </div>
                              <select bind="profile.pages" bind-event="input" class="uk-width-1-1">
                                  <option value=""></option>
                                  @foreach($collections as $c)
                                  <option value="{{ $c['name'] }}">{{ $c['label'] }}</option>
                                  @endforeach
                              </select>
                          </div>

                          <div class="uk-margin">
                              <div class="uk-flex uk-flex-middle uk-margin-small">
                                  <label class="uk-text-small">
                                      @lang('Select posts collection')
                                  </label>
                                  <span class="uk-flex-item-1"></span>
                                  <!--<i class="uk-icon-info-circle uk-margin-small-left" title="@lang('Default: posts')" data-uk-tooltip></i>-->
                              </div>

                              <select bind="profile.posts" bind-event="input" class="uk-width-1-1">
                                  <option value=""></option>
                                  @foreach($collections as $c)
                                  <option value="{{ $c['name'] }}">{{ $c['label'] }}</option>
                                  @endforeach
                              </select>
                          </div>

                          <div class="uk-margin">
                              <div class="uk-flex uk-flex-middle uk-margin-small">
                                  <label class="uk-text-small">
                                      @lang('Select site singleton')
                                  </label>
                                  <span class="uk-flex-item-1"></span>
                                  <!--<i class="uk-icon-info-circle uk-margin-small-left" title="@lang('Default: site')" data-uk-tooltip></i>-->
                              </div>

                              <select bind="profile.siteSingleton" bind-event="input" class="uk-width-1-1">
                                  <option value=""></option>
                                  @foreach($singletons as $c)
                                  <option value="{{ $c['name'] }}">{{ $c['label'] }}</option>
                                  @endforeach
                              </select>
                          </div>

                          <div class="uk-margin">
                              <div class="uk-flex uk-flex-middle uk-margin-small">
                                  <label class="uk-text-small">
                                      @lang('Field name for slugs')
                                  </label>
                                  <span class="uk-flex-item-1"></span>
                                  <i class="uk-icon-info-circle uk-margin-small-left" title="@lang('Default: _id (no slugs)')" data-uk-tooltip></i>
                              </div>
                              <input type="text" class="uk-width-1-1" bind="profile.slugName" />
                          </div>

                          <div class="uk-margin">
                              <div class="uk-flex uk-flex-middle uk-margin-small">
                                  <label class="uk-text-small">
                                      @lang('Theme')
                                  </label>
                              </div>
                              <select class="uk-width-1-1" onchange="{ selectTheme }">
                                  <option value=""></option>
                                  <option value="{ thm.name }" selected="{ profile.theme == thm.name }" each="{ thm, idx in themes }">{ thm.label || thm.name }</option>
                              </select>
                          </div>
                      </div>
                  </div>

                  <div class="uk-width-medium-1-2 uk-width-large-1-3">
                      <div class="uk-panel-box uk-panel-card">
                          <div class="uk-grid" data-uk-grid-margin>
                              <div class="uk-width-xlarge-1-2">
                                  <label class="uk-text-small">@lang('Use collections')</label>
                                  <field-multipleselect bind="profile.use.collections" options="{ selectCollectionsOptions }"></field-multipleselect>
                              </div>
                              <div class="uk-width-xlarge-1-2">
                                  <label class="uk-text-small">@lang('Use singletons')</label>
                                  <field-multipleselect bind="profile.use.singletons" options="{ selectSingletonsOptions }"></field-multipleselect>
                              </div>
                              <div class="uk-width-xlarge-1-2">
                                  <label class="uk-text-small">@lang('Use Forms')</label>
                                  <field-multipleselect bind="profile.use.forms" options="{ selectFormsOptions }"></field-multipleselect>
                              </div>
                          </div>
                      </div>
                  </div>

                  <div class="uk-width-medium-1-2 uk-width-large-1-3">
                      <div class="uk-panel-box uk-panel-card">

                          <div class="uk-margin">
                              <div class="uk-flex uk-flex-middle uk-margin-small">
                                  <label class="uk-text-small">
                                      @lang('Multilingual website')
                                  </label>
                                  <span class="uk-flex-item-1"></span>
                                  <i class="uk-icon-info-circle uk-margin-small-left" title="@lang('Use the UniqueSlugs addon to localize slug fields.')" data-uk-tooltip></i>
                              </div>
                              <field-boolean bind="profile.isMultilingual" label="@lang('multilingual')"></field-boolean>
                          </div>

                          <div class="uk-margin">
                              <div class="uk-flex uk-flex-middle uk-margin-small">
                                  <label class="uk-text-small">
                                      @lang('Admin user interface')
                                  </label>
                                  <span class="uk-flex-item-1"></span>
                                  
                              </div>
                              <field-boolean bind="profile.guiDisplayCustomNav" label="@lang('Enable custom menu in top bar')"></field-boolean>
                          </div>

                          <div class="uk-margin">
                              <div class="uk-flex uk-flex-middle uk-margin-small">
                                  <label class="uk-text-small">
                                      @lang('Breadcrumbs')
                                  </label>
                                  <span class="uk-flex-item-1"></span>
                                  <i class="uk-icon-info-circle uk-margin-small-left" title="@lang('Display breadcrumbs')" data-uk-tooltip></i>
                              </div>
                              <field-boolean bind="profile.displayBreadcrumbs" label="@lang('On')"></field-boolean>
                          </div>

                      </div>
                  </div>

                </div>
            </div>



            <div class="uk-width-1-1" show="{tab=='themes'}">

                <div class="uk-grid uk-grid-match uk-grid-gutter uk-grid-width-medium-1-2 uk-grid-width-xlarge-1-3 uk-margin-top">

                    <div class="" each="{ theme, idx in themes }">
                        <div class="uk-panel-box uk-panel-card">

                            <div class="uk-panel-teaser uk-position-relative">
                                <cp-thumbnail src="{ theme.image }" alt="screenshot" width="600px" height="350px" if="{ theme.image }"></cp-thumbnail>
                                <canvas width="600" height="350" if="{ !theme.image }"></canvas>
                                <a aria-label="{ theme.label || theme.name }" class="uk-position-cover uk-flex uk-flex-middle uk-flex-center uk-link-muted">
                                    <div class="uk-width-1-4 uk-svg-adjust" style="color:{ theme.color }" if="{ !theme.image }">
                                        <img src="@url('cpmultiplanegui:icon.svg')" alt="icon" data-uk-svg>
                                    </div>
                                </a>
                                <div class="uk-panel-badge">
                                    <div class="uk-badge uk-badge-success" if="{ profile.theme == theme.name }">@lang('active')</div>
                                    <div class="uk-badge uk-badge-success" if="{ !profile.theme && theme.name == 'rljbase' }">@lang('Default')</div>
                                    <div class="uk-badge" if="{ theme.config.parentTheme }" title="@lang('Parent theme')" data-uk-tooltip>{ theme.config.parentTheme }</div>
                                </div>
                            </div>

                            <div class="uk-grid uk-grid-small">

                                <div data-uk-dropdown="mode:'click'">

                                    <a class="panel-footer-aside uk-icon-cog" style="color:{ theme.color }"></a>

                                    <div class="uk-dropdown">
                                        <ul class="uk-nav uk-nav-dropdown">
                                            <li class="uk-nav-header">@lang('Actions')</li>
                                            <li><a class="uk-dropdown-close" onclick="{ selectTheme }">@lang('Select theme')</a></li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="uk-flex-item-1 uk-text-center uk-text-truncate">
                                    <a class="uk-text-bold uk-link-muted">{ theme.label || theme.name }</a>
                                </div>

                                <div class="panel-footer-aside uk-text-right">
                                    <span class="" if="{ theme.info.version }">{ theme.info.version }</span>
                                </div>
                                
                                <div class="uk-width-1-1 uk-text-center uk-margin-small" if="{ theme.info }">
                                    <div class="uk-text-small uk-margin-small">{ theme.info.description }</div>
                                    <span class="badge" each="{ k in theme.info.keywords }" if="{ theme.info.keywords && Array.isArray(theme.info.keywords) }">{ k }</span>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>

            </div>



            <div class="uk-width-1-1" show="{tab=='other'}">

                <div class="uk-panel-box uk-panel-card uk-panel-header">

                    <h3 class="uk-panel-title">@lang('Content Preview')</h3>
                    <div class="uk-grid uk-grid-small">

                        <div class="uk-width-1-2">
                            <field-boolean bind="profile.isPreviewEnabled" label="@lang('Enable content preview')"></field-boolean>
                            <div class="uk-text-small uk-margin">
                                @lang('You have to enable the preview in your collection settings manually.')
                            </div>
                        </div>

                        <div class="uk-width-small-1-2" if="{ profile.isPreviewEnabled }">
                            <label class="uk-text-small uk-text-middle">
                                @lang('Method for content preview')
                            </label>
                            <select bind="profile.previewMethod">
                                <option value="html">HTML</option>
                                <option value="json">JSON</option>
                            </select>
                            <div class="uk-text-small uk-margin">
                                @lang('Default and implemented: HTML - If you want to use JSON, you have to built your own content preview.')
                            </div>
                        </div>

                        <div class="uk-width-small-1-2 uk-margin" if="{ profile.isPreviewEnabled }">
                            <label class="uk-text-small uk-margin-small">
                                @lang('livePreviewToken')
                            </label>
                            <div class="uk-flex uk-flex-middle">
                                <input type="text" bind="profile.livePreviewToken" class="uk-width-1-1" />

                                <span class="uk-flex-item-1"></span>
                                <a class="uk-icon-refresh" onclick="{ generateToken }" style="pointer-events:auto;" title="@lang('Generate Token')" data-uk-tooltip></a>
                            </div>

                        </div>

                        <div class="uk-width-small-1-2 uk-margin" if="{ profile.isPreviewEnabled }">
                            <label class="uk-text-small uk-text-middle">
                                @lang('Delay')
                            </label>
                            <input type="number" step="500" min="0" bind="profile.previewDelay" class="uk-form-width-small" />
                            <div class="uk-text-small uk-margin">
                                @lang('In milliseconds, default: 0. Adjust this value, if you have problems with massive requests while editing a page in content preview.')
                            </div>
                        </div>

                        <div class="uk-width-small-1-2 uk-margin" if="{ profile.isPreviewEnabled }">
                            <field-boolean bind="profile.previewScripts" label="@lang('Reload scripts in preview')"></field-boolean>
                            <div class="uk-text-small uk-margin">
                                @lang('Enable this if your content changes dynamically after loading, e. g. with video links or galleries.')
                            </div>
                        </div>

                        <div class="uk-width-1-1 uk-margin" if="{ profile.isPreviewEnabled && profile.livePreviewToken }">

                            <div class="uk-text-small">
                                @lang('Copy one of these links to your collections content preview settings.')
                            </div>
                            <code>{{ $app->module('cpmultiplanegui')->getSiteUrl(true) }}/livePreview?token={ profile.livePreviewToken }</code>
                            <br>
                            <code>root://livePreview?token={ profile.livePreviewToken }</code>

                        </div>

                    </div>

                </div>

                <div class="uk-panel-box uk-panel-card uk-panel-header uk-margin">

                    <h3 class="uk-panel-title">@lang('Search')</h3>

                    <field-set fields="{searchFields}" bind="profile.search"></field-set>

                    @lang('For more granular settings use the config file.')

                </div>

                <div class="uk-panel-box uk-panel-card uk-panel-header uk-margin">

                    <h3 class="uk-panel-title">@lang('Forms')</h3>

                    <div class="uk-grid uk-grid-small">
                        <div class="uk-width-1-2">
                            <label class="uk-text-small">@lang('Prefix for form field names')</label>
                            <input aria-label="@lang('Prefix for form fields')" class="uk-width-1-1 uk-form-large" type="text" ref="label" bind="profile.formIdPrefix">
                            <div class="uk-text-small uk-margin-small">
                                @lang('Default: mp_form_')
                            </div>
                        </div>

                        <div class="uk-width-1-2">
                            <label class="uk-text-small">@lang('Name attribute of submit button')</label>
                            <input aria-label="@lang('Name attribute of submit button')" class="uk-width-1-1 uk-form-large" type="text" ref="label" bind="profile.formSubmitButtonName">
                            <div class="uk-text-small uk-margin-small">
                                @lang('Default: submit')
                            </div>
                        </div>
                    </div>

                    <div class="uk-margin">
                        <field-boolean bind="profile.formSendReferer" label="@lang('Send referer url')"></field-boolean>
                        <div class="uk-text-small uk-margin-small">
                            @lang('Send the referer url when sending the contact form. You have to create an invisible field named \'referer\' in the form validator manually.')
                        </div>
                    </div>

                </div>

                <div class="uk-panel-box uk-panel-card uk-panel-header uk-margin">

                    <h3 class="uk-panel-title">@lang('Field pre-rendering')</h3>

                    <field-tags bind="profile.preRenderFields" autocomplete="{fieldnames}" placeholder="@lang('Add field name')"></field-tags>
                    <div class="uk-text-small uk-margin-small">
                        @lang('For markdown fields or to normalize relative links in localized wysiwyg fields...')
                    </div>

                </div>

                <div class="uk-panel-box uk-panel-card uk-panel-header uk-margin">

                    <h3 class="uk-panel-title">@lang('Sitemap')</h3>

                    to do...

                </div>

                <div class="uk-panel-box uk-panel-card uk-panel-header uk-margin">

                    <h3 class="uk-panel-title">@lang('Maintenance mode')</h3>

                    <field-boolean bind="profile.isInMaintenanceMode" label="@lang('Maintenance mode')"></field-boolean>
                    <div class="uk-text-small uk-margin-small">
                        @lang('Display an \'under construction\' page to your visitors.')
                    </div>

                    <div class="uk-margin">
                        <label class="uk-text-small">@lang('Allowed ips in maintenance mode')</label>
                        <div class="uk-flex uk-flex-middle">
                            <input type="text" class="uk-width-1-1" bind="profile.allowedIpsInMaintenanceMode" />
                            <span class="uk-flex-item-1"></span>
                            <a href="#" class="uk-button uk-button-small uk-margin-left uk-text-nowrap" onclick="{ addCurrentIp }">@lang('Add current ip')</a>
                        </div>
                        <div class="uk-text-small uk-margin-small">
                            @lang('Enter ip addresses, that can access the page while maintenance mode is enabled. Separate them with whitespaces.')
                        </div>
                    </div>

                </div>

            </div>



            <div class="uk-width-1-1" show="{tab=='theme'}">

                <div class="uk-grid uk-grid-small uk-grid-match" data-uk-grid-margin>

                    <div class="uk-width-medium-1-2" if="{ theme.info }">
                        <dl class="uk-description-list-horizontal">
                            <dt>@lang('Theme')</dt><dd>{ theme.name }</dd>
                            <dt>@lang('Version')</dt><dd if="{ theme.info }">{ theme.info.version }</dd>
                            <dt>@lang('License')</dt><dd if="{ theme.info }">{ theme.info.license }</dd>
                            <dt>@lang('Author')</dt><dd if="{ theme.info.author && typeof theme.info.author == 'string' }">{ theme.info.author }</dd>
                        </dl>

                        <p if="{ theme.info && theme.info.description }">{ theme.info.description }</p>

                        <div>
                            <span class="uk-badge uk-margin-small-right" each="{ k in theme.info.keywords }" if="{ theme.info.keywords && Array.isArray(theme.info.keywords) }">{ k }</span>
                        </div>
                    </div>

                    <div class="uk-width-medium-1-2">
                        <cp-thumbnail src="{ theme.image }" if="{ theme.image }" width="600px" height="400px"></cp-thumbnail
                    </div>
                </div>

                <div class="uk-margin uk-width-1-1" if="{ theme.config }">

                    <h3 class="">@lang('Theme defaults')</h3>

                    <div each="{ conf, idx in theme.config }" if="{ idx != 'lexy' }">
                        <span class="uk-text-bold">{ idx }:</span> { JSON.stringify(conf) }
                    </div>

                </div>

                <div class="uk-margin uk-width-1-1" if="{ theme.config && theme.config.lexy }">

                    <h4 class="">@lang('Lexy image shortcuts')</h3>

                    <div class="uk-grid uk-grid-small uk-grid-match" data-uk-grid-margin>

                        <div class="uk-width-large-1-2" each="{ lexy, idx in theme.config.lexy }">
                        <div class="uk-panel-box uk-panel-card">
                            <strong class="">@{ idx }</strong>

                            <div if="{ lexy == 'raw' }">
                                @lang('raw output')
                            </div>
                            <div class="uk-grid uk-grid-width-small-1-4 uk-flex-middle" if="{ typeof lexy != 'string' }">

                                <div each="{ v,k in lexy }" class="">
                                    <label class="uk-text-small uk-text-middle">{ k }</label>
                                    <input if="{ k != 'method' }" type="number" placeholder="{ v }" bind="profile.lexy.{idx}.{k}" class="uk-form-width-small" />
                                    <select if="{ k == 'method' }" bind="profile.lexy.{idx}.{k}" bind-event="input">
                                        <option value="" disabled selected="{ !profile.lexy || !profile.lexy[idx] || !profile.lexy[idx][k] ? true:false }">{v}</option>
                                        <option value="{ method }" each="{ method in thumbnailMethods }">{ method }</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>

                </div>

            </div>

          </div>

        </div>
        <cp-actionbar>
            <div class="uk-container uk-container-center">
                <button class="uk-button uk-button-large uk-button-primary">@lang('Save')</button>
                <a class="uk-button uk-button-link" href="@route('/multiplane')">
                    <span show="{ !profile._id }">@lang('Cancel')</span>
                    <span show="{ profile._id }">@lang('Close')</span>
                </a>
            </div>
        </cp-actionbar>
      </form>
    </div>

    <cp-inspectobject ref="inspect"></cp-inspectobject>

    <script type="view/script">

        var $this = this;

        riot.util.bind(this);

        this.profile      = {{ !empty($profile) ? json_encode($profile) : '{}' }};
        this.fieldnames   = {{ json_encode($fieldnames) }};
        this.collections  = {{ json_encode($collections) }};
        this.singletons   = {{ json_encode($singletons) }};
        this.forms        = {{ json_encode($forms) }};

        this.themes       = {};
        this.theme        = {};

        this.tab = 'main';
//         this.tab = 'other';
//         this.tab = 'themes';

        this.thumbnailMethods = ['thumbnail', 'bestFit'];

        this.selectCollectionsOptions = {};
        this.collections.forEach(function(e) {
            $this.selectCollectionsOptions[e.name] = e.label || e.name;
        });
        this.selectSingletonsOptions = {};
        this.singletons.forEach(function(e) {
            $this.selectSingletonsOptions[e.name] = e.label || e.name;
        });
        this.selectFormsOptions = {};
        this.forms.forEach(function(e) {
            $this.selectFormsOptions[e.name] = e.label || e.name;
        });

        this.searchFields = [
            {
                name: 'enabled',
                label: App.i18n.get('Enabled'),
                type: 'boolean',
            },
        ];

        this.on('mount', function() {

            // bind global command + save
            Mousetrap.bindGlobal(['command+s', 'ctrl+s'], function(e) {
                e.preventDefault();
                $this.submit();
                return false;
            });

            this.get_multiplane_config();

            this.update();

        });

        this.on('update', function() {

            // lock name if saved
            if (this.profile._id) {
                this.refs.name.disabled = true;
            }

        });

        submit(e) {

            if (e) e.preventDefault();

            App.request('/multiplane/save_profile/'+this.profile.name, {profile:this.profile}).then(function(profile) {

                App.ui.notify("Saving successful", "success");
                $this.profile = profile;
                $this.update();

            }).catch(function(e) {
                App.ui.notify("Saving failed.", "danger");
                console.log(e);
            });

        }

        toggleTab(e) {
            this.tab = e.target.getAttribute('data-tab');

            // fix margin on dynamic elements
            App.$('[data-uk-grid-margin').trigger('resize');
        }

        addCurrentIp(e) {

            if (e) e.preventDefault();

            if (!this.profile.allowedIpsInMaintenanceMode) {
                this.profile.allowedIpsInMaintenanceMode = '{{ $app->getClientIp() }}';
            } else {
                this.profile.allowedIpsInMaintenanceMode += ' {{ $app->getClientIp() }}';
            }

        }

        generateToken() {
            this.profile.livePreviewToken = App.Utils.generateToken(120);
        }

        showEntryObject() {
            $this.refs.inspect.show($this.profile);
            $this.update();
        }
        
        get_multiplane_config() {
            
            App.request('/multiplane/get_multiplane_config').then(function(data) {

                if (data.themes) {

                    $this.themes = data.themes;

                    $this.theme = $this.themes[$this.profile.theme || 'rljbase'] || {};

                    if ($this.theme.config && $this.theme.config.parentTheme
                      && $this.themes[$this.theme.config.parentTheme]) {

                        App.$.extend($this.theme.config, $this.themes[$this.theme.config.parentTheme].config);

                    }
                }

                $this.update();

            }).catch(function(e) {console.log(e);});

        }

        selectTheme(e) {

            if (e) e.preventDefault();

            this.profile.theme = e.item ? e.item.theme.name : e.target.value;

            this.theme = this.themes[this.profile.theme || 'rljbase'] || {};
            if (this.theme.config && this.theme.config.parentTheme
              && this.themes[this.theme.config.parentTheme]) {
                App.$.extend(this.theme.config, this.themes[this.theme.config.parentTheme].config);
            }
        }

        selectIcon(e) {
            this.profile.icon = e.target.getAttribute('icon');
        }

    </script>

</div>
