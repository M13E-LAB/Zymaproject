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
                        
                        <button type="button" class="btn btn-outline-light me-3" onclick="document.getElementById('comment-form').scrollIntoView();">
                            <i class="far fa-comment me-1"></i>
                            <span>{{ $post->comments_count }}</span> Commenter
                        </button>
                        
                        @if($post->user_id === auth()->id())
                            <!-- Boutons de gestion pour le propriétaire -->
                            <div class="dropdown">
                                <button class="btn btn-outline-light dropdown-toggle" type="button" id="postActions" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="postActions">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('social.edit', $post) }}">
                                            <i class="fas fa-edit me-2"></i> Modifier
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('social.destroy', $post) }}" method="POST" 
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette publication ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="fas fa-trash me-2"></i> Supprimer
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @if($post->post_type === 'meal')
        <!-- Section d'analyse IA du repas -->
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <h4 class="mb-4">
                            <i class="fas fa-robot me-2"></i>Analyse IA du repas
                        </h4>
                        
                        @if($post->mealScore)
                            <!-- Affichage du score IA -->
                            <div class="meal-score-display mb-4">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="score-item text-center">
                                            <div class="score-circle health">
                                                <span class="score-value">{{ $post->mealScore->health_score }}</span>
                                            </div>
                                            <div class="score-label">🫀 Santé</div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="score-item text-center">
                                            <div class="score-circle visual">
                                                <span class="score-value">{{ $post->mealScore->visual_score }}</span>
                                            </div>
                                            <div class="score-label">👁️ Visuel</div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="score-item text-center">
                                            <div class="score-circle diversity">
                                                <span class="score-value">{{ $post->mealScore->diversity_score }}</span>
                                            </div>
                                            <div class="score-label">🌱 Diversité</div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="score-item text-center">
                                            <div class="score-circle total">
                                                <span class="score-value total-score">{{ $post->mealScore->total_score }}</span>
                                            </div>
                                            <div class="score-label">🏆 Total</div>
                                        </div>
                                    </div>
                                </div>
                                
                                @if($post->mealScore->feedback)
                                    <div class="feedback-section mt-4">
                                        <h6><i class="fas fa-comment-dots me-1"></i> Analyse détaillée :</h6>
                                        <div class="ai-feedback">
                                            {!! nl2br(e($post->mealScore->feedback)) !!}
                                        </div>
                                    </div>
                                @endif
                                
                                @if($post->mealScore->ai_analysis)
                                    <div class="ai-detailed-analysis mt-4">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="analysis-card">
                                                    <h6><i class="fas fa-utensils me-1"></i> Aliments détectés :</h6>
                                                    <div class="detected-foods">
                                                        @foreach($post->mealScore->ai_analysis['detected_foods'] ?? [] as $food)
                                                            <span class="badge bg-secondary me-1 mb-1">{{ $food }}</span>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="analysis-card">
                                                    <h6><i class="fas fa-chart-pie me-1"></i> Estimation nutritionnelle :</h6>
                                                    <div class="nutrition-grid">
                                                        @if(isset($post->mealScore->ai_analysis['nutrition_estimation']))
                                                            <div class="nutrition-item">
                                                                <span class="nutrition-label">Calories:</span>
                                                                <span class="nutrition-value">{{ $post->mealScore->ai_analysis['nutrition_estimation']['calories'] ?? 'N/A' }} kcal</span>
                                                            </div>
                                                            <div class="nutrition-item">
                                                                <span class="nutrition-label">Protéines:</span>
                                                                <span class="nutrition-value">{{ $post->mealScore->ai_analysis['nutrition_estimation']['proteins'] ?? 'N/A' }}g</span>
                                                            </div>
                                                            <div class="nutrition-item">
                                                                <span class="nutrition-label">Glucides:</span>
                                                                <span class="nutrition-value">{{ $post->mealScore->ai_analysis['nutrition_estimation']['carbs'] ?? 'N/A' }}g</span>
                                                            </div>
                                                            <div class="nutrition-item">
                                                                <span class="nutrition-label">Lipides:</span>
                                                                <span class="nutrition-value">{{ $post->mealScore->ai_analysis['nutrition_estimation']['fats'] ?? 'N/A' }}g</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        @if(isset($post->mealScore->ai_analysis['improvement_suggestions']))
                                            <div class="improvement-suggestions mt-3">
                                                <h6><i class="fas fa-lightbulb me-1"></i> Suggestions d'amélioration :</h6>
                                                <ul class="suggestions-list">
                                                    @foreach($post->mealScore->ai_analysis['improvement_suggestions'] as $suggestion)
                                                        <li>{{ $suggestion }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                        
                                        <div class="ai-confidence mt-3">
                                            <small class="text-muted">
                                                <i class="fas fa-robot me-1"></i>
                                                Analyse réalisée par IA avec {{ $post->mealScore->ai_analysis['analysis_confidence'] ?? 85 }}% de confiance
                                            </small>
                                        </div>
                                    </div>
                                @endif
                                
                                <div class="ai-badge mt-3 text-center">
                                    <span class="badge bg-primary fs-6">
                                        <i class="fas fa-robot me-1"></i> Analysé automatiquement par notre IA
                                    </span>
                                    @if($post->mealScore->total_score >= 60)
                                        <span class="badge bg-success fs-6 ms-2">
                                            <i class="fas fa-trophy me-1"></i> Points gagnés pour les ligues !
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @else
                            <!-- Repas en attente d'analyse -->
                            <div class="text-center p-4">
                                <div class="spinner-border text-primary mb-3" role="status">
                                    <span class="visually-hidden">Analyse en cours...</span>
                                </div>
                                <h5>🤖 Analyse IA en cours...</h5>
                                <p class="text-muted">Notre intelligence artificielle analyse votre repas. Cela ne prend que quelques secondes !</p>
                                <small class="text-muted">L'analyse se base sur l'image et la description de votre repas pour évaluer la santé, la présentation et la diversité nutritionnelle.</small>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
    
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

<style>
/* Styles pour la notation des repas */
.meal-score-display {
    background: rgba(255, 255, 255, 0.03);
    border-radius: 16px;
    padding: 2rem;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.score-item {
    margin-bottom: 1rem;
}

.score-circle {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 0.5rem;
    font-weight: bold;
    font-size: 1.2rem;
    border: 3px solid;
    position: relative;
}

.score-circle.health {
    background: rgba(40, 167, 69, 0.1);
    border-color: #28a745;
    color: #28a745;
}

.score-circle.visual {
    background: rgba(23, 162, 184, 0.1);
    border-color: #17a2b8;
    color: #17a2b8;
}

.score-circle.diversity {
    background: rgba(255, 193, 7, 0.1);
    border-color: #ffc107;
    color: #ffc107;
}

.score-circle.total {
    background: rgba(52, 152, 219, 0.1);
    border-color: #3498DB;
    color: #3498DB;
    font-size: 1.5rem;
    width: 90px;
    height: 90px;
}

.score-label {
    font-weight: 600;
    color: var(--text-secondary);
    font-size: 0.9rem;
}

.score-input {
    background: rgba(255, 255, 255, 0.03);
    border-radius: 12px;
    padding: 1.5rem;
    border: 1px solid rgba(255, 255, 255, 0.1);
    margin-bottom: 1rem;
}

.form-range {
    background: transparent;
}

.form-range::-webkit-slider-track {
    background: rgba(255, 255, 255, 0.2);
    border-radius: 5px;
    height: 8px;
}

.form-range::-webkit-slider-thumb {
    background: #3498DB;
    border: none;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    cursor: pointer;
}

.form-range::-moz-range-track {
    background: rgba(255, 255, 255, 0.2);
    border-radius: 5px;
    height: 8px;
    border: none;
}

.form-range::-moz-range-thumb {
    background: #3498DB;
    border: none;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    cursor: pointer;
}

.total-preview {
    font-size: 1.1rem;
}

.feedback-section {
    background: rgba(255, 255, 255, 0.03);
    border-radius: 12px;
    padding: 1.5rem;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.ai-feedback {
    color: #ffffff;
    line-height: 1.6;
    font-size: 0.95rem;
}

.ai-detailed-analysis {
    background: rgba(0, 123, 255, 0.05);
    border-radius: 16px;
    padding: 1.5rem;
    border: 1px solid rgba(0, 123, 255, 0.2);
}

.analysis-card {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 12px;
    padding: 1rem;
    margin-bottom: 1rem;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.analysis-card h6 {
    color: #3498DB;
    margin-bottom: 0.8rem;
}

.detected-foods {
    display: flex;
    flex-wrap: wrap;
    gap: 0.3rem;
}

.nutrition-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.5rem;
}

.nutrition-item {
    display: flex;
    justify-content: space-between;
    padding: 0.3rem 0;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.nutrition-label {
    font-weight: 500;
    color: var(--text-secondary);
}

.nutrition-value {
    font-weight: bold;
    color: #3498DB;
}

.improvement-suggestions {
    background: rgba(255, 193, 7, 0.05);
    border-radius: 12px;
    padding: 1rem;
    border: 1px solid rgba(255, 193, 7, 0.2);
}

.suggestions-list {
    margin: 0;
    padding-left: 1.2rem;
}

.suggestions-list li {
    margin-bottom: 0.5rem;
    color: var(--text-primary);
}

.ai-confidence {
    text-align: center;
    opacity: 0.7;
}

.ai-badge {
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    padding-top: 1rem;
}

/* Animation pour les scores */
.score-circle {
    transition: all 0.3s ease;
}

.score-circle:hover {
    transform: scale(1.05);
}

/* Responsive */
@media (max-width: 768px) {
    .score-circle {
        width: 60px;
        height: 60px;
        font-size: 1rem;
    }
    
    .score-circle.total {
        width: 70px;
        height: 70px;
        font-size: 1.2rem;
    }
    
    .score-input {
        padding: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .nutrition-grid {
        grid-template-columns: 1fr;
    }
    
    .analysis-card {
        margin-bottom: 0.8rem;
    }
}
</style>

<script>
function updateScoreValue(type, value) {
    // Mettre à jour l'affichage de la valeur
    document.getElementById(type + '_value').textContent = value;
    
    // Mettre à jour la couleur du badge selon la valeur
    const badge = document.getElementById(type + '_value');
    badge.className = 'badge';
    
    if (value >= 80) {
        badge.className += ' bg-success';
    } else if (value >= 60) {
        badge.className += ' bg-warning';
    } else if (value >= 40) {
        badge.className += ' bg-info';
    } else {
        badge.className += ' bg-danger';
    }
    
    // Calculer et afficher le score total
    updateTotalScore();
}

function updateTotalScore() {
    const healthScore = parseInt(document.getElementById('health_score').value);
    const visualScore = parseInt(document.getElementById('visual_score').value);
    const diversityScore = parseInt(document.getElementById('diversity_score').value);
    
    // Calcul du score total (moyenne pondérée)
    const totalScore = Math.round((healthScore * 0.5 + visualScore * 0.3 + diversityScore * 0.2));
    
    document.getElementById('total_preview').textContent = totalScore;
    
    // Mettre à jour la couleur selon le score
    const preview = document.getElementById('total_preview');
    preview.className = '';
    
    if (totalScore >= 80) {
        preview.className = 'text-success';
    } else if (totalScore >= 60) {
        preview.className = 'text-warning';
    } else if (totalScore >= 40) {
        preview.className = 'text-info';
    } else {
        preview.className = 'text-danger';
    }
}

// Initialiser le score total au chargement
document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('health_score')) {
        updateTotalScore();
    }
});
</script>
@endsection 