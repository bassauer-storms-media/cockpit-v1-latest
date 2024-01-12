
<div class="dashboard-header-panel uk-container-breakout">

    <div class="uk-container uk-container-center">

        <div class="uk-grid uk-grid-width uk-grid-margin" data-uk-grid-margin>

            <div class="uk-width-medium-1-4">

                <div class="uk-flex">
                    <div riot-mount>
                        <cp-gravatar
                            email="{{ $app['user']['email'] }}"
                            size="87"
                            alt="{{ $app["user"]["name"] ? $app["user"]["name"] : $app["user"]["user"] }}">
                            <canvas
                                width="87"
                                height="87"
                            ></canvas>
                        </cp-gravatar>
                    </div>
                    <div class="uk-flex-item-1 uk-margin-left">
                        <div class="uk-h3 uk-text-bold uk-margin-small-top">
                            {{ $app['user/name'] ? $app['user/name'] : $app['user/user'] }}
                        </div>

                        <hr>

                        <div class="uk-flex uk-flex-middle">
                            <span class="uk-badge uk-margin-small-right">{{ $app['user/group'] }}</span>
                            <a class="uk-button uk-button-link uk-link-muted" href="@route('/accounts/account')"><img class="uk-margin-small-right inherit-color" src="@base('assets:app/media/icons/settings.svg')" width="20" height="20" data-uk-svg /> @lang('Account')</a>
                        </div>
                    </div>
                </div>

            </div>

            <div class="uk-width-medium-1-6">
                @trigger('admin.dashboard.header')
            </div>

        </div>

    </div>
</div>


<div id="dashboard">

    <div class="uk-margin">
        @trigger('admin.dashboard.top')
    </div>

    <div class="uk-grid uk-margin" data-uk-grid-margin>
        <div class="uk-width-medium-1-4" data-area="main">
            <div class="uk-sortable uk-grid uk-grid-gutter uk-grid-width-1-1" data-uk-sortable="{group:'dashboard',animation:false}">
                @foreach($areas['main'] as $widget)
                <div data-widget="{{ $widget['name'] }}">
                    {{ $widget['content'] }}
                </div>
                @endforeach
            </div>
        </div>
        <div class="uk-width-medium-3-4" data-area="aside-left">
            <div class="uk-sortable uk-grid uk-grid-gutter uk-grid-width-medium-1-1" data-uk-sortable="{group:'dashboard',animation:false}">
                @foreach($areas['aside-left'] as $widget)
                <div data-widget="{{ $widget['name'] }}">
                    {{ $widget['content'] }}
                </div>
                @endforeach
            </div>
        </div>
        <div class="uk-width-medium-1-4" data-area="aside-right">
            <div class="uk-sortable uk-grid uk-grid-gutter uk-grid-width-medium-1-1" data-uk-sortable="{group:'dashboard',animation:false}">
                @foreach($areas['aside-right'] as $widget)
                <div data-widget="{{ $widget['name'] }}">
                    {{ $widget['content'] }}
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="uk-margin">
        @trigger('admin.dashboard.bottom')
    </div>

</div>

<style>

    #dashboard .uk-grid.uk-sortable {
        min-height: 30vh;
    }

</style>

<script>

    App.$(function($){

        var data, dashboard = App.$('#dashboard').on('stop.uk.sortable', function(){

            data = {};

            dashboard.find('[data-area]').each(function(){
                var $a      = $(this),
                    area    = $a.data('area'),
                    widgets = $a.find('[data-widget]');

                widgets.each(function(prio){
                    data[this.getAttribute('data-widget')] = {
                        area: area,
                        prio: prio + 1
                    };
                });
            });

            App.request('/cockpit/savedashboard',{widgets:data}).then(function(){

            });
        });
    });

</script>
