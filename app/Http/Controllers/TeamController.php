<?php

namespace App\Http\Controllers;

use App\Http\Requests\SelectTeamRequest;
use App\Http\Resources\PlayerResource;
use App\Services\TeamService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TeamController extends Controller
{
    public function __construct(private readonly TeamService $teamService){}

    public function process(SelectTeamRequest $request): AnonymousResourceCollection
    {
        return PlayerResource::collection($this->teamService->selectTeam($request->validated()));
    }
}
