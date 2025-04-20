@extends('layouts.app')

@section('content')
<div class="container py-5">
    <!-- En-tête produit -->
    <div class="row mb-5">
        <div class="col-md-5 mb-4 mb-md-0">
            <div class="product-image">
                <img src="{{ $productInfo['image_url'] }}" alt="{{ $productInfo['product_name'] }}" class="img-fluid">
            </div>
        </div>
        <div class="col-md-7">
            <div class="product-details">
                <h6 class="text-success mb-3">powered by etchelast</h6>
                <h1 class="mb-3">{{ $productInfo['product_name'] }}</h1>
                <p class="text-secondary mb-2">{{ $productInfo['product_quantity'] }}</p>
                <p class="text-muted"><small>{{ $productCode }}</small></p>
                
                <div class="mt-4 mb-4 text-center">
                    <h3 class="text-success mb-3">MEILLEUR PRIX ACTUEL</h3>
                    <h2 class="display-4 fw-bold mb-3">{{ number_format($stats['min'], 2) }}€</h2>
                    <p class="text-secondary">
                        soit <span class="text-success">{{ number_format($stats['max'] - $stats['min'], 2) }}€</span> d'économie possible
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="price-stats">
                <div class="price-stat-item">
                    <i class="fas fa-arrow-down"></i>
                    <h4>Prix Min</h4>
                    <h3 class="text-success fw-bold">{{ number_format($stats['min'], 2) }}€</h3>
                </div>
                <div class="price-stat-item">
                    <i class="fas fa-equals"></i>
                    <h4>Prix Moyen</h4>
                    <h3 class="fw-bold">{{ number_format($stats['avg'], 2) }}€</h3>
                </div>
                <div class="price-stat-item">
                    <i class="fas fa-arrow-up"></i>
                    <h4>Prix Max</h4>
                    <h3 class="text-danger fw-bold">{{ number_format($stats['max'], 2) }}€</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des magasins -->
    <div class="row mb-5">
        <div class="col-12 mb-4 text-center">
            <h2 class="text-success position-relative d-inline-block">
                <span class="position-relative">COMPARAISON DES PRIX</span>
                <div class="position-absolute bg-success" style="height: 2px; width: 50%; bottom: -10px; left: 25%;"></div>
            </h2>
        </div>

        @foreach($prices as $price)
        <div class="col-12 mb-4">
            <div class="card {{ $price['price'] == $stats['min'] ? 'border-success' : '' }}" 
                 style="border-left-width: {{ $price['price'] == $stats['min'] ? '4px' : '1px' }};">
                <div class="card-body">
                    <div class="row align-items-center">
                        <!-- Infos du magasin -->
                        <div class="col-md-7 mb-3 mb-md-0">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle me-3 d-flex align-items-center justify-content-center" 
                                     style="width: 60px; height: 60px; background: {{ $price['price'] == $stats['min'] ? 'rgba(0, 209, 178, 0.2)' : 'var(--card-bg)' }};">
                                    <i class="fas fa-store {{ $price['price'] == $stats['min'] ? 'text-success' : '' }} fa-lg"></i>
                                </div>
                                <div>
                                    <div class="d-flex align-items-center mb-1">
                                        <h4 class="mb-0 me-2">{{ $price['store'] }}</h4>
                                        @if($price['price'] == $stats['min'])
                                        <span class="badge bg-success bg-opacity-25 text-success small">
                                            MEILLEUR PRIX
                                        </span>
                                        @endif
                                    </div>
                                    <p class="text-secondary mb-2 small">{{ $price['address'] }}</p>
                                    
                                    @if(isset($price['maps_url']))
                                    <a href="{{ $price['maps_url'] }}" target="_blank" 
                                       class="btn btn-sm {{ $price['price'] == $stats['min'] ? 'btn-outline-success' : 'btn-outline-light' }}">
                                        <i class="fas fa-map-marker-alt me-1"></i>
                                        Voir sur la carte
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- Prix -->
                        <div class="col-md-5 text-md-end text-start">
                            <div class="d-flex align-items-center justify-content-md-end justify-content-start">
                                <h3 class="mb-0 {{ $price['price'] == $stats['min'] ? 'text-success' : '' }} fw-bold">
                                    {{ number_format($price['price'], 2) }}€
                                </h3>
                                
                                @if($price['price'] == $stats['min'])
                                <i class="fas fa-crown text-success ms-2 fa-sm"></i>
                                @endif
                            </div>
                            
                            @if($price['price'] > $stats['min'])
                            <div class="text-danger mt-1">
                                <small>
                                    <i class="fas fa-arrow-up me-1 fa-xs"></i>
                                    +{{ number_format($price['price'] - $stats['min'], 2) }}€ par rapport au min
                                </small>
                            </div>
                            @endif
                            
                            <div class="text-muted mt-2 small">
                                <i class="far fa-calendar-alt me-1"></i>
                                {{ \Carbon\Carbon::parse($price['date'])->format('d/m/Y') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        
        <!-- Légende & Mentions -->
        <div class="col-12 mt-3">
            <div class="alert alert-info">
                <p class="mb-0 text-center">
                    <i class="fas fa-info-circle me-2"></i>
                    Les prix sont mis à jour régulièrement. Dernière vérification : {{ date('d/m/Y') }}
                </p>
            </div>
        </div>
    </div>
</div>
@endsection