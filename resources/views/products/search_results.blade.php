@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="mb-4">Résultats pour "{{ $query }}"</h1>
            <p class="text-secondary">{{ count($products) }} produits trouvés</p>
        </div>
    </div>

    @if(count($products) > 0)
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            @foreach($products as $product)
                <div class="col">
                    <div class="card h-100 product-card">
                        <div class="card-body">
                            <div class="d-flex mb-3">
                                <div class="product-image-container me-3">
                                    @if($product['image'])
                                        <img src="{{ $product['image'] }}" class="product-thumb" alt="{{ $product['name'] }}">
                                    @else
                                        <div class="product-thumb-placeholder">
                                            <i class="fas fa-image"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="product-info">
                                    @if(isset($product['brand']) && !empty($product['brand']))
                                        <div class="product-brand">{{ $product['brand'] }}</div>
                                    @endif
                                    <h5 class="product-name">{{ $product['name'] }}</h5>
                                    @if(isset($product['quantity']) && !empty($product['quantity']))
                                        <div class="product-quantity">{{ $product['quantity'] }}</div>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="product-meta">
                                @if(isset($product['nutriscore']) && !empty($product['nutriscore']))
                                    <div class="product-score nutriscore-{{ strtolower($product['nutriscore']) }}">
                                        Nutriscore {{ strtoupper($product['nutriscore']) }}
                                    </div>
                                @endif
                                
                                @if(isset($product['categories']) && !empty($product['categories']))
                                    <div class="product-categories">
                                        {{ \Illuminate\Support\Str::limit($product['categories'], 50) }}
                                    </div>
                                @endif
                            </div>
                            
                            <div class="mt-3 d-flex justify-content-between align-items-center">
                                <a href="{{ route('products.show', ['id' => $product['id']]) }}" class="btn btn-purple">
                                    <i class="fas fa-search-dollar me-2"></i> Comparer les prix
                                </a>
                                
                                <span class="price-indicator">
                                    <i class="fas fa-tag"></i> ~2.99€
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i> Aucun produit trouvé pour "{{ $query }}". Essayez avec d'autres termes.
        </div>
    @endif
</div>

<style>
    .product-card {
        transition: all 0.3s ease;
        border: 1px solid rgba(255, 255, 255, 0.05);
    }
    
    .product-card:hover {
        transform: translateY(-5px);
        border-color: rgba(114, 83, 246, 0.3);
    }
    
    .product-image-container {
        width: 80px;
        height: 80px;
        overflow: hidden;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: rgba(255, 255, 255, 0.05);
    }
    
    .product-thumb {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }
    
    .product-thumb-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-secondary);
        font-size: 1.5rem;
    }
    
    .product-info {
        flex: 1;
    }
    
    .product-brand {
        font-size: 0.8rem;
        color: var(--text-secondary);
        margin-bottom: 0.3rem;
        text-transform: uppercase;
    }
    
    .product-name {
        font-weight: 600;
        margin-bottom: 0.3rem;
        line-height: 1.3;
    }
    
    .product-quantity {
        font-size: 0.85rem;
        color: var(--text-secondary);
    }
    
    .product-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-top: 1rem;
    }
    
    .product-score {
        font-size: 0.8rem;
        font-weight: 600;
        padding: 0.3rem 0.7rem;
        border-radius: 50px;
        color: #fff;
        background-color: #888;
    }
    
    .nutriscore-a {
        background-color: #27AE60;
    }
    
    .nutriscore-b {
        background-color: #A9D159;
    }
    
    .nutriscore-c {
        background-color: #FFDA3A;
    }
    
    .nutriscore-d {
        background-color: #F39C12;
    }
    
    .nutriscore-e {
        background-color: #E74C3C;
    }
    
    .product-categories {
        font-size: 0.8rem;
        color: var(--text-secondary);
        margin-top: 0.5rem;
    }
    
    .price-indicator {
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--text-secondary);
        padding: 0.3rem 0.7rem;
        border-radius: 50px;
        background-color: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
</style>
@endsection 