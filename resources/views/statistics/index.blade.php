@extends('layouts.app')

@section('content')
<div class="zyma-container statistics-page">
    <!-- En-tête avec titre -->
    <div class="stats-header">
        <h1 class="stats-title">Statistiques des prix</h1>
        <p class="stats-subtitle">
            Découvrez les tendances des prix alimentaires en France et comprenez où vous pouvez économiser
        </p>
    </div>

    <!-- Cartes récapitulatives -->
    <div class="stats-overview">
        <div class="stats-card total-prices">
            <div class="card-icon">
                <i class="fas fa-tag"></i>
            </div>
            <div class="card-content">
                <h3 class="card-number">{{ number_format($totalPrices) }}</h3>
                <p class="card-label">Prix enregistrés</p>
            </div>
        </div>
        
        <div class="stats-card today-prices">
            <div class="card-icon">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div class="card-content">
                <h3 class="card-number">{{ number_format($todayPrices) }}</h3>
                <p class="card-label">Prix enregistrés aujourd'hui</p>
            </div>
        </div>
        
        <div class="stats-card top-cities">
            <div class="card-icon">
                <i class="fas fa-city"></i>
            </div>
            <div class="card-content">
                <h3 class="card-number">{{ count($cityStats) }}</h3>
                <p class="card-label">Villes couvertes</p>
            </div>
        </div>
        
        <div class="stats-card last-update">
            <div class="card-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="card-content">
                <h3 class="card-time">{{ $lastUpdate }}</h3>
                <p class="card-label">Dernière mise à jour</p>
            </div>
        </div>
    </div>

    <!-- Graphique des tendances de prix -->
    <div class="chart-container price-trends">
        <div class="chart-header">
            <h2 class="chart-title">Évolution des prix par ville</h2>
        </div>
        <div class="chart-body">
            <canvas id="priceChart"></canvas>
        </div>
    </div>
    
    <!-- Tableau des villes avec le plus grand nombre de prix enregistrés -->
    <div class="table-container city-rankings">
        <div class="table-header">
            <h2 class="table-title">Villes avec le plus de prix enregistrés</h2>
            <div class="table-filter">
                <label for="city-filter">Filtrer:</label>
                <input type="text" id="city-filter" placeholder="Rechercher une ville...">
            </div>
        </div>
        <div class="table-responsive">
            <table class="stats-table">
                <thead>
                    <tr>
                        <th>Rang</th>
                        <th>Ville</th>
                        <th>Nombre de prix</th>
                        <th>Dernière mise à jour</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cityStats as $index => $city)
                    <tr>
                        <td class="rank">{{ $index + 1 }}</td>
                        <td class="city-name">{{ $city['city'] }}</td>
                        <td class="price-count">{{ number_format($city['count']) }}</td>
                        <td class="last-update">{{ \Carbon\Carbon::parse($city['last_update'])->format('d/m/Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Section de participation communautaire -->
    <div class="community-section">
        <div class="community-content">
            <h2 class="community-title">Contribuez à la communauté</h2>
            <p class="community-desc">
                Aidez-nous à construire la plus grande base de données de prix alimentaires en France. 
                Partagez les prix que vous trouvez et aidez d'autres à économiser.
            </p>
            <div class="community-actions">
                <a href="{{ route('products.search') }}" class="btn-scan">
                    <i class="fas fa-camera"></i> Scanner un produit
                </a>
                <a href="#" class="btn-contribute">
                    <i class="fas fa-plus"></i> Ajouter un prix manuellement
                </a>
            </div>
        </div>
    </div>
</div>

<style>
/* Variables et resets */
:root {
    --bg-dark: #111111;
    --bg-card: #1a1a1a;
    --bg-light: #222222;
    --text-primary: #ffffff;
    --text-secondary: rgba(255, 255, 255, 0.7);
    --text-muted: rgba(255, 255, 255, 0.5);
    --accent-primary: #E67E22;
    --accent-secondary: #4CAF50;
    --accent-tertiary: #3498db;
    --gradient-primary: linear-gradient(135deg, #E67E22, #F39C12);
    --gradient-secondary: linear-gradient(135deg, #4CAF50, #8BC34A);
    --gradient-tertiary: linear-gradient(135deg, #3498db, #2980b9);
    --gradient-quaternary: linear-gradient(135deg, #9b59b6, #8e44ad);
    --transition-smooth: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    --shadow-soft: 0 10px 30px rgba(0, 0, 0, 0.1);
    --shadow-strong: 0 15px 35px rgba(0, 0, 0, 0.2);
    --radius-sm: 8px;
    --radius-md: 16px;
    --radius-lg: 24px;
    --font-sans: 'Inter', sans-serif;
}

/* Styles pour la page de statistiques */
.statistics-page {
    padding: 6rem 2rem 2rem;
}

.stats-header {
    text-align: center;
    margin-bottom: 3rem;
    animation: fadeInUp 1s ease-out;
}

.stats-title {
    font-size: 3.5rem;
    font-weight: 800;
    margin-bottom: 1rem;
    background: var(--gradient-primary);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.stats-subtitle {
    font-size: 1.2rem;
    color: var(--text-secondary);
    max-width: 700px;
    margin: 0 auto;
}

/* Cartes de statistiques récapitulatives */
.stats-overview {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 3rem;
    animation: fadeInUp 1s ease-out 0.2s both;
}

.stats-card {
    background: var(--bg-card);
    border-radius: var(--radius-md);
    padding: 1.5rem;
    display: flex;
    align-items: center;
    border: 1px solid rgba(255, 255, 255, 0.05);
    transition: var(--transition-smooth);
}

.stats-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-soft);
}

.stats-card.total-prices {
    border-left: 4px solid var(--accent-primary);
}

.stats-card.today-prices {
    border-left: 4px solid var(--accent-secondary);
}

.stats-card.top-cities {
    border-left: 4px solid var(--accent-tertiary);
}

.stats-card.last-update {
    border-left: 4px solid #9b59b6;
}

.card-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1.5rem;
    font-size: 1.5rem;
}

.total-prices .card-icon {
    background: rgba(230, 126, 34, 0.15);
    color: var(--accent-primary);
}

.today-prices .card-icon {
    background: rgba(76, 175, 80, 0.15);
    color: var(--accent-secondary);
}

.top-cities .card-icon {
    background: rgba(52, 152, 219, 0.15);
    color: var(--accent-tertiary);
}

.last-update .card-icon {
    background: rgba(155, 89, 182, 0.15);
    color: #9b59b6;
}

.card-content {
    flex: 1;
}

.card-number, .card-time {
    font-size: 2rem;
    font-weight: 700;
    margin: 0;
    line-height: 1.2;
}

.card-label {
    margin: 0;
    color: var(--text-secondary);
    font-size: 0.9rem;
}

/* Conteneur de graphique */
.chart-container {
    background: var(--bg-card);
    border-radius: var(--radius-md);
    margin-bottom: 3rem;
    padding: 2rem;
    border: 1px solid rgba(255, 255, 255, 0.05);
    animation: fadeInUp 1s ease-out 0.4s both;
}

.chart-header {
    margin-bottom: 1.5rem;
}

.chart-title {
    font-size: 1.5rem;
    font-weight: 600;
    margin: 0;
}

.chart-body {
    height: 400px;
    position: relative;
}

/* Tableau des villes */
.table-container {
    background: var(--bg-card);
    border-radius: var(--radius-md);
    margin-bottom: 3rem;
    padding: 2rem;
    border: 1px solid rgba(255, 255, 255, 0.05);
    animation: fadeInUp 1s ease-out 0.6s both;
}

.table-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
    gap: 1rem;
}

.table-title {
    font-size: 1.5rem;
    font-weight: 600;
    margin: 0;
}

.table-filter {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.table-filter label {
    color: var(--text-secondary);
}

.table-filter input {
    background: var(--bg-light);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--radius-sm);
    padding: 0.5rem 1rem;
    color: var(--text-primary);
}

.table-responsive {
    overflow-x: auto;
}

.stats-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
}

.stats-table th, .stats-table td {
    padding: 1rem;
    text-align: left;
}

.stats-table th {
    background: var(--bg-light);
    color: var(--text-secondary);
    font-weight: 600;
    position: sticky;
    top: 0;
}

.stats-table th:first-child {
    border-top-left-radius: var(--radius-sm);
    border-bottom-left-radius: var(--radius-sm);
}

.stats-table th:last-child {
    border-top-right-radius: var(--radius-sm);
    border-bottom-right-radius: var(--radius-sm);
}

.stats-table tbody tr {
    transition: var(--transition-smooth);
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
}

.stats-table tbody tr:hover {
    background: rgba(255, 255, 255, 0.05);
}

.stats-table .rank {
    font-weight: 700;
    width: 80px;
}

.stats-table .city-name {
    font-weight: 600;
}

.stats-table .price-count {
    font-weight: 600;
    color: var(--accent-primary);
}

.stats-table .last-update {
    color: var(--text-secondary);
}

/* Section de participation communautaire */
.community-section {
    background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('https://images.unsplash.com/photo-1604719312566-8912e9667d9f?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1374&q=80');
    background-size: cover;
    background-position: center;
    border-radius: var(--radius-lg);
    padding: 4rem 2rem;
    margin-bottom: 2rem;
    text-align: center;
    position: relative;
    animation: fadeInUp 1s ease-out 0.8s both;
}

.community-content {
    max-width: 700px;
    margin: 0 auto;
}

.community-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
    color: var(--text-primary);
}

.community-desc {
    color: var(--text-secondary);
    margin-bottom: 2rem;
    font-size: 1.1rem;
}

.community-actions {
    display: flex;
    justify-content: center;
    gap: 1.5rem;
    flex-wrap: wrap;
}

.btn-scan {
    background: var(--gradient-primary);
    color: white;
    padding: 1rem 2rem;
    border-radius: var(--radius-md);
    text-decoration: none;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    transition: var(--transition-smooth);
}

.btn-scan:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 20px rgba(230, 126, 34, 0.3);
}

.btn-contribute {
    background: rgba(255, 255, 255, 0.1);
    color: var(--text-primary);
    padding: 1rem 2rem;
    border-radius: var(--radius-md);
    text-decoration: none;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    border: 1px solid rgba(255, 255, 255, 0.2);
    transition: var(--transition-smooth);
}

.btn-contribute:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: translateY(-3px);
}

