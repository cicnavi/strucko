<?php

namespace app\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Term;
use App\PartOfSpeech;
use App\Language;
use App\Concept;
use App\Status;
use Auth;
use App\Http\Requests\EditTermRequest;
use App\Http\Requests\ShowTermRequest;
use App\Repositories\TermsFilterRepository;
use App\Http\Controllers\Traits\ManagesTerms;

class TermsController extends Controller
{

    use ManagesTerms;

    /**
     * Filters used to get specific terms.
     * 
     * @var type 
     */
    protected $filters;

    public function __construct()
    {
        // User has to be authenticated, except for specified methods.
        $this->middleware('auth', ['except' => ['index', 'show']]);
        // Check if user has Administrator role for specified methods.
        $this->middleware('role:1000', ['only' => ['edit', 'update', 'updateStatus']]);
    }

    /**
     * List the terms.
     *
     * @return \Illuminate\View\View
     */
    public function index(TermsFilterRepository $filters)
    {
        $this->filters = $filters;
        $allFilters = $this->filters->allFilters();
        $termFilters = $this->filters->termFilters();
        $menuLetterFilters = $this->filters->menuLetterFilters();

        // Check appropriate query parameters and variables.
        if ($this->filters->isSetLanguageAndField()) {

            $menuLetters = $this->getMenuLettersForLanguageAndField($allFilters);

            $languageId = $allFilters['language_id'];
            $scientificFieldId = $allFilters['scientific_field_id'];

            // Check if the menu_letter is set. If so, get terms with that letter
            // and other term filters.
            if ($this->filters->isSetMenuLetter()) {
                $terms = Term::approved()
                        ->where($termFilters)
                        ->orderBy('term')
                        ->get();
            }

            // Check if the search is set. If so, try to find terms.
            if ($this->filters->isSetSearch()) {
                $terms = Term::approved()
                        ->where('term', 'like', '%' . $allFilters['search'] . '%')
                        ->where('language_id', $allFilters['language_id'])
                        ->where('scientific_field_id', $allFilters['scientific_field_id'])
                        ->orderBy('term')
                        ->get();
            }
        }

        // Prepare languages and fields for filtering
        $languages = Language::active()->orderBy('ref_name')->get();
        $scientificFields = $this->prepareScientificFields();

        return view('terms.index', compact('terms', 'menuLetters', 'languageId', 'scientificFieldId', 'languages', 'scientificFields', 'menuLetterFilters'));
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
        $scientificFields = $this->prepareScientificFields();
        $languages = Language::active()->orderBy('ref_name')->get();

        return view('terms.create', compact(
                        'partOfSpeeches', 'scientificFields', 'languages'
        ));
    }

    /**
     * TODO Make sure that only active language, part of speech and category can be set (implement guarding - trough request?).
     * TODO Consider making this a transaction.
     * 
     * @return type
     */
    public function store(EditTermRequest $request)
    {
        // Get input from the request and prepare slug and menu letter.
        $input = $this->prepareInputValues($request->all());
        // Get the user suggesting the term
        $input['user_id'] = Auth::id();

        // Make sure that the term doesn't already exist (check unique constraint).
        if ($this->termExists($input)) {
            // Flash messages that the term exists.
            $this->flashTermExists();
            return back()->withInput();
        }

        // Prepare new concept
        $concept = Concept::create();

        // Persist the new Term using the relationship
        $concept->terms()->create($input);

        // Redirect with alerts in session.
        return redirect('terms/' . $input['slug'])->with([
                    'alert' => 'Term suggested...',
                    'alert_class' => 'alert alert-success'
        ]);
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
        //$term = Term::where('slug', $slug)->firstOrFail();
        // Get languages for translation options in suggest translation section.
        $languages = Language::active()
                ->without($term->language_id)
                ->orderBy('ref_name')
                ->get();
        // Prepare filters for synonyms. Approved ones are only for authenticated users.
        $synonymFilters = [];
        $synonymFilters['concept_id'] = $term->concept_id;
        $synonymFilters['language_id'] = $term->language_id;
        Auth::check() ? '' : $synonymFilters['status_id'] = 1000;
        
        // Get the terms with the same concept_id and the same language (synonyms)
        $synonyms = Term::where($synonymFilters)
                ->without($term->id)
                ->with('status')
                ->orderBy('status_id', 'DESC')
                ->orderBy('votes_sum', 'DESC')
                ->get();

        return view('terms.show', compact('term', 'synonyms', 'languages'));
    }

