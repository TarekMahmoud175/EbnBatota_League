<?php

namespace App\Http\Controllers;

use App\Models\Matches;
use App\Models\Teams;
use Illuminate\Http\Request;

class MatchesController extends Controller
{

    /**
     * get the status of the match and winner and loser id
     *
     * @param  \App\Model\Matches  $matches
     * @return  winnerId , LoserId, Status Of match
     * if status is  draw the two players are still with the same name winner and loser but it had different action at setDraw data 
     */
    public function GetMatchStatus(Matches $match)
    {
        $TeamStatus = [];
        if ($match->team1_score > $match->team2_score) {
            $TeamStatus['winnerid'] = $match->team1_id;
            $TeamStatus['loserid'] = $match->team2_id;
            $TeamStatus['status'] = 'win';
        } elseif ($match->team1_score < $match->team2_score) {
            $TeamStatus['winnerid'] = $match->team2_id;
            $TeamStatus['loserid'] = $match->team1_id;
            $TeamStatus['status'] = 'win';
        } else {
            $TeamStatus['winnerid'] = $match->team1_id;
            $TeamStatus['loserid'] = $match->team2_id;
            $TeamStatus['status'] = 'draw';
        }
        return $TeamStatus;
    }


    /**
     * set points and match played for the winner
     *
     * @param  winnerId
     * @return noReturn
     *  
     */
    public function setWinData($id)
    {
        $firstTeam = Teams::where('id', $id)->first();
        $firstTeam->played += 1;
        $firstTeam->won    += 1;
        $firstTeam->points += 3;
        $firstTeam->save();
    }

    /**
     * set points and match played for the loser
     *
     * @param  LoserId
     * @return noReturn
     *  
     */
    public function setLoseData($id)
    {
        $Team = Teams::where('id', $id)->first();
        $Team->played += 1;
        $Team->lost   += 1;
        $Team->save();
    }

    /**
     * set points and match played for a Team
     *
     * @param  TeamId
     * @return noReturn
     *  
     */
    public function setDrawData($id)
    {
        $Team = Teams::where('id', $id)->first();
        $Team->played += 1;
        $Team->drawn  += 1;
        $Team->points += 1;
        $Team->save();
    }

    /**
     * set points and match played for both teams
     *
     * @param  TeamStatus array which contain status , first team id , second team id 
     * @return noReturn
     *  
     */
    public function updateLeagueTable(array $TeamStatus)
    {
        if ($TeamStatus['status'] == 'draw') {
            $this->setDrawData($TeamStatus['loserid']);
            $this->setDrawData($TeamStatus['winnerid']);
        } elseif ($TeamStatus['status'] == 'win') {
            $this->setWinData($TeamStatus['winnerid']);
            $this->setLoseData($TeamStatus['loserid']);
        }
    }
    
    /**
     * set rankings based on points 
     *
     * @param  no params
     * @return no Return
     *  
     */
    public function setRanking()
    {
        $teams = Teams::orderBy('points', 'DESC')->get();
        for ($i = 0; $i < count($teams); $i++) {
            $teams[$i]->position = $i + 1;
            $teams[$i]->save();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $match = Matches::create([
            'team1_id' => $request->team1_id,
            'team2_id' => $request->team2_id,
            'team1_score' => $request->team1_score,
            'team2_score' => $request->team2_score
        ]);

        $TeamStatus = $this->GetMatchStatus($match);
        $this->updateLeagueTable($TeamStatus);
        $this->setRanking();


        return response()->json([
            'team_a_id' => $match->team1_id,
            'team_b_id' => $match->team2_id,
            'team_a_score' => $match->team1_score,
            'team_b_score' => $match->team2_score,
        ]);
    }
}
