@extends('layouts.app')

@section('content')
<div class="profile-container">
    <div class="container">
        <!-- En-tête de la page de ligue -->
        <div class="d-flex justify-content-between align-items-start flex-wrap mb-4">
            <div>
                <h1 class="section-title mb-2">{{ $league->name }}</h1>
                <p class="feed-subtitle">
                    @if($league->is_private)
                        <span class="badge bg-secondary me-2">
                            <i class="fas fa-lock me-1"></i> Ligue Privée
                        </span>
                    @else
                        <span class="badge bg-success me-2">
                            <i class="fas fa-globe me-1"></i> Ligue Publique
                        </span>
                    @endif
                    
                    <span class="text-muted">Créée par {{ $league->creator->name }}</span>
                </p>
                
                @if($league->description)
                    <div class="league-description mb-3">
                        {{ $league->description }}
                    </div>
                @endif
            </div>
            
            <div class="d-flex flex-wrap gap-2">
                @if($isMember)
                    <form action="{{ route('leagues.leave', $league->slug) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-light" onclick="return confirm('Êtes-vous sûr de vouloir quitter cette ligue ?')">
                            <i class="fas fa-sign-out-alt me-2"></i> Quitter la ligue
                        </button>
                    </form>
                @endif
                
                @if($league->created_by === auth()->id())
                    <button type="button" class="btn btn-outline-light" data-bs-toggle="modal" data-bs-target="#inviteModal">
                        <i class="fas fa-user-plus me-2"></i> Inviter des amis
                    </button>
                @endif
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
        
        <!-- Affichage des classements avec onglets -->
        <div class="card mb-4">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" id="leaderboardTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="weekly-tab" data-bs-toggle="tab" data-bs-target="#weekly" type="button" role="tab" aria-controls="weekly" aria-selected="true">
                            <i class="fas fa-calendar-week me-1"></i> Classement hebdo
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="monthly-tab" data-bs-toggle="tab" data-bs-target="#monthly" type="button" role="tab" aria-controls="monthly" aria-selected="false">
                            <i class="fas fa-calendar-alt me-1"></i> Classement mensuel
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="overall-tab" data-bs-toggle="tab" data-bs-target="#overall" type="button" role="tab" aria-controls="overall" aria-selected="false">
                            <i class="fas fa-trophy me-1"></i> Classement général
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="members-tab" data-bs-toggle="tab" data-bs-target="#members" type="button" role="tab" aria-controls="members" aria-selected="false">
                            <i class="fas fa-users me-1"></i> Membres
                        </button>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="leaderboardTabContent">
                    <!-- Classement hebdomadaire -->
                    <div class="tab-pane fade show active" id="weekly" role="tabpanel" aria-labelledby="weekly-tab">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Position</th>
                                        <th>Membre</th>
                                        <th>Score hebdo</th>
                                        <th>Dernière activité</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($weeklyLeaderboard as $member)
                                        <tr @if($member->id === auth()->id()) class="bg-dark" @endif>
                                            <td>{{ $member->pivot->position }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($member->avatar)
                                                        <img src="{{ $member->avatar }}" alt="Avatar" class="avatar-small me-2">
                                                    @else
                                                        <i class="fas fa-user-circle me-2" style="font-size: 1.5rem;"></i>
                                                    @endif
                                                    {{ $member->name }}
                                                </div>
                                            </td>
                                            <td>{{ $member->pivot->weekly_score }}</td>
                                            <td>
                                                @if($member->pivot->last_score_update)
                                                    @if(is_string($member->pivot->last_score_update))
                                                        {{ \Carbon\Carbon::parse($member->pivot->last_score_update)->diffForHumans() }}
                                                    @else
                                                        {{ $member->pivot->last_score_update->diffForHumans() }}
                                                    @endif
                                                @else
                                                    Jamais
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">Aucun score cette semaine</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Classement mensuel -->
                    <div class="tab-pane fade" id="monthly" role="tabpanel" aria-labelledby="monthly-tab">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Position</th>
                                        <th>Membre</th>
                                        <th>Score mensuel</th>
                                        <th>Dernière activité</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($monthlyLeaderboard as $member)
                                        <tr @if($member->id === auth()->id()) class="bg-dark" @endif>
                                            <td>{{ $member->pivot->position }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($member->avatar)
                                                        <img src="{{ $member->avatar }}" alt="Avatar" class="avatar-small me-2">
                                                    @else
                                                        <i class="fas fa-user-circle me-2" style="font-size: 1.5rem;"></i>
                                                    @endif
                                                    {{ $member->name }}
                                                </div>
                                            </td>
                                            <td>{{ $member->pivot->monthly_score }}</td>
                                            <td>
                                                @if($member->pivot->last_score_update)
                                                    @if(is_string($member->pivot->last_score_update))
                                                        {{ \Carbon\Carbon::parse($member->pivot->last_score_update)->diffForHumans() }}
                                                    @else
                                                        {{ $member->pivot->last_score_update->diffForHumans() }}
                                                    @endif
                                                @else
                                                    Jamais
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">Aucun score ce mois-ci</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Classement général -->
                    <div class="tab-pane fade" id="overall" role="tabpanel" aria-labelledby="overall-tab">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Position</th>
                                        <th>Membre</th>
                                        <th>Score total</th>
                                        <th>Membre depuis</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($overallLeaderboard as $member)
                                        <tr @if($member->id === auth()->id()) class="bg-dark" @endif>
                                            <td>{{ $member->pivot->position }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($member->avatar)
                                                        <img src="{{ $member->avatar }}" alt="Avatar" class="avatar-small me-2">
                                                    @else
                                                        <i class="fas fa-user-circle me-2" style="font-size: 1.5rem;"></i>
                                                    @endif
                                                    {{ $member->name }}
                                                </div>
                                            </td>
                                            <td>{{ $member->pivot->total_score }}</td>
                                            <td>{{ $member->pivot->created_at->format('d/m/Y') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">Aucun score disponible</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Liste des membres -->
                    <div class="tab-pane fade" id="members" role="tabpanel" aria-labelledby="members-tab">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Membre</th>
                                        <th>Rôle</th>
                                        <th>Membre depuis</th>
                                        @if($league->created_by === auth()->id())
                                            <th>Actions</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($league->members as $member)
                                        <tr @if($member->id === auth()->id()) class="bg-dark" @endif>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($member->avatar)
                                                        <img src="{{ $member->avatar }}" alt="Avatar" class="avatar-small me-2">
                                                    @else
                                                        <i class="fas fa-user-circle me-2" style="font-size: 1.5rem;"></i>
                                                    @endif
                                                    {{ $member->name }}
                                                    @if($league->created_by === $member->id)
                                                        <span class="badge bg-warning ms-2">Créateur</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                @if($member->pivot->role === 'admin')
                                                    <span class="badge bg-primary">Admin</span>
                                                @else
                                                    <span class="badge bg-secondary">Membre</span>
                                                @endif
                                            </td>
                                            <td>{{ $member->pivot->created_at->format('d/m/Y') }}</td>
                                            @if($league->created_by === auth()->id() && $member->id !== auth()->id())
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <form action="{{ route('leagues.updateMemberRole', ['slug' => $league->slug, 'userId' => $member->id]) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="hidden" name="role" value="{{ $member->pivot->role === 'admin' ? 'member' : 'admin' }}">
                                                            <button type="submit" class="btn btn-sm btn-outline-light me-2">
                                                                @if($member->pivot->role === 'admin')
                                                                    <i class="fas fa-user me-1"></i> Rétrograder
                                                                @else
                                                                    <i class="fas fa-user-shield me-1"></i> Promouvoir
                                                                @endif
                                                            </button>
                                                        </form>
                                                        
                                                        <form action="{{ route('leagues.removeMember', ['slug' => $league->slug, 'userId' => $member->id]) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Êtes-vous sûr de vouloir retirer ce membre ?')">
                                                                <i class="fas fa-user-times me-1"></i> Retirer
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Statistiques de la ligue -->
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i> Statistiques de la ligue</h5>
                    </div>
                    <div class="card-body">
                        <div class="league-stats">
                            <div class="stat-item">
                                <div class="stat-label">Membres</div>
                                <div class="stat-value">{{ $league->members->count() }} / {{ $league->max_members }}</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-label">Score moyen</div>
                                <div class="stat-value">{{ round($league->members->avg('pivot.total_score')) }}</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-label">Créée le</div>
                                <div class="stat-value">{{ $league->created_at->format('d/m/Y') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-share-alt me-2"></i> Partager cette ligue</h5>
                    </div>
                    <div class="card-body">
                        <p>Partagez ce code d'invitation avec vos amis pour qu'ils rejoignent votre ligue :</p>
                        <div class="invite-code-container">
                            <input type="text" value="{{ $league->invite_code }}" class="form-control" id="inviteCode" readonly>
                            <button class="btn btn-outline-light" onclick="copyInviteCode()">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal d'invitation -->
<div class="modal fade" id="inviteModal" tabindex="-1" aria-labelledby="inviteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content bg-dark text-white">
            <div class="modal-header">
                <h5 class="modal-title" id="inviteModalLabel">Inviter des amis dans la ligue</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Partagez ce code d'invitation avec vos amis :</p>
                <div class="invite-code-container mb-3">
                    <input type="text" value="{{ $league->invite_code }}" class="form-control" id="modalInviteCode" readonly>
                    <button class="btn btn-outline-light" onclick="copyModalInviteCode()">
                        <i class="fas fa-copy"></i>
                    </button>
                </div>
                
                <p>Ou partagez le lien direct :</p>
                <div class="invite-code-container">
                    <input type="text" value="{{ url('/leagues/join?code=' . $league->invite_code) }}" class="form-control" id="inviteLink" readonly>
                    <button class="btn btn-outline-light" onclick="copyInviteLink()">
                        <i class="fas fa-copy"></i>
                    </button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<style>
.league-description {
    background-color: rgba(255, 255, 255, 0.05);
    padding: 15px;
    border-radius: 10px;
    font-style: italic;
}

.stat-item {
    background-color: rgba(255, 255, 255, 0.05);
    border-radius: 10px;
    padding: 15px;
    margin-bottom: 10px;
}

.stat-label {
    color: #999;
    font-size: 0.9rem;
    margin-bottom: 5px;
}

.stat-value {
    font-size: 1.5rem;
    font-weight: 600;
}

.invite-code-container {
    display: flex;
    gap: 10px;
}

.invite-code-container .form-control {
    background-color: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: white;
}

.nav-tabs .nav-link {
    color: rgba(255, 255, 255, 0.7);
    border: none;
    padding: 10px 15px;
}

.nav-tabs .nav-link:hover {
    color: white;
    background-color: rgba(255, 255, 255, 0.05);
}

.nav-tabs .nav-link.active {
    color: white;
    background-color: transparent;
    border-bottom: 2px solid white;
}

.gap-2 {
    gap: 0.5rem;
}
</style>

<script>
function copyInviteCode() {
    const inviteCode = document.getElementById('inviteCode');
    inviteCode.select();
    document.execCommand('copy');
    alert('Code d\'invitation copié !');
}

function copyModalInviteCode() {
    const modalInviteCode = document.getElementById('modalInviteCode');
    modalInviteCode.select();
    document.execCommand('copy');
    alert('Code d\'invitation copié !');
}

function copyInviteLink() {
    const inviteLink = document.getElementById('inviteLink');
    inviteLink.select();
    document.execCommand('copy');
    alert('Lien d\'invitation copié !');
}
</script>
@endsection 