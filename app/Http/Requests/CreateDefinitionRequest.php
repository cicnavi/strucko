<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class CreateDefinitionRequest extends Request
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
        // Remove spaces from definition.
        $input = $this->all();
        $input['definition'] = trim($input['definition']);
        $input['source'] = trim($input['source']);
        $this->replace($input);
        
        return [
            'definition' => 'required',
            'concept_id' => 'required|integer|min:1',
            'term_id' => 'required|integer|min:1',
            'language_id' => 'required|alpha|min:1|max:3',
            'link' => 'url',
        ];
    }
}
