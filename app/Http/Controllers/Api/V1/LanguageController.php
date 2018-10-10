<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Language;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class LanguageController extends Controller
{
    public function index()
    {
    	$languages = Cache::remember('languages', 24 * 60, function(){
		    return Language::where('active', true)
		            ->orderBy('ref_name')
		            ->get();
	    });

		return response()->json($languages);
    }

    public function letters(Language $language)
    {
		$cacheName = 'letters-' . $language->id;

//		$letters = Cache::remember($cacheName, 60, function () {
//			DB::table('terms')->select('language_id')
//			->limit(5)
//			->get(10);
//		});

	    $letters = DB::select(
	    	"SELECT DISTINCT LEFT(term, 1) as letter
	    	FROM strucko.terms
	    	WHERE language_id = '" . $language->id . "'
	    	ORDER BY letter"
	    );

	    return response()->json($letters);
    }
}
