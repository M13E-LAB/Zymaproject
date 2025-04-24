@extends('layouts.app')

@section('content')
<div class="zyma-container statistics-page">
    <!-- En-tête avec titre -->
    <div class="stats-header">
        <a href="{{ route('statistics') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Retour aux statistiques globales
        </a>
        <h1 class="stats-title">Statistiques pour {{ $city }}</h1>
        <p class="stats-subtitle">
            Analyse détaillée des prix alimentaires à {{ $city }} et comparaison avec la moyenne nationale
        </p>
    </div>

    <!-- Vue d'ensemble de la ville -->
    <div class="city-overview">
        <div class="city-card main-info">
            <div class="city-image" style="background-image: url('https://source.unsplash.com/1600x900/?{{ urlencode($city) }}')">
                <div class="city-overlay">
                    <div class="city-name">{{ $city }}</div>
                    <div class="city-population">~{{ number_format(rand(50000, 2000000)) }} habitants</div>
                </div>
            </div>
            <div class="city-stats">
                <div class="stat-item">
                    <div class="stat-value">{{ number_format(rand(5000, 15000)) }}</div>
                    <div class="stat-label">Prix enregistrés</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">{{ number_format(rand(50, 150)) }}</div>
                    <div class="stat-label">Magasins</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">{{ number_format(rand(200, 1000)) }}</div>
                    <div class="stat-label">Contributeurs</div>
                </div>
            </div>
        </div>
        
        <div class="city-card price-index">
            <h3 class="card-title">Indice des prix</h3>
            <div class="price-gauge">
                <div class="gauge-value">{{ $priceIndex = rand(85, 115) }}</div>
                <div class="gauge-bar">
                    <div class="gauge-fill" style="width: {{ min(($priceIndex / 150) * 100, 100) }}%"></div>
                </div>
                <div class="gauge-labels">
                    <span>Moins cher</span>
                    <span>Moyenne</span>
                    <span>Plus cher</span>
                </div>
            </div>
            <div class="price-comparison">
                <p>
                    @if($priceIndex < 100)
                        Les prix à {{ $city }} sont en moyenne <span class="text-success">{{ 100 - $priceIndex }}% moins chers</span> que la moyenne nationale.
                    @elseif($priceIndex > 100)
                        Les prix à {{ $city }} sont en moyenne <span class="text-danger">{{ $priceIndex - 100 }}% plus chers</span> que la moyenne nationale.
                    @else
                        Les prix à {{ $city }} sont équivalents à la moyenne nationale.
                    @endif
                </p>
            </div>
        </div>
    </div>

    <!-- Graphique des tendances de prix mensuels -->
    <div class="chart-container monthly-trends">
        <div class="chart-header">
            <h2 class="chart-title">Évolution des prix sur 12 mois à {{ $city }}</h2>
        </div>
        <div class="chart-body">
            <canvas id="monthlyTrendsChart"></canvas>
        </div>
    </div>
    
    <!-- Top des magasins les moins chers -->
    <div class="table-container top-stores">
        <div class="table-header">
            <h2 class="table-title">Magasins les moins chers à {{ $city }}</h2>
        </div>
        <div class="table-responsive">
            <table class="stats-table">
                <thead>
                    <tr>
                        <th>Rang</th>
                        <th>Magasin</th>
                        <th>Quartier</th>
                        <th>Indice de prix</th>
                        <th>Économie moyenne</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $stores = [
                            ['name' => 'Carrefour', 'district' => 'Centre Commercial', 'index' => rand(85, 95), 'savings' => rand(5, 15)],
                            ['name' => 'Lidl', 'district' => 'Périphérie Nord', 'index' => rand(82, 92), 'savings' => rand(8, 18)],
                            ['name' => 'Auchan', 'district' => 'Zone Commerciale Est', 'index' => rand(87, 97), 'savings' => rand(3, 13)],
                            ['name' => 'Intermarché', 'district' => 'Centre-Ville', 'index' => rand(88, 98), 'savings' => rand(2, 12)],
                            ['name' => 'E.Leclerc', 'district' => 'Zone Sud', 'index' => rand(84, 94), 'savings' => rand(6, 16)]
                        ];
                        usort($stores, function($a, $b) {
                            return $a['index'] - $b['index'];
                        });
                    @endphp
                    
                    @foreach($stores as $index => $store)
                    <tr>
                        <td class="rank">{{ $index + 1 }}</td>
                        <td class="store-name">{{ $store['name'] }}</td>
                        <td>{{ $store['district'] }}</td>
                        <td class="price-index">{{ $store['index'] }}</td>
                        <td class="savings"><span class="text-success">{{ $store['savings'] }}%</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Catégories de produits les plus économiques -->
    <div class="chart-container category-savings">
        <div class="chart-header">
            <h2 class="chart-title">Catégories de produits économiques à {{ $city }}</h2>
            <p class="chart-subtitle">Catégories où {{ $city }} propose des prix plus avantageux que la moyenne nationale</p>
        </div>
        <div class="chart-body">
            <canvas id="categorySavingsChart"></canvas>
        </div>
    </div>
    
    <!-- Appel à la contribution -->
    <div class="community-section">
        <div class="community-content">
            <h2 class="community-title">Contribuez aux données pour {{ $city }}</h2>
            <p class="community-desc">
                Aidez les habitants de {{ $city }} à économiser en partageant les prix que vous trouvez.
                Plus nous avons de données, plus les statistiques sont précises.
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

