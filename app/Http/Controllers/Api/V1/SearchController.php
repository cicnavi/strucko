<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Term;

class SearchController extends Controller
{
    public function search(Request $request)
    {
	    $results = [
	    	'exactMatch' => null,
		    'similarTerms' => []
	    ];

	    $term = Term::where('term', $request->get('term'))
	        ->where('language_id', 'eng')
            ->with('definitions')
            ->first();

	    if ($term) {
	    	$results['exactMatch'] = $term;
	    }


		return response()->json($results);
    }
}
