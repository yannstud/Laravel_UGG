@foreach ($champions as $key => $champion)
    <img class="champion_face" src="/riotImages/{{$key}}.png" height="48" width="48"> {{ $key }}<br>
    Winrate : {{ number_format($champion['win'] / $champion['played'], 2, '.', '') }}%<br>
    Match count : {{ number_format($champion['played']) }}<br><br>
@endforeach 