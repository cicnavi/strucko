@extends('layouts.master')

@section('meta-description', $showMeta['description'])

@section('title', $showMeta['title'])

@section('content')

<div class="row">

    <div class="col-xs-12 text-right">
        <a class="btn-link btn-lg"
           href="{{ resolveUrlAsAction('TermsController@index', [
               'language_id' => $term->language_id,
               'scientific_field_id' => $term->scientific_field_id,
               'menu_letter' => $term->menu_letter,
           ] + Session::get('termShowFilters')) }}">
            {{ trans('languages.' . str_replace(' ', '_', $term->language->ref_name)) }}, {{ $term->menu_letter }}
        </a>
    </div>
</div>
<div class="row">
    <div class="col-xs-2 text-center vertical-center">
        <div class="center-block">
            @include('votes.form_term')
        </div>
    </div>
    <div class="col-xs-10 vertical-center">
        <h1>
            <span lang="{{ $term->language->part1 }}">{{ $term->term }}</span>
            <small>
                {{ trans('partofspeeches.' . str_replace(' ', '_', $term->partOfSpeech->part_of_speech)) }}
            </small>
            {!! $term->status->id < 1000 ?
            status_warning(trans('statuses.' . str_replace(' ', '_', $term->status->status))) : '' !!}
        </h1>
        <small>
            <strong>
                {{ trans('languages.' . str_replace(' ', '_', $term->language->ref_name)) }},
                {{ trans('scientificfields.' . str_replace(' ', '_', $term->scientificField->scientific_field)) }};
            </strong>
        </small>
        <small> 
            {{ trans('terms.show.addedby') }}
            <em> {{ $term->user->name }} </em>
        </small>
    </div>
</div>

<hr>

<!-- Definitions -->
<div class="row">
    <div class="col-xs-12">
        <table class="table table-condensed">
            <thead>
                <tr>
                    <th class="col-xs-9">
                        {{ trans('terms.show.definitions') }}
                        ({{ trans('languages.' . str_replace(' ', '_', $term->language->ref_name)) }})
                    </th>
                    <th class="col-xs-1"></th>
                    <th class="col-xs-1 text-center">{{ trans('terms.show.votes') }}</th>
                    <th class="col-xs-1"></th>
                </tr>
            </thead>
            <tbody>
                @unless($term->definitions->isEmpty())
                    @foreach ($term->definitions as $definition)
                        <tr>
                            <td class="vertical-center-cell">
                                <span lang="{{ $definition->language->part1 }}">{{ $definition->definition }}</span>
                                {!! $definition->status->id < 1000 ?
                                    status_warning(trans('statuses.' . str_replace(' ', '_', $definition->status->status))) : '' !!}
                                @if($definition->source)
                                    @if($definition->link)
                                        <br>
                                        <small>
                                            {{ trans('terms.show.source') }} 
                                            <a href="{{ $definition->link }}" target="_blank">
                                                {{ $definition->source }}</a>
                                        </small>
                                    @else
                                        <br>
                                        <small>{{ trans('terms.show.source') }} {{ $definition->source }}</small>
                                    @endif
                                @endif

                                <br>
                                <small>
                                    {{ trans('terms.show.addedby') }}
                                    <em>{{ $definition->user->name }}</em>
                                </small>
                                <br>
                                @if (Auth::check() && Auth::user()->role_id == 1000 || $definition->user_id == Auth::id())
                                <a href="{{ resolveUrlAsAction('DefinitionsController@edit', ['id' => $definition->id])}}"
                                   class="">edit</a>
                                @endif
                            </td>
                            {{-- Votes for definition --}}
                            @include('votes.form_definition_table')
                        </tr>
                    @endforeach
                    {{-- I have to null $definition variable, so it does not
                    appear in 'Suggest definition' form--}}
                    {{ $definition = null }}
                @else
                    <tr><td><span class="text-warning">
                                    {{ trans('terms.show.nodefinitions') }}
                                </span></td></tr>
                @endunless
            </tbody>
        </table>
    </div>
</div>

