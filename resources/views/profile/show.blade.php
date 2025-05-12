@extends('layouts.app')

@section('content')
<div class="profile-container">
    <div class="container">
        <!-- En-tête du profil -->
        <h1 class="section-title mb-4">Mon profil</h1>
        
        <div class="row">
            <div class="col-lg-4 mb-4">
                <!-- Carte principale du profil -->
                <div class="card profile-card">
                    <div class="card-body">
                        <div class="profile-header">
                            <div class="avatar-container">
                                @if($user->avatar)
                                    <img src="{{ $user->avatar }}" alt="Avatar" class="profile-avatar">
                                @else
                                    <div class="profile-avatar-placeholder">
                                        <i class="fas fa-user"></i>
                                    </div>
                                @endif
                                <div class="level-badge">{{ $user->level_title ?? 'Débutant' }}</div>
                            </div>
                            
                            <h2 class="profile-name">{{ $user->name }}</h2>
                            @if($user->username)
                                <p class="profile-username">@{{ $user->username }}</p>
                            @endif
                            
                            <div class="profile-stats">
                                <div class="stat-item">
                                    <span class="stat-value">{{ $user->posts_count ?? 0 }}</span>
                                    <span class="stat-label">Publications</span>
                                </div>
                                <div class="stat-item">
                                    <span class="stat-value">{{ $user->points ?? 0 }}</span>
                                    <span class="stat-label">Points</span>
                                </div>
                                <div class="stat-item">
                                    <span class="stat-value">{{ $user->badges_count ?? 0 }}</span>
                                    <span class="stat-label">Badges</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="profile-bio">
                            @if($user->bio)
                                <p>{{ $user->bio }}</p>
                            @else
                                <p class="text-muted">Aucune bio définie. <a href="{{ route('profile.edit') }}" class="edit-link">Ajouter une bio</a></p>
                            @endif
                        </div>
                        
                        <div class="profile-actions">
                            <a href="{{ route('profile.edit') }}" class="btn btn-edit">
                                <i class="fas fa-pencil-alt"></i> Modifier mon profil
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Carte des badges -->
                <div class="card profile-card mt-4">
                    <div class="card-header">
                        <h3 class="card-title">Mes badges</h3>
                        <a href="{{ route('profile.badges') }}" class="badge-link">Voir tous</a>
                    </div>
                    <div class="card-body">
                        <div class="badges-container">
                            @if(isset($earnedBadges) && count($earnedBadges) > 0)
                                @foreach($earnedBadges->take(3) as $badge)
                                    <div class="badge-item">
                                        <div class="badge-icon {{ $badge->rarity_class }}">
                                            <i class="fas fa-{{ $badge->icon }}"></i>
                                        </div>
                                        <div class="badge-info">
                                            <span class="badge-name">{{ $badge->name }}</span>
                                            <span class="badge-desc">{{ $badge->description }}</span>
                                        </div>
                                        <div class="badge-reward">
                                            <span class="points-reward">+{{ $badge->points }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-muted">Participez à la <span class="d-inline-flex align-items-center">communauté <img src="{{ asset('images/etchelast-logo.svg') }}" width="18" height="18" class="mx-1"> ZYMA</span> pour obtenir des badges!</p>
                            @endif
                            
                            @if(isset($availableBadges) && count($availableBadges) > 0)
                                <div class="mt-3">
                                    <h5 class="mb-2">Badges à débloquer</h5>
                                    <div class="available-badges">
                                        @foreach($availableBadges->take(2) as $badge)
                                            <div class="badge-item locked">
                                                <div class="badge-icon locked {{ $badge->rarity_class }}">
                                                    <i class="fas fa-{{ $badge->icon }}"></i>
                                                </div>
                                                <div class="badge-info">
                                                    <span class="badge-name">{{ $badge->name }}</span>
                                                    <span class="badge-desc">{{ $badge->description }}</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-8">
                <!-- Carte des statistiques de points -->
                <div class="card profile-card">
                    <div class="card-header">
                        <h3 class="card-title">Points actuels</h3>
                    </div>
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-5">
                                <div class="points-chart">
                                    <div class="points-circle">
                                        <div class="inner-circle">
                                            <span class="points-value">{{ $user->points ?? 0 }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="level-info">
                                    <h4 class="level-title">Niveau: {{ $user->level_title ?? 'Débutant' }}</h4>
                                    <div class="progress level-progress">
                                        <div class="progress-bar" role="progressbar" style="width: {{ $user->level_progress ?? 0 }}%" aria-valuenow="{{ $user->level_progress ?? 0 }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <p class="next-level">
                                        <span>Prochain niveau: {{ $user->next_level_title ?? 'Éclaireur' }}</span>
                                        <span class="points-needed">{{ $user->points_to_next_level ?? 100 }} points restants</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Historique des transactions de points -->
                <div class="card profile-card mt-4">
                    <div class="card-header">
                        <h3 class="card-title">Transactions de points</h3>
                    </div>
                    <div class="card-body">
                        <div class="transaction-tabs">
                            <div class="nav nav-tabs" id="transactionTabs" role="tablist">
                                <button class="nav-link active" id="partages-tab" data-bs-toggle="tab" data-bs-target="#partages" type="button" role="tab" aria-controls="partages" aria-selected="true">Partages</button>
                                <button class="nav-link" id="profil-tab" data-bs-toggle="tab" data-bs-target="#profil" type="button" role="tab" aria-controls="profil" aria-selected="false">Profil</button>
                                <button class="nav-link" id="commentaires-tab" data-bs-toggle="tab" data-bs-target="#commentaires" type="button" role="tab" aria-controls="commentaires" aria-selected="false">Commentaires</button>
                            </div>
                        </div>
                        
                        <div class="tab-content" id="transactionTabsContent">
                            <div class="tab-pane fade show active" id="partages" role="tabpanel" aria-labelledby="partages-tab">
                                <table class="table transaction-table">
                                    <thead>
                                        <tr>
                                            <th>ACTION</th>
                                            <th>DESCRIPTION</th>
                                            <th>POINTS</th>
                                            <th>DATE</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(isset($pointTransactions) && count($pointTransactions) > 0)
                                            @foreach($pointTransactions as $transaction)
                                                <tr>
                                                    <td>{{ $transaction->action }}</td>
                                                    <td>{{ $transaction->description }}</td>
                                                    <td class="points-cell {{ $transaction->amount > 0 ? 'positive' : 'negative' }}">
                                                        {{ $transaction->amount > 0 ? '+' : '' }}{{ $transaction->amount }}
                                                    </td>
                                                    <td>{{ $transaction->created_at->format('d/m/Y') }}</td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="4" class="text-center">
                                                    <div class="empty-state">
                                                        <div class="empty-icon">
                                                            <i class="fas fa-star"></i>
                                                        </div>
                                                        <p>Aucune transaction de points pour le moment.</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="tab-pane fade" id="profil" role="tabpanel" aria-labelledby="profil-tab">
                                <!-- Contenu pour l'onglet Profil -->
                                <div class="empty-state">
                                    <div class="empty-icon">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <p>Les transactions liées au profil s'afficheront ici.</p>
                                </div>
                            </div>
                            
                            <div class="tab-pane fade" id="commentaires" role="tabpanel" aria-labelledby="commentaires-tab">
                                <!-- Contenu pour l'onglet Commentaires -->
                                <div class="empty-state">
                                    <div class="empty-icon">
                                        <i class="fas fa-comment"></i>
                                    </div>
                                    <p>Les transactions liées aux commentaires s'afficheront ici.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Publications récentes -->
                <div class="card profile-card mt-4">
                    <div class="card-header">
                        <h3 class="card-title">Mes publications récentes</h3>
                        <a href="{{ route('profile.posts') }}" class="posts-link">Voir toutes</a>
                    </div>
                    <div class="card-body">
                        @if(isset($recentPosts) && count($recentPosts) > 0)
                            <div class="recent-posts">
                                @foreach($recentPosts as $post)
                                    <div class="post-item">
                                        <div class="post-image">
                                            @if($post->image)
                                                <img src="{{ $post->image }}" alt="Post image">
                                            @else
                                                <div class="post-image-placeholder">
                                                    <i class="fas fa-image"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="post-content">
                                            <h4 class="post-title">{{ $post->title }}</h4>
                                            <p class="post-excerpt">{{ Str::limit($post->content, 100) }}</p>
                                            <div class="post-meta">
                                                <span class="post-date">{{ $post->created_at->format('d/m/Y') }}</span>
                                                <span class="post-interactions">
                                                    <i class="fas fa-heart"></i> {{ $post->likes_count }}
                                                    <i class="fas fa-comment"></i> {{ $post->comments_count }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="empty-state">
                                <div class="empty-icon">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                                <p>Vous n'avez pas encore publié de contenu.</p>
                                <a href="{{ route('social.create') }}" class="btn btn-create-post">
                                    <i class="fas fa-plus"></i> Créer une publication
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
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
        margin-bottom: 1.5rem;
    }
    
    /* Cartes */
    .profile-card {
        background-color: #111;
        border: 1px solid #222;
        border-radius: 10px;
        overflow: hidden;
        margin-bottom: 1.5rem;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }
    
    .card-header {
        background-color: #191919;
        border-bottom: 1px solid #333;
        padding: 1rem 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .card-title {
        margin: 0;
        font-size: 1.2rem;
        font-weight: 600;
        color: #fff;
    }
    
    .card-body {
        padding: 1.5rem;
    }
    
    /* Avatar et informations de profil */
    .profile-header {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        margin-bottom: 1.5rem;
    }
    
    .avatar-container {
        position: relative;
        margin-bottom: 1rem;
    }
    
    .profile-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #E67E22;
    }
    
    .profile-avatar-placeholder {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background-color: #333;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #666;
        font-size: 2.5rem;
        border: 3px solid #E67E22;
    }
    
    .level-badge {
        position: absolute;
        bottom: 0;
        right: 0;
        background-color: #E67E22;
        color: #fff;
        font-size: 0.7rem;
        padding: 0.2rem 0.6rem;
        border-radius: 12px;
        font-weight: 600;
    }
    
    .profile-name {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 0.3rem;
    }
    
    .profile-username {
        color: #999;
        margin-bottom: 1rem;
        font-size: 0.9rem;
    }
    
    /* Statistiques du profil */
    .profile-stats {
        display: flex;
        justify-content: space-around;
        width: 100%;
        margin-bottom: 1.5rem;
        border-top: 1px solid #222;
        border-bottom: 1px solid #222;
        padding: 1rem 0;
    }
    
    .stat-item {
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    
    .stat-value {
        font-size: 1.3rem;
        font-weight: 700;
        color: #E67E22;
    }
    
    .stat-label {
        font-size: 0.8rem;
        color: #999;
        margin-top: 0.2rem;
    }
    
    /* Bio et actions */
    .profile-bio {
        text-align: center;
        margin-bottom: 1.5rem;
        color: #ccc;
        font-size: 0.9rem;
        line-height: 1.6;
    }
    
    .profile-actions {
        display: flex;
        justify-content: center;
    }
    
    .btn-edit {
        background-color: #333;
        color: #fff;
        border: none;
        padding: 0.5rem 1.2rem;
        border-radius: 5px;
        font-size: 0.9rem;
        transition: all 0.3s;
    }
    
    .btn-edit:hover {
        background-color: #444;
        color: #fff;
    }
    
    /* Graphique de points */
    .points-chart {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 1rem;
    }
    
    .points-circle {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        background: linear-gradient(135deg, #E67E22, #F39C12);
        display: flex;
        justify-content: center;
        align-items: center;
        position: relative;
    }
    
    .inner-circle {
        width: 125px;
        height: 125px;
        border-radius: 50%;
        background-color: #191919;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    
    .points-value {
        font-size: 2.5rem;
        font-weight: 700;
        color: #fff;
    }
    
    /* Information de niveau */
    .level-info {
        padding: 1rem;
    }
    
    .level-title {
        font-size: 1.2rem;
        margin-bottom: 1rem;
        color: #E67E22;
    }
    
    .level-progress {
        height: 8px;
        background-color: #333;
        border-radius: 4px;
        margin-bottom: 1rem;
        overflow: hidden;
    }
    
    .progress-bar {
        background-color: #E67E22;
        border-radius: 4px;
    }
    
    .next-level {
        display: flex;
        justify-content: space-between;
        font-size: 0.9rem;
        color: #999;
    }
    
    .points-needed {
        color: #E67E22;
    }
    
    /* Onglets de transaction */
    .transaction-tabs {
        margin-bottom: 1.5rem;
    }
    
    .nav-tabs {
        border-bottom: 1px solid #333;
    }
    
    .nav-link {
        color: #999;
        background-color: transparent;
        border: none;
        padding: 0.75rem 1.5rem;
        font-size: 0.9rem;
        border-bottom: 3px solid transparent;
        transition: all 0.3s;
    }
    
    .nav-link:hover {
        color: #fff;
        border-color: #666;
    }
    
    .nav-link.active {
        color: #E67E22;
        background-color: transparent;
        border-color: #E67E22;
    }
    
    /* Tableau des transactions */
    .transaction-table {
        color: #ccc;
        width: 100%;
    }
    
    .transaction-table th {
        font-size: 0.8rem;
        text-transform: uppercase;
        color: #999;
        font-weight: 600;
        border-bottom: 1px solid #333;
        padding: 0.75rem;
    }
    
    .transaction-table td {
        padding: 0.75rem;
        border-bottom: 1px solid #222;
        font-size: 0.9rem;
    }
    
    .points-cell {
        font-weight: 600;
    }
    
    .points-cell.positive {
        color: #4CAF50;
    }
    
    .points-cell.negative {
        color: #F44336;
    }
    
    /* État vide */
    .empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 2rem;
        text-align: center;
    }
    
    .empty-icon {
        font-size: 2.5rem;
        color: #444;
        margin-bottom: 1rem;
    }
    
    .empty-state p {
        color: #999;
        margin-bottom: 1.5rem;
    }
    
    .btn-create-post {
        background-color: #E67E22;
        color: #fff;
        border: none;
        padding: 0.5rem 1.2rem;
        border-radius: 5px;
        font-size: 0.9rem;
        transition: all 0.3s;
    }
    
    .btn-create-post:hover {
        background-color: #D35400;
        color: #fff;
    }
    
    /* Badges */
    .badges-container {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    
    .badge-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 0.75rem;
        background-color: #191919;
        border-radius: 8px;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    
    .badge-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }
    
    .badge-item.locked {
        opacity: 0.6;
        background-color: #171717;
    }
    
    .badge-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 1.2rem;
    }
    
    .badge-icon.commun, .badge-icon.common {
        background-color: #607D8B;
    }
    
    .badge-icon.rare {
        background-color: #3F51B5;
    }
    
    .badge-icon.épique, .badge-icon.epic {
        background-color: #9C27B0;
    }
    
    .badge-icon.légendaire, .badge-icon.legendary {
        background-color: #FFC107;
    }
    
    .badge-icon.locked {
        background-color: #333;
    }
    
    .badge-info {
        display: flex;
        flex-direction: column;
        flex: 1;
    }
    
    .badge-name {
        font-weight: 600;
        font-size: 0.9rem;
    }
    
    .badge-desc {
        font-size: 0.8rem;
        color: #999;
        margin-top: 0.2rem;
    }
    
    .badge-reward {
        text-align: right;
    }
    
    .points-reward {
        font-weight: 600;
        color: #4CAF50;
    }
    
    .available-badges {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }
    
    /* Publications récentes */
    .recent-posts {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }
    
    .post-item {
        display: flex;
        gap: 1rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid #222;
    }
    
    .post-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }
    
    .post-image {
        width: 80px;
        height: 80px;
        border-radius: 8px;
        overflow: hidden;
        flex-shrink: 0;
    }
    
    .post-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .post-image-placeholder {
        width: 100%;
        height: 100%;
        background-color: #333;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #666;
        font-size: 1.5rem;
    }
    
    .post-content {
        flex: 1;
    }
    
    .post-title {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    
    .post-excerpt {
        font-size: 0.9rem;
        color: #999;
        margin-bottom: 0.5rem;
        line-height: 1.4;
    }
    
    .post-meta {
        display: flex;
        justify-content: space-between;
        font-size: 0.8rem;
        color: #777;
    }
    
    .post-interactions {
        display: flex;
        gap: 0.75rem;
    }
    
    .post-interactions i {
        margin-right: 0.25rem;
    }
    
    /* Liens */
    .badge-link, .posts-link {
        color: #E67E22;
        text-decoration: none;
        font-size: 0.9rem;
        transition: all 0.3s;
    }
    
    .badge-link:hover, .posts-link:hover {
        color: #F39C12;
        text-decoration: underline;
    }
    
    .edit-link {
        color: #E67E22;
        text-decoration: none;
    }
    
    .edit-link:hover {
        text-decoration: underline;
    }
    
    /* Responsive */
    @media (max-width: 767px) {
        .post-item {
            flex-direction: column;
        }
        
        .post-image {
            width: 100%;
            height: 150px;
        }
        
        .level-info {
            margin-top: 1.5rem;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Gestion des onglets
        const tabs = document.querySelectorAll('.nav-link');
        const tabContents = document.querySelectorAll('.tab-pane');
        
        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                // Désactiver tous les onglets
                tabs.forEach(t => t.classList.remove('active'));
                tabContents.forEach(content => content.classList.remove('show', 'active'));
                
                // Activer l'onglet cliqué
                this.classList.add('active');
                const target = this.getAttribute('data-bs-target');
                document.querySelector(target).classList.add('show', 'active');
            });
        });
    });
</script>
@endsection 