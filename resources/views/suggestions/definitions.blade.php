@extends('layouts.master')

@section('meta-description', 'List and vote for new definitions')

@section('title', 'New definition suggestions')

@section('content')
<div class="row">
    <div class="col-md-3">
        @include('suggestions.menu')
    </div>
    <div class="col-md-9">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12">
                        <form method="GET" action="/suggestions/definitions" class="form-horizontal">
                            @include('suggestions.filter')
                        </form>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-condensed table-striped">
                            <thead>
                                <tr>
                                    <th class="col-xs-9">Definitions</th>
                                    <th class="col-xs-1 text-center">Votes</th>
                                    
                                    @if (Auth::check() && ! (Auth::user()->role_id < 1000))
                                        <th class="col-xs-1 text-center">Approve</th>
                                        <th class="col-xs-1 text-center">Reject</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @if ( ! $definitions->isEmpty())
                                    @foreach($definitions as $definition)
                                        <tr>
                                            <td class="vertical-center-cell">
                                                {{ $definition->definition }}
                                                <br>
                                                <a href="{{action('TermsController@show', $definition->term->slug)}}">
                                                    {{ $definition->term->term }} ({{ $definition->term->status->status }})</a>
                                            </td>

                                            <td class="text-center vertical-center-cell">
                                                {{ $definition->votes_sum }}
                                            </td>

                                            @if (Auth::check() && ! (Auth::user()->role_id < 1000))
                                            <td class="text-center vertical-center-cell">
                                                <form method="POST" action="{{ action('DefinitionsController@approve', [$definition->id]) }}">
                                                    @include('suggestions.forms.approve')
                                                </form>
                                            </td>
                                            <td class="text-center vertical-center-cell">
                                                <form method="POST" action="{{ action('DefinitionsController@reject', [$definition->id]) }}">
                                                    @include('suggestions.forms.reject')
                                                </form>
                                            </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                @else
                                {{--No definitions for selected language and field--}}
                                <td colspan="3"><span class="text-info">No definitons</span></td>
                                <td></td><td></td><td></td>

                                @endif
                            </tbody>
                        </table>
                        @if (isset($definitions) && ! ($definitions->isEmpty()))
                            {!! $definitions->appends($termFilters)->render() !!}
                        @endif
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>

@endsection
