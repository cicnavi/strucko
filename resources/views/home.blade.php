@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-xs-12">
            <h1 class="text-center hidden-xs">Strucko <small>IT Dictionary</small></h1>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <router-view></router-view>
        </div>
    </div>

@endsection
