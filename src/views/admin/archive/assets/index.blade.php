@extends('maintenance::layouts.pages.admin.panel')

@section('panel.extra.top')
    @include('maintenance::assets.modals.search', array(
        'url' => route('maintenance.admin.archive.assets.index', Input::only('field', 'sort'))
    ))
@stop

@section('panel.head.content')
    <div class="btn-toolbar">
        <a href="#" class="btn btn-primary" data-target="#search-modal" data-toggle="modal"
           title="Filter results">
            <i class="fa fa-search"></i>
            Search
        </a>
    </div>
@stop

@section('panel.body.content')

    @if($assets->count() > 0)
        {{
            $assets->columns(array(
                    'id' => 'ID',
                    'name' => 'Name',
                    'location' => 'Location',
                    'category' => 'Category',
                    'condition' => 'Condition',
                    'added_on' => 'Added On',
                    'action' => 'Action'
            ))
            ->means('location', 'location.trail')
            ->means('category', 'category.trail')
            ->means('added_on', 'created_at')
            ->modify('action', function($asset){
                return $asset->viewer()->btnActionsArchive;
            })
            ->sortable(array(
                'id',
                'name',
                'location' => 'location_id',
                'category' => 'category_id',
                'condition',
                'added_on' => 'created_at',
            ))
            ->hidden(array('id', 'added_on', 'location', 'condition'))
            ->showPages()
            ->render()
        }}
    @else

        <h5>There are no archived assets to display.</h5>

    @endif

@stop