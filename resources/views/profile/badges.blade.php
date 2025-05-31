@extends('layouts.app')

@section('content')
<div class="profile-container">
    <div class="container">
        <!-- En-tête avec barre de navigation -->
        <div class="header-section mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <a href="{{ route('profile.show') }}" class="btn-back me-3">
                        <i class="fas fa-arrow-left me-2"></i> Retour au profil
                    </a>
                    <h1 class="page-title mb-0">Mes badges</h1>
                </div>
                
                <div class="badges-count">
                    <span class="badge-counter">{{ $earnedBadges->count() }}</span>
                    <span class="badge-total">/ {{ $earnedBadges->count() + $availableBadges->count() }}</span>
                </div>
            </div>
        </div>
        
        <!-- Texte d'introduction -->
        <div class="card intro-card mb-4">
            <div class="card-body">
                <p class="mb-0">Participez à la communauté ZYMA pour obtenir des badges!</p>
            </div>
        </div>

        <!-- Badges à débloquer -->
        <div class="badges-section mb-4">
            <div class="section-header mb-3">
                <h2 class="section-title">Badges à débloquer</h2>
            </div>
            
            @if($availableBadges->count() > 0)
                <div class="row g-4">
                    @foreach($availableBadges as $badge)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="badge-card locked">
                                <div class="badge-header {{ $badge->rarity_class }}">
                                    <div class="badge-icon-container">
                                        <i class="fas fa-{{ $badge->icon }}"></i>
                                    </div>
                                    <div class="badge-points">+{{ $badge->points }} points</div>
                                </div>
                                <div class="badge-content">
                                    <div class="badge-info">
                                        <h3 class="badge-title">{{ $badge->name }}</h3>
                                    </div>
                                    <div class="badge-status locked">
                                        <i class="fas fa-lock me-2"></i> À débloquer
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state success">
                    <div class="empty-icon">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <h3>Félicitations !</h3>
                    <p>Vous avez débloqué tous les badges disponibles dans la communauté ZYMA.</p>
                </div>
            @endif
        </div>
        
        <!-- Badges obtenus -->
        <div class="badges-section">
            <div class="section-header mb-3">
                <h2 class="section-title">Badges obtenus</h2>
            </div>
            
            @if($earnedBadges->count() > 0)
                <div class="row g-4">
                    @foreach($earnedBadges as $badge)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="badge-card earned">
                                <div class="badge-header {{ $badge->rarity_class }}">
                                    <div class="badge-icon-container">
                                        <i class="fas fa-{{ $badge->icon }}"></i>
                                    </div>
                                    <div class="badge-points">+{{ $badge->points }} points</div>
                                </div>
                                <div class="badge-content">
                                    <div class="badge-info">
                                        <h3 class="badge-title">{{ $badge->name }}</h3>
                                    </div>
                                    <div class="badge-status earned">
                                        <i class="fas fa-check-circle me-2"></i> Obtenu
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-medal"></i>
                    </div>
                    <h3>Aucun badge obtenu</h3>
                    <p>Participez activement à la communauté ZYMA pour débloquer vos premiers badges !</p>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
/* Variables et styles de base */
:root {
    --primary-color: #E67E22;
    --bg-dark: #121212;
    --bg-card: #1A1A1A;
    --text-primary: #FFFFFF;
    --text-secondary: #CCCCCC;
    --text-muted: #999999;
    --border-color: rgba(255, 255, 255, 0.1);
    --success-color: #4CAF50;
    --common-color: #78909C;
    --rare-color: #5C6BC0;
    --epic-color: #AB47BC;
    --legendary-color: #FFC107;
}

/* Styles généraux de la page */
.profile-container {
    background-color: var(--bg-dark);
    color: var(--text-primary);
    padding: 2rem 0;
    min-height: calc(100vh - 70px);
}

/* En-tête de page */
.header-section {
    margin-bottom: 2rem;
}

.page-title {
    font-size: 2rem;
    font-weight: 700;
    color: var(--primary-color);
}

.btn-back {
    background-color: rgba(230, 126, 34, 0.1);
    color: var(--primary-color);
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-back:hover {
    background-color: var(--primary-color);
    color: var(--text-primary);
}

.badges-count {
    background-color: var(--bg-card);
    padding: 0.5rem 1rem;
    border-radius: 10px;
    border: 1px solid var(--border-color);
}

.badge-counter {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--primary-color);
}

.badge-total {
    font-size: 1.2rem;
    color: var(--text-muted);
}

/* Carte d'introduction */
.intro-card {
    background-color: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    margin-bottom: 2rem;
}

/* Sections badges */
.badges-section {
    margin-bottom: 2.5rem;
}

.section-header {
    border-bottom: 1px solid var(--border-color);
    padding-bottom: 0.75rem;
    margin-bottom: 1.5rem;
}

.section-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--text-primary);
}

/* Cartes de badges */
.badge-card {
    background-color: var(--bg-card);
    border-radius: 12px;
    overflow: hidden;
    height: 100%;
    border: 1px solid var(--border-color);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    transition: all 0.3s ease;
}

.badge-card.earned {
    border-color: rgba(230, 126, 34, 0.3);
}

.badge-card.locked {
    opacity: 0.7;
}

.badge-header {
    padding: 1.5rem;
    text-align: center;
    position: relative;
}

.badge-header.common {
    background: linear-gradient(135deg, #546E7A, #78909C);
}

.badge-header.rare {
    background: linear-gradient(135deg, #3949AB, #5C6BC0);
}

.badge-header.epic {
    background: linear-gradient(135deg, #8E24AA, #AB47BC);
}

.badge-header.legendary {
    background: linear-gradient(135deg, #FFA000, #FFC107);
}

.badge-icon-container {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background-color: rgba(255, 255, 255, 0.2);
    display: flex;
    justify-content: center;
    align-items: center;
    margin: 0 auto 1rem;
    font-size: 1.5rem;
    color: white;
}

.badge-points {
    background-color: rgba(0, 0, 0, 0.3);
    padding: 0.3rem 0.8rem;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 600;
    display: inline-block;
    color: white;
}

.badge-content {
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    height: calc(100% - 150px);
}

.badge-info {
    margin-bottom: 1rem;
}

.badge-title {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 1rem;
    color: var(--text-primary);
    text-align: center;
}

.badge-description {
    color: var(--text-muted);
    font-size: 0.9rem;
    background-color: rgba(0, 0, 0, 0.15);
    padding: 0.8rem;
    border-radius: 6px;
    text-align: center;
    word-break: break-word;
}

.badge-status {
    padding: 0.6rem;
    border-radius: 8px;
    font-weight: 500;
    text-align: center;
    margin-top: 1rem;
}

.badge-status.earned {
    background-color: rgba(76, 175, 80, 0.1);
    color: var(--success-color);
    border: 1px solid rgba(76, 175, 80, 0.2);
}

.badge-status.locked {
    background-color: rgba(255, 255, 255, 0.05);
    color: var(--text-muted);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

/* États vides */
.empty-state {
    background-color: var(--bg-card);
    border-radius: 12px;
    padding: 2rem;
    text-align: center;
    border: 1px solid var(--border-color);
}

.empty-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
    color: var(--text-muted);
}

.empty-state.success .empty-icon {
    color: var(--primary-color);
}

.empty-state h3 {
    font-size: 1.3rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: var(--text-primary);
}

.empty-state p {
    color: var(--text-secondary);
    font-size: 1rem;
}

/* Responsive */
@media (max-width: 768px) {
    .header-section .d-flex {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .badges-count {
        margin-top: 0.5rem;
    }
}
</style>
@endsection 