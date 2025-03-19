@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="display-4 mb-4" style="color: var(--text-primary); font-weight: 800;">Statistiques Globales</h1>
            <div class="alert alert-info">
                <i class="fas fa-clock me-2"></i>
                Dernière mise à jour : {{ $lastUpdate }}
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="feature-icon me-3">
                            <i class="fas fa-database"></i>
                        </div>
                        <div>
                            <h5 class="card-title text-secondary mb-1">Nombre total de prix</h5>
                            <h2 class="display-5 mb-0">{{ number_format($totalPrices) }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="feature-icon me-3">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div>
                            <h5 class="card-title text-secondary mb-1">Prix ajoutés aujourd'hui</h5>
                            <h2 class="display-5 mb-0">{{ number_format($todayPrices) }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-map-marker-alt me-2" style="color: var(--accent-primary)"></i>
                            Prix par ville (Top 100)
                        </h3>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Ville</th>
                                    <th>Nombre de prix</th>
                                    <th>Dernière mise à jour</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cityStats as $city)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-city me-2" style="color: var(--accent-primary)"></i>
                                                {{ $city['city'] ?? 'Inconnue' }}
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge" style="background: var(--accent-gradient)">
                                                {{ number_format($city['count'] ?? 0) }}
                                            </span>
                                        </td>
                                        <td>
                                            <small class="text-secondary">
                                                <i class="fas fa-clock me-1"></i>
                                                {{ $city['last_update'] ?? 'N/A' }}
                                            </small>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.badge {
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.9rem;
}

.card-title {
    font-size: 1rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.display-5 {
    font-weight: 800;
    color: var(--text-primary);
}

.text-secondary {
    color: var(--text-secondary) !important;
}
</style>
@endsection 