<div class="row">
    <!-- Translations -->
    <div class="col-sm-6">

        <table class="table table-condensed">
            <thead>
                <tr>
                    <th class="col-xs-9">
                        {{ trans('terms.show.translations') }} 
                        ({{ trans('languages.' . str_replace(' ', '_', $translateToLanguage['ref_name'])) }})

                    </th>
                    <th class="col-xs-1"></th>
                    <th class="col-xs-1 text-center">{{ trans('terms.show.votes') }}</th>
                    <th class="col-xs-1"></th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <td class="text-right" colspan="4">
                    {{-- Show link to terms in translated language --}}
                        <a class="btn-link"
                           href="{{ resolveUrlAsAction('TermsController@index', [
                               'language_id' => $translateToLanguage['id'],
                               'scientific_field_id' => $term->scientific_field_id,
                               'translate_to' => $term->language_id,
                           ])}}">
                            {{ trans('terms.show.allterms') }}
                            ({{ trans('languages.' . str_replace(' ', '_', $translateToLanguage['ref_name'])) }})</a>
                    </td>
                </tr>
            </tfoot>
            <tbody>
                
                @if ($term->relationLoaded('translations'))
                    @unless($term->translations->isEmpty())
                        @foreach($term->translations as $translation)
                        <tr>
                            <td class="vertical-center-cell">
                                <a lang="{{ $translation->translation->language->part1 }}"
                                    href="{{ resolveUrlAsAction('TermsController@show', [
                                            'slug' => $translation->translation->slug,
                                            'translate_to' => $term->language_id
                                        ])}}">
                                            {{ $translation->translation->term }}</a>
                                    
                                {!! $translation->status->id < 1000 ?
                                    status_warning(trans('statuses.' . str_replace(' ', '_', $translation->status->status))) : '' !!}
                                <br>
                                <small>
                                    {{ trans('terms.show.addedby') }}
                                    <i>{{ $translation->user->name }}</i>
                                </small>
                            </td>

                            {{-- Votes for translations --}}
                            @include('votes.form_translation_table')

                        </tr>
                        @endforeach
                    @else
                        {{-- No translations, translations is empty --}}
                        <tr>
                            <td><span class="text-warning">
                                    {{ trans('terms.show.notranslations') }}
                                </span>
                            </td>
                        </tr>
                    @endunless
                @endif
            </tbody>
        </table>

    </div>

    <!-- Synonyms -->
    <div class="col-sm-6">
        <table class="table table-condensed">
            <thead>
                <tr>
                    <th class="col-xs-9">
                        {{ trans('terms.show.synonyms') }}
                        ({{ trans('languages.' . str_replace(' ', '_', $term->language->ref_name)) }})
                    </th>
                    <th class="col-xs-1"></th>
                    <th class="col-xs-1 text-center">{{ trans('terms.show.votes') }}</th>
                    <th class="col-xs-1"></th>
                </tr>
            </thead>
            <tbody>
                @if( ! $term->synonyms->isEmpty())
                    @foreach($term->synonyms as $synonym)
                    <tr>
                        <td class="vertical-center-cell">
                            {{--Depending on the translate_to query,
                                show the appropriate link with translate_to--}}
                            @if(Session::has('termShowFilters')
                                && (null !== Session::get('termShowFilters.translate_to')))
                                <a lang="{{ $synonym->synonym->language->part1 }}"
                                    href="{{ resolveUrlAsAction('TermsController@show', [
                                    'slug' => $synonym->synonym->slug,
                                    'translate_to' => Session::get('termShowFilters.translate_to')
                                ])}}">
                                    {{ $synonym->synonym->term }}</a>
                            @else
                            <a lang="{{ $synonym->synonym->language->part1 }}"
                                href="{{ resolveUrlAsAction('TermsController@show', 
                                    ['slug' => $synonym->synonym->slug]) }}">
                                {{ $synonym->synonym->term }}</a>
                            @endif
                            {!! $synonym->status->id < 1000 ? 
                            status_warning(trans('statuses.' . str_replace(' ', '_', $synonym->status->status))) : '' !!}
                            <br>
                            <small>
                                {{ trans('terms.show.addedby') }}
                                <i>{{ $synonym->user->name }}</i>
                            </small>
                        </td>
                        {{-- Votes for synonyms --}}
                        @include('votes.form_synonym_table')
                    </tr>
                    @endforeach
                @else
                    {{-- No synonyms --}}
                    <tr><td><span class="text-warning">
                                    {{ trans('terms.show.nosynonyms') }}
                            </span></td>
                            <td></td><td></td><td></td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

@if (Auth::check() && ! (Auth::user()->role_id < 1000))
    <a class="btn btn-default" 
       href="{{ resolveUrlAsAction('TermsController@edit', ['slug' =>
                    $term->slug]) }}">{{ trans('terms.show.edit') }}</a>
    @include('terms.suggestions')

@elseif(Auth::check())
    @include('terms.suggestions')
@endif

@endsection