/* Responsive */
@media (max-width: 768px) {
    .statistics-page {
        padding: 5rem 1rem 1rem;
    }
    
    .stats-title {
        font-size: 2.5rem;
    }
    
    .table-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .community-section {
        padding: 3rem 1.5rem;
    }
    
    .community-title {
        font-size: 2rem;
    }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Configuration du graphique
    const ctx = document.getElementById('priceChart').getContext('2d');
    
    // Données simulées pour le graphique
    const cities = {!! json_encode(array_slice(array_column($cityStats, 'city'), 0, 5)) !!};
    
    // Génération de données aléatoires pour les 6 derniers mois
    const months = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin'];
    const datasets = cities.map((city, index) => {
        const colors = [
            'rgba(230, 126, 34, 0.7)',
            'rgba(76, 175, 80, 0.7)',
            'rgba(52, 152, 219, 0.7)',
            'rgba(155, 89, 182, 0.7)',
            'rgba(241, 196, 15, 0.7)'
        ];
        
        return {
            label: city,
            data: Array.from({length: 6}, () => Math.floor(Math.random() * 500) + 100),
            backgroundColor: colors[index % colors.length],
            borderColor: colors[index % colors.length].replace('0.7', '1'),
            borderWidth: 2,
            tension: 0.4
        };
    });
    
    const priceChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: months,
            datasets: datasets
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        color: 'rgba(255, 255, 255, 0.7)',
                        font: {
                            family: 'Inter, sans-serif'
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleFont: {
                        family: 'Inter, sans-serif'
                    },
                    bodyFont: {
                        family: 'Inter, sans-serif'
                    },
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' + context.raw + ' prix';
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        color: 'rgba(255, 255, 255, 0.05)'
                    },
                    ticks: {
                        color: 'rgba(255, 255, 255, 0.7)'
                    }
                },
                y: {
                    grid: {
                        color: 'rgba(255, 255, 255, 0.05)'
                    },
                    ticks: {
                        color: 'rgba(255, 255, 255, 0.7)'
                    }
                }
            }
        }
    });
    
    // Filtre du tableau des villes
    const cityFilter = document.getElementById('city-filter');
    const tableRows = document.querySelectorAll('.stats-table tbody tr');
    
    if (cityFilter) {
        cityFilter.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            
            tableRows.forEach(row => {
                const cityName = row.querySelector('.city-name').textContent.toLowerCase();
                if (cityName.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }
});
</script>
@endsection 