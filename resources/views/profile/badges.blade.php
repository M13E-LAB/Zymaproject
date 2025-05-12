@extends('layouts.app')

@section('content')
<div class="profile-container">
    <div class="container">
        <!-- En-tête avec navigation de retour -->
        <div class="back-navigation mb-4">
            <a href="{{ route('profile.show') }}" class="back-link">
                <i class="fas fa-arrow-left"></i> Retour au profil
            </a>
            <h1 class="section-title">Mes badges</h1>
        </div>
        
        <div class="badges-description mb-4">
            <div class="card profile-card">
                <div class="card-body">
                    <div class="badges-intro">
                        <div class="badges-icon">
                            <i class="fas fa-medal"></i>
                        </div>
                        <div class="badges-text">
                            <h2>Badges et récompenses</h2>
                            <p>Les badges sont des récompenses que vous obtenez pour votre participation dans la communauté <span class="zyma-text">ZYMA</span>. Complétez différentes actions pour tous les obtenir !</p>
                        </div>
                    </div>
                    
                    <div class="badges-progress">
                        <div class="progress-text">
                            <span>Progression</span>
                            <span>{{ $earnedBadges->count() }} / {{ $earnedBadges->count() + $availableBadges->count() }}</span>
                        </div>
                        <div class="progress badges-progress-bar">
                            <div class="progress-bar" role="progressbar" 
                                style="width: {{ ($earnedBadges->count() / ($earnedBadges->count() + $availableBadges->count())) * 100 }}%" 
                                aria-valuenow="{{ $earnedBadges->count() }}" 
                                aria-valuemin="0" 
                                aria-valuemax="{{ $earnedBadges->count() + $availableBadges->count() }}">
                            </div>
                        </div>
                    </div>
                    
                    <div class="badge-types mt-4">
                        <div class="badge-type">
                            <div class="badge-type-icon common">
                                <i class="fas fa-circle"></i>
                            </div>
                            <span>Commun</span>
                        </div>
                        <div class="badge-type">
                            <div class="badge-type-icon rare">
                                <i class="fas fa-circle"></i>
                            </div>
                            <span>Rare</span>
                        </div>
                        <div class="badge-type">
                            <div class="badge-type-icon epic">
                                <i class="fas fa-circle"></i>
                            </div>
                            <span>Épique</span>
                        </div>
                        <div class="badge-type">
                            <div class="badge-type-icon legendary">
                                <i class="fas fa-circle"></i>
                            </div>
                            <span>Légendaire</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Badges obtenus -->
        <div class="earned-badges mb-4">
            <h2 class="badges-section-title">Badges obtenus ({{ $earnedBadges->count() }})</h2>
            
            @if($earnedBadges->count() > 0)
                <div class="row">
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
                                    <h3 class="badge-title">{{ $badge->name }}</h3>
                                    <p class="badge-description">{{ $badge->description }}</p>
                                    <div class="badge-obtained">
                                        <i class="fas fa-check-circle"></i> Obtenu
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-badges-card">
                    <div class="empty-badges-icon">
                        <i class="fas fa-medal"></i>
                    </div>
                    <p>Vous n'avez pas encore obtenu de badges. Participez à la <span class="d-inline-flex align-items-center">communauté <img src="{{ asset('images/etchelast-logo.svg') }}" width="18" height="18" class="mx-1"> ZYMA</span> pour en gagner!</p>
                </div>
            @endif
        </div>
        
        <!-- Badges à débloquer -->
        <div class="unearned-badges">
            <h2 class="badges-section-title">Badges à débloquer ({{ $availableBadges->count() }})</h2>
            
            @if($availableBadges->count() > 0)
                <div class="row">
                    @foreach($availableBadges as $badge)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="badge-card unearned">
                                <div class="badge-header {{ $badge->rarity_class }}">
                                    <div class="badge-icon-container">
                                        <i class="fas fa-{{ $badge->icon }}"></i>
                                    </div>
                                    <div class="badge-points">+{{ $badge->points }} points</div>
                                </div>
                                <div class="badge-content">
                                    <h3 class="badge-title">{{ $badge->name }}</h3>
                                    <p class="badge-description">{{ $badge->description }}</p>
                                    <div class="badge-locked">
                                        <i class="fas fa-lock"></i> À débloquer
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-badges-card">
                    <div class="empty-badges-icon success">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <p>Félicitations ! Vous avez débloqué tous les badges disponibles.</p>
                </div>
            @endif
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
        margin-bottom: 0;
    }
    
    .back-navigation {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .back-link {
        color: #E67E22;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 500;
        transition: all 0.3s;
    }
    
    .back-link:hover {
        color: #F39C12;
    }
    
    /* Carte de profil */
    .profile-card {
        background-color: #111;
        border: 1px solid #222;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }
    
    .card-body {
        padding: 1.5rem;
    }
    
    /* Intro des badges */
    .badges-intro {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .badges-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #E67E22, #F39C12);
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 1.8rem;
        color: #fff;
        flex-shrink: 0;
    }
    
    .badges-text h2 {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }
    
    .badges-text p {
        margin-bottom: 0;
        color: #ccc;
        font-size: 0.95rem;
        line-height: 1.5;
    }
    
    .zyma-text {
        color: #E67E22;
        font-weight: 700;
    }
    
    /* Barre de progression */
    .badges-progress {
        background-color: #191919;
        padding: 1.5rem;
        border-radius: 10px;
    }
    
    .progress-text {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.8rem;
        font-size: 0.9rem;
        color: #ccc;
    }
    
    .badges-progress-bar {
        height: 10px;
        background-color: #333;
        border-radius: 5px;
        overflow: hidden;
    }
    
    .progress-bar {
        background-color: #E67E22;
        border-radius: 5px;
    }
    
    /* Types de badges */
    .badge-types {
        display: flex;
        justify-content: space-between;
    }
    
    .badge-type {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.85rem;
        color: #999;
    }
    
    .badge-type-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
    }
    
    .badge-type-icon.common {
        color: #607D8B;
    }
    
    .badge-type-icon.rare {
        color: #3F51B5;
    }
    
    .badge-type-icon.epic {
        color: #9C27B0;
    }
    
    .badge-type-icon.legendary {
        color: #FFC107;
    }
    
    /* Titres de section */
    .badges-section-title {
        font-size: 1.3rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        padding-bottom: 0.8rem;
        border-bottom: 1px solid #222;
    }
    
    /* Cartes de badge */
    .badge-card {
        background-color: #111;
        border-radius: 10px;
        overflow: hidden;
        height: 100%;
        border: 1px solid #222;
        transition: all 0.3s;
    }
    
    .badge-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
    }
    
    .badge-card.earned:hover {
        border-color: #E67E22;
    }
    
    .badge-header {
        padding: 1.5rem;
        text-align: center;
        position: relative;
        background-color: #191919;
    }
    
    .badge-header.common {
        background: linear-gradient(to bottom, #455A64, #607D8B);
    }
    
    .badge-header.rare {
        background: linear-gradient(to bottom, #303F9F, #3F51B5);
    }
    
    .badge-header.epic {
        background: linear-gradient(to bottom, #7B1FA2, #9C27B0);
    }
    
    .badge-header.legendary {
        background: linear-gradient(to bottom, #FFA000, #FFC107);
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
        color: #fff;
    }
    
    .badge-points {
        background-color: rgba(0, 0, 0, 0.2);
        padding: 0.3rem 0.8rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        display: inline-block;
        color: #fff;
    }
    
    .badge-content {
        padding: 1.5rem;
    }
    
    .badge-title {
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }
    
    .badge-description {
        color: #999;
        font-size: 0.9rem;
        margin-bottom: 1rem;
        line-height: 1.4;
    }
    
    .badge-obtained, .badge-locked {
        font-size: 0.85rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .badge-obtained {
        color: #4CAF50;
    }
    
    .badge-locked {
        color: #999;
    }
    
    /* État vide */
    .empty-badges-card {
        background-color: #111;
        border: 1px solid #222;
        border-radius: 10px;
        padding: 3rem 1.5rem;
        text-align: center;
        margin-bottom: 2rem;
    }
    
    .empty-badges-icon {
        font-size: 3rem;
        color: #444;
        margin-bottom: 1rem;
    }
    
    .empty-badges-icon.success {
        color: #E67E22;
    }
    
    .empty-badges-card p {
        color: #999;
        font-size: 1rem;
        max-width: 400px;
        margin: 0 auto;
    }
    
    /* Responsive */
    @media (max-width: 767px) {
        .badges-intro {
            flex-direction: column;
            text-align: center;
        }
        
        .badge-types {
            flex-wrap: wrap;
            gap: 1rem;
            justify-content: center;
        }
        
        .back-navigation {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }
    }
</style>
@endsection 