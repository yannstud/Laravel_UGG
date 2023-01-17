<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Binance Api Chart') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{-- @foreach ($data as $item)
                        timestamp {{$item[0]}}
                        open {{$item[1]}}
                        high {{$item[2]}}
                        low {{$item[3]}}
                        close {{$item[4]}}
                    @endforeach --}}
                </div>
                <canvas id="priceChart"></canvas>

            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js" ></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" ></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        var ctx = document.getElementById('priceChart').getContext('2d');
        var prices = @json($data); // Récupération des données de prix passées à la vue
        var labels = []; // Tableau des étiquettes (dates) du graphique
        var data = []; // Tableau des données (prix) du graphique

        // Remplissage des tableaux labels et data à partir des données de prix
        for (var i = 0; i < prices.length; i++) {
            labels.push(moment(prices[i][0]).format('DD/MM/YYYY HH:mm'));
            data.push(prices[i][4]);
        }

        var chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Prix : {{$label}}',
                    data: data,
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
    </script>
</x-app-layout>
