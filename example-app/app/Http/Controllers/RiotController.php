<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use stdClass;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isNan;
use function PHPUnit\Framework\isNull;

class RiotController extends Controller
{
    public function search(Request $request)
    {
        return view('riotApi.riot');
    }
    
    public function getMatchPlayers($match, $match_id)
    {
        $client = new Client();
        $api_key = "RGAPI-1b2e2699-e357-43ff-bb3a-0a0d30afa002";

        $response = $client->get('https://europe.api.riotgames.com/lol/match/v5/matches/'. $match , [
            'headers' => [
                'X-Riot-Token' => $api_key,
            ]
        ]);
        $decoded_match = json_decode($response->getBody());

        foreach ($decoded_match->info->participants as $participant) {
            DB::table('match_player')->insert([
                'username' => $participant->summonerName,
                'championName' => $participant->championName,
                'teamId' => $participant->teamId,
                'summonerSpell1Id' => $participant->summoner1Id,
                'summonerSpell2Id' => $participant->summoner2Id,
                'kills' => $participant->kills,
                'deaths' => $participant->deaths,
                'assists' => $participant->assists,
                'kda' => $participant->challenges->kda,
                'totalMinionKilled' => $participant->totalMinionsKilled,
                'visionScore' => $participant->visionScore,
                'perks10' => $participant->perks->styles[0]->style,
                'perks11' => $participant->perks->styles[0]->selections[0]->perk,
                'perks12' => $participant->perks->styles[0]->selections[1]->perk,
                'perks13' => $participant->perks->styles[0]->selections[2]->perk,
                'perks14' => $participant->perks->styles[0]->selections[3]->perk,
                'perks20' => $participant->perks->styles[1]->style,
                'perks21' => $participant->perks->styles[1]->selections[0]->perk,
                'perks22' => $participant->perks->styles[1]->selections[1]->perk,
                'item0' => $participant->item0,
                'item1' => $participant->item1,
                'item2' => $participant->item2,
                'item3' => $participant->item3,
                'item4' => $participant->item4,
                'item5' => $participant->item5,
                'match_id' => $match_id,
                'win' => $participant->win,
            ]);
        }
    }

    public function getMatches($user)
    {
        $client = new Client();
        $api_key = "RGAPI-1b2e2699-e357-43ff-bb3a-0a0d30afa002";

        // Retrieve a list of matches played by the summoner
        $response = $client->get('https://europe.api.riotgames.com/lol/match/v5/matches/by-puuid/'. $user->puuid .'/ids' , [
            'headers' => [
                'X-Riot-Token' => $api_key,
            ],
            'query' => [
                'start' => 0, 
                'count' => 20, 
            ]
        ]);
        $matches = json_decode($response->getBody());
        foreach ($matches as $match){
            $response = $client->get('https://europe.api.riotgames.com/lol/match/v5/matches/'. $match , [
                'headers' => [
                    'X-Riot-Token' => $api_key,
                ]
            ]);
            $decoded_match = json_decode($response->getBody());
            $dbMatch = DB::table('match')->where('match_id', $decoded_match->metadata->matchId)->get();
            if ($dbMatch->isEmpty()) {
                DB::table('match')->insert([
                    'match_id' => $decoded_match->metadata->matchId,
                    'summoner_id' => $user->id,
                ]);
            }
        }
    }

    public function getUser($username)
    {
        $client = new Client();
        $api_key = "RGAPI-1b2e2699-e357-43ff-bb3a-0a0d30afa002";
        
        // Retrieve the encrypted account ID for the summoner
        $response = $client->get('https://EUW1.api.riotgames.com/lol/summoner/v4/summoners/by-name/' . $username, [
            'headers' => [
                'X-Riot-Token' => $api_key,
            ]
        ]);

        $summoner = json_decode($response->getBody());

        // Retrieve the summoner entries: winrate leaguepoints etc
        $response = $client->get('https://EUW1.api.riotgames.com/lol/league/v4/entries/by-summoner/' . $summoner->id, [
            'headers' => [
                'X-Riot-Token' => $api_key,
            ]
        ]);

        $summoner_entries = json_decode($response->getBody());
        DB::table('summoner')->insert([
            'name' => strtolower($summoner->name),
            'puuid' => $summoner->puuid,
            'tiers' => !isNull($summoner_entries) ? $summoner_entries[1]->tier : '-',
            'rank' => !isNull($summoner_entries) ? $summoner_entries[1]->rank : '-',
            'win' => !isNull($summoner_entries) ? $summoner_entries[1]->wins : 0,
            'losses' => !isNull($summoner_entries) ? $summoner_entries[1]->losses : 0,
            'leaguePoints' => !isNull($summoner_entries) ? $summoner_entries[1]->leaguePoints : 0,
        ]);
    }

