<?php

namespace App\Enums;

enum TeamSelectionErrors: string
{
    case DUPLICATE_REQUIREMENT = 'Duplicate position and skill combination';
    case INSUFFICIENT_PLAYERS = 'Insufficient number of players for position: %s';
}
