@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <a href="{{ route('social.feed') }}" class="btn btn-outline-light mb-3">
                <i class="fas fa-arrow-left me-2"></i> Retour au feed
            </a>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-7 mb-4">
            <div class="card h-100">
                <div class="position-relative">
                    <img src="{{ $post->image }}" class="card-img-top" alt="{{ $post->product_name }}" style="width: 100%; object-fit: cover;">
                    <span class="badge bg-dark position-absolute top-0 end-0 m-3">
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
                        <span class="badge bg-danger position-absolute top-0 start-0 m-3">
                            -{{ $post->getSavingsPercentage() }}%
                        </span>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-lg-5 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        @if($post->user->avatar)
                            <img src="{{ $post->user->avatar }}" alt="Avatar" class="avatar-small me-2">
                        @else
                            <i class="fas fa-user-circle me-2" style="font-size: 1.5rem;"></i>
                        @endif
                        <div>
                            <div class="fw-bold">{{ $post->user->name }}</div>
                            <div class="text-secondary small">{{ $post->created_at->format('d/m/Y à H:i') }}</div>
                        </div>
                    </div>
                    
                    <h3 class="mb-1">{{ $post->product_name }}</h3>
                    <h5 class="text-secondary mb-4">{{ $post->store_name }}</h5>
                    
                    <div class="price-stats mb-4">
                        <div class="price-stat-item">
                            <i class="fas fa-tag"></i>
                            <h4 class="mt-2 mb-0 text-success">{{ number_format($post->price, 2) }} €</h4>
                            <div class="text-secondary small">Prix actuel</div>
                        </div>
                        
                        @if($post->regular_price)
                            <div class="price-stat-item">
                                <i class="fas fa-euro-sign"></i>
                                <h4 class="mt-2 mb-0 text-secondary text-decoration-line-through">{{ number_format($post->regular_price, 2) }} €</h4>
                                <div class="text-secondary small">Prix habituel</div>
                            </div>
                            
                            <div class="price-stat-item">
                                <i class="fas fa-piggy-bank"></i>
                                <h4 class="mt-2 mb-0 text-success">{{ number_format($post->getSavingsAttribute(), 2) }} €</h4>
                                <div class="text-secondary small">Économie</div>
                            </div>
                        @endif
                    </div>
                    
                    @if($post->description)
                        <div class="mb-4">
                            <h5>Description</h5>
                            <p>{{ $post->description }}</p>
                        </div>
                    @endif
                    
                    @if($post->expires_at)
                        <div class="alert {{ $post->getIsExpiredAttribute() ? 'alert-danger' : 'alert-info' }} mb-4">
                            <i class="fas fa-clock me-2"></i>
                            @if($post->getIsExpiredAttribute())
                                <span>Offre expirée depuis le {{ $post->expires_at->format('d/m/Y') }}</span>
                            @else
                                <span>Offre valable jusqu'au {{ $post->expires_at->format('d/m/Y') }}</span>
                            @endif
                        </div>
                    @endif
                    
                    <div class="d-flex mb-3">
                        <form action="{{ route('social.like', $post) }}" method="POST" class="me-3">
                            @csrf
                            <button type="submit" class="btn btn-outline-light">
                                @if($post->likes()->where('user_id', auth()->id())->exists())
                                    <i class="fas fa-heart text-danger me-1"></i>
                                @else
                                    <i class="far fa-heart me-1"></i>
                                @endif
                                <span>{{ $post->likes_count }}</span> J'aime
                            </button>
                        </form>
                        
                        <button type="button" class="btn btn-outline-light" onclick="document.getElementById('comment-form').scrollIntoView();">
                            <i class="far fa-comment me-1"></i>
                            <span>{{ $post->comments_count }}</span> Commenter
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-body">
                    <h4 class="mb-4">Commentaires</h4>
                    
                    <form action="{{ route('social.comment', $post) }}" method="POST" id="comment-form" class="mb-4">
                        @csrf
                        <div class="d-flex">
                            @if(auth()->user()->avatar)
                                <img src="{{ auth()->user()->avatar }}" alt="Avatar" class="avatar-small me-3 mt-1">
                            @else
                                <i class="fas fa-user-circle me-3 mt-1" style="font-size: 1.5rem;"></i>
                            @endif
                            <div class="flex-grow-1">
                                <textarea class="form-control mb-2" name="content" rows="2" placeholder="Ajouter un commentaire..." required></textarea>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane me-1"></i> Commenter
                                </button>
                            </div>
                        </div>
                        @error('content')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </form>
                    
                    <div class="comments-list">
                        @forelse($post->comments as $comment)
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="d-flex mb-2">
                                        @if($comment->user->avatar)
                                            <img src="{{ $comment->user->avatar }}" alt="Avatar" class="avatar-small me-2">
                                        @else
                                            <i class="fas fa-user-circle me-2" style="font-size: 1.5rem;"></i>
                                        @endif
                                        <div>
                                            <div class="fw-bold">{{ $comment->user->name }}</div>
                                            <div class="text-secondary small">{{ $comment->created_at->diffForHumans() }}</div>
                                        </div>
                                    </div>
                                    
                                    <p class="mb-0">{{ $comment->content }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="text-center p-4">
                                <i class="far fa-comment-dots mb-3" style="font-size: 3rem; color: var(--accent-primary);"></i>
                                <h5>Aucun commentaire pour le moment</h5>
                                <p class="text-secondary">Soyez le premier à commenter cette publication !</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 