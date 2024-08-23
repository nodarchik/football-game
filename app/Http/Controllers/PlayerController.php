<?php

// /////////////////////////////////////////////////////////////////////////////
// PLEASE DO NOT RENAME OR REMOVE ANY OF THE CODE BELOW.
// YOU CAN ADD YOUR CODE TO THIS FILE TO EXTEND THE FEATURES TO USE THEM IN YOUR WORK.
// /////////////////////////////////////////////////////////////////////////////

namespace App\Http\Controllers;

use App\Http\Requests\CreatePlayerRequest;
use App\Http\Requests\DeletePlayerRequest;
use App\Http\Requests\ShowPlayerRequest;
use App\Http\Requests\UpdatePlayerRequest;
use App\Http\Resources\PlayerResource;
use App\Services\PlayerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PlayerController extends Controller
{
    public function __construct(private readonly PlayerService $playerService){}
    public function index(): AnonymousResourceCollection
    {
        return $this->playerService->getPlayers();
    }

    public function show(ShowPlayerRequest $request, int $playerId): PlayerResource
    {
        return $this->playerService->getPlayer($playerId);
    }

    public function store(CreatePlayerRequest $request): PlayerResource
    {
        return $this->playerService->createPlayer($request->validated());
    }

    public function update(UpdatePlayerRequest $request, int $playerId): PlayerResource
    {
        return $this->playerService->updatePlayer($playerId, $request->validated());
    }

    public function destroy(DeletePlayerRequest $request, int $playerId): JsonResponse
    {
        return $this->playerService->deletePlayer($playerId);
    }
}
