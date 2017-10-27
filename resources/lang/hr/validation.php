<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => ':attribute mora biti prihvaćeno.',
    'active_url'           => ':attribute nije ispravan URL.',
    'after'                => ':attribute mora biti datum nakon :date.',
    'alpha'                => ':attribute može sadržavati samo određene znakove.',
    'alpha_dash'           => ':attribute može sadržavati samo slova, brojeve i crtice.',
    'alpha_num'            => ':attribute može sadržavati samo slova i brojeve.',
    'array'                => ':attribute mora biti polje.',
    'before'               => ':attribute mora biti datum prije :date.',
    'between'              => [
        'numeric' => ':attribute mora biti između :min i :max.',
        'file'    => ':attribute mora biti između :min i :max kilobajta.',
        'string'  => ':attribute mora imati između :min and :max znakova.',
        'array'   => 'The :attribute mora imati između :min and :max stavki.',
    ],
    'boolean'              => ':attribute mora biti istina ili laž.',
    'confirmed'            => ':attribute potvrda se ne podudara.',
    'date'                 => ':attribute nije ispravan datum.',
    'date_format'          => ':attribute ne odgovara formatu :format.',
    'different'            => ':attribute i :other moraju biti različiti.',
    'digits'               => ':attribute moraju biti :digits znamenke.',
    'digits_between'       => ':attribute mora biti između :min i :max znamenki.',
    'email'                => ':attribute mora biti ispravna email adresa.',
    'filled'               => ':attribute polje je obavezno.',
    'exists'               => 'odabran :attribute nije ispravan.',
    'image'                => ':attribute mora biti slika.',
    'in'                   => 'Odabran :attribute nije ispravan.',
    'integer'              => ':attribute mora biti cjeli broj.',
    'ip'                   => ':attribute mora biti ispravna IP adresa.',
    'max'                  => [
        'numeric' => ':attribute ne može biti veći od :max.',
        'file'    => ':attribute ne može biti veći od :max kilobajta.',
        'string'  => ':attribute ne može imati više od :max znakova.',
        'array'   => ':attribute ne može imati više od :max stavki.',
    ],
    'mimes'                => ':attribute mora biti tipa: :values.',
    'min'                  => [
        'numeric' => ':attribute mora biti barem :min.',
        'file'    => ':attribute mora biti barem :min kilobajta.',
        'string'  => ':attribute mora imati barem :min znakova.',
        'array'   => ':attribute mora imati barem  :min stavki.',
    ],
    'not_in'               => 'Odabran :attribute nije ispravan.',
    'numeric'              => ':attribute mora biti broj.',
    'regex'                => ':attribute format nije ispravan.',
    'required'             => ':attribute polje je obavezno.',
    'required_if'          => ':attribute polje je obavezno kada :other ima vrijednost :value.',
    'required_with'        => ':attribute polje je obavezno kada :values postoji.',
    'required_with_all'    => ':attribute polje je obavezno kada :values postoji.',
    'required_without'     => ':attribute polje je obavezno kada :values ne postoji.',
    'required_without_all' => ':attribute polje je obavezno kada niti jedna od :values ne postoje.',
    'same'                 => ':attribute i :other se moraju podudarati.',
    'size'                 => [
        'numeric' => ':attribute mora biti :size.',
        'file'    => ':attribute mora biti :size kilobajta.',
        'string'  => ':attribute mora imati :size znakova.',
        'array'   => ':attribute mora sadržavati :size stavki.',
    ],
    'string'               => ':attribute mora biti tekst.',
    'timezone'             => ':attribute mora biti ispravna zona.',
    'unique'               => ':attribute je već odabran.',
    'url'                  => ':attribute format nije ispravan.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [
	    'attributes' => [
		    'language_id' => 'Jezik',
		    'translate_to' => 'Prevedi na',
	    ],
    ],

];
