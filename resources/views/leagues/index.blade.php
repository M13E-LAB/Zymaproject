@extends('layouts.app')

@section('content')
<div class="profile-container">
    <div class="container">
        <!-- En-tête de la page des ligues -->
        <h1 class="section-title mb-4">Ligues de Nutrition</h1>
        <p class="feed-subtitle">Comparez vos habitudes alimentaires avec celles de vos amis et des autres utilisateurs</p>
        
        <div class="row mb-4">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <div>
                    <a href="{{ route('leagues.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus-circle me-2"></i> Créer une nouvelle ligue
                    </a>
                </div>
                
                <div>
                    <a href="{{ route('leaderboard.global') }}" class="btn btn-secondary">
                        <i class="fas fa-list-ol me-2"></i> Classement global
                    </a>
                </div>
            </div>
        </div>
        
        @if(session('success'))
            <div class="alert alert-success mb-4">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger mb-4">
                <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
            </div>
        @endif
        
        <!-- Rejoindre une ligue existante -->
        <div class="card mb-4">
            <div class="card-body">
                <h4><i class="fas fa-sign-in-alt me-2"></i> Rejoindre une ligue</h4>
                <p>Entrez le code d'invitation partagé par vos amis pour rejoindre leur ligue</p>
                
                <form action="{{ route('leagues.join') }}" method="POST" class="d-flex">
                    @csrf
                    <input type="text" name="invite_code" class="form-control me-2" placeholder="Code d'invitation">
                    <button type="submit" class="btn btn-primary">Rejoindre</button>
                </form>
            </div>
        </div>
        
        <!-- Mes ligues -->
        <h3 class="mb-3"><i class="fas fa-users me-2"></i> Mes ligues</h3>
        
        <div class="row">
            @forelse($userLeagues as $league)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h4 class="card-title">{{ $league->name }}</h4>
                            <p class="text-muted">Créée par {{ $league->creator->name }}</p>
                            
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge bg-light text-dark">
                                    <i class="fas fa-users me-1"></i> {{ $league->members()->count() }} membres
                                </span>
                                
                                @if($league->is_private)
                                    <span class="badge bg-secondary">
                                        <i class="fas fa-lock me-1"></i> Privée
                                    </span>
                                @else
                                    <span class="badge bg-success">
                                        <i class="fas fa-globe me-1"></i> Publique
                                    </span>
                                @endif
                            </div>
                            
                            <!-- Ma position dans cette ligue -->
                            @php
                                $myPosition = $league->members()
                                    ->where('user_id', auth()->id())
                                    ->first()
                                    ->pivot
                                    ->position ?? 0;
                            @endphp
                            
                            <div class="league-position-indicator mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>Ma position :</span>
                                    <span class="badge bg-primary rounded-pill">{{ $myPosition }}</span>
                                </div>
                            </div>
                            
                            <a href="{{ route('leagues.show', $league->slug) }}" class="btn btn-outline-light w-100 mb-2">
                                Voir le classement
                            </a>
                            <a href="{{ route('leagues.meal.upload', $league->slug) }}" class="btn btn-primary w-100">
                                <i class="fas fa-camera me-2"></i> Partager un repas dans cette ligue
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <i class="fas fa-users fa-3x mb-3 text-muted"></i>
                            <h4>Vous n'avez pas encore rejoint de ligue</h4>
                            <p class="text-muted">Créez une ligue ou rejoignez-en une avec le code d'invitation</p>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
        
        <!-- Mes derniers repas -->
        <div class="row mt-5">
            <div class="col-12">
                <h3 class="section-title mb-4">
                    <i class="fas fa-utensils me-2"></i>Mes derniers repas
                </h3>
                
                @php
                    $userMeals = App\Models\Post::where('user_id', auth()->id())
                        ->where('post_type', 'meal')
                        ->with('mealScore')
                        ->orderBy('created_at', 'desc')
                        ->limit(3)
                        ->get();
                @endphp
                
                @if($userMeals->count() > 0)
                    <div class="row">
                        @foreach($userMeals as $meal)
                            <div class="col-md-4 mb-4">
                                <div class="card profile-card">
                                    <div class="card-img-container">
                                        <img src="{{ $meal->image }}" class="card-img-top meal-image" alt="{{ $meal->product_name }}">
                                        @if($meal->mealScore)
                                            <div class="meal-score-badge">
                                                <span class="score-value">{{ $meal->mealScore->total_score }}/100</span>
                                            </div>
                                        @else
                                            <div class="meal-score-badge pending">
                                                <span class="score-value">En attente</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="card-body">
                                        <h6 class="card-title">{{ $meal->product_name }}</h6>
                                        <p class="card-text small text-muted">
                                            {{ $meal->store_name }} • {{ $meal->created_at->diffForHumans() }}
                                        </p>
                                        @if($meal->mealScore)
                                            <div class="score-breakdown mb-2">
                                                <div class="score-mini">
                                                    <span class="score-label">🫀</span>
                                                    <span>{{ $meal->mealScore->health_score }}</span>
                                                </div>
                                                <div class="score-mini">
                                                    <span class="score-label">👁️</span>
                                                    <span>{{ $meal->mealScore->visual_score }}</span>
                                                </div>
                                                <div class="score-mini">
                                                    <span class="score-label">🌱</span>
                                                    <span>{{ $meal->mealScore->diversity_score }}</span>
                                                </div>
                                            </div>
                                        @endif
                                        <a href="{{ route('social.show', $meal) }}" class="btn btn-sm btn-outline-light">
                                            Voir détails
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="text-center mt-3">
                        <a href="{{ route('social.feed') }}?filter=meal" class="btn btn-outline-light">
                            <i class="fas fa-utensils me-2"></i>Voir tous mes repas
                        </a>
                    </div>
                @else
                    <div class="empty-meals-state">
                        <div class="text-center p-5">
                            <i class="fas fa-camera-retro mb-3" style="font-size: 3rem; color: #3498DB;"></i>
                            <h5>Aucun repas partagé</h5>
                            <p class="text-muted mb-4">Commencez à partager vos repas pour gagner des points dans vos ligues !</p>
                            <a href="{{ route('leagues.meal.upload') }}" class="btn btn-primary">
                                <i class="fas fa-camera me-2"></i>Partager mon premier repas
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.league-position-indicator {
    background-color: rgba(255, 255, 255, 0.05);
    padding: 10px 15px;
    border-radius: 10px;
}

/* Styles pour les repas dans les ligues */
.meal-image {
    height: 200px;
    object-fit: cover;
    width: 100%;
}

.card-img-container {
    position: relative;
    overflow: hidden;
}

.meal-score-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    background: rgba(0, 0, 0, 0.8);
    color: white;
    padding: 0.3rem 0.6rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: bold;
    border: 2px solid #3498DB;
}

.meal-score-badge.pending {
    border-color: #6c757d;
    background: rgba(108, 117, 125, 0.8);
}

.score-breakdown {
    display: flex;
    gap: 0.5rem;
    font-size: 0.8rem;
}

.score-mini {
    display: flex;
    align-items: center;
    gap: 0.2rem;
    background: rgba(255, 255, 255, 0.05);
    padding: 0.2rem 0.4rem;
    border-radius: 8px;
}

.empty-meals-state {
    background: rgba(255, 255, 255, 0.03);
    border-radius: 16px;
    border: 1px solid rgba(255, 255, 255, 0.1);
}
</style>
@endsection 