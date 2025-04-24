@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="page-title">
                    <i class="fas fa-share-alt"></i> Mes partages
                </h1>
                <div>
                    <a href="{{ route('profile.show') }}" class="btn btn-back me-2">
                        <i class="fas fa-arrow-left me-2"></i> Retour au profil
                    </a>
                    <a href="{{ route('social.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus-circle me-2"></i> Nouveau partage
                    </a>
                </div>
            </div>
            
            <div class="card posts-stats">
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <div class="stat-item">
                                <div class="stat-value">{{ $posts->total() }}</div>
                                <div class="stat-label">Total des partages</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-item">
                                <div class="stat-value">{{ $user->posts()->where('created_at', '>=', now()->subMonth())->count() }}</div>
                                <div class="stat-label">Partages ce mois</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-item">
                                <div class="stat-value">{{ $user->pointTransactions()->where('action_type', 'LIKE', 'share%')->sum('points') }}</div>
                                <div class="stat-label">Points gagnés</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-item">
                                <div class="stat-value">{{ $user->posts()->withCount('comments')->get()->sum('comments_count') }}</div>
                                <div class="stat-label">Commentaires reçus</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="posts-container">
                @forelse($posts as $post)
                <div class="post-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="post-header">
                                <div class="post-date">
                                    <i class="far fa-calendar-alt"></i> {{ $post->created_at->format('d M Y') }}
                                </div>
                                <div class="post-actions dropdown">
                                    <button class="btn btn-icon" type="button" id="postActions{{ $post->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="postActions{{ $post->id }}">
                                        <li><a class="dropdown-item" href="{{ route('social.edit', $post->id) }}"><i class="fas fa-edit me-2"></i> Modifier</a></li>
                                        <li>
                                            <a class="dropdown-item text-danger" href="#" 
                                               onclick="event.preventDefault(); document.getElementById('delete-post-{{ $post->id }}').submit();">
                                                <i class="fas fa-trash-alt me-2"></i> Supprimer
                                            </a>
                                            <form id="delete-post-{{ $post->id }}" action="{{ route('social.destroy', $post->id) }}" method="POST" class="d-none">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="post-image">
                                        @if($post->image)
                                            <img src="{{ $post->image }}" alt="{{ $post->title }}" class="img-fluid rounded">
                                        @else
                                            <div class="no-image">
                                                <i class="fas fa-image"></i>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <h3 class="post-title">
                                        <a href="{{ route('social.show', $post->id) }}">{{ $post->title }}</a>
                                    </h3>
                                    
                                    @if($post->product)
                                    <div class="product-info">
                                        <div class="product-badge">
                                            <i class="fas fa-tag me-1"></i> Produit
                                        </div>
                                        <h4>{{ $post->product->name }}</h4>
                                        @if($post->product->brand)
                                        <div class="product-brand">
                                            <i class="fas fa-industry me-1"></i> {{ $post->product->brand }}
                                        </div>
                                        @endif
                                    </div>
                                    @endif
                                    
                                    <div class="post-content">
                                        <p>{{ Str::limit($post->content, 150) }}</p>
                                    </div>
                                    
                                    <div class="post-meta">
                                        <div class="post-stats">
                                            <div class="stat">
                                                <i class="fas fa-comment"></i> {{ $post->comments_count ?? $post->comments()->count() }}
                                            </div>
                                            <div class="stat">
                                                <i class="fas fa-heart"></i> {{ $post->likes_count ?? $post->likes()->count() }}
                                            </div>
                                            <div class="stat">
                                                <i class="fas fa-eye"></i> {{ $post->views ?? 0 }}
                                            </div>
                                        </div>
                                        
                                        <a href="{{ route('social.show', $post->id) }}" class="btn btn-outline-primary btn-sm">
                                            Voir détails <i class="fas fa-arrow-right ms-1"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="empty-posts">
                    <div class="card">
                        <div class="card-body text-center">
                            <div class="empty-state">
                                <i class="fas fa-share-alt mb-4"></i>
                                <h3>Aucun partage pour le moment</h3>
                                <p>Vous n'avez pas encore partagé de produits avec la communauté.</p>
                                <a href="{{ route('social.create') }}" class="btn btn-primary mt-3">
                                    <i class="fas fa-plus-circle me-2"></i> Partager votre premier produit
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforelse
            </div>
            
            <div class="pagination-container">
                {{ $posts->links() }}
            </div>
            
            @if(count($posts) > 0)
            <div class="card posts-analytics mt-5">
                <div class="card-body">
                    <h2 class="card-title with-icon">
                        <i class="fas fa-chart-pie"></i> Analyse de vos partages
                    </h2>
                    
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="analytics-card mb-4">
                                <h3>Évolution des partages</h3>
                                <div class="chart-container">
                                    <canvas id="postsChart" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="analytics-card mb-4">
                                <h3>Engagement</h3>
                                <div class="chart-container">
                                    <canvas id="engagementChart" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="engagement-tips">
                        <h3>Conseils pour augmenter l'engagement</h3>
                        <div class="tips-list">
                            <div class="tip-item">
                                <div class="tip-icon">
                                    <i class="fas fa-camera"></i>
                                </div>
                                <div class="tip-content">
                                    <h4>Ajoutez des photos de qualité</h4>
                                    <p>Les publications avec photos reçoivent 2,3x plus de réactions.</p>
                                </div>
                            </div>
                            
                            <div class="tip-item">
                                <div class="tip-icon">
                                    <i class="fas fa-comment-dots"></i>
                                </div>
                                <div class="tip-content">
                                    <h4>Soyez détaillé dans vos descriptions</h4>
                                    <p>Décrivez le goût, la texture et votre avis sincère sur le produit.</p>
                                </div>
                            </div>
                            
                            <div class="tip-item">
                                <div class="tip-icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div class="tip-content">
                                    <h4>Publiez régulièrement</h4>
                                    <p>Les utilisateurs actifs chaque semaine gagnent 5x plus de points.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