    public function getData(Request $request)
    {
        // self::getDbData($request->username);
        $ret = array();
        if (isNull($summoner = DB::table('summoner')->where('name', strtolower($request->username))->first())){
            self::getUser($request->username);
            $summoner =  DB::table('summoner')->where('name', strtolower($request->username))->first();
        }
        $matches =  DB::table('match')->where('summoner_id', $summoner->id)->get();
        
        if ($matches->isEmpty()) {
            self::getMatches($summoner);
            $matches = DB::table('match')->where('summoner_id', $summoner->id)->get();
        }
        foreach ($matches as $match_key => $match) {
            $ret[$match_key] = $match;
            $players = DB::table('match_player')->where('match_id', $match->id)->get()->toArray(); 
            if (!$players) {
                self::getMatchPlayers($match->match_id, $match->id);
            } 
    
            foreach (DB::table('match_player')->where('match_id', $match->id)->get()->toArray() as $player_key => $player){
                $ret[$match_key]->{$player_key} = $player;
            }
        }

        return view('riotApi.sumonner', [
            'matches' => $ret,
            'summoner' => $summoner,
            
        ]);
    }

    function sortByWinrate($a, $b) {
        $aWinrate = $a['win'] / $a['played'];
        $bWinrate = $b['win'] / $b['played'];
        if ($aWinrate == $bWinrate) {
            return 0;
        }
        return ($aWinrate > $bWinrate) ? -1 : 1;
    }

    public function tierlist(Request $request)
    {
        $players = DB::table('match_player')->get();
        $champions = array();

        foreach ($players as $player) {
            if (!in_array($player->championName, $champions)) {
                $champions[$player->championName] = $player->championName; 
                $champions[$player->championName] = array('name' => $player->championName, 'win' => 0, 'losses' => 0, 'played' => 0, 'kda' => 1);
            }
        }
        foreach ($players as $player){
            $player->win == 1 ? $champions[$player->championName]['win'] += 1 : $champions[$player->championName]['losses'] += 1;
            $champions[$player->championName]['played'] += 1;
            $champions[$player->championName]['kda'] = ($champions[$player->championName]['kda'] * $champions[$player->championName]['played'] + $player->kda)  / ($champions[$player->championName]['played'] + 1);
        }
        
        return view('riotApi.tierlist', [
            'champions' => $champions,
        ]);
    }

    public function update(Request $request)
    {
        // Update code goes here
        self::getUser($request->input('name'));

        $summoner =  DB::table('summoner')->where('name', strtolower($request->input('name')))->first();

        self::getMatches($summoner);
        $matches = DB::table('match')->where('summoner_id', $summoner->id)->get();
        
        foreach ($matches as $match_key => $match) {
            $ret[$match_key] = $match;
            self::getMatchPlayers($match->match_id, $match->id);
            foreach (DB::table('match_player')->where('match_id', $match->id)->get()->toArray() as $player_key => $player){
                $ret[$match_key]->{$player_key} = $player;
            }
        }

        return view('riotApi.sumonner', [
            'matches' => $ret,
            'summoner' => $summoner,
            
        ]);
    }

    public function bravery(Request $request)
    {
        $files = File::files(public_path('riotImages/items/'));
        $files_sliced = array_slice($files, 70);
        shuffle($files_sliced);
        $images = [];
        

        foreach ($files_sliced as $file) {
            $images[] = $file->getRelativePathname();
            if (count($images) > 5){
                break;
            }
        }
        dump($images);
        return view('riotApi.bravery', [
            'images' => $images,            
        ]);
    }
}


