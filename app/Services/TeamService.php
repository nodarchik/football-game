<?php

namespace App\Services;

use App\Enums\TeamSelectionErrors;
use App\Models\Player;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Collection;

class TeamService
{
    public function selectTeam(array $requirements): Collection
    {
        return collect($requirements)
            ->map(function ($requirement) {
                $players = Player::getByPosition($requirement['position']);
                $this->ensureSufficientPlayers($players, $requirement['position'], $requirement['numberOfPlayers']);

                return $this->getBestPlayers($players, $requirement['mainSkill'], $requirement['numberOfPlayers']);
            })
            ->flatten();
    }

    private function ensureSufficientPlayers(Collection $players, string $position, int $numberOfPlayers): void
    {
        if ($players->count() < $numberOfPlayers) {
            throw new HttpResponseException(response()->json(['message' => sprintf(TeamSelectionErrors::INSUFFICIENT_PLAYERS->value, $position)], 422));
        }
    }

    private function getBestPlayers(Collection $players, string $mainSkill, int $numberOfPlayers): Collection
    {
        $playersWithSkill = Player::filterPlayersBySkill($players, $mainSkill);
        $bestPlayersWithSkill = Player::sortPlayersBySkillValue($playersWithSkill, $mainSkill);

        if ($bestPlayersWithSkill->count() >= $numberOfPlayers) {
            return $bestPlayersWithSkill->take($numberOfPlayers);
        }

        $remainingPlayers = $numberOfPlayers - $bestPlayersWithSkill->count();
        $bestPlayersWithoutSkill = Player::getBestPlayersWithoutSkill($players, $playersWithSkill, $remainingPlayers);

        return $bestPlayersWithSkill->merge($bestPlayersWithoutSkill);
    }
}
