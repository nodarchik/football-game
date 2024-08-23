<?php

namespace App\Enums;

enum PlayerSkill: string
{
    case ATTACK = 'attack';
    case SPEED = 'speed';
    case STRENGTH = 'strength';
    case STAMINA = 'stamina';
    case DEFENSE = 'defense';
}
