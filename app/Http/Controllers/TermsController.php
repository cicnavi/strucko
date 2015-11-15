<?php

namespace app\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Term;
use App\PartOfSpeech;
use App\ScientificField;
use App\Language;
use App\Synonym;
use App\ScientificArea;
use App\Status;
// use App\Definition;
// use Request;
use Auth;
use App\Http\Requests\CreateTermRequest;
use App\Http\Requests\ShowTermRequest;
use Session;

class TermsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    /**
     * List the terms.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // TODO Implement filtering
        // null !== \Input::get('filter') ? dd('true') : dd('false');
        
        // Get the latest terms.
        $terms = Term::latest()->approved()->get();

        return view('terms.index', compact('terms'));
    }
    /**
     * Show suggested terms - logged in users only. 
     * 
     * @return type
     */
    public function suggestions()
    {
        $terms = Term::latest()->suggested()->get();
        return view('terms.index', compact('terms'));
    }

    
    /**
     * Show the create view.
     *  
     * @return type
     */
    public function create()
    {
        // Prepare data for the form.
        $partOfSpeeches = PartOfSpeech::active()->orderBy('part_of_speech')->get();
        $scientificFields = $this->prepareFields();
        $languages = Language::active()->orderBy('ref_name')->get();
        
        return view('terms.create', compact(
            'partOfSpeeches',
            'scientificFields',
            'languages'
        ));
    }
    
    /**
     * Show the term.
     * This method uses route model binding, just to have that example.
     * 
     * @param Term $term
     * @param ShowTermRequest $request
     * @return type
     */
    public function show(Term $term, ShowTermRequest $request)
    {
        // return $term;
        //$term = Term::where('slug_unique', $slugUnique)->firstOrFail();

        return view('terms.show', compact('term'));
    }

    /**
     * TODO Make sure that only active language, part of speech and category can be set.
     * TODO Consider changing the slug_unique logic to
     * take into account the posibility to change category...
     * Consider making this a transaction.
     * 
     * @return type
     */
    public function store(CreateTermRequest $request)
    {
        // Get input from the request and prepare slugs.
        // $input = Request::all();
        $input = $this->prepareSlugs($request->all());

        // Make sure that the term doesn't already exist (check unique constraint).
        // TODO: Unique constraint - try to check using custom validation.
        if ($this->termExists($input)) {
            // Flash messages that the term exists.
            $this->flashTermExists();
            
            return back()->withInput();
        }

        // Prepare new synonym and append synonym_id to the input
        $synonym = Synonym::create();
        $input['synonym_id'] = $synonym->id;
        
        // Get the user who is suggesting the Term.
//        $user = Auth::user();
//        $input['user_id'] = $user->id;
        $input['user_id'] = Auth::id();
        
        // If definition is not empty, create it for the synonym.
        if ($request->has('definition')) {
            // Definition::create();
            $synonym->definitions()->create([
                'definition' => $input['definition'], 
                'synonym_id' => $input['synonym_id'],
                'user_id' => $input['user_id'],
            ]);
        }

        // Persist the new Term
        Term::create($input);
        
        // Set alerts to session.
//        Session::flash('alert', 'Term suggested...');
//        Session::flash('alert_class', 'alert alert-success');
        
        // Redirect with alerts in session.
        return redirect('terms/' . $input['slug_unique'])->with([
            'alert' => 'Term suggested...',
            'alert_class' => 'alert alert-success'
        ]);
    }

    /**
     * Show the view to edit the term.
     * TODO Only administrators can edit terms - implement with middleware.
     * * 
     * @param Term $term
     * @return type
     */
    public function edit($slugUnique)
    {
        // Get the term with relationships.
        $term = Term::where('slug_unique', $slugUnique)
                ->with('language', 'status', 'scientificField', 'partOfSpeech', 'synonym.definitions')
                ->firstOrFail();
        
        // Prepare data for the form withouth the ones already in the term instance.
        $partOfSpeeches = PartOfSpeech::active()->without($term->part_of_speech_id)->get();
        $scientificFields = $this->prepareFields();
        // Left filterLanguages() method for example. Using the Form::select for Languages.
        // $languages = $this->filterLanguages($term->language_id);
        $languages = Language::active()->orderBy('ref_name')->get();
        $statuses = Status::active()->orderBy('id')->lists('status', 'id');
        
        return view('terms.edit', 
                compact('term', 'partOfSpeeches', 'scientificFields', 'languages', 'statuses'));
    }

    /**
     * Update the term.
     * TODO Create the update request for validation or use existing one (create?).
     * TODO Make sure that only administrator can make this request.
     * 
     * @param type $slugUnique
     * @param Request $request
     */
    public function update($slugUnique, Request $request)
    {
        // Get the term to be updated.
        $term = Term::where('slug_unique', $slugUnique)->firstOrFail();
        
        // Prepare new slugs from the new input.
        $input = $this->prepareSlugs($request->all());
        
        // Make sure that the term doesn't already exist (check unique constraint).
        // We will send the ID of the term we are updating so that we can check
        // if the term which exists is the same term we are updating.
        // TODO: Unique constraint - try to check using custom validation.
        if ($this->termExists($input, $term->id)) {
            // Flash messages that the term exists.
            $this->flashTermExists();
            
            return back()->withInput();
        }
        
        // If definition is not empty, create it for the synonym.
        if ($request->has('definition')) {
            //dd($term->synonym->definitions);
            $term->synonym->definitions()->create([
                'definition' => $input['definition'], 
                //'synonym_id' => $input['synonym_id'],
                'user_id' => Auth::id(),
            ]);
        }
        
        // Update the term.
        $term->update($input);
        
        return redirect(action('TermsController@show', ['slugUnique' => $input['slug_unique']]))
                ->with([
                    'alert' => 'Term updated...',
                    'alert_class' => 'alert alert-success'
                ]);
    }
    
    // TODO Set the appropriate request, validaiton and ensure admins. 
    public function updateStatus(Request $request, $slugUnique) {
        
        $term = Term::where('slug_unique', $slugUnique)->firstOrFail();
        
        $term->status_id = $request->input('status_id');
        
        $term->save();
        
        return back()->with([
                    'alert' => 'Term updated...',
                    'alert_class' => 'alert alert-success'
                ]);
    }

        /**
     * Check if the term already exists in the database for the choosen language,
     * part of speech and category.
     *
     * @param array $input
     * @return App\Term
     */
    protected function termExists($input, $updatedTermId = 0)
    {
        // Try to get the term.
        $term = Term::where('term', $input['term'])
                ->where('language_id', $input['language_id'])
                ->where('part_of_speech_id', $input['part_of_speech_id'])
                ->where('scientific_field_id', $input['scientific_field_id'])
                ->first();
                
        // If the term term doesn't exist, we can go on.
        if (is_null($term)) {
            return false;
        }
        
        // The term exists, but we have to check if this is the update() method
        // by comparing ID of the term we are trying to update
        // and the ID of the term we found in database.
        
        if ($updatedTermId == $term->id) {
            // This is the update, so we will let this action go on.
            return false;
        }
        
        // The term exists.
        return true;
        
    }
    
    /**
     * Prepare slug and slug_unique for the given term.
     * 
     * @param array $input
     * @return array
     */
    protected function prepareSlugs($input) {
        // Get the strings for language, partOfSpeech and category, for SEO.
        $language = Language::where('id', $input['language_id'])->firstOrFail();
        $partOfSpeech = PartOfSpeech::where('id', $input['part_of_speech_id'])->firstOrFail();
        $scientificField = ScientificField::where('id', $input['scientific_field_id'])->firstOrFail();
        
        // Prepare 'slug' attribute.
        $slug = str_limit(str_slug($input['term']), 100);
        $input['slug'] = $slug;
        
        // Prepare 'slug_unique' attribute.
        $input['slug_unique'] = $slug . "-" . str_slug(
                $language->ref_name . "-"
                . $partOfSpeech->part_of_speech. "-"
                . $scientificField->scientific_field
                );
        // Limit the length of the slug_unique and append the IDs
        $input['slug_unique'] = str_limit($input['slug_unique'], 200);
        $input['slug_unique'] = $input['slug_unique'] . "-"
                . str_limit($language->id . $partOfSpeech->id . $scientificField->id, 55);
        
        return $input;
    }
    
    /**
     * Get and filter trough all languages and return the ones without the
     * one asked to be removed.
     * 
     * @param integer $itemToRemove
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function filterLanguages($itemToRemove) {
        return Language::active()
                ->orderBy('ref_name')
                ->get()
                ->reject(function($item) use ($itemToRemove) {
                    return $item->id == $itemToRemove;
                });
    }
    
    /** 
     * Flash the messages when the term exists.
     * 
     */
    protected function flashTermExists() {
        Session::flash('alert', 'This term already exists for the '
                    . 'selected language, part of speech, and category...');
        session()->flash('alert_class', 'alert alert-warning');
    }
    
    protected function prepareFields() {
        $fields = [];
        
        // Get areas including their fileds.
        $areas = ScientificArea::active()
                ->with(['scientificFields' => function ($query) {
                    $query->where('active', 1)->orderBy('scientific_field');
                }])
                ->orderBy('scientific_area')
                ->get();
        
        // Populate an array with areas as keys, and fields as sub arrays.
        foreach ($areas as $area) {
            $fields[$area->scientific_area] = array();
            
            foreach ($area->scientificFields->all() as $field) {
                
                $fields[$area->scientific_area][$field->id] = $field->scientific_field;
            }
        }
        
        return $fields;
    }
}
