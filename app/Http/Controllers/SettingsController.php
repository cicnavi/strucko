<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class SettingsController extends Controller
{
	public function set( Request $request )
	{
		$input = $this->validateSettings( $request );

		Session::put( 'settings' , $input );

		return redirect('/');
	}


	protected function validateSettings( Request $request )
	{
		$rules = [
			'language_id' => 'required|exists:languages,id,active,1',
			'translate_to' => 'required|exists:languages,id,active,1'
		];

		$this->validate( $request, $rules );

		return $request->only( array_keys( $rules ) );
	}
}
