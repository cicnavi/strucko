<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Language;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

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
}
