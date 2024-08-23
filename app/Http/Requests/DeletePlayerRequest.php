<?php

namespace App\Http\Requests;

use App\Rules\PlayerExists;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class DeletePlayerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        $token = str_replace('Bearer ', '', $this->header('Authorization'));

        return $token === 'SkFabTZibXE1aE14ckpQUUxHc2dnQ2RzdlFRTTM2NFE2cGI4d3RQNjZmdEFITmdBQkE=';
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

    protected function failedAuthorization(): void
    {
        throw new HttpResponseException(response()->json(['message' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED));
    }

    protected function failedValidation(Validator $validator): void
    {
        $errors = $validator->errors()->first();
        throw new HttpResponseException(response()->json(['message' => $errors], Response::HTTP_UNPROCESSABLE_ENTITY));
    }
}
