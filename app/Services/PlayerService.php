<?php
namespace App\Services;

use App\Http\Resources\PlayerResource;
use App\Models\Player;
use App\Models\PlayerSkill;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PlayerService
{
    public function getPlayers(): AnonymousResourceCollection
    {
        return PlayerResource::collection(Player::with('skills')->get());
    }

    public function getPlayer(int $playerId): PlayerResource
    {
        return new PlayerResource(Player::with('skills')->findOrFail($playerId));
    }

    public function createPlayer(array $data): PlayerResource
    {
        $player = Player::create([
            'name' => $data['name'],
            'position' => $data['position'],
        ]);

        $skills = array_map(fn($skillData) => new PlayerSkill($skillData), $data['playerSkills']);
        $player->skills()->saveMany($skills);

        return new PlayerResource($player);
    }

    public function updatePlayer(int $playerId, array $data): PlayerResource
    {
        $player = Player::findOrFail($playerId);

        $player->update([
            'name' => $data['name'],
            'position' => $data['position'],
        ]);

        $player->skills()->delete();

        $skills = array_map(fn($skillData) => new PlayerSkill($skillData), $data['playerSkills']);
        $player->skills()->saveMany($skills);

        return new PlayerResource($player);
    }

    public function deletePlayer(int $playerId): JsonResponse
    {
        $player = Player::findOrFail($playerId);

        $player->delete();

        return response()->json(['message' => 'Player deleted successfully']);
    }
}
