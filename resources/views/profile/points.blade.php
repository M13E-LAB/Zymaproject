@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="page-title">
                    <i class="fas fa-star-half-alt"></i> Historique des points
                </h1>
                <a href="{{ route('profile.show') }}" class="btn btn-back">
                    <i class="fas fa-arrow-left me-2"></i> Retour au profil
                </a>
            </div>
            
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card points-summary-card">
                        <div class="card-body">
                            <div class="current-points-display">
                                <div class="points-bubble">
                                    {{ $user->points ?? 0 }}
                                </div>
                                <div class="points-info">
                                    <h2>Points actuels</h2>
                                    <p>Niveau: <span class="badge level-badge">{{ $user->level_title }}</span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card next-level-card">
                        <div class="card-body">
                            @if($user->next_level_points)
                                <h2>Prochain niveau</h2>
                                <div class="next-level-info">
                                    <div class="level-icon">
                                        @if($user->level_title == 'Débutant')
                                            <i class="fas fa-user-graduate"></i>
                                        @elseif($user->level_title == 'Éclaireur')
                                            <i class="fas fa-user-tie"></i>
                                        @elseif($user->level_title == 'Expert')
                                            <i class="fas fa-crown"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <h3>
                                            @if($user->level_title == 'Débutant')
                                                Éclaireur
                                            @elseif($user->level_title == 'Éclaireur')
                                                Expert
                                            @elseif($user->level_title == 'Expert')
                                                Maître
                                            @endif
                                        </h3>
                                        <div class="progress progress-lg">
                                            <div class="progress-bar" role="progressbar" style="width: {{ $user->level_progress }}%;" 
                                                aria-valuenow="{{ $user->level_progress }}" aria-valuemin="0" aria-valuemax="100">
                                                {{ round($user->level_progress) }}%
                                            </div>
                                        </div>
                                        <p class="points-to-next-level">
                                            <strong>{{ $user->next_level_points - ($user->points ?? 0) }}</strong> points restants
                                        </p>
                                    </div>
                                </div>
                            @else
                                <div class="max-level-reached">
                                    <i class="fas fa-trophy"></i>
                                    <h2>Niveau maximum atteint !</h2>
                                    <p>Félicitations ! Vous avez atteint le niveau le plus élevé : <strong>Maître ZYMA</strong></p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card transactions-card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="mb-0">Transactions de points</h2>
                        <div class="transactions-filter">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-filter active" data-filter="all">Tous</button>
                                <button type="button" class="btn btn-filter" data-filter="share">Partages</button>
                                <button type="button" class="btn btn-filter" data-filter="profile">Profil</button>
                                <button type="button" class="btn btn-filter" data-filter="comment">Commentaires</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table transactions-table">
                            <thead>
                                <tr>
                                    <th>Action</th>
                                    <th>Description</th>
                                    <th>Points</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transactions as $transaction)
                                    <tr class="transaction-row" data-type="{{ explode('_', $transaction->action_type)[0] }}">
                                        <td>
                                            <div class="transaction-icon">
                                                @if(strpos($transaction->action_type, 'share') !== false)
                                                    <i class="fas fa-share-alt"></i>
                                                @elseif(strpos($transaction->action_type, 'comment') !== false)
                                                    <i class="fas fa-comment"></i>
                                                @elseif(strpos($transaction->action_type, 'profile') !== false)
                                                    <i class="fas fa-user-edit"></i>
                                                @elseif(strpos($transaction->action_type, 'login') !== false)
                                                    <i class="fas fa-sign-in-alt"></i>
                                                @elseif(strpos($transaction->action_type, 'receipt') !== false)
                                                    <i class="fas fa-receipt"></i>
                                                @elseif(strpos($transaction->action_type, 'badge') !== false)
                                                    <i class="fas fa-medal"></i>
                                                @else
                                                    <i class="fas fa-star"></i>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="transaction-description">{{ $transaction->description }}</td>
                                        <td class="transaction-points">+{{ $transaction->points }}</td>
                                        <td class="transaction-date">
                                            <div>{{ $transaction->created_at->format('d/m/Y') }}</div>
                                            <small>{{ $transaction->created_at->format('H:i') }}</small>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5">
                                            <div class="empty-state">
                                                <i class="fas fa-star-half-alt mb-3"></i>
                                                <h3>Aucune transaction de points</h3>
                                                <p>Commencez à partager des produits pour gagner des points !</p>
                                                <a href="{{ route('social.create') }}" class="btn btn-primary mt-3">
                                                    <i class="fas fa-plus-circle me-2"></i> Partager un produit
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="pagination-container p-4">
                        {{ $transactions->links() }}
                    </div>
                </div>
            </div>
            
            <div class="card mt-4 earning-guide-card">
                <div class="card-body">
                    <h2 class="mb-4">Comment gagner des points ?</h2>
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="earning-method">
                                <div class="earning-icon">
                                    <i class="fas fa-share-alt"></i>
                                </div>
                                <div class="earning-details">
                                    <h3>Partager des produits</h3>
                                    <p>Partagez des produits avec la communauté</p>
                                    <div class="point-value">+10 points</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-4">
                            <div class="earning-method">
                                <div class="earning-icon">
                                    <i class="fas fa-comment"></i>
                                </div>
                                <div class="earning-details">
                                    <h3>Commenter</h3>
                                    <p>Commentez les partages d'autres utilisateurs</p>
                                    <div class="point-value">+2 points</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-4">
                            <div class="earning-method">
                                <div class="earning-icon">
                                    <i class="fas fa-user-edit"></i>
                                </div>
                                <div class="earning-details">
                                    <h3>Compléter votre profil</h3>
                                    <p>Ajoutez une photo, une bio et un nom d'utilisateur</p>
                                    <div class="point-value">+15 points</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-4">
                            <div class="earning-method">
                                <div class="earning-icon">
                                    <i class="fas fa-receipt"></i>
                                </div>
                                <div class="earning-details">
                                    <h3>Ajouter un prix</h3>
                                    <p>Contribuez aux prix des produits</p>
                                    <div class="point-value">+5 points</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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

