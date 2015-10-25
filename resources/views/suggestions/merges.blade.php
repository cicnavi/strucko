@extends('layouts.master')

@section('meta-description', 'List and manage all synonym merge suggestions')

@section('title', 'Synonym merge suggestions')

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
                        <form method="GET" action="/suggestions/merges" class="form-horizontal">
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
                                    <th class="col-xs-3">Term</th>
                                    <th class="col-xs-4">Merge with</th>
                                    <th class="col-xs-1 text-center">Votes</th>
                                    @if (Auth::check() && ! (Auth::user()->role_id < 1000))
                                        <th class="col-xs-1 text-center">Approve</th>
                                        <th class="col-xs-1 text-center">Reject</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                {{-- We have to have at least language_id --}}
                                @if(isset($termFilters['language_id']))
                                    @if(isset($mergeSuggestions) && ! ($mergeSuggestions->isEmpty()))
                                        @foreach($mergeSuggestions as $mergeSuggestion)
                                            <tr>
                                                <td class="vertical-center-cell">
                                                    <a class="btn-link btn-lg" href="{{ action('TermsController@show', ['slug' => $mergeSuggestion->term->slug]) }}">
                                                        {{ $mergeSuggestion->term->term }}</a>
                                                    <br><small>merge suggested by <i>{{ $mergeSuggestion->user->name }}</i></small>
                                                </td>

                                                <td class="vertical-center-cell">
                                                    @foreach($mergeSuggestion->concept->terms as $key => $suggestedTerm)
                                                        @if(is_last($mergeSuggestion->concept->terms, $key))
                                                            {{ $suggestedTerm->term }}
                                                        @else
                                                            {{ $suggestedTerm->term }},
                                                        @endif
                                                    @endforeach
                                                </td>

                                                <td class="text-center vertical-center-cell">
                                                    {{ $mergeSuggestion->votes_sum }}
                                                </td>

                                                @if (Auth::check() && ! (Auth::user()->role_id < 1000))
                                                <td class="text-center vertical-center-cell">
                                                    todo
                                                </td>
                                                <td class="text-center vertical-center-cell">
                                                    todo
                                                </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    @else
                                    {{--No merge suggestions --}}
                                        <td colspan="3"><span class="text-warning">No merge suggestions...</span></td>
                                    @endif
                                @else
                                {{-- language_id is not set --}}
                                    <td colspan="3"><span class="text-warning">Please filter by language at least</span></td>
                                @endif
                            </tbody>
                        </table>
                        
                        @if(isset($mergeSuggestions) && ! ($mergeSuggestions->isEmpty()))
                            {!! $mergeSuggestions->appends($termFilters)->render() !!}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
