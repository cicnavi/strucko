<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Language;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LanguageController extends Controller
{
    public function index()
    {
    	$languages = Language::where('active', true)
	                         ->orderBy('ref_name')
	                         ->get();

		return response()->json($languages);
    }
}