/* Styles pour la page de statistiques de ville */
.statistics-page {
    padding: 6rem 2rem 2rem;
}

.stats-header {
    text-align: center;
    margin-bottom: 3rem;
    animation: fadeInUp 1s ease-out;
}

.btn-back {
    display: inline-block;
    color: var(--text-secondary);
    text-decoration: none;
    margin-bottom: 1.5rem;
    transition: var(--transition-smooth);
    font-size: 0.9rem;
}

.btn-back:hover {
    color: var(--accent-primary);
    transform: translateX(-5px);
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

/* Vue d'ensemble de la ville */
.city-overview {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
    margin-bottom: 3rem;
    animation: fadeInUp 1s ease-out 0.2s both;
}

.city-card {
    background: var(--bg-card);
    border-radius: var(--radius-md);
    overflow: hidden;
    border: 1px solid rgba(255, 255, 255, 0.05);
    transition: var(--transition-smooth);
}

.city-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-soft);
}

.city-image {
    height: 200px;
    background-size: cover;
    background-position: center;
    position: relative;
}

.city-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 1.5rem;
    background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
    color: var(--text-primary);
}

.city-name {
    font-size: 1.8rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.city-population {
    font-size: 0.9rem;
    color: var(--text-secondary);
}

.city-stats {
    display: flex;
    padding: 1.5rem;
}

.stat-item {
    flex: 1;
    text-align: center;
}

.stat-value {
    font-size: 1.8rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    color: var(--accent-primary);
}

.stat-label {
    font-size: 0.9rem;
    color: var(--text-secondary);
}

/* Indice des prix */
.price-index {
    padding: 2rem;
}

.card-title {
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 1.5rem;
}

.price-gauge {
    margin-bottom: 2rem;
}

.gauge-value {
    font-size: 3rem;
    font-weight: 800;
    text-align: center;
    margin-bottom: 1rem;
    background: var(--gradient-primary);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.gauge-bar {
    height: 10px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 5px;
    overflow: hidden;
    margin-bottom: 0.5rem;
}

.gauge-fill {
    height: 100%;
    background: var(--gradient-primary);
    border-radius: 5px;
    transition: width 1s ease;
}

.gauge-labels {
    display: flex;
    justify-content: space-between;
    font-size: 0.8rem;
    color: var(--text-secondary);
}

.price-comparison {
    text-align: center;
    margin-top: 2rem;
}

.price-comparison p {
    color: var(--text-secondary);
    line-height: 1.6;
}

.text-success {
    color: var(--accent-secondary) !important;
}

.text-danger {
    color: #e74c3c !important;
}

/* Styles communs pour les autres sections */
.chart-container, .table-container {
    background: var(--bg-card);
    border-radius: var(--radius-md);
    margin-bottom: 3rem;
    padding: 2rem;
    border: 1px solid rgba(255, 255, 255, 0.05);
    animation: fadeInUp 1s ease-out 0.4s both;
}

.chart-header, .table-header {
    margin-bottom: 1.5rem;
}

.chart-title, .table-title {
    font-size: 1.5rem;
    font-weight: 600;
    margin: 0;
}

.chart-subtitle {
    color: var(--text-secondary);
    font-size: 0.9rem;
    margin-top: 0.5rem;
}

.chart-body {
    height: 400px;
    position: relative;
}

/* Tableau des magasins */
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

.stats-table .store-name {
    font-weight: 600;
}

.stats-table .price-index {
    font-weight: 600;
    color: var(--accent-primary);
}

.stats-table .savings {
    font-weight: 600;
}

/* Section de participation communautaire */
.community-section {
    background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('https://images.unsplash.com/photo-1542838132-92c53300491e?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1374&q=80');
    background-size: cover;
    background-position: center;
    border-radius: var(--radius-lg);
    padding: 4rem 2rem;
    margin-bottom: 2rem;
    text-align: center;
    position: relative;
    animation: fadeInUp 1s ease-out 0.8s both;
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
    max-width: 700px;
    margin-left: auto;
    margin-right: auto;
}

.community-actions {
    display: flex;
    justify-content: center;
    gap: 1.5rem;
    flex-wrap: wrap;
}

.btn-scan, .btn-contribute {
    padding: 1rem 2rem;
    border-radius: var(--radius-md);
    text-decoration: none;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    transition: var(--transition-smooth);
}

.btn-scan {
    background: var(--gradient-primary);
    color: white;
}

.btn-scan:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 20px rgba(230, 126, 34, 0.3);
}

.btn-contribute {
    background: rgba(255, 255, 255, 0.1);
    color: var(--text-primary);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.btn-contribute:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: translateY(-3px);
}

/* Animations */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Responsive */
@media (max-width: 992px) {
    .city-overview {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .statistics-page {
        padding: 5rem 1rem 1rem;
    }
    
    .stats-title {
        font-size: 2.5rem;
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
    // Configuration du graphique des tendances mensuelles
    const monthlyCtx = document.getElementById('monthlyTrendsChart').getContext('2d');
    
    // Mois de l'année
    const months = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'];
    
    // Génération de données aléatoires pour {{ $city }} et la moyenne nationale
    const cityData = [];
    const nationalData = [];
    
    let cityValue = 100;
    let nationalValue = 100;
    
    for (let i = 0; i < 12; i++) {
        // Variation aléatoire entre -3% et +3%
        const cityVariation = (Math.random() * 6) - 3;
        const nationalVariation = (Math.random() * 4) - 2;
        
        cityValue += cityVariation;
        nationalValue += nationalVariation;
        
        cityData.push(cityValue);
        nationalData.push(nationalValue);
    }
    
    // Configuration du graphique
    const monthlyChart = new Chart(monthlyCtx, {
        type: 'line',
        data: {
            labels: months,
            datasets: [
                {
                    label: '{{ $city }}',
                    data: cityData,
                    backgroundColor: 'rgba(230, 126, 34, 0.2)',
                    borderColor: '#E67E22',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Moyenne nationale',
                    data: nationalData,
                    backgroundColor: 'rgba(52, 152, 219, 0.2)',
                    borderColor: '#3498db',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        color: 'rgba(255, 255, 255, 0.7)',
                        font: { family: 'Inter, sans-serif' }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleFont: { family: 'Inter, sans-serif' },
                    bodyFont: { family: 'Inter, sans-serif' }
                }
            },
            scales: {
                x: {
                    grid: { color: 'rgba(255, 255, 255, 0.05)' },
                    ticks: { color: 'rgba(255, 255, 255, 0.7)' }
                },
                y: {
                    grid: { color: 'rgba(255, 255, 255, 0.05)' },
                    ticks: { color: 'rgba(255, 255, 255, 0.7)' }
                }
            }
        }
    });
    
    // Configuration du graphique des économies par catégorie
    const categoryCtx = document.getElementById('categorySavingsChart').getContext('2d');
    
    // Catégories de produits
    const categories = ['Fruits & Légumes', 'Produits laitiers', 'Viandes', 'Boissons', 'Épicerie', 'Surgelés'];
    
    // Génération de données aléatoires pour les économies par catégorie
    const savings = categories.map(() => Math.floor(Math.random() * 15) + 2);
    
    // Configuration du graphique
    const categoryChart = new Chart(categoryCtx, {
        type: 'bar',
        data: {
            labels: categories,
            datasets: [{
                label: 'Économie moyenne (%)',
                data: savings,
                backgroundColor: [
                    'rgba(230, 126, 34, 0.7)',
                    'rgba(76, 175, 80, 0.7)',
                    'rgba(52, 152, 219, 0.7)',
                    'rgba(155, 89, 182, 0.7)',
                    'rgba(241, 196, 15, 0.7)',
                    'rgba(231, 76, 60, 0.7)'
                ],
                borderColor: [
                    '#E67E22',
                    '#4CAF50',
                    '#3498db',
                    '#9b59b6',
                    '#f1c40f',
                    '#e74c3c'
                ],
                borderWidth: 2
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
                    titleFont: { family: 'Inter, sans-serif' },
                    bodyFont: { family: 'Inter, sans-serif' },
                    callbacks: {
                        label: function(context) {
                            return 'Économie : ' + context.raw + '%';
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { color: 'rgba(255, 255, 255, 0.05)' },
                    ticks: { color: 'rgba(255, 255, 255, 0.7)' }
                },
                y: {
                    grid: { color: 'rgba(255, 255, 255, 0.05)' },
                    ticks: { color: 'rgba(255, 255, 255, 0.7)' },
                    beginAtZero: true
                }
            }
        }
    });
});
</script>
@endsection 