<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'publish_date' => 'required|date',
            'author_id' => 'required|exists:authors,id',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'El título es obligatorio.',
            'publish_date.required' => 'La fecha de publicación es obligatoria.',
            'publish_date.date' => 'La fecha de publicación no tiene un formato válido.',
            'author_id.required' => 'El ID del autor es obligatorio.',
            'author_id.exists' => 'El autor seleccionado no existe.',
        ];
    }
}
