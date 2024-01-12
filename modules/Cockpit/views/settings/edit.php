<div>
    <ul class="uk-breadcrumb">
        <li><a href="@route('/settings')">@lang('Settings')</a></li>
        <li class="uk-active"><span>@lang('System Settings')</span></li>
    </ul>
</div>

<style>
    picoedit .CodeMirror {
        height: auto;
    }
</style>

<div class="uk-margin-top" riot-view>

    <?php
    //d($app['config']);

    $omit = ['i18n', 'sec-key', 'session.name']; // settings that shall not be shown in the form

    $rowTplFile = 'modules/Cockpit/views/settings/partials/config-row.php'; // comp. for recursive rendering of config rows

    // translation map for labels (en only - all other langs come from the respective lang files)
    $label_map_en = [
        'debug' => 'Debug mode',
        'app.name' => 'App name',
        'base_url' => 'Base URL',
        'base_route' => 'Base route',
        'docs_root' => 'Docs root',
        'session.name' => 'Session name',
        'session.init' => 'Session init',
        'sec-key' => 'Sec key',
        'i18n' => 'I18n',
        'database.server' => 'Database server',
        'database.options.db' => 'Database options db',
        'database.driverOptions' => 'Database driver options',
        'memory.server' => 'Memory server',
        'memory.options' => 'Memory options',
        'paths.#root' => 'Paths root',
        'paths.#storage' => 'Paths storage',
        'paths.#pstorage' => 'Paths public storage',
        'paths.#data' => 'Paths data',
        'paths.#cache' => 'Paths cache',
        'paths.#tmp' => 'Paths tmp',
        'paths.#thumbs' => 'Paths thumbs',
        'paths.#uploads' => 'Paths uploads',
        'paths.#modules' => 'Paths modules',
        'paths.#addons' => 'Paths addons',
        'paths.#config' => 'Paths config',
        'paths.assets' => 'Paths assets',
        'paths.site' => 'Paths site',
        'filestorage' => 'Filestorage',
    ];
    ?>

    <form class="uk-form uk-grid" onsubmit="{ submit }">
        <div class="uk-width-medium-3-4">
            <?php
            $app->configurableSettings = [];

            ob_start();
            echo $app->renderer->file($rowTplFile, array_merge(compact('omit', 'rowTplFile', 'label_map_en'), [
                'config' => $app['config'],
                'level' => 0
            ]));
            $out = ob_get_clean();

            $configurableSettings = $app->configurableSettings;
            ?>

            <!--<ul>-->
            @foreach($configurableSettings as $k => $v)
                <!--<li>-->
                <!--    <a href="#">-->
                <!--    </a>-->
                <!--</li>-->
                <?php
                d($k, $v);
                ?>
            @endforeach
            <!--</ul>-->

            {{$out}}
        </div>
        <div class="uk-width-medium-1-4">
            <div class="uk-container uk-container-center" style="position: sticky; top: 90px">
                <button class="uk-button uk-button-large uk-button-primary">@lang('Save')</button>
                <a class="uk-button uk-button-large uk-button-link" href="@route('/settings/edit')">@lang('Cancel')</a>
            </div>
        </div>
    </form>

    @if ($configexists)

        @if (!is_writable($configexists))
            <div class="uk-alert uk-alert-danger">
                @lang('Custom config file is not writable').
            </div>
        @endif

        <picoedit
              path="{{ str_replace(COCKPIT_SITE_DIR.'/', '', $configexists) }}"
              height="auto"
              readonly="{ {{ !is_writable($configexists) ? 'true':'false'}} }"
        ></picoedit>
    @else
        <div class="uk-alert">
            @lang('Custom config file does not exist').
            <a class="uk-button uk-button-link" href="@route('/settings/edit/true')"><i class="uk-icon-magic"></i> @lang('Create config file')</a>
        </div>
    @endif

    <script @noOp()type="view/script">

        this.mixin(RiotBindMixin);

        var $this = this;

        this.settings = {{ str_replace(['.#', '.'],['.', '_DOT_'], json_encode($app['config'])) }};

        window.configurableSettings = {{ json_encode($configurableSettings) }};

        this.on('mount', function(){

            // bind global command + save
            Mousetrap.bindGlobal(['command+s', 'ctrl+s'], function(e) {
                e.preventDefault();
                $this.save();
                return false;
            });
        });

        submit(e) {

            if(e)
                e.preventDefault();

            let settings = {};
            _.forEach(window.configurableSettings, (val, key) => {
                if(this.settings[key] !== undefined && (_.isObject(this.settings[key]) && !_.isEmpty(this.settings[key]) || !_.isObject(this.settings[key])))
                    settings[val] = this.settings[key];
            });

            App.request('/media/api', {
                cmd: "writefile",
                content: convertToYAML(settings),
                path: "config/config.yaml"
            }).then((data) => {
                if (data) {
                    App.ui.notify("Saving successful", "success");
                    //$this.foobar = data;
                    //$this.update();

                } else {
                    App.ui.notify("Saving failed.", "danger");
                }
            });
        }

        function convertToYAML(obj, indent = 0) {
            let yamlString = '';

            // Helper function to add indentation
            function addIndentation(level) {
                return '  '.repeat(level);
            }

            // Check if the given value is an object
            function isObject(value) {
                return typeof value === 'object' && value !== null;
            }

            // Iterate over the object properties
            for (const key in obj) {
                if (obj.hasOwnProperty(key)) {
                    const value = obj[key];

                    // Add indentation
                    yamlString += addIndentation(indent);

                    // Append key
                    let surroundKeyWithQuotes = (key) => key;
                    if(key[0]==='#')
                        surroundKeyWithQuotes = (key) => '"' + key + '"';
                    yamlString += surroundKeyWithQuotes(key) + ': ';

                    // Check if the value is an object
                    if (isObject(value)) {
                        // Recursively convert nested object
                        yamlString += '\n' + convertToYAML(value, indent + 1);
                    } else {
                        // Add simple value
                        yamlString += JSON.stringify(value);
                    }

                    // Add newline
                    yamlString += '\n';
                }
            }

            return yamlString;
        }

    </script>

</div>
