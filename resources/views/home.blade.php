@extends('layouts.app')

@section('content')

    <h1 class="text-center">Strucko <small>IT Dictionary</small></h1>
    <example-component :app-name="appName"></example-component>
    <search-form></search-form>

@endsection