.points-summary-card, .next-level-card {
    border: none;
    border-radius: 20px;
    overflow: hidden;
    height: 100%;
    background: linear-gradient(135deg, var(--bg-tertiary) 0%, var(--bg-secondary) 100%);
}

.current-points-display {
    display: flex;
    align-items: center;
    padding: 1rem;
}

.points-bubble {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: var(--accent-gradient);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    font-weight: 800;
    color: var(--bg-primary);
    margin-right: 2rem;
    box-shadow: 0 0 30px rgba(0, 209, 178, 0.3);
    position: relative;
    overflow: hidden;
}

.points-bubble:before {
    content: '';
    position: absolute;
    width: 150%;
    height: 150%;
    background: linear-gradient(rgba(255, 255, 255, 0.3), transparent);
    transform: rotate(45deg);
    top: -90%;
    left: -80%;
    animation: shine 3s infinite;
}

@keyframes shine {
    0% { top: -90%; left: -80%; }
    100% { top: 100%; left: 100%; }
}

.points-info h2 {
    font-size: 1.8rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    color: var(--text-primary);
}

.level-badge {
    background: var(--accent-gradient);
    color: var(--bg-primary);
    font-weight: 600;
    font-size: 1rem;
    padding: 0.4rem 1rem;
    border-radius: 30px;
}

.next-level-info {
    display: flex;
    align-items: center;
    padding: 1rem 0;
}

.level-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
    color: var(--accent-primary);
    margin-right: 1.5rem;
    flex-shrink: 0;
    border: 2px dashed var(--accent-primary);
}

.next-level-info h3 {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 0.8rem;
    color: var(--text-primary);
}

.progress-lg {
    height: 1rem;
    border-radius: 0.5rem;
    background: rgba(255, 255, 255, 0.1);
    margin-bottom: 0.8rem;
    width: 100%;
}

.points-to-next-level {
    color: var(--text-secondary);
    margin-bottom: 0;
    font-size: 0.9rem;
}

.points-to-next-level strong {
    color: var(--accent-primary);
}

.max-level-reached {
    text-align: center;
    padding: 2rem;
    color: var(--text-primary);
}

.max-level-reached i {
    font-size: 3rem;
    color: gold;
    margin-bottom: 1rem;
}

.max-level-reached h2 {
    font-size: 1.8rem;
    font-weight: 700;
    margin-bottom: 1rem;
}

.max-level-reached p {
    font-size: 1.1rem;
    color: var(--text-secondary);
}

.max-level-reached p strong {
    color: var(--accent-primary);
}

.transactions-card {
    border: none;
    border-radius: 20px;
    overflow: hidden;
}

.transactions-card .card-header {
    background: var(--bg-secondary);
    padding: 1.5rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
}

.transactions-card .card-header h2 {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 0;
}

.btn-filter {
    background: transparent;
    color: var(--text-secondary);
    border: 1px solid rgba(255, 255, 255, 0.1);
    padding: 0.4rem 1rem;
    transition: all 0.3s ease;
    font-size: 0.9rem;
}

.btn-filter:first-child {
    border-top-left-radius: 12px;
    border-bottom-left-radius: 12px;
}

.btn-filter:last-child {
    border-top-right-radius: 12px;
    border-bottom-right-radius: 12px;
}

.btn-filter.active, .btn-filter:hover {
    background: var(--accent-primary);
    color: var(--bg-primary);
}

.transactions-table {
    margin-bottom: 0;
}

