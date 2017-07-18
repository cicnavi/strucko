@extends('layouts.master')

@section('meta-description', trans('pages.register.description'))

@section('title', trans('pages.register.title'))

@section('content')
<form method="POST" action="{{ action('Auth\AuthController@postRegister') }}">

    {!! csrf_field() !!}
    
    {!! getLocaleInputField() !!}

    <div class="form-group">
        <label for="name">{{ trans('pages.register.name') }}:</label>
        <input type="text" id="name" name="name" maxlength="255" required="required"
               placeholder="{{ trans('pages.register.name') }}" class="form-control"
               value="{{ old('name') }}">
    </div>
    
    <div class="form-group">
        <label for="email">{{ trans('pages.register.email') }}:</label>
        <input type="email" id="email" name="email" maxlength="255" required="required"
               placeholder="{{ trans('pages.register.email') }}" class="form-control"
               value="{{ old('email') }}">
    </div>
        
    <div class="form-group">
        <label for="password">{{ trans('pages.register.password') }}:</label>
        <input type="password" id="password" name="password" required="required"
               placeholder="{{ trans('pages.register.password') }}" class="form-control">
    </div>
    
    <div class="form-group">
        <label for="password_confirmation">{{ trans('pages.register.passwordconfirm') }}:</label>
        <input type="password" id="password_confirmation" name="password_confirmation" 
               required="required" placeholder="{{ trans('pages.register.passwordconfirm') }}" class="form-control">
    </div>

    <div class="form-group">
        <label for="captcha">{{ trans('pages.register.captcha') }}:</label>
        <input type="text" id="captcha" name="captcha" required="required"
               placeholder="{{ trans('pages.register.captcha') }}" class="form-control">
    </div>

    <div class="form-group">
        <img src="{!!  $captcha->inline() !!}"/>
    </div>

    <div class="form-group">
        <button type="submit" id="submit" name="submit" 
                class="btn btn-primary">
            {{ trans('pages.register.register') }}
        </button>
    </div>

</form>
@endsection
