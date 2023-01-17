<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Binance Api') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                {{-- <form action="/search" method="POST">
                    <input type="text" name="query" placeholder="Rechercher...">
                    <button type="submit">Go</button>
                </form>
                <ul>
                    <form>
                        <label for="menu">Choose a currency pair</label><br>
                        <select name="menu" id="menu" onchange="location='/binance/' + this.value">
                            @foreach ($data as $item)
                                <option value="{{$item['symbol']}}">{{$item['symbol']}}</option>
                            @endforeach
                        </select>

                    </form>
                </ul> --}}
                <form action="/binance/chart" method="POST">
                    @csrf
                    <label for="symbol">Choose a currency pair: </label><br>

                    <select name="symbol" id="symbol">
                        @foreach ($data as $item)
                            <option value="{{$item['symbol']}}">{{$item['symbol']}}</option>
                        @endforeach
                    </select><br>
                    <label for="interval">Choose an interval</label><br>
                    <select name="interval" id="interval">
                        <option value="1d">1D</option>
                        <option value="1m">1m</option>
                        <option value="5m">5m</option>
                        <option value="15m">15m</option>
                    </select><br>
                    <input type="submit" value="submit">
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
