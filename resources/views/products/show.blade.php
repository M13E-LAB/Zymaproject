@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-black">
    <!-- Hero section avec image -->
    <div class="relative h-[60vh] overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-b from-black via-transparent to-black z-10"></div>
        <div class="absolute inset-0 flex items-center justify-center">
            <img src="{{ $productInfo['image_url'] }}" 
                 alt="{{ $productInfo['product_name'] }}" 
                 class="w-[400px] h-[400px] object-contain">
        </div>
    </div>

    <!-- Contenu principal -->
    <div class="max-w-4xl mx-auto px-4 -mt-20 relative z-20">
        <!-- En-tête produit -->
        <div class="text-center mb-16">
            <p class="text-[#00FFA3] text-lg mb-4 font-medium tracking-wide">powered by etchelast</p>
            <h1 class="text-white text-5xl font-bold mb-3">{{ $productInfo['product_name'] }}</h1>
            <p class="text-gray-400 text-xl mb-2">{{ $productInfo['product_quantity'] }}</p>
            <p class="text-gray-600 font-mono">{{ $productCode }}</p>
        </div>

        <!-- Prix principal -->
        <div class="text-center mb-16">
            <div class="inline-block">
                <h2 class="text-[#00FFA3] text-2xl mb-3">MEILLEUR PRIX ACTUEL</h2>
                <p class="text-white text-7xl font-bold mb-3">{{ number_format($stats['min'], 2) }}€</p>
                <p class="text-gray-400 text-xl">
                    soit <span class="text-[#00FFA3]">{{ number_format($stats['max'] - $stats['min'], 2) }}€</span> d'économie possible
                </p>
            </div>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-3 gap-12 mb-20">
            <div class="text-center transform hover:scale-105 transition-transform">
                <p class="text-[#00FFA3] text-lg mb-2">
                    <i class="fas fa-arrow-down mr-2"></i>
                    Min
                </p>
                <p class="text-white text-3xl font-bold">{{ number_format($stats['min'], 2) }}€</p>
            </div>

            <div class="text-center transform hover:scale-105 transition-transform">
                <p class="text-gray-400 text-lg mb-2">
                    <i class="fas fa-equals mr-2"></i>
                    Moy
                </p>
                <p class="text-white text-3xl font-bold">{{ number_format($stats['avg'], 2) }}€</p>
            </div>

            <div class="text-center transform hover:scale-105 transition-transform">
                <p class="text-red-500 text-lg mb-2">
                    <i class="fas fa-arrow-up mr-2"></i>
                    Max
                </p>
                <p class="text-white text-3xl font-bold">{{ number_format($stats['max'], 2) }}€</p>
            </div>
        </div>

        <!-- Liste des magasins -->
        <div class="space-y-4">
            <h3 class="text-[#00FFA3] text-xl mb-6 text-center">DISPONIBLE DANS CES MAGASINS</h3>
            @foreach($prices as $price)
            <div class="bg-black border border-gray-800 rounded-xl p-6 flex items-center justify-between hover:border-[#00FFA3] transition-all duration-300">
                <div class="flex items-center gap-6">
                    <div class="w-12 h-12 rounded-xl bg-gray-900 flex items-center justify-center">
                        <i class="fas fa-store text-[#00FFA3]"></i>
                    </div>
                    <div>
                        <h3 class="text-white text-lg font-medium">{{ $price['store'] }}</h3>
                        <p class="text-gray-400">{{ $price['address'] }}</p>
                        @if(isset($price['maps_url']))
                        <a href="{{ $price['maps_url'] }}" target="_blank" 
                           class="text-[#00FFA3] text-sm inline-flex items-center gap-2 mt-2 hover:opacity-80">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>Voir sur la carte</span>
                        </a>
                        @endif
                    </div>
                </div>

                <div class="text-right">
                    <div class="flex items-center justify-end gap-3">
                        <p class="text-2xl font-bold {{ $price['price'] == $stats['min'] ? 'text-[#00FFA3]' : 'text-white' }}">
                            {{ number_format($price['price'], 2) }}€
                        </p>
                        @if($price['price'] == $stats['min'])
                        <i class="fas fa-crown text-[#00FFA3] animate-pulse"></i>
                        @endif
                    </div>
                    @if($price['price'] > $stats['min'])
                    <p class="text-red-500">+{{ number_format($price['price'] - $stats['min'], 2) }}€</p>
                    @endif
                    <p class="text-gray-500 text-sm mt-2">
                        {{ \Carbon\Carbon::parse($price['date'])->format('d/m/Y') }}
                    </p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection