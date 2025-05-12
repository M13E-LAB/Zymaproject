@extends('layouts.app')

@section('content')
<div class="profile-container">
    <div class="container">
        <!-- En-tête du feed social -->
        <h1 class="section-title mb-4">Feed Social</h1>
        <p class="feed-subtitle">Découvrez les produits partagés par la communauté</p>
        
        <div class="row mb-4">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <div>
                    <a href="{{ route('social.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus-circle me-2"></i> Partager un produit
                    </a>
                </div>
                
                <div class="filter-tabs">
                    <button type="button" class="filter-btn active" data-filter="all">
                        <i class="fas fa-th-large me-1"></i> Tous
                    </button>
                    <button type="button" class="filter-btn" data-filter="price">
                        <i class="fas fa-tag me-1"></i> Prix
                    </button>
                    <button type="button" class="filter-btn" data-filter="deal">
                        <i class="fas fa-percent me-1"></i> Promos
                    </button>
                    <button type="button" class="filter-btn" data-filter="meal">
                        <i class="fas fa-utensils me-1"></i> Repas
                    </button>
                    <button type="button" class="filter-btn" data-filter="review">
                        <i class="fas fa-star me-1"></i> Avis
                    </button>
                </div>
            </div>
        </div>
        
        @if(session('success'))
            <div class="alert alert-success mb-4">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            </div>
        @endif
        
        <div class="row">
            @forelse($posts as $post)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card profile-card h-100">
                        <div class="post-image-container">
                            <img src="{{ $post->image }}" class="post-image" alt="{{ $post->product_name }}">
                            <span class="post-type-badge">
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
                                <span class="discount-badge">
                                    -{{ $post->getSavingsPercentage() }}%
                                </span>
                            @endif
                        </div>
                        
                        <div class="card-body">
                            <div class="post-author">
                                @if($post->user->avatar)
                                    <img src="{{ $post->user->avatar }}" alt="Avatar" class="author-avatar">
                                @else
                                    <div class="author-avatar-placeholder">
                                        <i class="fas fa-user"></i>
                                    </div>
                                @endif
                                <div>
                                    <div class="author-name">{{ $post->user->name }}</div>
                                    <div class="post-date">{{ $post->created_at->diffForHumans() }}</div>
                                </div>
                            </div>
                            
                            <h5 class="post-title">{{ $post->product_name }}</h5>
                            <h6 class="store-name">{{ $post->store_name }}</h6>
                            
                            <div class="price-container">
                                <span class="current-price">{{ number_format($post->price, 2) }} €</span>
                                
                                @if($post->regular_price)
                                    <span class="original-price">
                                        {{ number_format($post->regular_price, 2) }} €
                                    </span>
                                @endif
                            </div>
                            
                            @if($post->description)
                                <p class="post-description">{{ Str::limit($post->description, 100) }}</p>
                            @endif
                            
                            <div class="post-actions">
                                <div>
                                    <form action="{{ route('social.like', $post) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-action">
                                            @if($post->likes()->where('user_id', auth()->id())->exists())
                                                <i class="fas fa-heart liked"></i>
                                            @else
                                                <i class="far fa-heart"></i>
                                            @endif
                                            <span>{{ $post->likes_count }}</span>
                                        </button>
                                    </form>
                                    
                                    <a href="{{ route('social.show', $post) }}" class="btn btn-action ms-1">
                                        <i class="far fa-comment"></i>
                                        <span>{{ $post->comments_count }}</span>
                                    </a>
                                </div>
                                
                                <a href="{{ route('social.show', $post) }}" class="btn btn-view">
                                    Voir plus <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card profile-card">
                        <div class="card-body empty-state">
                            <div class="empty-icon">
                                <i class="fas fa-stream"></i>
                            </div>
                            <h3>Aucune publication pour le moment</h3>
                            <p>Soyez le premier à partager un produit !</p>
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
</div>

<style>
/* Styles généraux */
.profile-container {
    background-color: #000;
    color: #fff;
    padding: 2rem 0;
    min-height: calc(100vh - 70px);
}

.section-title {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    color: #fff;
}

.feed-subtitle {
    color: #999;
    margin-bottom: 2rem;
}

/* Cartes */
.profile-card {
    background-color: #111;
    border: 1px solid #222;
    border-radius: 10px;
    overflow: hidden;
    margin-bottom: 1.5rem;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    transition: transform 0.3s, box-shadow 0.3s;
}

.profile-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
}

.card-body {
    padding: 1.5rem;
}

/* Boutons */
.btn-primary {
    background-color: #E67E22;
    border: none;
    padding: 0.7rem 1.5rem;
    font-weight: 600;
    transition: all 0.3s;
    border-radius: 8px;
}

