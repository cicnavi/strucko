
{!! csrf_field() !!}

<div class="form-group">
    <label hidden="hidden" for="definition">Definition</label>
    <textarea id="definition" name="definition" placeholder="Definition"
              rows="3" class="form-control">{{ old('definition') }}</textarea>
</div>

<div class="form-group">
    <label hidden="hidden" for="source">Source (if quoting)</label>
    <textarea id="source" name="source" placeholder="Source (if quoting)"
              aria-describedby="sourceHelp" rows="2"
              class="form-control">{{ old('source') }}</textarea>
    <span id="sourceHelp" class="help-block">
        If quoting, you are required to indicate the source from which you copied 
        the definition. If you do not specify a source, we will assume that you are 
        the author of the definition.
    </span>
</div>

<input type="hidden" id="synonym_id" name="synonym_id" 
       value="{{ isset($term) ? $term->synonym_id : old('synonym_id') }}">

