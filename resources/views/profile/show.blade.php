@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            @if(session('success'))
            <div class="alert alert-info mb-4">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            </div>
            @endif
            
            <div class="card mb-5 profile-hero">
                <div class="card-body p-lg-5">
                    <div class="row align-items-center">
                        <div class="col-md-3 text-center mb-4 mb-md-0">
                            <div class="avatar-container">
                                <div class="avatar-wrapper">
                                    @if($user->avatar)
                                        <img src="{{ $user->avatar }}" alt="Avatar" class="avatar-large">
                                    @else
                                        <div class="avatar-placeholder">
                                            <i class="fas fa-user"></i>
                                        </div>
                                    @endif
                                    <div class="level-badge">{{ $user->level_title }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h1 class="profile-name mb-0">{{ $user->name }}</h1>
                                    <p class="text-secondary mb-2">
                                        @if($user->username)
                                            <span class="username">@{{ $user->username }}</span>
                                        @else
                                            <span class="text-muted fst-italic">Aucun nom d'utilisateur défini</span>
                                        @endif
                                    </p>
                                </div>
                                <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-edit-profile">
                                    <i class="fas fa-pencil-alt me-2"></i> Modifier
                                </a>
                            </div>
                            
                            <div class="bio mb-4">
                                @if($user->bio)
                                    <p>{{ $user->bio }}</p>
                                @else
                                    <p class="text-muted fst-italic">Aucune bio définie</p>
                                @endif
                            </div>
                            
                            <div class="user-stats">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="stat-item">
                                            <div class="stat-icon">
                                                <i class="fas fa-star"></i>
                                            </div>
                                            <div class="stat-text">
                                                <h3>{{ $user->points ?? 0 }}</h3>
                                                <p>Points</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="stat-item">
                                            <div class="stat-icon">
                                                <i class="fas fa-share-alt"></i>
                                            </div>
                                            <div class="stat-text">
                                                <h3>{{ $user->posts()->count() }}</h3>
                                                <p>Partages</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="stat-item">
                                            <div class="stat-icon">
                                                <i class="fas fa-medal"></i>
                                            </div>
                                            <div class="stat-text">
                                                <h3>{{ $user->badges()->count() }}</h3>
                                                <p>Badges</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-8">
                    <div class="card mb-4 level-progress-card">
                        <div class="card-body p-4">
                            <h2 class="card-title with-icon">
                                <i class="fas fa-trophy"></i> Progression
                            </h2>
                            
                            <div class="level-info mb-3">
                                <div class="d-flex justify-content-between">
                                    <h3 class="level-title">{{ $user->level_title }}</h3>
                                    @if($user->next_level_points)
                                        <p class="level-target">
                                            <span class="current-points">{{ $user->points ?? 0 }}</span> / 
                                            <span class="target-points">{{ $user->next_level_points }}</span> points
                                        </p>
                                    @else
                                        <p class="level-max">Niveau maximum atteint !</p>
                                    @endif
                                </div>
                                
                                <div class="progress progress-lg">
                                    <div class="progress-bar" role="progressbar" style="width: {{ $user->level_progress }}%;" 
                                        aria-valuenow="{{ $user->level_progress }}" aria-valuemin="0" aria-valuemax="100">
                                        {{ round($user->level_progress) }}%
                                    </div>
                                </div>
                                
                                <div class="level-description mt-3">
                                    @if($user->level_title == 'Débutant')
                                        <p>Vous débutez votre aventure. Partagez des produits et complétez votre profil pour gagner des points.</p>
                                    @elseif($user->level_title == 'Éclaireur')
                                        <p>Vous êtes un contributeur régulier. Continuez à partager pour atteindre le niveau Expert !</p>
                                    @elseif($user->level_title == 'Expert')
                                        <p>Vous êtes un membre important de la communauté. Encore un effort pour devenir Maître !</p>
                                    @elseif($user->level_title == 'Maître')
                                        <p>Vous êtes un maître de ZYMA ! Continuez à partager votre expertise avec la communauté.</p>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="level-milestones">
                                <h4>Prochains paliers</h4>
                                <div class="milestones-list">
                                    <div class="milestone @if($user->points >= 101) achieved @endif">
                                        <div class="milestone-marker">
                                            <i class="fas @if($user->points >= 101) fa-check-circle @else fa-circle @endif"></i>
                                        </div>
                                        <div class="milestone-content">
                                            <h5>Éclaireur</h5>
                                            <p>101 points - Débloquez de nouvelles fonctionnalités</p>
                                        </div>
                                    </div>
                                    
                                    <div class="milestone @if($user->points >= 501) achieved @endif">
                                        <div class="milestone-marker">
                                            <i class="fas @if($user->points >= 501) fa-check-circle @else fa-circle @endif"></i>
                                        </div>
                                        <div class="milestone-content">
                                            <h5>Expert</h5>
                                            <p>501 points - Débloquez des fonctionnalités avancées</p>
                                        </div>
                                    </div>
                                    
                                    <div class="milestone @if($user->points >= 2001) achieved @endif">
                                        <div class="milestone-marker">
                                            <i class="fas @if($user->points >= 2001) fa-check-circle @else fa-circle @endif"></i>
                                        </div>
                                        <div class="milestone-content">
                                            <h5>Maître</h5>
                                            <p>2001 points - Statut ultime de ZYMA</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card mb-4 badges-card">
                        <div class="card-body p-4">
                            <h2 class="card-title with-icon">
                                <i class="fas fa-medal"></i> Badges
                            </h2>
                            
                            <div class="badges-grid">
                                <div class="badge-item @if($user->hasBadge('welcome')) earned @endif" data-bs-toggle="tooltip" title="Premier jour - Inscription">
                                    <i class="fas fa-user-plus"></i>
                                </div>
                                
                                <div class="badge-item @if($user->hasBadge('profile_complete')) earned @endif" data-bs-toggle="tooltip" title="Profil complet">
                                    <i class="fas fa-id-card"></i>
                                </div>
                                
                                <div class="badge-item @if($user->hasBadge('first_share')) earned @endif" data-bs-toggle="tooltip" title="Premier partage">
                                    <i class="fas fa-share-alt"></i>
                                </div>
                                
                                <div class="badge-item @if($user->hasBadge('five_shares')) earned @endif" data-bs-toggle="tooltip" title="5 partages">
                                    <i class="fas fa-share-square"></i>
                                </div>
                                
                                <div class="badge-item @if($user->hasBadge('twenty_shares')) earned @endif" data-bs-toggle="tooltip" title="20 partages">
                                    <i class="fas fa-award"></i>
                                </div>
                                
                                <div class="badge-item @if($user->hasBadge('first_comment')) earned @endif" data-bs-toggle="tooltip" title="Premier commentaire">
                                    <i class="fas fa-comment"></i>
                                </div>
                            </div>
                            
                            <div class="text-center mt-4">
                                <a href="#" class="btn btn-sm btn-view-all-badges">
                                    Voir tous les badges <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card recent-activity-card">
                        <div class="card-body p-4">
                            <h2 class="card-title with-icon">
                                <i class="fas fa-history"></i> Activité récente
                            </h2>
                            
                            <div class="activity-timeline">
                                @foreach($user->pointTransactions()->latest()->take(5)->get() as $transaction)
                                <div class="activity-item">
                                    <div class="activity-icon">
                                        @if(strpos($transaction->action_type, 'share') !== false)
                                            <i class="fas fa-share-alt"></i>
                                        @elseif(strpos($transaction->action_type, 'comment') !== false)
                                            <i class="fas fa-comment"></i>
                                        @elseif(strpos($transaction->action_type, 'profile') !== false)
                                            <i class="fas fa-user-edit"></i>
                                        @elseif(strpos($transaction->action_type, 'login') !== false)
                                            <i class="fas fa-sign-in-alt"></i>
                                        @else
                                            <i class="fas fa-star"></i>
                                        @endif
                                    </div>
                                    <div class="activity-content">
                                        <p class="activity-text">{{ $transaction->description }}</p>
                                        <p class="activity-points">+{{ $transaction->points }} points</p>
                                        <p class="activity-time">{{ $transaction->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                @endforeach
                                
                                @if($user->pointTransactions()->count() == 0)
                                    <div class="text-center text-muted py-4">
                                        <i class="fas fa-history mb-2" style="font-size: 2rem;"></i>
                                        <p>Aucune activité récente</p>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="text-center mt-3">
                                <a href="{{ route('profile.points') }}" class="btn btn-sm btn-view-all-activity">
                                    Voir toute l'activité <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* DESIGN SYSTÈME DE NIVEAU ET GAMIFICATION */
.profile-hero {
    border: none;
    border-radius: 24px;
    overflow: hidden;
    background: linear-gradient(135deg, var(--bg-tertiary) 0%, var(--bg-secondary) 100%);
    position: relative;
    z-index: 1;
}

.profile-hero:before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 100%;
    background: url('https://i.imgur.com/JXaIwsM.png') no-repeat center center;
    background-size: cover;
    opacity: 0.05;
    z-index: -1;
}

.avatar-container {
    position: relative;
    display: inline-block;
}

.avatar-wrapper {
    position: relative;
    display: inline-block;
}

.avatar-large {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid var(--accent-primary);
    box-shadow: 0 0 30px rgba(0, 209, 178, 0.3);
}

.avatar-placeholder {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    background: var(--accent-gradient);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 4rem;
    color: var(--bg-primary);
}

.level-badge {
    position: absolute;
    bottom: 0;
    right: 0;
    background: var(--accent-gradient);
    color: var(--bg-primary);
    font-weight: bold;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.9rem;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    transform: translateY(25%);
}

.profile-name {
    font-size: 2.5rem;
    font-weight: 800;
    letter-spacing: -1px;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
}

.username {
    font-size: 1.2rem;
    color: var(--accent-primary);
    font-weight: 600;
}

.btn-edit-profile {
    background: rgba(255, 255, 255, 0.1);
    color: var(--text-primary);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 12px;
    transition: all 0.3s ease;
}

.btn-edit-profile:hover {
    background: var(--accent-primary);
    color: var(--bg-primary);
    transform: translateY(-2px);
}

.bio {
    font-size: 1.1rem;
    line-height: 1.6;
    max-height: 4.8em;
    overflow: hidden;
    position: relative;
}

.user-stats {
    background: rgba(0, 0, 0, 0.2);
    border-radius: 16px;
    padding: 1.5rem;
    margin-top: 1rem;
}

.stat-item {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    text-align: center;
    transition: all 0.3s ease;
    padding: 1rem;
    border-radius: 12px;
}

.stat-item:hover {
    background: rgba(255, 255, 255, 0.05);
    transform: translateY(-3px);
}

.stat-icon {
    font-size: 2rem;
    color: var(--accent-primary);
    margin-bottom: 0.5rem;
}

.stat-text h3 {
    font-size: 2rem;
    font-weight: 800;
    margin-bottom: 0;
    background: var(--accent-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.stat-text p {
    margin-bottom: 0;
    color: var(--text-secondary);
    font-weight: 600;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.card-title.with-icon {
    display: flex;
    align-items: center;
    font-size: 1.4rem;
    margin-bottom: 1.5rem;
    font-weight: 700;
    color: var(--text-primary);
}

.card-title.with-icon i {
    color: var(--accent-primary);
    margin-right: 0.8rem;
    font-size: 1.2rem;
}

.level-progress-card, .badges-card, .recent-activity-card {
    border: none;
    border-radius: 24px;
    overflow: hidden;
}

.level-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--accent-primary);
    margin-bottom: 0;
}

.level-target, .level-max {
    font-weight: 600;
    color: var(--text-secondary);
    margin-bottom: 0;
}

.current-points {
    color: var(--accent-primary);
    font-weight: 700;
}

.progress-lg {
    height: 1.5rem;
    border-radius: 1rem;
    background: rgba(255, 255, 255, 0.1);
    margin: 1rem 0;
}

.progress-bar {
    border-radius: 1rem;
    background: var(--accent-gradient);
    font-weight: 600;
    color: var(--bg-primary);
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
    box-shadow: 0 0 10px rgba(0, 209, 178, 0.3);
    position: relative;
    overflow: hidden;
}

.progress-bar:before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg, rgba(255,255,255,0.2) 25%, transparent 25%, transparent 50%, rgba(255,255,255,0.2) 50%, rgba(255,255,255,0.2) 75%, transparent 75%, transparent);
    background-size: 30px 30px;
    animation: progress-animation 2s linear infinite;
    opacity: 0.5;
}

@keyframes progress-animation {
    0% { background-position: 0 0; }
    100% { background-position: 30px 0; }
}

.level-description {
    background: rgba(0, 0, 0, 0.2);
    padding: 1rem;
    border-radius: 12px;
    border-left: 4px solid var(--accent-primary);
}

.level-description p {
    margin-bottom: 0;
    font-size: 0.95rem;
}

.level-milestones {
    margin-top: 2rem;
}

.level-milestones h4 {
    font-size: 1.2rem;
    margin-bottom: 1rem;
    color: var(--text-primary);
    font-weight: 600;
}

.milestones-list {
    position: relative;
}

.milestones-list:before {
    content: '';
    position: absolute;
    top: 0;
    bottom: 0;
    left: 15px;
    width: 2px;
    background: rgba(255, 255, 255, 0.1);
}

.milestone {
    display: flex;
    align-items: flex-start;
    margin-bottom: 1.5rem;
    position: relative;
}

.milestone-marker {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: var(--bg-tertiary);
    border: 2px solid rgba(255, 255, 255, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    z-index: 1;
    color: var(--text-secondary);
}

.milestone.achieved .milestone-marker {
    background: var(--accent-primary);
    border-color: var(--accent-primary);
    color: var(--bg-primary);
    box-shadow: 0 0 15px rgba(0, 209, 178, 0.4);
}

.milestone-content {
    flex: 1;
}

.milestone-content h5 {
    font-size: 1.1rem;
    margin-bottom: 0.2rem;
    color: var(--text-primary);
    font-weight: 600;
}

.milestone-content p {
    font-size: 0.9rem;
    color: var(--text-secondary);
    margin-bottom: 0;
}

.milestone.achieved .milestone-content h5 {
    color: var(--accent-primary);
}

.badges-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.2rem;
    margin: 1rem 0;
}

.badge-item {
    width: 70px;
    height: 70px;
    margin: 0 auto;
    cursor: pointer;
    transition: all 0.3s ease;
}

.badge-item:hover {
    transform: translateY(-5px) rotate(5deg);
}

.badge-item.earned {
    animation: badge-glow 2s ease-in-out infinite alternate;
}

@keyframes badge-glow {
    from { box-shadow: 0 0 10px rgba(0, 209, 178, 0.3); }
    to { box-shadow: 0 0 20px rgba(0, 209, 178, 0.7); }
}

.btn-view-all-badges, .btn-view-all-activity {
    background: rgba(255, 255, 255, 0.05);
    color: var(--text-primary);
    border-radius: 12px;
    border: 1px solid rgba(255, 255, 255, 0.1);
    transition: all 0.3s ease;
    padding: 0.5rem 1rem;
}

.btn-view-all-badges:hover, .btn-view-all-activity:hover {
    background: var(--accent-primary);
    color: var(--bg-primary);
    transform: translateY(-2px);
}

.activity-timeline {
    margin: 1rem 0;
}

.activity-item {
    display: flex;
    align-items: flex-start;
    padding: 1rem;
    border-radius: 12px;
    background: rgba(255, 255, 255, 0.02);
    margin-bottom: 1rem;
    transition: all 0.3s ease;
}

.activity-item:hover {
    background: rgba(255, 255, 255, 0.05);
    transform: translateX(5px);
}

.activity-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: var(--accent-primary);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    color: var(--bg-primary);
    flex-shrink: 0;
}

.activity-content {
    flex: 1;
}

.activity-text {
    margin-bottom: 0.2rem;
    font-weight: 500;
}

.activity-points {
    margin-bottom: 0.2rem;
    font-weight: 700;
    color: var(--accent-primary);
}

.activity-time {
    margin-bottom: 0;
    font-size: 0.8rem;
    color: var(--text-secondary);
}

/* Animation de l'intro */
.profile-hero, .level-progress-card, .badges-card, .recent-activity-card {
    opacity: 0;
    transform: translateY(30px);
    animation: fadeInUp 0.8s forwards;
}

.level-progress-card {
    animation-delay: 0.2s;
}

.badges-card {
    animation-delay: 0.4s;
}

.recent-activity-card {
    animation-delay: 0.6s;
}

@keyframes fadeInUp {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialisation des tooltips Bootstrap
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
});
</script>
@endsection 