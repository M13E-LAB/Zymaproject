@extends('layouts.app')

@section('content')
<div class="container py-5">
    <!-- En-tête produit -->
    <div class="row mb-5">
        <div class="col-md-5 mb-4 mb-md-0">
            <div class="product-image text-center">
                @if($product['image'])
                    <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}" class="img-fluid rounded" style="max-height: 400px;">
                @else
                    <div class="bg-light d-flex align-items-center justify-content-center rounded" style="height: 400px;">
                        <i class="fas fa-image fa-3x text-muted"></i>
                    </div>
                @endif
            </div>
        </div>
        <div class="col-md-7">
            <div class="product-details">
                <h6 class="text-primary mb-3">✨ powered by OpenFoodFacts</h6>
                <h1 class="mb-3">{{ $product['name'] }}</h1>
                
                @if($product['brand'])
                    <p class="text-secondary mb-2"><strong>Marque :</strong> {{ $product['brand'] }}</p>
                @endif
                
                @if($product['quantity'])
                    <p class="text-secondary mb-2"><strong>Quantité :</strong> {{ $product['quantity'] }}</p>
                @endif
                
                <p class="text-muted"><small><strong>Code-barres :</strong> {{ $product['id'] }}</small></p>
                
                @if($product['nutriscore'])
                    <div class="my-3">
                        <span class="badge bg-{{ $product['nutriscore'] === 'a' ? 'success' : ($product['nutriscore'] === 'b' ? 'info' : ($product['nutriscore'] === 'c' ? 'warning' : 'danger')) }} fs-6">
                            Nutri-Score : {{ strtoupper($product['nutriscore']) }}
                        </span>
                    </div>
                @endif

                <!-- Prix récents simulés -->
                <div class="mt-4 p-3 bg-light rounded">
                    <h5 class="text-primary mb-3">💰 Prix récents</h5>
                    @foreach($product['recent_prices'] as $price)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span><strong>{{ $price['store'] }}</strong></span>
                            <span class="badge bg-primary">{{ number_format($price['price'], 2) }}€</span>
                        </div>
                    @endforeach
                    <small class="text-muted">Prix collectés par la communauté</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Informations nutritionnelles -->
    @if($product['nutrition'] && array_filter($product['nutrition']))
    <div class="row mb-5">
        <div class="col-12">
            <h3 class="text-primary mb-4">📊 Informations nutritionnelles (pour 100g)</h3>
            <div class="row">
                @if($product['nutrition']['energy'])
                    <div class="col-md-3 mb-3">
                        <div class="card h-100 text-center">
                            <div class="card-body">
                                <h5 class="card-title">Énergie</h5>
                                <p class="card-text display-6 fw-bold text-primary">{{ $product['nutrition']['energy'] }}</p>
                                <small class="text-muted">kcal</small>
                            </div>
                        </div>
                    </div>
                @endif
                
                @if($product['nutrition']['proteins'])
                    <div class="col-md-3 mb-3">
                        <div class="card h-100 text-center">
                            <div class="card-body">
                                <h5 class="card-title">Protéines</h5>
                                <p class="card-text display-6 fw-bold text-success">{{ number_format($product['nutrition']['proteins'], 1) }}</p>
                                <small class="text-muted">g</small>
                            </div>
                        </div>
                    </div>
                @endif
                
                @if($product['nutrition']['carbohydrates'])
                    <div class="col-md-3 mb-3">
                        <div class="card h-100 text-center">
                            <div class="card-body">
                                <h5 class="card-title">Glucides</h5>
                                <p class="card-text display-6 fw-bold text-warning">{{ number_format($product['nutrition']['carbohydrates'], 1) }}</p>
                                <small class="text-muted">g</small>
                            </div>
                        </div>
                    </div>
                @endif
                
                @if($product['nutrition']['fat'])
                    <div class="col-md-3 mb-3">
                        <div class="card h-100 text-center">
                            <div class="card-body">
                                <h5 class="card-title">Lipides</h5>
                                <p class="card-text display-6 fw-bold text-info">{{ number_format($product['nutrition']['fat'], 1) }}</p>
                                <small class="text-muted">g</small>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- Ingrédients -->
    @if($product['ingredients'])
    <div class="row mb-5">
        <div class="col-12">
            <h3 class="text-primary mb-3">🧪 Ingrédients</h3>
            <div class="card">
                <div class="card-body">
                    <p class="card-text">{{ $product['ingredients'] }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Catégories -->
    @if($product['categories'])
    <div class="row mb-5">
        <div class="col-12">
            <h3 class="text-primary mb-3">🏷️ Catégories</h3>
            <p>{{ $product['categories'] }}</p>
        </div>
    </div>
    @endif

    <!-- Actions -->
    <div class="row mb-5">
        <div class="col-12 text-center">
            <a href="{{ route('products.search') }}" class="btn btn-outline-primary me-3">
                <i class="fas fa-arrow-left me-2"></i>Retour à la recherche
            </a>
            <form action="{{ route('products.fetch') }}" method="POST" class="d-inline">
                @csrf
                <input type="hidden" name="product_code" value="{{ $product['id'] }}">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-chart-line me-2"></i>Comparer les prix
                </button>
            </form>
        </div>
    </div>
</div>

<style>
.product-image img {
    max-width: 100%;
    height: auto;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.card {
    border: none;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    transition: transform 0.2s;
}

.card:hover {
    transform: translateY(-2px);
}

.badge {
    font-size: 0.9rem;
}
</style>
@endsection 