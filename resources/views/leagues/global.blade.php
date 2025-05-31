@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <!-- En-tête de la page -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="section-title mb-0">
                    <i class="fas fa-trophy me-2 text-warning"></i>Classement global
                </h1>
                <a href="{{ route('leagues.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Retour aux ligues
                </a>
            </div>

            <!-- Onglets pour les types de classements -->
            <ul class="nav nav-tabs mb-4" id="leaderboardTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="points-tab" data-bs-toggle="tab" data-bs-target="#points" type="button" role="tab" aria-controls="points" aria-selected="true">
                        <i class="fas fa-star me-1"></i> Par points
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="meals-tab" data-bs-toggle="tab" data-bs-target="#meals" type="button" role="tab" aria-controls="meals" aria-selected="false">
                        <i class="fas fa-utensils me-1"></i> Par qualité des repas
                    </button>
                </li>
            </ul>

            <!-- Contenu des onglets -->
            <div class="tab-content" id="leaderboardTabContent">
                <!-- Classement par points -->
                <div class="tab-pane fade show active" id="points" role="tabpanel" aria-labelledby="points-tab">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-transparent py-3">
                            <h5 class="mb-0 text-primary">
                                <i class="fas fa-medal me-2"></i> Top contributeurs
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="px-4">Position</th>
                                            <th>Utilisateur</th>
                                            <th>Points</th>
                                            <th>Niveau</th>
                                            <th class="text-center">Badge</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($topUsers as $index => $user)
                                            <tr @if($user->id === auth()->id()) class="table-primary" @endif>
                                                <td class="px-4 position-relative">
                                                    <span class="position-indicator @if($index < 3) top-3 @endif">{{ $index + 1 }}</span>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @if($user->avatar)
                                                            <img src="{{ $user->avatar }}" alt="Avatar" class="avatar-small me-2 rounded-circle">
                                                        @else
                                                            <i class="fas fa-user-circle me-2" style="font-size: 1.5rem;"></i>
                                                        @endif
                                                        <div>
                                                            <div class="fw-semibold">{{ $user->name }}</div>
                                                            <small class="text-muted">{{ '@' . ($user->username ?? 'utilisateur') }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="fw-bold">{{ number_format($user->points) }}</td>
                                                <td>
                                                    <span class="badge 
                                                        @if($user->level_title === 'Maître') bg-danger
                                                        @elseif($user->level_title === 'Expert') bg-warning text-dark
                                                        @elseif($user->level_title === 'Éclaireur') bg-info text-dark
                                                        @else bg-secondary
                                                        @endif
                                                        p-2">
                                                        {{ $user->level_title }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    @if($index === 0)
                                                        <i class="fas fa-crown text-warning" style="font-size: 1.5rem;" title="Meilleur contributeur"></i>
                                                    @elseif($user->points > 1000)
                                                        <i class="fas fa-award text-info" style="font-size: 1.2rem;" title="Contributeur exceptionnel"></i>
                                                    @elseif($user->points > 500)
                                                        <i class="fas fa-medal text-secondary" style="font-size: 1.2rem;" title="Contributeur régulier"></i>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-4">Aucun utilisateur trouvé</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">Mise à jour en temps réel</small>
                                <div>{{ $topUsers->links() }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Classement par qualité des repas -->
                <div class="tab-pane fade" id="meals" role="tabpanel" aria-labelledby="meals-tab">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-transparent py-3">
                            <h5 class="mb-0 text-success">
                                <i class="fas fa-leaf me-2"></i> Meilleurs gourmets
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="px-4">Position</th>
                                            <th>Utilisateur</th>
                                            <th>Score moyen</th>
                                            <th>Repas partagés</th>
                                            <th class="text-center">Badge</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($topMealUsers as $index => $user)
                                            <tr @if($user->id === auth()->id()) class="table-primary" @endif>
                                                <td class="px-4 position-relative">
                                                    <span class="position-indicator @if($index < 3) top-3 @endif">{{ $index + 1 }}</span>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @if($user->avatar)
                                                            <img src="{{ $user->avatar }}" alt="Avatar" class="avatar-small me-2 rounded-circle">
                                                        @else
                                                            <i class="fas fa-user-circle me-2" style="font-size: 1.5rem;"></i>
                                                        @endif
                                                        <div>
                                                            <div class="fw-semibold">{{ $user->name }}</div>
                                                            <small class="text-muted">{{ '@' . ($user->username ?? 'utilisateur') }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="fw-bold">
                                                    {{ number_format($user->average_meal_score, 1) }} / 10
                                                    @if($user->average_meal_score >= 8.5)
                                                        <i class="fas fa-star text-warning ms-1" title="Score exceptionnel"></i>
                                                    @elseif($user->average_meal_score >= 7)
                                                        <i class="fas fa-thumbs-up text-success ms-1" title="Très bon score"></i>
                                                    @endif
                                                </td>
                                                <td>{{ $user->meal_count }}</td>
                                                <td class="text-center">
                                                    @if($index === 0)
                                                        <i class="fas fa-utensils text-success" style="font-size: 1.5rem;" title="Meilleur gourmet"></i>
                                                    @elseif($user->average_meal_score >= 8)
                                                        <i class="fas fa-seedling text-success" style="font-size: 1.2rem;" title="Alimentation exceptionnelle"></i>
                                                    @elseif($user->average_meal_score >= 7)
                                                        <i class="fas fa-carrot text-success" style="font-size: 1.2rem;" title="Alimentation équilibrée"></i>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-4">Aucun utilisateur avec des repas évalués</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">Basé sur les repas partagés et évalués</small>
                                <div>{{ $topMealUsers->links() }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Informations sur les classements -->
            <div class="card shadow-sm border-0 bg-dark text-white mt-4">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-info-circle me-2"></i>Comment fonctionnent les classements?
                    </h5>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <h6><i class="fas fa-star me-2 text-warning"></i>Classement par points</h6>
                            <ul class="small">
                                <li>Gagnez des points en partageant des repas, des prix et en participant activement</li>
                                <li>Progressez à travers les niveaux (Débutant, Éclaireur, Expert, Maître)</li>
                                <li>Les badges spéciaux sont accordés aux meilleurs contributeurs</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6><i class="fas fa-utensils me-2 text-success"></i>Classement par qualité des repas</h6>
                            <ul class="small">
                                <li>Basé sur les scores nutritionnels moyens de vos repas partagés</li>
                                <li>Partagez des repas équilibrés pour obtenir de meilleurs scores</li>
                                <li>Les repas sont évalués sur leur valeur nutritionnelle globale</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.position-indicator {
    background-color: #f0f0f0;
    color: #555;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 0.9rem;
}

.position-indicator.top-3 {
    background-color: #4F46E5;
    color: white;
}

.avatar-small {
    width: 40px;
    height: 40px;
    object-fit: cover;
    border-radius: 50%;
}

.nav-tabs .nav-link {
    color: #6c757d;
    border: none;
    padding: 0.5rem 1rem;
    margin-right: 0.5rem;
    font-weight: 500;
}

.nav-tabs .nav-link:hover {
    color: #4F46E5;
}

.nav-tabs .nav-link.active {
    color: #4F46E5;
    border-bottom: 2px solid #4F46E5;
    background-color: transparent;
}

.page-link {
    color: #4F46E5;
    background-color: #fff;
    border: 1px solid #dee2e6;
}

.page-item.active .page-link {
    background-color: #4F46E5;
    border-color: #4F46E5;
}

/* Adaptations pour le mode sombre si nécessaire */
@media (prefers-color-scheme: dark) {
    .table-light {
        background-color: rgba(255, 255, 255, 0.05);
        color: #ccc;
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(255, 255, 255, 0.05);
    }
    
    .position-indicator {
        background-color: #444;
        color: #ddd;
    }
    
    .card {
        background-color: #2d3748;
        border-color: #4a5568;
    }
    
    .nav-tabs .nav-link {
        color: #cbd5e0;
    }
    
    .nav-tabs .nav-link:hover,
    .nav-tabs .nav-link.active {
        color: #90cdf4;
    }
    
    .nav-tabs .nav-link.active {
        border-bottom-color: #90cdf4;
    }
    
    .table-primary {
        background-color: rgba(66, 153, 225, 0.2) !important;
    }
}
</style>
@endsection 