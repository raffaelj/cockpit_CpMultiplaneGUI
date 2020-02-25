
<div riot-view>
    <div>
        <ul class="uk-breadcrumb">
            <li><a href="@route('/multiplane')">@lang('Multiplane')</a></li>
            <li class="uk-active"><span>@lang('Settings')</span></li>
        </ul>
        <a class="uk-button uk-button-outline uk-text-warning uk-float-right" onclick="{ showEntryObject }">@lang('Show json')</a>
    </div>

  <div class="uk-container-center">

    <div class="uk-tab-center uk-width-1-1 uk-margin-bottom">
        <ul class="uk-tab uk-margin-bottom">
            <li class="{ tab=='setup' && 'uk-active'}"><a class="uk-text-capitalize" onclick="{ toggleTab }" data-tab="setup">@lang('Setup')</a></li>
            <li class="{ tab=='main' && 'uk-active'}"><a class="uk-text-capitalize" onclick="{ toggleTab }" data-tab="main">@lang('Main')</a></li>
            <li class="{ tab=='forms' && 'uk-active'}"><a class="uk-text-capitalize" onclick="{ toggleTab }" data-tab="forms">@lang('Forms')</a></li>
            <li class="{ tab=='preview' && 'uk-active'}"><a class="uk-text-capitalize" onclick="{ toggleTab }" data-tab="preview">@lang('Content Preview')</a></li>
            <li class="{ tab=='gui' && 'uk-active'}"><a class="uk-text-capitalize" onclick="{ toggleTab }" data-tab="gui">@lang('GUI')</a></li>
            <li class="{ tab=='maintenance' && 'uk-active'}"><a class="uk-text-capitalize" onclick="{ toggleTab }" data-tab="maintenance">@lang('Maintenance Mode')</a></li>
        </ul>
    </div>

    <div class="uk-container-center uk-width-large-3-4">

        <div class="uk-width-1-1">

          <form class="" onsubmit="{ submit }">

              <div class="uk-width-1-1" show="{tab=='setup'}">
                  
                  <p>To do: Create collections, singletons and forms from default templates</p>
                  
              </div>

              <div class="uk-width-1-1" show="{tab=='main'}">

                  <div class="uk-panel-box uk-panel-card uk-margin">

                      <div class="uk-grid uk-grid-small uk-grid-match">

                          <div class="uk-panel-box uk-width-1-3">
                              <label class="uk-display-block uk-margin-small">
                                  @lang('Name of your pages collection')
                                  <i class="uk-icon-info-circle uk-margin-small-left" title="@lang('Default: pages')" data-uk-tooltip></i>
                              </label>
                              <field-text bind="config.pages"></field-text>
                          </div>

                          <div class="uk-panel-box uk-width-1-3">
                              <label class="uk-display-block uk-margin-small">
                                  @lang('Name of your posts collection')
                                  <i class="uk-icon-info-circle uk-margin-small-left" title="@lang('Default: posts')" data-uk-tooltip></i>
                              </label>
                              <field-text bind="config.posts"></field-text>
                          </div>

                          <div class="uk-panel-box uk-width-1-3">
                              <label class="uk-display-block uk-margin-small">
                                  @lang('Field name for slugs')
                                  <i class="uk-icon-info-circle uk-margin-small-left" title="@lang('Default: _id (no slugs)')" data-uk-tooltip></i>
                              </label>
                              <field-text bind="config.slugName"></field-text>
                          </div>

                          <div class="uk-panel-box uk-width-1-3">
                              <label class="uk-display-block uk-margin-small">
                                  @lang('isMultilingual')
                                  <i class="uk-icon-info-circle uk-margin-small-left" title="@lang('Use the UniqueSlugs addon to localize slug fields.')" data-uk-tooltip></i>
                              </label>
                              <field-boolean bind="config.isMultilingual" label="@lang('isMultilingual')"></field-boolean>
                          </div>

                          <div class="uk-panel-box uk-width-1-3">
                              <label class="uk-display-block uk-margin-small">
                                  @lang('displayBreadcrumbs')
                                  <i class="uk-icon-info-circle uk-margin-small-left" title="@lang('')" data-uk-tooltip></i>
                              </label>
                              <field-boolean bind="config.displayBreadcrumbs" label="@lang('displayBreadcrumbs')"></field-boolean>
                          </div>

                          <div class="uk-panel-box uk-width-1-1">
                              <label class="uk-display-block uk-margin-small">
                                  @lang('preRenderFields')
                                  <i class="uk-icon-info-circle uk-margin-small-left" title="@lang('for markdown fields or to normalize relative links in localized wysiwyg fields...')" data-uk-tooltip></i>
                              </label>
                              <field-tags bind="config.preRenderFields" autocomplete="{fieldnames}" placeholder="@lang('Add field name')"></field-tags>
                          </div>

                      </div>

                  </div>

                  <div class="uk-panel-box uk-panel-card uk-margin">

                      <label class="uk-display-block uk-margin-small">@lang('lexy')</label>
                      
                      <div class="uk-panel-box uk-panel-card uk-margin">

                          <label class="uk-display-block uk-margin-small">@lang('@headerimage')</label>

                          <div class="uk-grid uk-grid-small uk-grid-match">
                              <div class="uk-panel-box uk-width-1-4">
                                  <label class="uk-display-block uk-margin-small">
                                      @lang('width')
                                      <i class="uk-icon-info-circle uk-margin-small-left" title="@lang('')" data-uk-tooltip></i>
                                  </label>
                                  <field-text bind="config.lexy.headerimage.width"></field-text>
                              </div>
                              <div class="uk-panel-box uk-width-1-4">
                                  <label class="uk-display-block uk-margin-small">
                                      @lang('height')
                                      <i class="uk-icon-info-circle uk-margin-small-left" title="@lang('')" data-uk-tooltip></i>
                                  </label>
                                  <field-text bind="config.lexy.headerimage.height"></field-text>
                              </div>
                              <div class="uk-panel-box uk-width-1-4">
                                  <label class="uk-display-block uk-margin-small">
                                      @lang('quality')
                                      <i class="uk-icon-info-circle uk-margin-small-left" title="@lang('')" data-uk-tooltip></i>
                                  </label>
                                  <field-text bind="config.lexy.headerimage.quality"></field-text>
                              </div>
                              <div class="uk-panel-box uk-width-1-4">
                                  <label class="uk-display-block uk-margin-small">
                                      @lang('method')
                                      <i class="uk-icon-info-circle uk-margin-small-left" title="@lang('')" data-uk-tooltip></i>
                                  </label>
                                  <field-text bind="config.lexy.headerimage.method"></field-text>
                              </div>
                          </div>

                      </div>
                      
                      <div class="uk-panel-box uk-panel-card uk-margin">

                          <label class="uk-display-block uk-margin-small">@lang('@logo')</label>

                          <div class="uk-grid uk-grid-small uk-grid-match">
                              <div class="uk-panel-box uk-width-1-4">
                                  <label class="uk-display-block uk-margin-small">
                                      @lang('width')
                                      <i class="uk-icon-info-circle uk-margin-small-left" title="@lang('')" data-uk-tooltip></i>
                                  </label>
                                  <field-text bind="config.lexy.logo.width"></field-text>
                              </div>
                              <div class="uk-panel-box uk-width-1-4">
                                  <label class="uk-display-block uk-margin-small">
                                      @lang('height')
                                      <i class="uk-icon-info-circle uk-margin-small-left" title="@lang('')" data-uk-tooltip></i>
                                  </label>
                                  <field-text bind="config.lexy.logo.height"></field-text>
                              </div>
                              <div class="uk-panel-box uk-width-1-4">
                                  <label class="uk-display-block uk-margin-small">
                                      @lang('quality')
                                      <i class="uk-icon-info-circle uk-margin-small-left" title="@lang('')" data-uk-tooltip></i>
                                  </label>
                                  <field-text bind="config.lexy.logo.quality"></field-text>
                              </div>
                          </div>

                      </div>
                      
                      <div class="uk-panel-box uk-panel-card uk-margin">

                          <label class="uk-display-block uk-margin-small">@lang('@image')</label>

                          <div class="uk-grid uk-grid-small uk-grid-match">
                              <div class="uk-panel-box uk-width-1-4">
                                  <label class="uk-display-block uk-margin-small">
                                      @lang('width')
                                      <i class="uk-icon-info-circle uk-margin-small-left" title="@lang('')" data-uk-tooltip></i>
                                  </label>
                                  <field-text bind="config.lexy.image.width"></field-text>
                              </div>
                              <div class="uk-panel-box uk-width-1-4">
                                  <label class="uk-display-block uk-margin-small">
                                      @lang('height')
                                      <i class="uk-icon-info-circle uk-margin-small-left" title="@lang('')" data-uk-tooltip></i>
                                  </label>
                                  <field-text bind="config.lexy.image.height"></field-text>
                              </div>
                              <div class="uk-panel-box uk-width-1-4">
                                  <label class="uk-display-block uk-margin-small">
                                      @lang('quality')
                                      <i class="uk-icon-info-circle uk-margin-small-left" title="@lang('')" data-uk-tooltip></i>
                                  </label>
                                  <field-text bind="config.lexy.image.quality"></field-text>
                              </div>
                              <div class="uk-panel-box uk-width-1-4">
                                  <label class="uk-display-block uk-margin-small">
                                      @lang('method')
                                      <i class="uk-icon-info-circle uk-margin-small-left" title="@lang('')" data-uk-tooltip></i>
                                  </label>
                                  <field-text bind="config.lexy.image.method"></field-text>
                              </div>
                          </div>

                      </div>
                      
                      <div class="uk-panel-box uk-panel-card uk-margin">

                          <label class="uk-display-block uk-margin-small">@lang('@thumbnail')</label>

                          <div class="uk-grid uk-grid-small uk-grid-match">
                              <div class="uk-panel-box uk-width-1-4">
                                  <label class="uk-display-block uk-margin-small">
                                      @lang('width')
                                      <i class="uk-icon-info-circle uk-margin-small-left" title="@lang('')" data-uk-tooltip></i>
                                  </label>
                                  <field-text bind="config.lexy.thumbnail.width"></field-text>
                              </div>
                              <div class="uk-panel-box uk-width-1-4">
                                  <label class="uk-display-block uk-margin-small">
                                      @lang('height')
                                      <i class="uk-icon-info-circle uk-margin-small-left" title="@lang('')" data-uk-tooltip></i>
                                  </label>
                                  <field-text bind="config.lexy.thumbnail.height"></field-text>
                              </div>
                              <div class="uk-panel-box uk-width-1-4">
                                  <label class="uk-display-block uk-margin-small">
                                      @lang('quality')
                                      <i class="uk-icon-info-circle uk-margin-small-left" title="@lang('')" data-uk-tooltip></i>
                                  </label>
                                  <field-text bind="config.lexy.thumbnail.quality"></field-text>
                              </div>
                              <div class="uk-panel-box uk-width-1-4">
                                  <label class="uk-display-block uk-margin-small">
                                      @lang('method')
                                      <i class="uk-icon-info-circle uk-margin-small-left" title="@lang('')" data-uk-tooltip></i>
                                  </label>
                                  <field-text bind="config.lexy.thumbnail.method"></field-text>
                              </div>
                          </div>

                      </div>

                  </div>

              </div>

              <div class="uk-width-1-1" show="{tab=='forms'}">

                  <div class="uk-panel-box">
                      <div class="uk-grid uk-grid-small uk-grid-match">

                          <div class="uk-panel-box uk-width-1-3">
                              <label class="uk-display-block uk-margin-small">
                                  @lang('Name of your contact form')
                                  <i class="uk-icon-info-circle uk-margin-small-left" title="@lang('Default: contact')" data-uk-tooltip></i>
                              </label>
                              <field-text bind="config.contact"></field-text>
                          </div>

                          <div class="uk-panel-box uk-width-1-3">
                              <label class="uk-display-block uk-margin-small">
                                  @lang('formIdPrefix')
                                  <i class="uk-icon-info-circle uk-margin-small-left" title="@lang('Default: mp_form_')" data-uk-tooltip></i>
                              </label>
                              <field-text bind="config.formIdPrefix"></field-text>
                          </div>

                          <div class="uk-panel-box uk-width-1-3">
                              <label class="uk-display-block uk-margin-small">
                                  @lang('formSubmitButtonName')
                                  <i class="uk-icon-info-circle uk-margin-small-left" title="@lang('Default: submit')" data-uk-tooltip></i>
                              </label>
                              <field-text bind="config.formSubmitButtonName"></field-text>
                          </div>

                          <div class="uk-panel-box uk-width-1-3">
                              <label class="uk-display-block uk-margin-small">
                                  @lang('formSendReferer')
                                  <i class="uk-icon-info-circle uk-margin-small-left" title="@lang('Send the referer url when sending the contact form. You have to create an invisible field named \'referer\' in the form validator manually.')" data-uk-tooltip></i>
                              </label>
                              <field-boolean bind="config.formSendReferer" label="@lang('formSendReferer')"></field-boolean>
                          </div>

                      </div>
                  </div>
              </div>

              <div class="uk-width-1-1" show="{tab=='preview'}">

                  <div class="uk-panel-box">
                      <div class="uk-grid uk-grid-small uk-grid-match">

                          <div class="uk-panel-box uk-width-1-3">
                              <label class="uk-display-block uk-margin-small">
                                  @lang('isPreviewEnabled')
                                  <i class="uk-icon-info-circle uk-margin-small-left" title="@lang('You have to enable the preview in your collection settings manually.')" data-uk-tooltip></i>
                              </label>
                              <field-boolean bind="config.isPreviewEnabled" label="@lang('use content preview')"></field-boolean>
                          </div>

                          <div class="uk-panel-box uk-width-1-4">
                              <label class="uk-display-block uk-margin-small">
                                  @lang('previewMethod')
                                  <i class="uk-icon-info-circle uk-margin-small-left" title="@lang('default and implemented: HTML - If you want to use JSON, you have to built your own content preview.')" data-uk-tooltip></i>
                              </label>
                              <select bind="config.previewMethod">
                                  <option value="html">HTML</option>
                                  <option value="json">JSON</option>
                              </select>
                          </div>

                          <div class="uk-panel-box uk-width-1-4">
                              <label class="uk-display-block uk-margin-small">
                                  @lang('previewDelay')
                                  <i class="uk-icon-info-circle uk-margin-small-left" title="@lang('in milliseconds, default: 0. Adjust this value, if you have problems with massive requests while editing a page in content preview.')" data-uk-tooltip></i>
                              </label>
                              <field-text type="number" step="500" min="0" bind="config.previewDelay"></field-text>
                          </div>

                          <div class="uk-panel-box uk-width-1-1">
                              <label class="uk-display-block uk-margin-small">
                                  @lang('livePreviewToken')
                              </label>
                              <div class="uk-grid uk-grid-small uk-grid-match">

                                  <div class="uk-width-1-3">
                                      <field-text bind="config.livePreviewToken"></field-text>
                                  </div>

                                  <a class="uk-icon-refresh uk-margin-left" onclick="{ generateToken }" style="pointer-events:auto;" title="@lang('Generate Token')" data-uk-tooltip></a>

                                  <code>{{ $app->getSiteUrl() }}/livePreview?token={ config.livePreviewToken }</code>
                                  <i class="uk-icon-info-circle uk-margin-small-left" title="@lang('Copy this link to your collections content preview settings. You might have to adjust it, if you use subfolders.')" data-uk-tooltip></i>

                              </div>
                          </div>

                      </div>
                  </div>

              </div>

              <div class="uk-width-1-1" show="{tab=='gui'}">

                  <div class="uk-panel-box">
                      <div class="uk-grid uk-grid-small">

                          <div class="uk-panel-box uk-width-1-2">
                              <label class="uk-display-block uk-margin-small">
                                  @lang('guiDisplayCustomNav')
                              </label>
                              <field-boolean bind="config.guiDisplayCustomNav" label="@lang('Display custom nav in backend')"></field-boolean>
                          </div>

                      </div>
                  </div>

              </div>

              <div class="uk-width-1-1" show="{tab=='maintenance'}">

                  <div class="uk-panel-box">
                      <div class="uk-grid uk-grid-small">

                          <div class="uk-panel-box uk-width-1-4">
                              <label class="uk-display-block uk-margin-small">
                                  @lang('isInMaintenanceMode')
                                  <i class="uk-icon-info-circle uk-margin-small-left" title="@lang('Display an \'under construction\' page to your visitors.')" data-uk-tooltip></i>
                              </label>
                              <field-boolean bind="config.isInMaintenanceMode" label="@lang('Maintenance mode')"></field-boolean>
                          </div>

                          <div class="uk-panel-box uk-width-1-2">
                              <label class="uk-display-block uk-margin-small">
                                  @lang('allowedIpsInMaintenanceMode')
                                  <i class="uk-icon-info-circle uk-margin-small-left" title="@lang('enter ip addresses, that can access the page while maintenance mode is enabled. Separate them with whitespaces.')" data-uk-tooltip></i>
                              </label>
                              <field-text class="uk-width-3-4 uk-margin-small-top" bind="config.allowedIpsInMaintenanceMode"></field-text>
                          </div>

                          <div class="uk-panel-box uk-width-1-4">
                              <label class="uk-display-block uk-margin-small">
                                  @lang('Add your current IP to the list.')
                              </label>
                              <a href="#" class="uk-button" onclick="{ addCurrentIp }">@lang('Add current ip')</a>
                          </div>

                      </div>
                  </div>

              </div>

              <cp-actionbar>
                  <div class="uk-container uk-container-center">
                      <button class="uk-button uk-button-large uk-button-primary">@lang('Save')</button>
                      <a class="uk-button uk-button-link" href="@route('/settings')">
                          <span>@lang('Cancel')</span>
                      </a>
                  </div>
              </cp-actionbar>

          </form>
        </div>

    </div>
  </div>

    <cp-inspectobject ref="inspect"></cp-inspectobject>


    <script type="view/script">

        var $this = this;

        riot.util.bind(this);

        this.config  = {{ !empty($config) ? json_encode($config) : '{}' }};
        this.fields  = {{ json_encode($fieldnames) }};
        this.tab     = 'main';
        // this.tab     = 'preview';

        this.on('mount', function() {

            // bind global command + save
            Mousetrap.bindGlobal(['command+s', 'ctrl+s'], function(e) {
                e.preventDefault();
                $this.submit();
                return false;
            });

            this.update();

        });

        this.on('update', function() {

        });

        submit(e) {

            if (e) e.preventDefault();

            App.request('/cpmultiplane/saveConfig', {config:this.config}).then(function(data) {

               if (data) {
                   App.ui.notify("Saving successful", "success");
                } else {
                    App.ui.notify("Saving failed.", "danger");
                }

            });

        }
        

        toggleTab(e) {
            this.tab = e.target.getAttribute('data-tab');
        }
        
        addCurrentIp(e) {

            if (e) e.preventDefault();

            if (!this.config.allowedIpsInMaintenanceMode) {
                this.config.allowedIpsInMaintenanceMode = '{{ $app->getClientIp() }}';
            } else {
                this.config.allowedIpsInMaintenanceMode += ' {{ $app->getClientIp() }}';
            }

        }

        generateToken() {
            this.config.livePreviewToken = App.Utils.generateToken(120);
        }

        showEntryObject() {
            $this.refs.inspect.show($this.config);
            $this.update();
        }

/* 
        // load README as help page
        loadReadme(e) {

            var content = document.getElementById('help-content');

            if (content.innerHTML == '') {

                App.request('/cpmultiplane/getReadme', null, 'html').then(function(data) {
                    content.innerHTML = data;
                });

            }

        }
 */
    </script>

</div>
