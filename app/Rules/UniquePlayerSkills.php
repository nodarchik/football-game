<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class UniquePlayerSkills implements Rule
{
    public function passes($attribute, $value): bool
    {
        $skills = array_column($value, 'skill');
        return count($skills) === count(array_unique($skills));
    }

    public function message(): string
    {
        return 'The playerSkills array contains duplicate skills.';
    }
}
