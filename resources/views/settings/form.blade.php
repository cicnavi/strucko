<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
    <div class="panel panel-default">
        <div class="panel-heading" role="tab" id="headingOne">
            <h4 class="panel-title">
                <a class="btn-block" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne"
                   aria-expanded="true" aria-controls="collapseOne">
                    Settings
                </a>
            </h4>
        </div>
        <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">

            <div class="panel-body">

                <form method="POST" action="/settings" class="form-horizontal">
                    {!! csrf_field() !!}
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
                            <button type="submit" class="btn btn-primary">{{ trans('home.form.set') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>

</div>