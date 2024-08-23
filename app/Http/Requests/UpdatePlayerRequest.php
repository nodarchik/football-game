<?php

namespace App\Http\Requests;

use App\Enums\PlayerPosition;
use App\Enums\PlayerSkill;
use Illuminate\Contracts\Validation\Validator as ValidationValidator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class UpdatePlayerRequest extends FormRequest
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
        $playerPositionValues = array_map(
            static fn (PlayerPosition $enum): string => $enum->value,
            PlayerPosition::cases()
        );

        $playerSkillValues = array_map(
            static fn (PlayerSkill $enum): string => $enum->value,
            PlayerSkill::cases()
        );

        return [
            'name' => 'required|string|max:255',
            'position' => 'required|string|in:' . implode(',', $playerPositionValues),
            'playerSkills' => 'required|array',
            'playerSkills.*.skill' => 'required|string|in:' . implode(',', $playerSkillValues),
            'playerSkills.*.value' => 'required|integer|between:1,100',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'position.in' => 'Invalid value for position: :input',
            'playerSkills.*.skill.in' => 'Invalid value for skill: :input',
        ];
    }

    /**
     * Handle failed validation.
     *
     * @param ValidationValidator $validator
     * @throws HttpResponseException
     */
    protected function failedValidation(ValidationValidator $validator)
    {
        $errors = $validator->errors();
        $field = array_key_first($errors->messages());
        $message = $errors->first($field);
        Log::error('Validation error: ' . $message);
        throw new HttpResponseException(response()->json(['message' => $message], 422));
    }
}
