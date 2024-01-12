<div>

    <div class="uk-panel-box uk-panel-card">

        <div class="uk-panel-box-header uk-flex uk-flex-middle">
            <strong class="uk-panel-box-header-title uk-flex-item-1">
                @lang('Collections')

                @hasaccess?('collections', 'create')
                <a href="@route('/collections/collection')" class="uk-icon-plus uk-margin-small-left" title="@lang('Create Collection')" data-uk-tooltip></a>
                @end
            </strong>
            @if(count($collections))
            <span class="uk-badge uk-flex uk-flex-middle"><span>{{ count($collections) }}</span></span>
            @endif
        </div>

        @if(count($collections))

            <div class="uk-margin uk-clearfix">

                <div class="uk-list uk-list-space uk-margin-top uk-grid">
                    @foreach($collections as $col)
                        <div class="uk-grid uk-grid-small uk-column-medium-1-2 uk-margin-remove">
                            <div class="uk-flex-item-1 uk-text-truncate">
                                <a class="uk-link-muted" href="@route('/collections/entries/'.$col['name'])">

                                    <img class="uk-margin-small-right uk-svg-adjust" src="@url(isset($col['icon']) && $col['icon'] ? 'assets:app/media/icons/'.$col['icon']:'collections:icon.svg')" width="18px" alt="icon" data-uk-svg>

                                    {{ htmlspecialchars(@$col['label'] ? $col['label'] : $col['name'], ENT_QUOTES, 'UTF-8') }}
                                </a>
                            </div>
                            <div>
                                @if($app->module('collections')->hasaccess($col['name'], 'entries_create'))
                                <a class="uk-text-muted" href="@route('/collections/entry')/{{ $col['name'] }}" title="@lang('Add entry')" aria-label="@lang('Add entry')" data-uk-tooltip="pos:'right'">
                                    <img src="@url('assets:app/media/icons/plus-circle.svg')" width="1.2em" data-uk-svg />
                                </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>

            @if(count($collections) > 5)
            <div class="uk-panel-box-footer uk-text-center">
                <a class="uk-button uk-button-small uk-button-link" href="@route('/collections')">@lang('Show all')</a>
            </div>
            @endif

        @else

            <div class="uk-margin uk-text-center uk-text-muted">

                <p>
                    <img src="@url('collections:icon.svg')" width="30" height="30" alt="Collections" data-uk-svg />
                </p>

                @lang('No collections')
            </div>

        @endif

    </div>

</div>
