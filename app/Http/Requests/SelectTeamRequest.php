<?php

namespace App\Http\Requests;

use App\Enums\TeamSelectionErrors;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class SelectTeamRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            '*.position' => 'required|string',
            '*.mainSkill' => 'required|string',
            '*.numberOfPlayers' => 'required|integer',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $this->validateRequirements($this->all());
        });
    }

    private function validateRequirements(array $requirements): void
    {
        $uniqueRequirements = collect($requirements)->unique(function ($requirement) {
            return $requirement['position'] . '-' . $requirement['mainSkill'];
        });

        if ($uniqueRequirements->count() !== count($requirements)) {
            throw new HttpResponseException(
                response()->json(['message' => TeamSelectionErrors::DUPLICATE_REQUIREMENT->value],
                    Response::HTTP_UNPROCESSABLE_ENTITY)
            );
        }
    }
}