.btn-primary:hover {
    background-color: #D35400;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(230, 126, 34, 0.3);
}

/* Filtres */
.filter-tabs {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.filter-btn {
    background-color: #1a1a1a;
    border: 1px solid #333;
    color: #ccc;
    padding: 0.6rem 1rem;
    border-radius: 8px;
    font-size: 0.9rem;
    transition: all 0.3s;
}

.filter-btn:hover {
    background-color: #222;
    color: #fff;
}

.filter-btn.active {
    background-color: #E67E22;
    border-color: #E67E22;
    color: #fff;
}

/* Images des posts */
.post-image-container {
    position: relative;
    height: 200px;
    overflow: hidden;
}

.post-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.post-type-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    background-color: #1a1a1a;
    color: #fff;
    padding: 0.3rem 0.8rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
}

.discount-badge {
    position: absolute;
    top: 10px;
    left: 10px;
    background-color: #E67E22;
    color: #fff;
    padding: 0.3rem 0.8rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 700;
}

/* Auteur du post */
.post-author {
    display: flex;
    align-items: center;
    margin-bottom: 1.2rem;
}

.author-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
    margin-right: 0.8rem;
    border: 2px solid #E67E22;
}

.author-avatar-placeholder {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: #333;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 0.8rem;
    color: #666;
    border: 2px solid #E67E22;
}

.author-name {
    font-weight: 600;
    font-size: 0.95rem;
}

.post-date {
    color: #999;
    font-size: 0.8rem;
}

/* Contenu du post */
.post-title {
    font-size: 1.2rem;
    font-weight: 700;
    margin-bottom: 0.3rem;
}

.store-name {
    color: #999;
    font-size: 0.95rem;
    margin-bottom: 1rem;
}

.price-container {
    display: flex;
    align-items: baseline;
    margin-bottom: 1rem;
}

.current-price {
    font-size: 1.5rem;
    font-weight: 700;
    color: #4CAF50;
    margin-right: 0.8rem;
}

.original-price {
    color: #999;
    text-decoration: line-through;
    font-size: 1rem;
}

.post-description {
    color: #ccc;
    font-size: 0.95rem;
    margin-bottom: 1.2rem;
    line-height: 1.5;
}

/* Actions du post */
.post-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid #222;
}

.btn-action {
    background-color: #1a1a1a;
    border: 1px solid #333;
    color: #ccc;
    padding: 0.4rem 0.8rem;
    border-radius: 8px;
    font-size: 0.9rem;
    transition: all 0.3s;
}

.btn-action:hover {
    background-color: #222;
    color: #fff;
}

.btn-action .liked {
    color: #e74c3c;
}

.btn-view {
    background-color: transparent;
    border: 1px solid #E67E22;
    color: #E67E22;
    padding: 0.4rem 0.8rem;
    border-radius: 8px;
    font-size: 0.9rem;
    transition: all 0.3s;
}

.btn-view:hover {
    background-color: #E67E22;
    color: #fff;
}

/* État vide */
.empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 3rem 1.5rem;
    text-align: center;
}

.empty-icon {
    font-size: 3rem;
    color: #E67E22;
    margin-bottom: 1.5rem;
}

.empty-state h3 {
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 0.8rem;
}

.empty-state p {
    color: #999;
    margin-bottom: 1.5rem;
}

/* Alerte */
.alert-success {
    background-color: rgba(76, 175, 80, 0.1);
    border: 1px solid rgba(76, 175, 80, 0.3);
    color: #4CAF50;
    border-radius: 8px;
    padding: 1rem;
}

/* Pagination */
.pagination {
    margin-top: 2rem;
}

.page-item.active .page-link {
    background-color: #E67E22;
    border-color: #E67E22;
}

.page-link {
    color: #E67E22;
    background-color: #1a1a1a;
    border-color: #333;
}

.page-link:hover {
    color: #fff;
    background-color: #E67E22;
    border-color: #E67E22;
}

/* Responsive */
@media (max-width: 767px) {
    .filter-tabs {
        margin-top: 1rem;
        justify-content: center;
    }
    
    .post-actions {
        flex-direction: column;
        gap: 1rem;
    }
    
    .post-actions > div {
        display: flex;
        width: 100%;
        justify-content: space-between;
    }
    
    .btn-view {
        width: 100%;
    }
}
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterButtons = document.querySelectorAll('.filter-btn');
        const postCards = document.querySelectorAll('.profile-card');
        
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
                        const postType = card.querySelector('.post-type-badge').textContent.trim().toLowerCase();
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