@extends('maintenance::layouts.pages.main.panel')

@section('header')
    <h1>{{ $title }}</h1>
@stop

@section('breadcrumb')
    <li>
        <a href="{{ route('maintenance.work-orders.index') }}">
            <i class="fa fa-exclamation-triangle"></i>
            Work Requests
        </a>
    </li>
@stop

@section('panel.extra.top')


@stop

@section('panel.head.content')
    <div class="btn-toolbar">
        <a href="{{ route('maintenance.work-requests.create') }}" class="btn btn-primary">
            <i class="fa fa-plus"></i>
            New Work Request
        </a>
        <a href="#" class="btn btn-primary" data-target="#search-modal" data-toggle="modal" title="Filter results">
            <i class="fa fa-search"></i>
            Search
        </a>
    </div>
@stop

@section('panel.body.content')

    @if($workRequests->count() > 0)

        {{

            $workRequests
                ->columns(array(
                    'id' => 'ID',
                    'user' => 'Created By',
                    'subject' => 'Subject',
                    'description' => 'Description',
                    'action' => 'Action',
                ))
                ->means('user', 'user.full_name')
                ->modify('action', function($workRequest){
                    return $workRequest->viewer()->btnActions;
                })
                ->render()

        }}

    @else

        <h5>There are no work requests to display.</h5>

    @endif

@stop