    /**
     * Show the view to edit the term.
     * 
     * @param string $slug The unique slug used to identify term.
     * @return type
     */
    public function edit($slug)
    {
        // Get the term with relationships.
        $term = Term::where('slug', $slug)
                ->with('status', 'language', 'scientificField', 'partOfSpeech', 'concept.definitions')
                ->firstOrFail();

        // Prepare data for the form withouth the ones already in the term instance.
        $partOfSpeeches = PartOfSpeech::active()->without($term->part_of_speech_id)->get();
        $scientificFields = $this->prepareScientificFields();
        // Left filterLanguages() method for example. Using the Form::select for Languages.
        // $languages = $this->filterLanguages($term->language_id);
        $languages = Language::active()->orderBy('ref_name')->get();
        $statuses = Status::active()->orderBy('id')->lists('status', 'id');

        return view('terms.edit', compact('term', 'partOfSpeeches', 'scientificFields', 'languages', 'statuses'));
    }

    /**
     * Update the term.
     * 
     * @param string $slug Unique slug used to identify term.
     * @param EditTermRequest $request
     */
    public function update($slug, EditTermRequest $request)
    {
        // Get the term to be updated, and synonym.
        $term = Term::where('slug', $slug)->firstOrFail();

        // Prepare new input values, without user_id
        $input = $this->prepareInputValues($request->all());
        // Make sure that the user_id stays the same
        $input['user_id'] = $term->user_id;

        // Make sure that the term doesn't already exist (check unique constraint).
        // We will send the ID of the term we are updating so that we can check
        // if the term which exists is the same term we are updating.
        if ($this->termExists($input, $term->id)) {
            // Flash messages that the term exists.
            $this->flashTermExists();
            return back()->withInput();
        }

        // Update the term.
        $term->update($input);

        return redirect(action('TermsController@show', ['slug' => $input['slug']]))
                        ->with([
                            'alert' => 'Term updated...',
                            'alert_class' => 'alert alert-success'
        ]);
    }

    /**
     * Update the status of the Term.
     * 
     * @param \App\Http\Requests\EditStatusRequest $request
     * @param string $slug Unique slug for Term
     * @return type Return to the previous page
     */
    public function updateStatus(Requests\EditStatusRequest $request, $slug)
    {

        $term = Term::where('slug', $slug)->firstOrFail();

        $term->status_id = $request->input('status_id');

        $term->save();

        return back()->with([
                    'alert' => 'Status updated...',
                    'alert_class' => 'alert alert-success'
        ]);
    }

    /**
     * Set the status of the term to approved.
     * 
     * @param string $slug Unique slug of the term
     * @return \Illuminate\Http\RedirectResponse Go back
     */
    public function approveTerm($slug)
    {
        $term = Term::where('slug', $slug)->firstOrFail();

        $term->status_id = 1000;

        $term->save();

        return back()->with([
                    'alert' => 'Term approved...',
                    'alert_class' => 'alert alert-success'
        ]);
    }

    /**
     * Set the status of the term to rejected.
     * 
     * @param string $slug Unique slug of the term
     * @return \Illuminate\Http\RedirectResponse Go back
     */
    public function rejectTerm($slug)
    {
        $term = Term::where('slug', $slug)->firstOrFail();

        $term->status_id = 250;

        $term->save();

        return back()->with([
                    'alert' => 'Term rejected...',
                    'alert_class' => 'alert alert-success'
        ]);
    }

}
