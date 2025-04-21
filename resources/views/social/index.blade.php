@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="display-5 mb-0">Feed Social</h1>
            <p class="text-secondary">Découvrez les produits partagés par la communauté</p>
        </div>
    </div>
    
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <div>
                <a href="{{ route('social.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle me-2"></i> Partager un produit
                </a>
            </div>
            
            <div class="btn-group">
                <button type="button" class="btn btn-outline-light active" data-filter="all">
                    <i class="fas fa-th-large me-1"></i> Tous
                </button>
                <button type="button" class="btn btn-outline-light" data-filter="price">
                    <i class="fas fa-tag me-1"></i> Prix
                </button>
                <button type="button" class="btn btn-outline-light" data-filter="deal">
                    <i class="fas fa-percent me-1"></i> Promos
                </button>
                <button type="button" class="btn btn-outline-light" data-filter="meal">
                    <i class="fas fa-utensils me-1"></i> Repas
                </button>
                <button type="button" class="btn btn-outline-light" data-filter="review">
                    <i class="fas fa-star me-1"></i> Avis
                </button>
            </div>
        </div>
    </div>
    
    @if(session('success'))
        <div class="alert alert-info mb-4">
            {{ session('success') }}
        </div>
    @endif
    
    <div class="row">
        @forelse($posts as $post)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="position-relative">
                        <img src="{{ $post->image }}" class="card-img-top" alt="{{ $post->product_name }}" style="height: 200px; object-fit: cover;">
                        <span class="badge bg-dark position-absolute top-0 end-0 m-2">
                            @switch($post->post_type)
                                @case('price')
                                    <i class="fas fa-tag me-1"></i> Prix
                                    @break
                                @case('deal')
                                    <i class="fas fa-percent me-1"></i> Promo
                                    @break
                                @case('meal')
                                    <i class="fas fa-utensils me-1"></i> Repas
                                    @break
                                @case('review')
                                    <i class="fas fa-star me-1"></i> Avis
                                    @break
                            @endswitch
                        </span>
                        
                        @if($post->regular_price && $post->getSavingsPercentage() > 0)
                            <span class="badge bg-danger position-absolute top-0 start-0 m-2">
                                -{{ $post->getSavingsPercentage() }}%
                            </span>
                        @endif
                    </div>
                    
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            @if($post->user->avatar)
                                <img src="{{ $post->user->avatar }}" alt="Avatar" class="avatar-small me-2">
                            @else
                                <i class="fas fa-user-circle me-2" style="font-size: 1.5rem;"></i>
                            @endif
                            <div>
                                <div class="fw-bold">{{ $post->user->name }}</div>
                                <div class="text-secondary small">{{ $post->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                        
                        <h5 class="card-title">{{ $post->product_name }}</h5>
                        <h6 class="text-secondary">{{ $post->store_name }}</h6>
                        
                        <div class="d-flex align-items-baseline mb-3">
                            <span class="fs-4 fw-bold text-success me-2">{{ number_format($post->price, 2) }} €</span>
                            
                            @if($post->regular_price)
                                <span class="text-secondary text-decoration-line-through">
                                    {{ number_format($post->regular_price, 2) }} €
                                </span>
                            @endif
                        </div>
                        
                        @if($post->description)
                            <p class="card-text">{{ Str::limit($post->description, 100) }}</p>
                        @endif
                        
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div>
                                <form action="{{ route('social.like', $post) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-light">
                                        @if($post->likes()->where('user_id', auth()->id())->exists())
                                            <i class="fas fa-heart text-danger"></i>
                                        @else
                                            <i class="far fa-heart"></i>
                                        @endif
                                        <span>{{ $post->likes_count }}</span>
                                    </button>
                                </form>
                                
                                <a href="{{ route('social.show', $post) }}" class="btn btn-sm btn-outline-light ms-1">
                                    <i class="far fa-comment"></i>
                                    <span>{{ $post->comments_count }}</span>
                                </a>
                            </div>
                            
                            <a href="{{ route('social.show', $post) }}" class="btn btn-sm btn-outline-light">
                                Voir plus <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center p-5">
                        <i class="fas fa-stream mb-3" style="font-size: 3rem; color: var(--accent-primary);"></i>
                        <h3>Aucune publication pour le moment</h3>
                        <p class="text-secondary">Soyez le premier à partager un produit !</p>
                        <a href="{{ route('social.create') }}" class="btn btn-primary mt-3">
                            <i class="fas fa-plus-circle me-2"></i> Partager un produit
                        </a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
    
    <div class="d-flex justify-content-center mt-4">
        {{ $posts->links() }}
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterButtons = document.querySelectorAll('[data-filter]');
        const postCards = document.querySelectorAll('.card');
        
        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                const filter = this.getAttribute('data-filter');
                
                // Toggle active class
                filterButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                
                // Apply filter
                if (filter === 'all') {
                    postCards.forEach(card => {
                        card.closest('.col-md-6').style.display = 'block';
                    });
                } else {
                    postCards.forEach(card => {
                        const postType = card.querySelector('.badge.bg-dark').textContent.trim().toLowerCase();
                        if (postType.includes(filter)) {
                            card.closest('.col-md-6').style.display = 'block';
                        } else {
                            card.closest('.col-md-6').style.display = 'none';
                        }
                    });
                }
            });
        });
    });
</script>
@endsection 