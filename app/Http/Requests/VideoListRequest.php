<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VideoListRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() : bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules() : array
    {
        return [
            'limit' => 'integer|max:50|min:1',
            'page' => 'integer|min:1'
        ];
    }

    /**
     * Gets request limit
     *
     * @return integer
     */
    public function getLimit(): int {
        return $this->get('limit', 30);
    }

    /**
     * Get request page
     *
     * @return integer
     */
    public function getPage(): int {
        return $this->get('page', 1);
    }
}
