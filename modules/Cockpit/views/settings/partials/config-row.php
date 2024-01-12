<?php
if(!isset($level) || $level === 0)
    $root_cfg = $config;

$app = \Lime\App::instance($root_cfg['app.name']);

$prefix = $prefix ?? '';
?>
<div style="margin-left: {{10*$level}}px">
    @if(empty($config))
        <div class="uk-alert uk-alert-warning">
            <p>@lang('No settings found.')</p>
        </div>
    @endif
    @foreach($config as $key => $val)
        <?php
        if(in_array($key, $omit))
            continue;

        $sk = str_replace(['.#', '.'],['.', '_DOT_'], $key);
        //$sk = $key;

        $app->configurableSettings[$prefix.$sk] = $key;
        ?>

        @if(is_array($val))
            <h4>{{$key}}</h4>
            <?php
            echo $app->renderer->file($rowTplFile, array_merge(
                compact('omit', 'rowTplFile', 'label_map_en', 'root_cfg'), [
                    'config' => $val,
                    'level' => $level+1,
                    'prefix' => $prefix.$key.'.'
                ])
            );
            ?>
        @else
            <div id="config--{{$prefix.$sk}}" class="uk-form-row" style="background-color: rgba(255,255,255, 0.8); padding: 20px; border: 1px solid lightgrey">
                <label class="uk-text-small">@lang($prefix . ($label_map_en[$key] ?? $key)) <small class="uk-float-right">({{$prefix . $key}})</small></label>
                @if(gettype($val) === 'boolean')
                    <div class="uk-margin-small-top">
                        <field-boolean bind="settings.{{$prefix.$sk}}"></field-boolean>
                    </div>
                @else
                    <input class="uk-width-1-1 uk-form-large" type="text" bind="settings.{{$prefix.$sk}}" autocomplete="off">
                @endif
            </div>
        @endif
    @endforeach
</div>
