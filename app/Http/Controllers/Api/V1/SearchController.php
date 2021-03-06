<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\SearchRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Term;

class SearchController extends Controller
{
    public function search(SearchRequest $request)
    {
	    $results = [
	    	'exactMatch' => null,
		    'similarTerms' => []
	    ];

	    $exactMatch = Term::greaterThanRejected()
	        ->where('term', $request->get('term'))
	        ->where('language_id', $request->get('language_id'))
		    ->withTranslations($request->get('translate_to'))
            ->with('definitions', 'partOfSpeech')
            ->first();

	    if ($exactMatch) {
	    	$results['exactMatch'] = $exactMatch->toArray();
	    }

	    $similarTerms = Term::greaterThanRejected()
	        ->where('term', '<>', $request->get('term'))
            ->where('term', 'like', '%' . $request->get('term') . '%')
		    ->where('language_id', $request->get('language_id'))
		    ->inRandomOrder()
		    ->orderBy('votes_sum', 'desc')
		    ->withTranslations($request->get('translate_to'))
		    ->with('definitions', 'partOfSpeech')
		    ->limit(5)
		    ->get();

	    if ($similarTerms->isNotEmpty()) {
	    	$results['similarTerms'] = $similarTerms->toArray();
	    }


		return response()->json($results);
    }
}
