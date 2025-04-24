<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StatisticsController extends Controller
{
    /**
     * Display the statistics page with price data
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Dans une application réelle, ces données viendraient de la base de données
        // Pour cette démo, nous simulons les données
        
        // Nombre total de prix enregistrés
        $totalPrices = mt_rand(50000, 100000);
        
        // Nombre de prix enregistrés aujourd'hui
        $todayPrices = mt_rand(500, 2000);
        
        // Dernière mise à jour
        $lastUpdate = Carbon::now()->subMinutes(mt_rand(5, 60))->format('H:i');
        
        // Statistiques par ville
        $cityStats = $this->getCityStats();
        
        return view('statistics.index', compact(
            'totalPrices',
            'todayPrices',
            'lastUpdate',
            'cityStats'
        ));
    }
    
    /**
     * Génère des statistiques simulées par ville
     *
     * @return array
     */
    private function getCityStats()
    {
        $cities = [
            'Paris', 'Lyon', 'Marseille', 'Toulouse', 'Nice', 
            'Nantes', 'Strasbourg', 'Montpellier', 'Bordeaux', 'Lille',
            'Rennes', 'Reims', 'Le Havre', 'Saint-Étienne', 'Toulon'
        ];
        
        $stats = [];
        
        // Génération de données aléatoires pour chaque ville
        foreach ($cities as $city) {
            $count = mt_rand(1000, 10000);
            $stats[] = [
                'city' => $city,
                'count' => $count,
                'last_update' => Carbon::now()->subDays(mt_rand(0, 7))->toDateTimeString()
            ];
        }
        
        // Tri par nombre de prix décroissant
        usort($stats, function($a, $b) {
            return $b['count'] - $a['count'];
        });
        
        return $stats;
    }
    
    /**
     * Page pour afficher les statistiques détaillées par ville
     * 
     * @param string $city
     * @return \Illuminate\View\View
     */
    public function cityDetail($city)
    {
        // Cette méthode serait implémentée pour afficher des statistiques détaillées par ville
        // Pour l'instant, elle n'est pas utilisée
        
        return view('statistics.city', [
            'city' => $city,
            // Autres données...
        ]);
    }
    
    /**
     * API endpoint pour obtenir les données de statistiques au format JSON
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiStats()
    {
        // Cette méthode serait implémentée pour fournir des données au format JSON
        // pour les applications mobiles ou les requêtes AJAX
        
        return response()->json([
            'total_prices' => mt_rand(50000, 100000),
            'today_prices' => mt_rand(500, 2000),
            'city_stats' => $this->getCityStats(),
            'updated_at' => Carbon::now()->toIso8601String()
        ]);
    }
} 