.page-title {
    font-size: 2rem;
    font-weight: 800;
    color: var(--text-primary);
    display: flex;
    align-items: center;
}

.page-title i {
    color: var(--accent-primary);
    margin-right: 1rem;
    font-size: 1.8rem;
}

.btn-back {
    background: rgba(255, 255, 255, 0.05);
    color: var(--text-primary);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    padding: 0.5rem 1.2rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-back:hover {
    background: var(--accent-primary);
    color: var(--bg-primary);
    transform: translateX(-5px);
}

.posts-stats {
    border: none;
    border-radius: 20px;
    background: linear-gradient(135deg, var(--bg-tertiary) 0%, var(--bg-secondary) 100%);
    margin-bottom: 2rem;
    overflow: hidden;
}

.stat-item {
    padding: 1.5rem 0;
    transition: all 0.3s ease;
}

.stat-item:hover {
    transform: translateY(-5px);
}

.stat-value {
    font-size: 2.5rem;
    font-weight: 800;
    margin-bottom: 0.5rem;
    background: var(--accent-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.stat-label {
    font-size: 0.9rem;
    color: var(--text-secondary);
    text-transform: uppercase;
    letter-spacing: 1px;
    font-weight: 600;
}

.posts-container {
    margin: 2rem 0;
}

.post-card {
    margin-bottom: 1.5rem;
    transform: translateY(0);
    transition: all 0.5s cubic-bezier(0.22, 1, 0.36, 1);
}

.post-card:hover {
    transform: translateY(-10px);
}

.post-card .card {
    border: none;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(10px);
    background: rgba(30, 30, 30, 0.6);
    border: 1px solid rgba(255, 255, 255, 0.05);
}

.post-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.post-date {
    font-size: 0.9rem;
    color: var(--text-secondary);
}

.post-date i {
    color: var(--accent-primary);
    margin-right: 0.5rem;
}

.btn-icon {
    width: 34px;
    height: 34px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.05);
    color: var(--text-secondary);
    border: 1px solid rgba(255, 255, 255, 0.1);
    transition: all 0.3s ease;
}

.btn-icon:hover {
    background: var(--accent-primary);
    color: var(--bg-primary);
}

.post-image {
    border-radius: 12px;
    overflow: hidden;
    margin-bottom: 1rem;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
    background: var(--bg-tertiary);
    height: 180px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.post-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.no-image {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(45deg, var(--bg-tertiary), var(--bg-secondary));
    color: var(--text-secondary);
    font-size: 3rem;
}

.post-title {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
    line-height: 1.3;
}

.post-title a {
    color: var(--text-primary);
    text-decoration: none;
    transition: all 0.3s ease;
}

.post-title a:hover {
    color: var(--accent-primary);
}

.product-info {
    margin-bottom: 1rem;
    background: rgba(0, 0, 0, 0.2);
    padding: 1rem;
    border-radius: 12px;
    border-left: 4px solid var(--accent-primary);
}

.product-badge {
    display: inline-block;
    background: var(--accent-primary);
    color: var(--bg-primary);
    font-size: 0.8rem;
    padding: 0.3rem 0.8rem;
    border-radius: 30px;
    margin-bottom: 0.5rem;
    font-weight: 600;
}

.product-info h4 {
    font-size: 1.2rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: var(--text-primary);
}

.product-brand {
    font-size: 0.9rem;
    color: var(--text-secondary);
}

.post-content {
    color: var(--text-secondary);
    margin-bottom: 1.5rem;
    font-size: 0.95rem;
    line-height: 1.6;
}

.post-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.post-stats {
    display: flex;
    gap: 1.5rem;
}

.stat {
    display: flex;
    align-items: center;
    color: var(--text-secondary);
    font-size: 0.9rem;
}

.stat i {
    color: var(--accent-primary);
    margin-right: 0.5rem;
}

.btn-outline-primary {
    border: 1px solid var(--accent-primary);
    color: var(--accent-primary);
    background: transparent;
    border-radius: 12px;
    padding: 0.4rem 1rem;
    transition: all 0.3s ease;
}

.btn-outline-primary:hover {
    background: var(--accent-primary);
    color: var(--bg-primary);
    transform: translateX(5px);
}

.empty-posts {
    margin: 3rem 0;
}

.empty-state {
    padding: 3rem 1rem;
    text-align: center;
}

.empty-state i {
    font-size: 4rem;
    color: var(--accent-primary);
    opacity: 0.5;
    margin-bottom: 1.5rem;
}

.empty-state h3 {
    font-size: 1.8rem;
    margin-bottom: 1rem;
    color: var(--text-primary);
}

.empty-state p {
    color: var(--text-secondary);
    margin-bottom: 1.5rem;
    max-width: 500px;
    margin-left: auto;
    margin-right: auto;
}

.pagination-container {
    display: flex;
    justify-content: center;
    margin: 2rem 0;
}

.posts-analytics {
    border: none;
    border-radius: 20px;
    background: linear-gradient(135deg, var(--bg-tertiary) 0%, var(--bg-secondary) 100%);
    overflow: hidden;
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

.analytics-card {
    background: rgba(0, 0, 0, 0.2);
    border-radius: 16px;
    padding: 1.5rem;
    height: 100%;
}

.analytics-card h3 {
    font-size: 1.2rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 1.5rem;
    text-align: center;
}

.chart-container {
    position: relative;
    height: 200px;
}

.engagement-tips {
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.engagement-tips h3 {
    font-size: 1.3rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 1.5rem;
    text-align: center;
}

.tips-list {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
}

.tip-item {
    display: flex;
    align-items: flex-start;
    background: rgba(255, 255, 255, 0.03);
    border-radius: 16px;
    padding: 1.5rem;
    transition: all 0.3s ease;
    border: 1px solid rgba(255, 255, 255, 0.05);
}

.tip-item:hover {
    transform: translateY(-5px);
    background: rgba(255, 255, 255, 0.05);
    border-color: var(--accent-primary);
}

.tip-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: rgba(0, 209, 178, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--accent-primary);
    font-size: 1.5rem;
    margin-right: 1rem;
    flex-shrink: 0;
}

.tip-content h4 {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
}

.tip-content p {
    font-size: 0.9rem;
    color: var(--text-secondary);
    margin-bottom: 0;
}

/* Animation des cartes */
.post-card, .posts-stats, .posts-analytics {
    opacity: 0;
    transform: translateY(30px);
    animation: fadeInUp 0.8s forwards;
}

.post-card:nth-child(2) {
    animation-delay: 0.1s;
}

.post-card:nth-child(3) {
    animation-delay: 0.2s;
}

.post-card:nth-child(4) {
    animation-delay: 0.3s;
}

.post-card:nth-child(5) {
    animation-delay: 0.4s;
}

.posts-stats {
    animation-delay: 0.1s;
}

.posts-analytics {
    animation-delay: 0.5s;
}

@keyframes fadeInUp {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Graphique d'évolution des partages
    if (document.getElementById('postsChart')) {
        const ctx = document.getElementById('postsChart').getContext('2d');
        
        // Générer des données d'exemple basées sur le nombre total de posts
        const totalPosts = {{ $posts->total() }};
        const labels = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'];
        const currentMonth = new Date().getMonth();
        
        // Générer des données fictives basées sur le nombre total de posts
        const data = Array(12).fill(0);
        let remainingPosts = totalPosts;
        
        // Répartir les posts sur les mois précédents avec une tendance à la hausse
        for (let i = currentMonth; i >= 0; i--) {
            const monthPosts = Math.max(1, Math.floor(remainingPosts * (0.1 + (i / currentMonth) * 0.3)));
            data[i] = monthPosts;
            remainingPosts -= monthPosts;
            if (remainingPosts <= 0) break;
        }
        
        // Si il reste des posts, les attribuer à l'année précédente
        if (remainingPosts > 0) {
            for (let i = 11; i > currentMonth; i--) {
                const monthPosts = Math.max(1, Math.floor(remainingPosts * 0.2));
                data[i] = monthPosts;
                remainingPosts -= monthPosts;
                if (remainingPosts <= 0) break;
            }
        }
        
        const postsChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Partages par mois',
                    data: data,
                    borderColor: '#00d1b2',
                    backgroundColor: 'rgba(0, 209, 178, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#00d1b2',
                    pointBorderColor: '#00d1b2',
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        padding: 12,
                        cornerRadius: 8
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(255, 255, 255, 0.05)'
                        },
                        ticks: {
                            color: 'rgba(255, 255, 255, 0.7)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: 'rgba(255, 255, 255, 0.7)'
                        }
                    }
                }
            }
        });
    }
    
    // Graphique d'engagement
    if (document.getElementById('engagementChart')) {
        const ctx = document.getElementById('engagementChart').getContext('2d');
        
        // Données d'engagement fictives
        const engagementChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Vues', 'Likes', 'Commentaires'],
                datasets: [{
                    data: [{{ ($posts->sum('views') ?? $posts->count() * 15) }}, {{ $posts->sum('likes_count') ?? $posts->count() * 5 }}, {{ $posts->sum('comments_count') ?? $posts->count() * 2 }}],
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(54, 162, 235, 0.7)'
                    ],
                    borderColor: [
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: 'rgba(255, 255, 255, 0.7)',
                            padding: 15,
                            font: {
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        padding: 12,
                        cornerRadius: 8
                    }
                },
                cutout: '65%'
            }
        });
    }
});
</script>
@endsection 