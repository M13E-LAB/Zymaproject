@extends('layouts.app')

@section('content')
<div class="container dashboard-container">
    <div class="row">
        <div class="col-12 mb-4">
            <div class="welcome-banner">
                <h2>Bienvenue sur ZYMA, {{ Auth::user()->name }}</h2>
                <p>Votre assistant pour manger sainement sans vous ruiner.</p>
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="dashboard-card">
                <div class="dashboard-icon">
                    <i class="fas fa-star"></i>
                </div>
                <div class="dashboard-info">
                    <h3>Vos Points</h3>
                    <p class="counter">{{ Auth::user()->points ?? 0 }}</p>
                    <a href="{{ route('profile.points') }}" class="btn btn-sm btn-outline-light">Voir les détails</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="dashboard-card">
                <div class="dashboard-icon">
                    <i class="fas fa-share-alt"></i>
                </div>
                <div class="dashboard-info">
                    <h3>Vos Partages</h3>
                    <p class="counter">0</p>
                    <a href="{{ route('profile.posts') }}" class="btn btn-sm btn-outline-light">Gérer les partages</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="dashboard-card">
                <div class="dashboard-icon">
                    <i class="fas fa-medal"></i>
                </div>
                <div class="dashboard-info">
                    <h3>Badges</h3>
                    <p class="counter">0</p>
                    <a href="{{ route('profile.show') }}" class="btn btn-sm btn-outline-light">Voir le profil</a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row g-4">
        <div class="col-md-6">
            <div class="dashboard-section">
                <h3><i class="fas fa-tasks me-2"></i> Actions rapides</h3>
                <div class="action-buttons">
                    <a href="{{ route('products.search') }}" class="btn btn-primary">
                        <i class="fas fa-search me-2"></i>Rechercher un produit
                    </a>
                    <a href="{{ route('social.feed') }}" class="btn btn-outline-light">
                        <i class="fas fa-stream me-2"></i>Voir le feed social
                    </a>
                    <a href="{{ route('statistics') }}" class="btn btn-outline-light">
                        <i class="fas fa-chart-bar me-2"></i>Consulter les statistiques
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="dashboard-section recent-activity">
                <h3><i class="fas fa-history me-2"></i> Activité récente</h3>
                <div class="activity-empty">
                    <i class="fas fa-inbox"></i>
                    <p>Aucune activité récente pour le moment</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .dashboard-container {
        padding: 2rem 0;
        animation: fadeIn 0.5s ease-out;
    }
    
    .welcome-banner {
        background: rgba(255, 255, 255, 0.03);
        border-radius: 24px;
        padding: 2rem;
        margin-bottom: 2rem;
        border: 1px solid rgba(255, 255, 255, 0.1);
        text-align: center;
    }
    
    .welcome-banner h2 {
        color: var(--text-primary);
        font-weight: 700;
        margin-bottom: 1rem;
    }
    
    .welcome-banner p {
        color: var(--text-secondary);
        font-size: 1.1rem;
    }
    
    .dashboard-card {
        background: rgba(255, 255, 255, 0.03);
        border-radius: 24px;
        padding: 1.5rem;
        height: 100%;
        border: 1px solid rgba(255, 255, 255, 0.1);
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
    }
    
    .dashboard-card:hover {
        transform: translateY(-5px);
        border-color: var(--accent-primary);
        background: rgba(255, 255, 255, 0.05);
    }
    
    .dashboard-icon {
        width: 60px;
        height: 60px;
        background: var(--accent-gradient);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
    }
    
    .dashboard-icon i {
        font-size: 1.5rem;
        color: var(--bg-primary);
    }
    
    .dashboard-info {
        flex: 1;
    }
    
    .dashboard-info h3 {
        color: var(--text-primary);
        font-size: 1.1rem;
        margin-bottom: 0.3rem;
    }
    
    .counter {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        background: var(--accent-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    
    .dashboard-section {
        background: rgba(255, 255, 255, 0.03);
        border-radius: 24px;
        padding: 1.5rem;
        height: 100%;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .dashboard-section h3 {
        color: var(--text-primary);
        font-size: 1.2rem;
        margin-bottom: 1.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .action-buttons {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    
    .activity-empty {
        text-align: center;
        padding: 2rem 0;
        color: var(--text-secondary);
    }
    
    .activity-empty i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.6;
    }
</style>
@endsection
