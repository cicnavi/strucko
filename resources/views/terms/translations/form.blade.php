
{!! csrf_field() !!}

<input type="hidden" name="concept_id" required="required" id="concept_id" value="{{ $term->concept_id }}">
<input type="hidden" name="scientific_field_id" required="required" id="scientific_field_id" value="{{ $term->scientific_field_id }}">
<input type="hidden" name="part_of_speech_id" required="required" id="part_of_speech_id" value="{{ $term->part_of_speech_id }}">

<div class="form-group">
    <label for="language_id">Language to translate to:</label>
    {!! Form::select('language_id', $languages->lists('ref_name', 'id'),
    Input::has('translation_id') ? Input::get('translation_id') : old('language_id'), 
    ['id' => 'language_id', 'required' => 'required', 'class' => 'form-control']) !!}
</div>

<div class="form-group">
    <label for="term">Term</label>
    <input type="text" id="term" name="term" value="{{ old('term') }}" required="required" 
           maxlength="255" placeholder="Term" class="form-control">
</div>
<div class="checkbox">
    <label>
        {!! Form::checkbox('is_abbreviation', 1) !!}
        This is abbreviation
    </label>
</div>

