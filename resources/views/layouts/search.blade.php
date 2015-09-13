<div class="row margin-top-10">
    <div class="col-md-10">
        <form method="GET" action="/terms" class="form-inline">
            <div class="form-group">
                <input type="hidden" name="language_id" value="{{ $allFilters['language_id'] }}">
                <input type="hidden" name="scientific_field_id" value="{{ $allFilters['scientific_field_id'] }}">
                @if (isset($allFilters['translate_to']) && ! (empty($allFilters['translate_to'])))
                <input type="hidden" name="translate_to" value="{{ $allFilters['translate_to'] }}">
                @endif
                <input type="text" class="form-control" name="search" id="search" placeholder="Or search..."
                       value="{{ isset($allFilters['search']) ? $allFilters['search'] : old('search') }}">

                <button type="submit" class="btn btn-default">Search</button>
            </div>
        </form>
    </div>
</div>

