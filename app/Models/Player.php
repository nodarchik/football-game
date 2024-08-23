<?php

// /////////////////////////////////////////////////////////////////////////////
// PLEASE DO NOT RENAME OR REMOVE ANY OF THE CODE BELOW.
// YOU CAN ADD YOUR CODE TO THIS FILE TO EXTEND THE FEATURES TO USE THEM IN YOUR WORK.
// /////////////////////////////////////////////////////////////////////////////

namespace App\Models;

use App\Enums\PlayerPosition;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * @property integer $id
 * @property string $name
 * @property PlayerPosition $position
 * @property PlayerSkill $skill
 */
class Player extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $fillable = [
        'name',
        'position'
    ];

    protected $casts = [
        'position' => PlayerPosition::class
    ];

    protected $with = ['skills'];

    public function skills(): HasMany
    {
        return $this->hasMany(PlayerSkill::class);
    }

    public static function getByPosition(string $position): Collection
    {
        return self::where('position', $position)
            ->with('skills')
            ->get();
    }

    public static function filterPlayersBySkill(Collection $players, string $mainSkill): Collection
    {
        return $players->filter(function ($player) use ($mainSkill) {
            return $player->skills->contains(function ($skill) use ($mainSkill) {
                return $skill->skill === $mainSkill;
            });
        });
    }

    public static function sortPlayersBySkillValue(Collection $players, string $mainSkill): Collection
    {
        return self::sortPlayers($players, function ($player) use ($mainSkill) {
            return $player->skills->firstWhere('skill', $mainSkill)->value;
        });
    }

    public static function getBestPlayersWithoutSkill(Collection $players, Collection $playersWithSkill, int $numberOfPlayers): Collection
    {
        $playersWithoutSkill = $players->diff($playersWithSkill);

        return self::sortPlayers($playersWithoutSkill, function ($player) {
            return $player->skills->max('value');
        })->take($numberOfPlayers);
    }
    private static function sortPlayers(Collection $players, callable $getSortValue): Collection
    {
        $players = $players->all();

        usort($players, function ($a, $b) use ($getSortValue) {
            return $getSortValue($b) <=> $getSortValue($a);
        });

        return new Collection($players);
    }
}
