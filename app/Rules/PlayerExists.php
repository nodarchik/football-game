<?php

namespace App\Rules;

use App\Models\Player;
use Illuminate\Contracts\Validation\Rule;

class PlayerExists implements Rule
{
    public function passes($attribute, $value): bool
    {
        return Player::find($value) !== null;
    }

    public function message(): string
    {
        return 'The selected player ID does not exist.';
    }
}
