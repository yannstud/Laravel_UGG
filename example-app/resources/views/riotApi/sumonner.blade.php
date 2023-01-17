<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Riot Api') }}
        </h2>
    </x-slot>

    <div class=summoner>
        <form method="POST" action="{{ route('riotApi.update') }}">
            @csrf
            <input type="hidden" name="name" value="{{$summoner->name}}">
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
        name: {{$summoner->name}}<br>
        @if ($summoner->tiers)
            Queue type: Ranked<br>
            {{$summoner->tiers}} {{$summoner->rank}} {{$summoner->leaguePoints}} LP<br>
            @php 
                if ($summoner->win != 0 || $summoner->losses != 0){
                    $nb_games = $summoner->win + $summoner->losses;
                    $winrate = $summoner->win / $nb_games;
                } else {
                    $winrate = 0;
                }
            @endphp
            Winrate: {{ number_format($winrate, 2, '.', '') }}%<br>
        @else
            No ranked data
        @endif
    </div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                @foreach ($matches as $match)
                    @for ($player_key = 0; $player_key < 9; $player_key++)
                        @if (strtolower($match->{$player_key}->username) == strtolower($summoner->name)) 
                            @php
                                if ($match->{$player_key}->win == true) {
                                    $color = "#4169E1";
                                } else {
                                    $color = "#DC143C";
                                }
                            @endphp
                            
                            <div class="champ_container" style="background-color: {{$color}}">
                                @php($my_champ_team_id = $match->{$player_key}->teamId)
                                <img class="champion_face" src="/riotImages/{{$match->{$player_key}->championName}}.png" height="48" width="48">
                                <img class="summobnerspell" src="/riotImages/spells/{{$match->{$player_key}->summonerSpell1Id}}.png" height="24" width="24">
                                <img class="summobnerspell" src="/riotImages/spells/{{$match->{$player_key}->summonerSpell2Id}}.png" height="24" width="24">
                                <div class="champ_stats">
                                    <div class="kda_total">
                                        {{$match->{$player_key}->kills}} / {{$match->{$player_key}->deaths}} / {{$match->{$player_key}->assists}}
                                    </div>
                                    <div>
                                        {{number_format($match->{$player_key}->kda, 2, '.', '')}} KDA
                                    </div>
                                    <div>
                                        {{$match->{$player_key}->totalMinionKilled}} CS
                                    </div>
                                    <div>
                                        {{$match->{$player_key}->visionScore}} Vision 
                                    </div>
                                </div>
                                <div class="build">
                                    primary
                                    <img src="/riotImages/perk-images/Styles/{{$match->{$player_key}->perks10}}.png" height="25" width="25">
                                    <img src="/riotImages/perk-images/Styles/{{$match->{$player_key}->perks11}}.png" height="25" width="25">
                                    <img src="/riotImages/perk-images/Styles/{{$match->{$player_key}->perks12}}.png" height="25" width="25">
                                    <img src="/riotImages/perk-images/Styles/{{$match->{$player_key}->perks13}}.png" height="25" width="25">
                                    <img src="/riotImages/perk-images/Styles/{{$match->{$player_key}->perks14}}.png" height="25" width="25">
                                    secondary
                                    <img src="/riotImages/perk-images/Styles/{{$match->{$player_key}->perks20}}.png" height="25" width="25">
                                    <img src="/riotImages/perk-images/Styles/{{$match->{$player_key}->perks21}}.png" height="25" width="25">
                                    <img src="/riotImages/perk-images/Styles/{{$match->{$player_key}->perks22}}.png" height="25" width="25">
                                </div>
                                <div class=items>
                                    @if ($match->{$player_key}->item0 != 0)
                                        <img src="/riotImages/items/{{$match->{$player_key}->item0}}.png" height="48" width="48">
                                    @endif
                                    @if ($match->{$player_key}->item1 != 0)
                                        <img src="/riotImages/items/{{$match->{$player_key}->item1}}.png" height="48" width="48">
                                    @endif
                                    @if ($match->{$player_key}->item2 != 0)
                                        <img src="/riotImages/items/{{$match->{$player_key}->item2}}.png" height="48" width="48">
                                    @endif
                                    @if ($match->{$player_key}->item3 != 0)
                                        <img src="/riotImages/items/{{$match->{$player_key}->item3}}.png" height="48" width="48">
                                    @endif
                                    @if ($match->{$player_key}->item4 != 0)
                                        <img src="/riotImages/items/{{$match->{$player_key}->item4}}.png" height="48" width="48">
                                    @endif
                                    @if ($match->{$player_key}->item5 != 0)
                                        <img src="/riotImages/items/{{$match->{$player_key}->item5}}.png" height="48" width="48">
                                    @endif
                                </div>
                            </div><br>
                        @endif
                    @endfor
                    @for ($player_key = 0; $player_key < 9; $player_key++)
                        <span class="caption">{{strtolower($match->{$player_key}->username)}}</span>
                        <a href="{{ route('riotApi.sumonner', ['username' => $match->{$player_key}->username]) }}">
                            <img class="miniature" src="/riotImages/{{$match->{$player_key}->championName}}.png" height="48" width="48">
                        </a>
                    @endfor
                    <br>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
