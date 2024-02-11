<?php

namespace Yormy\TripwireLaravel\Http\Controllers\System\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BlockAddRequest extends FormRequest
{
    public function rules(): array
    {
        $rules['blocked_ip'] = ['required', 'string', 'min:8', 'max:20'];
        $rules['internal_comments'] = ['required', 'string', 'max:250'];

        return $rules;
    }
}
