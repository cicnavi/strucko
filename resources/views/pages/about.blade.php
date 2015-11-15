@extends('layouts.master')

@section('meta-description', 'Strucko - About')

@section('title', 'Strucko -About')

@section('content')
<article>
    <h2>About Strucko</h2>
    <p>
        Strucko.com is built using <a href="http://laravel.com/">Laravel PHP framework</a> 
        for backend stuff, <a href="https://www.mysql.com/">MySQL community edition</a> 
        for database storage,
        <a href="http://getbootstrap.com/">Twitter Bootstrap</a> as HTML, CSS, and JS framework 
        and <a href="https://jquery.com/">jQuery</a> for some specific JavaScript tasks.
        We use the the <a href="http://antiblock.org/">anti-Adblock script</a> to prevent users
        from running ad blockers on our site. We plan to implement <a href="http://vuejs.org/">Vue.js</a> 
        to enable MVVM architectural pattern for software development.
    </p>
    <p>
        To get us started we have seeded our database with terms and definitions
        for several languages from
        <a href="http://www.microsoft.com/Language/en-US/Terminology.aspx">
            Microsoft© Language Portal Terminology Collection.</a> 
        We've put those to the Computing scientific field.
        
    </p>
    <p>
        The complete strucko.com source code is available on GitHub: 
        <a href="https://github.com/cicnavi/strucko">https://github.com/cicnavi/strucko</a>
    </p>
    <p>
        
    </p>
        
</article>
@endsection


