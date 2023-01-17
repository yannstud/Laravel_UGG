<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Riot Api') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form action="{{ route('riotApi.sumonner') }}" method="GET">
                    <label for="username">Nom de l'utilisateur:</label>
                    <input type="text" name="username" id="username">
                    <button type="submit">Afficher les informations du sumonner</button>
                </form>
            </div>
        </div>
    </div>
    <div>
        <a type="button" class="btn btn-primary" href="/bravery">Ultimate bravery</a>
        <a type="button" class="btn btn-primary" href="/tierlist">Champions stats</a>
    </div>
</x-app-layout>
