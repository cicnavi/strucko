@extends('layouts.master')

@section('meta-description', 'Welcome to Strucko - the Expert Dictionary')

@section('title', 'The Expert Dictionary')

@section('content')
<div class="row">
    <div class="col-xs-12">
        <article>
            <h2>Popular languages and scientific fields</h2>
            <div class="row">
                @foreach($categories as $category)
                    <div class="col-xs-6 col-md-3 text-center">
                        <h3>
                            <a href="{{action('TermsController@index', ['language_id' => $category->language_id, 'scientific_field_id' => $category->scientific_field_id])}}" 
                               class="thumbnail">
                                {{ $category->language->ref_name }},
                                <br> {{ $category->scientificField->scientific_field }}
                                <br> <small>{{ $category->count }} term(s)</small>
                            </a>
                        </h3>
                  </div>
                @endforeach
            </div>
        </article>
        
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
        <article>
            <h2>Introducing Strucko - The Expert Dictionary </h2>
            <p>
                Although there are several great (and free) translation services, and 
                thousands of online dictionaries available to us, there is still a 
                gap in good online translations for technical or specific 
                terms related to some area, field, craft, occupation, etc.
            </p>
            <p>
                This is where Strucko comes into play. Strucko is open, free and 
                community driven expert dictionary. As a guest user, you are free to view all approved
                terms, translations and definitions. If you want to contribute to the dictionary, 
                you can create an account which will give you permissions to vote on all terms and their
                translations, and also to suggest new terms, translations and definitions.
                By voting, you participate in the process of defining the best term and
                translation for particular concept. And that's why Strucko is great -
                it shows us the opinion of the community!
            </p>
            <p>
                To get us started we've seeded our database with terms and definitions
                for several languages in Computing scientific field using
                <a href="http://www.microsoft.com/Language/en-US/Terminology.aspx">
                    Microsoft© Language Portal Terminology Collection.</a> 
                We will try to add more terms and definitions from different sources with open
                access. If you know one, let us know.
            </p>
            <p>
                Feel free to contact us if you have any other suggestion.
                <br>
                Marko I.
            </p>
        </article>
        @if(getenv('APP_ENV')=='production')
            <hr>
            <section>
                @include('shared.disqus', [
                    'url' => action('PagesController@getHome'),
                    'identifier' => 'home'
                ])
            </section>
        @endif
    </div>
</div>
@endsection