.transactions-table thead th {
    background: var(--bg-tertiary);
    padding: 1.2rem 1.5rem;
    font-weight: 600;
    color: var(--text-secondary);
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    border: none;
}

.transaction-row {
    transition: all 0.3s ease;
}

.transaction-row:hover {
    background: rgba(255, 255, 255, 0.05);
}

.transaction-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: rgba(0, 209, 178, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--accent-primary);
    font-size: 1.2rem;
}

.transaction-description {
    font-weight: 500;
    color: var(--text-primary);
}

.transaction-points {
    font-weight: 700;
    color: var(--accent-primary);
    font-size: 1.1rem;
}

.transaction-date {
    color: var(--text-secondary);
    font-size: 0.9rem;
}

.transaction-date small {
    font-size: 0.8rem;
    opacity: 0.7;
}

.pagination-container {
    display: flex;
    justify-content: center;
}

.pagination {
    margin-bottom: 0;
}

.page-link {
    background: rgba(255, 255, 255, 0.05);
    color: var(--text-primary);
    border: 1px solid rgba(255, 255, 255, 0.1);
    margin: 0 5px;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.page-link:hover, .page-item.active .page-link {
    background: var(--accent-primary);
    color: var(--bg-primary);
    border-color: var(--accent-primary);
}

.earning-guide-card {
    border: none;
    border-radius: 20px;
    overflow: hidden;
    background: linear-gradient(135deg, var(--bg-tertiary) 0%, var(--bg-secondary) 100%);
}

.earning-guide-card h2 {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-primary);
    text-align: center;
    position: relative;
}

.earning-guide-card h2:after {
    content: '';
    display: block;
    width: 80px;
    height: 4px;
    background: var(--accent-gradient);
    margin: 1rem auto;
    border-radius: 2px;
}

.earning-method {
    display: flex;
    align-items: center;
    background: rgba(255, 255, 255, 0.03);
    border-radius: 16px;
    padding: 1.2rem;
    border: 1px solid rgba(255, 255, 255, 0.05);
    transition: all 0.3s ease;
    height: 100%;
}

.earning-method:hover {
    transform: translateY(-5px);
    background: rgba(255, 255, 255, 0.05);
    border-color: var(--accent-primary);
}

.earning-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: rgba(0, 209, 178, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--accent-primary);
    font-size: 1.5rem;
    margin-right: 1.2rem;
    flex-shrink: 0;
}

.earning-details {
    flex: 1;
}

.earning-details h3 {
    font-size: 1.2rem;
    font-weight: 600;
    margin-bottom: 0.3rem;
    color: var(--text-primary);
}

.earning-details p {
    color: var(--text-secondary);
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
}

.point-value {
    display: inline-block;
    background: var(--accent-gradient);
    color: var(--bg-primary);
    font-weight: 700;
    padding: 0.2rem 0.8rem;
    border-radius: 20px;
    font-size: 0.9rem;
}

.empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 2rem;
    color: var(--text-secondary);
}

.empty-state i {
    font-size: 3rem;
    color: var(--accent-primary);
    opacity: 0.7;
}

.empty-state h3 {
    font-size: 1.5rem;
    margin: 1rem 0 0.5rem;
    color: var(--text-primary);
}

.empty-state p {
    margin-bottom: 1rem;
    color: var(--text-secondary);
}

/* Animation des éléments */
.points-summary-card, .next-level-card, .transactions-card, .earning-guide-card {
    opacity: 0;
    transform: translateY(30px);
    animation: fadeInUp 0.8s forwards;
}

.next-level-card {
    animation-delay: 0.2s;
}

.transactions-card {
    animation-delay: 0.4s;
}

.earning-guide-card {
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
    // Filtrage des transactions
    const filterButtons = document.querySelectorAll('.btn-filter');
    const transactionRows = document.querySelectorAll('.transaction-row');
    
    filterButtons.forEach(button => {
        button.addEventListener('click', () => {
            // Mettre à jour l'état actif des boutons
            filterButtons.forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');
            
            const filterValue = button.getAttribute('data-filter');
            
            // Filtrer les transactions
            transactionRows.forEach(row => {
                if (filterValue === 'all' || row.getAttribute('data-type') === filterValue) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });
    
    // Animation des points
    const pointsElement = document.querySelector('.points-bubble');
    if (pointsElement) {
        const points = parseInt(pointsElement.textContent);
        let currentPoints = 0;
        const duration = 1500; // Durée de l'animation en ms
        const interval = 20; // Intervalle entre chaque incrémentation
        const increment = points / (duration / interval);
        
        const counter = setInterval(() => {
            currentPoints += increment;
            if (currentPoints >= points) {
                currentPoints = points;
                clearInterval(counter);
            }
            pointsElement.textContent = Math.floor(currentPoints);
        }, interval);
    }
});
</script>
@endsection 