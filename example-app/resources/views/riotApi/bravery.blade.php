<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Riot Api') }}
        </h2>
    </x-slot>
    <h1>Ultimate bravery</h1>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                @foreach($images as $image)
                    <img src="/riotImages/items/{{$image}}" alt =""/>
                @endforeach 
            </div>
        </div>
    </div>
</x-app-layout>
