<?php

namespace App\Http\Controllers;

use App\Models\Teams;
use Illuminate\Http\Request;

class TeamsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Teams::select('name', 'id')
            ->orderBy('id')
            ->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $team = Teams::create([
            'name' => $request->name
        ]);
        return response()->json([
            "team_name" => $team->name
        ]);
    }


    /**
     * get all Teams ordered by position
     *  
     */
    public function getRankings()
    {
        return response()->json(Teams::select('position', 'name', 'id', 'played', 'won', 'lost', 'drawn', 'points')
            ->orderBy('position')
            ->get());
    }
}
