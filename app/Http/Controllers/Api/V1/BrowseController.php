<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\BrowseRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Term;

class BrowseController extends Controller
{
    public function browse(BrowseRequest $request)
    {
	    $results = [
	    	'terms' => [],
	    ];

	    $terms = Term::greaterThanRejected()
	        ->where('menu_letter', $request->get('letter'))
	        ->where('language_id', $request->get('language_id'))
		    ->withTranslations($request->get('translate_to'))
            ->with('definitions', 'partOfSpeech')
            ->orderBy('term')
            ->paginate();

	    if ($terms->isNotEmpty()) {
	    	$results['terms'] = $terms->toArray();
	    }
        
		return response()->json($results);
    }
}
