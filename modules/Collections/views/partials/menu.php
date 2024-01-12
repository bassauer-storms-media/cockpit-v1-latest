<div class="uk-margin-large-top uk-margin-bottom">
    <span class="uk-text-upper uk-text-small uk-text-bold">
        @lang('Collections')
        <a href="@route('/collections')">&raquo; @lang('View all')</a>
    </span>
</div>

<ul class="uk-grid uk-grid-small uk-grid-width-1-2 uk-grid-width-medium-1-4 uk-text-center">
    @foreach($collections as $collection)
        <li>
            <a class="uk-display-block uk-panel-card-hover uk-panel-box uk-panel-space " href="@route('/collections/entries/'.$collection['name'])">
                <div class="uk-svg-adjust">
                    @if(!empty($collection['icon']))
                        <img src="@url('assets:app/media/icons/'){{$collection['icon']}}" alt="icon" data-uk-svg width="50" height="50">
                    @else
                        <img class="uk-svg-adjust" src="@base('collections:icon.svg')" width="50" height="50" data-uk-svg>
                    @endif
                </div>
                <div class="uk-text-truncate uk-text-small uk-margin-small-top">
                    {{ htmlspecialchars($collection['label'] ? $collection['label'] : $collection['name']) }}
                </div>
            </a>
        </li>
    @endforeach
</ul>

