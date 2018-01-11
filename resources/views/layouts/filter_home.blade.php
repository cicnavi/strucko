<div class="well">
    <form method="GET" action="{{ action('TermsController@index') }}" class="form-horizontal">
        <div class="form-group">
            <label for="language_id" class="col-sm-2 control-label">{{ trans('home.form.term') }}</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="search" id="search" placeholder="{{ trans('home.form.term.placeholder') }}"
                       value="{{ isset($allFilters['search']) ? urldecode($allFilters['search']) : old('search') }}">      
            </div>
        </div>
        <div class="form-group">
            <label for="language_id" class="col-sm-2 control-label">{{ trans('home.form.language') }}</label>
            <div class="col-sm-10">
                {!! Form::select('language_id', ['' => trans('home.form.language.choose'),
                        'eng' => trans('home.form.language.choose.english'),
                        'hrv' => trans('home.form.language.choose.croatian')],
                isset($allFilters['language_id']) ? $allFilters['language_id'] : 'eng', 
                ['id' => 'language_id', 'required' => 'required', 'class' => 'form-control']) !!}
            </div>
        </div>

        <input type="hidden" name="scientific_field_id" value="19">

        <input type="hidden" id="translate_to" name="translate_to" value="">

        {!! getLocaleInputField() !!}       

        <div class="form-group">

            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-primary">{{ trans('home.form.search') }}</button>
            </div>
        </div>
    </form> 
</div>
