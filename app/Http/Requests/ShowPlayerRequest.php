<?php

namespace App\Http\Requests;

use App\Rules\PlayerExists;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class ShowPlayerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'id' => ['required', 'integer', new PlayerExists],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['id' => $this->route('id')]);
    }

    protected function failedValidation(Validator $validator): void
    {
        $errors = $validator->errors()->first();
        throw new HttpResponseException(response()->json(['message' => $errors], Response::HTTP_UNPROCESSABLE_ENTITY));
    }
}
