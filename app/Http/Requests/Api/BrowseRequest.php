<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class BrowseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'letter' => 'required|string|max:1',
	        'language_id' => 'required|string|exists:languages,id',
	        'translate_to' => 'required|string|exists:languages,id',
        ];
    }
}
