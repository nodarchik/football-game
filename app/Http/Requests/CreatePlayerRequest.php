<?php

namespace App\Http\Requests;

use App\Enums\PlayerPosition;
use App\Enums\PlayerSkill;
use App\Rules\UniquePlayerSkills;
use Illuminate\Contracts\Validation\Validator as ValidationValidator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;

class CreatePlayerRequest extends FormRequest
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
        $playerPositionValues = implode(',', array_column(PlayerPosition::cases(), 'value'));
        $playerSkillValues = implode(',', array_column(PlayerSkill::cases(), 'value'));

        return [
            'name' => 'required|string|max:255',
            'position' => "required|string|in:$playerPositionValues",
            'playerSkills' => ['required', 'array', new UniquePlayerSkills()],
            'playerSkills.*.skill' => "required|string|in:$playerSkillValues",
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
     * Handle a failed validation attempt.
     *
     * @param ValidationValidator $validator
     * @return void
     */
    protected function failedValidation(ValidationValidator $validator): void
    {
        $errors = $validator->errors();
        $field = array_key_first($errors->messages());
        $message = $errors->first($field);
        throw new HttpResponseException(response()->json(['message' => $message], 422));
    }
}
