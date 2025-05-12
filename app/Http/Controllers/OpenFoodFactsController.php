<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;

class OpenFoodFactsController extends Controller
{
    protected $priceApiUrl = 'https://prices.openfoodfacts.org/api/v1/prices';
    protected $productApiUrl = 'https://world.openfoodfacts.org/api/v0/product/';

    public function index()
    {
        return view('products.search');
    }

    public function fetch(Request $request)
    {
        $request->validate([
            'product_code' => 'required|string'
        ]);
        
        $productCode = $request->input('product_code');

        try {
            // Fetch price data with ALL location fields
            $priceResponse = Http::withOptions([
                'verify' => false,
            ])->get($this->priceApiUrl, [
                'product_code' => $productCode,
                'fields' => implode(',', [
                    'price',
                    'date',
                    'location.osm_name',
                    'location.osm_type',
                    'location.osm_id',
                    'location.osm_address_street',
                    'location.osm_address_city',
                    'location.osm_address_country',
                    'location.osm_address_postcode',
                    'location.osm_address_housenumber',
                    'location.osm_address_suburb',
                    'location.osm_address_district',
                    'location.osm_address_neighbourhood',
                    'location.name',
                    'location.display_name',
                    'location.osm_address_street',
                    'location.osm_address_road'
                ])
            ]);
            
            // Fetch product data
            $productResponse = Http::withOptions([
                'verify' => false,
            ])->get($this->productApiUrl . $productCode . '.json');

            if ($priceResponse->successful() && $productResponse->successful()) {
                $priceData = $priceResponse->json();
                $productData = $productResponse->json();

                // Debug complet de la première location
                if (isset($priceData['items']) && !empty($priceData['items'])) {
                    $firstItem = $priceData['items'][0];
                    \Log::info('Détails complets de la première location:', [
                        'location_complete' => $firstItem['location'] ?? [],
                        'osm_name' => $firstItem['location']['osm_name'] ?? 'non défini',
                        'osm_display_name' => $firstItem['location']['osm_display_name'] ?? 'non défini',
                        'street' => $firstItem['location']['osm_address_street'] ?? 'non défini',
                        'road' => $firstItem['location']['osm_address_road'] ?? 'non défini',
                        'housenumber' => $firstItem['location']['osm_address_housenumber'] ?? 'non défini',
                        'suburb' => $firstItem['location']['osm_address_suburb'] ?? 'non défini',
                        'city' => $firstItem['location']['osm_address_city'] ?? 'non défini'
                    ]);
                }

                $prices = $this->processPrices($priceData['items']);
                
                // Debug des prix traités
                \Log::info('Prix traités:', ['prices' => $prices]);
                
                $stats = $this->calculateStats($prices);
                $productInfo = $this->getProductInfo($productData);

                return view('products.show', compact('prices', 'productCode', 'stats', 'productInfo'));
            } else {
                \Log::error('API Response failed:', [
                    'price_status' => $priceResponse->status(),
                    'price_body' => $priceResponse->body(),
                    'product_status' => $productResponse->status()
                ]);
                return back()->with('error', 'Failed to fetch product data');
            }
        } catch (\Exception $e) {
            \Log::error('Exception:', ['message' => $e->getMessage()]);
            return back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    private function processPrices($items)
    {
        return collect($items)->map(function ($item) {
            $location = $item['location'] ?? [];
            
            // Construction de l'adresse complète
            $address = '';
            
            if (!empty($location['osm_display_name'])) {
                $parts = explode(',', $location['osm_display_name']);
                $relevantParts = array_slice($parts, 0, 5);
                $address = implode(', ', array_map(function($part) {
                    return trim($part);
                }, $relevantParts));
            }
            
            if (empty($address)) {
                $addressParts = array_filter([
                    $location['osm_name'],
                    $location['osm_address_housenumber'],
                    $location['osm_address_street'] ?? $location['osm_address_road'],
                    $location['osm_address_suburb'],
                    $location['osm_address_district']
                ]);
                $address = implode(', ', $addressParts);
            }

            // Récupérer les coordonnées GPS
            $lat = $location['osm_lat'] ?? null;
            $lon = $location['osm_lon'] ?? null;
            
            // Construire l'URL Google Maps
            $mapsUrl = '';
            if ($lat && $lon) {
                // Si on a les coordonnées GPS, les utiliser
                $mapsUrl = "https://www.google.com/maps/dir/?api=1&destination={$lat},{$lon}";
            } else {
                // Sinon utiliser l'adresse
                $mapsUrl = "https://www.google.com/maps/dir/?api=1&destination=" . urlencode($address);
            }
            
            return [
                'store' => $location['osm_name'] ?? $location['name'] ?? 'Unknown',
                'price' => $item['price'],
                'date' => $item['date'],
                'address' => $address,
                'maps_url' => $mapsUrl,
                'city' => $location['osm_address_city'] ?? '',
                'country' => $location['osm_address_country'] ?? 'France',
            ];
        })->sortByDesc('date')->values()->all();
    }

    private function calculateStats($prices)
    {
        $priceValues = array_column($prices, 'price');
        return [
            'min' => min($priceValues),
            'max' => max($priceValues),
            'avg' => array_sum($priceValues) / count($priceValues),
            'count' => count($priceValues)
        ];
    }

    private function getProductInfo($data)
    {
        $product = $data['product'] ?? [];
        return [
            'product_name' => $product['product_name'] ?? 'Unknown Product',
            'image_url' => $product['image_front_url'] ?? $product['image_url'] ?? 'https://via.placeholder.com/400x400',
            'product_quantity' => $product['quantity'] ?? 'Unknown',
            'product_quantity_unit' => $product['quantity_unit'] ?? ''
        ];
    }

    public function statistics()
    {
        try {
            // Fetch global statistics
            $response = Http::withOptions([
                'verify' => false,
            ])->get('https://prices.openfoodfacts.org/api/v1/prices', [
                'count' => true
            ]);

            $totalPrices = 0;
            $todayPrices = 0;
            $cityStats = [];

            if ($response->successful()) {
                $data = $response->json();
                $totalPrices = $data['count'] ?? 0;
                
                // Get today's prices
                $todayResponse = Http::withOptions([
                    'verify' => false,
                ])->get('https://prices.openfoodfacts.org/api/v1/prices', [
                    'date_start' => date('Y-m-d'),
                    'count' => true
                ]);

                if ($todayResponse->successful()) {
                    $todayData = $todayResponse->json();
                    $todayPrices = $todayData['count'] ?? 0;
                }

                // Get prices by city
                $cityResponse = Http::withOptions([
                    'verify' => false,
                ])->get('https://prices.openfoodfacts.org/api/v1/prices', [
                    'fields' => 'location.osm_address_city',
                    'group_by' => 'location.osm_address_city',
                    'sort_by' => 'count:desc',
                    'limit' => 100
                ]);

                if ($cityResponse->successful()) {
                    $cityData = $cityResponse->json();
                    $cityStats = collect($cityData['aggregations']['location.osm_address_city'] ?? [])
                        ->map(function ($item, $city) {
                            return [
                                'city' => $city,
                                'count' => $item['doc_count'] ?? 0,
                                'last_update' => $item['last_update'] ?? 'N/A'
                            ];
                        })
                        ->values()
                        ->all();
                }
            }

            return view('statistics.index', [
                'totalPrices' => $totalPrices,
                'todayPrices' => $todayPrices,
                'cityStats' => $cityStats,
                'lastUpdate' => now()->format('Y-m-d H:i:s')
            ]);

        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Rechercher des produits par nom via API.
     * Cette méthode est utilisée pour l'autocomplétion.
     */
    public function apiSearchByName(Request $request)
    {
        $query = $request->input('query');
        
        if (strlen($query) < 1) {
            return response()->json([]);
        }
        
        // Clé de cache spécifique à cette requête
        $cacheKey = 'product_search_' . md5($query);
        
        // Vérifier si les résultats sont en cache
        if (Cache::has($cacheKey)) {
            return response()->json(Cache::get($cacheKey));
        }
        
        // Données de démonstration améliorées pour une meilleure expérience utilisateur
        $demoProducts = [
            [
                'name' => 'Nutella - Ferrero - 400g',
                'code' => '3017620422003',
                'image' => 'https://images.openfoodfacts.org/images/products/301/762/042/2003/front_fr.429.400.jpg',
                'brand' => 'Nutella'
            ],
            [
                'name' => 'Nutella B-ready - Ferrero - 132g',
                'code' => '8000500267776',
                'image' => 'https://images.openfoodfacts.org/images/products/800/050/026/7776/front_fr.116.400.jpg',
                'brand' => 'Ferrero'
            ],
            [
                'name' => 'Nutella Biscuits - 304g',
                'code' => '7622201269388',
                'image' => 'https://images.openfoodfacts.org/images/products/762/220/126/9388/front_fr.186.400.jpg',
                'brand' => 'Nutella'
            ],
            [
                'name' => 'Nutella petit déjeuner pocket - 40g',
                'code' => '8000500267882',
                'image' => 'https://images.openfoodfacts.org/images/products/800/050/026/7882/front_fr.35.400.jpg',
                'brand' => 'Nutella'
            ],
            [
                'name' => 'Biscuits Petit Beurre - Lu - 200g',
                'code' => '7622210585448',
                'image' => 'https://images.openfoodfacts.org/images/products/762/221/058/5448/front_fr.427.400.jpg',
                'brand' => 'Lu'
            ],
            [
                'name' => 'LU Cracottes complètes - 250g',
                'code' => '7622210713780',
                'image' => 'https://images.openfoodfacts.org/images/products/762/221/071/3780/front_fr.166.400.jpg',
                'brand' => 'Lu'
            ],
            [
                'name' => 'Sablés Petit Beurre - Lu - 200g',
                'code' => '3175681851887',
                'image' => 'https://images.openfoodfacts.org/images/products/317/568/185/1887/front_fr.79.400.jpg',
                'brand' => 'Lu'
            ],
            [
                'name' => 'Chocolat au lait - Milka - 100g',
                'code' => '7622200003471',
                'image' => 'https://images.openfoodfacts.org/images/products/762/220/000/3471/front_fr.286.400.jpg',
                'brand' => 'Milka'
            ],
            [
                'name' => 'Chocolat noisettes entières - Milka - 100g',
                'code' => '7622200726516',
                'image' => 'https://images.openfoodfacts.org/images/products/762/220/072/6516/front_fr.59.400.jpg',
                'brand' => 'Milka'
            ],
            [
                'name' => 'Pizza Margherita - Buitoni - 335g',
                'code' => '7613034383648',
                'image' => 'https://images.openfoodfacts.org/images/products/761/303/438/3648/front_fr.148.400.jpg',
                'brand' => 'Buitoni'
            ],
            [
                'name' => 'Pizza 4 fromages - Buitoni - 390g',
                'code' => '7613035220133',
                'image' => 'https://images.openfoodfacts.org/images/products/761/303/522/0133/front_fr.84.400.jpg',
                'brand' => 'Buitoni'
            ],
            [
                'name' => 'Lait demi-écrémé - Lactel - 1L',
                'code' => '3428274370119',
                'image' => 'https://images.openfoodfacts.org/images/products/342/827/437/0119/front_fr.175.400.jpg',
                'brand' => 'Lactel'
            ],
            [
                'name' => 'Coca-Cola Original - 1.5L',
                'code' => '5449000000996',
                'image' => 'https://images.openfoodfacts.org/images/products/544/900/000/0996/front_fr.427.400.jpg',
                'brand' => 'Coca-Cola'
            ],
            [
                'name' => 'Coca-Cola Zero - 1.5L',
                'code' => '5449000131805',
                'image' => 'https://images.openfoodfacts.org/images/products/544/900/013/1805/front_fr.138.400.jpg',
                'brand' => 'Coca-Cola'
            ],
            [
                'name' => 'Eau minérale naturelle - Evian - 1.5L',
                'code' => '3068320070002',
                'image' => 'https://images.openfoodfacts.org/images/products/306/832/007/0002/front_fr.298.400.jpg',
                'brand' => 'Evian'
            ],
            [
                'name' => 'Petits pois et carottes - Cassegrain - 400g',
                'code' => '3083680085304',
                'image' => 'https://images.openfoodfacts.org/images/products/308/368/008/5304/front_fr.188.400.jpg',
                'brand' => 'Cassegrain'
            ],
            [
                'name' => 'Yaourt nature - Danone - 4x125g',
                'code' => '3033490004521',
                'image' => 'https://images.openfoodfacts.org/images/products/303/349/000/4521/front_fr.295.400.jpg',
                'brand' => 'Danone'
            ],
            [
                'name' => 'Activia nature - Danone - 4x125g',
                'code' => '3033490004842',
                'image' => 'https://images.openfoodfacts.org/images/products/303/349/000/4842/front_fr.106.400.jpg',
                'brand' => 'Danone'
            ],
            [
                'name' => 'Riz basmati - Uncle Ben\'s - 500g',
                'code' => '5410673005427', 
                'image' => 'https://images.openfoodfacts.org/images/products/541/067/300/5427/front_fr.178.400.jpg',
                'brand' => 'Uncle Ben\'s'
            ],
            [
                'name' => 'Pâtes Coquillettes - Panzani - 500g',
                'code' => '3038359008733',
                'image' => 'https://images.openfoodfacts.org/images/products/303/835/900/8733/front_fr.77.400.jpg',
                'brand' => 'Panzani'
            ]
        ];
        
        // Résultats à partir des données de démo (recherche locale)
        $localResults = [];
        
        // Première passe : recherche directe dans les données de démo
        foreach ($demoProducts as $product) {
            // Recherche insensible à la casse dans le nom ou la marque
            if (stripos($product['name'], $query) !== false || 
                stripos($product['brand'], $query) !== false) {
                $localResults[] = $product;
            }
        }
        
        // Deuxième passe si nécessaire : recherche par mots-clés dans les données de démo
        if (empty($localResults) && strlen($query) > 2) {
            $keywords = explode(' ', $query);
            
            foreach ($demoProducts as $product) {
                foreach ($keywords as $keyword) {
                    if (strlen($keyword) > 2 && 
                        (stripos($product['name'], $keyword) !== false || 
                        stripos($product['brand'], $keyword) !== false)) {
                        $localResults[] = $product;
                        break; // Éviter les doublons
                    }
                }
            }
        }
        
        // Si nous avons suffisamment de résultats locaux, les utiliser directement
        if (count($localResults) >= 5) {
            // Mettre en cache les résultats pour les futures requêtes
            Cache::put($cacheKey, $localResults, 3600); // Cache pendant 1 heure
            return response()->json($localResults);
        }
        
        // Sinon, essayer avec l'API externe (avec un court timeout)
        try {
            $client = new Client();
            $response = $client->request('GET', 'https://fr.openfoodfacts.org/cgi/search.pl', [
                'timeout' => 3, // Timeout court pour éviter d'attendre trop longtemps
                'query' => [
                    'search_terms' => $query,
                    'search_simple' => 1,
                    'action' => 'process',
                    'json' => 1,
                    'page_size' => 10
                ]
            ]);
            
            if ($response->getStatusCode() === 200) {
                $apiData = json_decode($response->getBody(), true);
                
                // Formater les résultats de l'API
                $apiResults = [];
                if (isset($apiData['products']) && is_array($apiData['products'])) {
                    foreach ($apiData['products'] as $product) {
                        // Ne pas inclure les produits sans nom ou sans image
                        if (empty($product['product_name']) || empty($product['image_url'])) {
                            continue;
                        }
                        
                        $apiResults[] = [
                            'name' => $product['product_name'],
                            'code' => $product['code'],
                            'image' => $product['image_url'] ?? '',
                            'brand' => $product['brands'] ?? 'Marque inconnue'
                        ];
                    }
                }
                
                // Fusionner les résultats locaux et API, en évitant les doublons
                $mergedResults = $localResults;
                $existingCodes = array_column($localResults, 'code');
                
                foreach ($apiResults as $apiProduct) {
                    if (!in_array($apiProduct['code'], $existingCodes)) {
                        $mergedResults[] = $apiProduct;
                    }
                }
                
                // Mettre en cache les résultats fusionnés
                Cache::put($cacheKey, $mergedResults, 3600); // Cache pendant 1 heure
                
                return response()->json($mergedResults);
            }
        } catch (\Exception $e) {
            // En cas d'erreur avec l'API, utiliser uniquement les résultats locaux
            Log::error('Erreur API OpenFoodFacts: ' . $e->getMessage());
        }
        
        // Si l'API échoue, utiliser les résultats locaux
        Cache::put($cacheKey, $localResults, 3600); // Cache pendant 1 heure
        return response()->json($localResults);
    }

    /**
     * Page de recherche de produits par nom
     */
    public function searchByName(Request $request)
    {
        $query = $request->input('query');
        
        if (empty($query)) {
            return redirect()->route('products.search')
                ->with('error', 'Veuillez saisir une recherche.');
        }
        
        // Clé de cache spécifique à cette requête
        $cacheKey = 'product_search_results_' . md5($query);
        
        // Vérifier si les résultats sont en cache
        if (Cache::has($cacheKey)) {
            return view('products.search_results', [
                'products' => Cache::get($cacheKey),
                'query' => $query
            ]);
        }
        
        // Utilisation du même jeu de données amélioré que pour l'autocomplétion
        $demoProducts = [
            [
                'id' => '3017620422003',
                'name' => 'Nutella - Ferrero - 400g',
                'image' => 'https://images.openfoodfacts.org/images/products/301/762/042/2003/front_fr.429.400.jpg',
                'brand' => 'FERRERO, NUTELLA',
                'nutriscore' => 'e',
                'quantity' => '400 g',
                'categories' => 'Petit-déjeuners, Produits à tartiner, Produits à tartiner sucrés, Pâtes à tartiner, Pâtes à tartiner aux noisettes, Pâtes à tartiner au chocolat',
                'ingredients' => 'Sucre, huile de palme, noisettes 13%, cacao maigre 7,4%, lait écrémé en poudre 6,6%, lactosérum en poudre, émulsifiants : lécithines (soja), vanilline.'
            ],
            [
                'id' => '8000500267776',
                'name' => 'Nutella B-ready - Ferrero - 132g',
                'image' => 'https://images.openfoodfacts.org/images/products/800/050/026/7776/front_fr.116.400.jpg',
                'brand' => 'FERRERO, NUTELLA',
                'nutriscore' => 'e',
                'quantity' => '132 g (6 x 22 g)',
                'categories' => 'Snacks, Snacks sucrés, Biscuits et gâteaux, Biscuits, Biscuits au chocolat, Gaufrettes',
                'ingredients' => 'Pâte à tartiner aux noisettes et au cacao, farine de blé, sucre, levure, émulsifiants, sel, vanilline, lait écrémé en poudre, arômes.'
            ],
            [
                'id' => '7622201269388',
                'name' => 'Nutella Biscuits - 304g',
                'image' => 'https://images.openfoodfacts.org/images/products/762/220/126/9388/front_fr.186.400.jpg',
                'brand' => 'FERRERO, NUTELLA',
                'nutriscore' => 'e',
                'quantity' => '304 g',
                'categories' => 'Biscuits, Biscuits fourrés, Biscuits au chocolat',
                'ingredients' => 'Farine de blé 30%, sucre, huile de palme, noisettes 9,9%, cacao maigre 4,9%, lait écrémé en poudre, beurre concentré, lactosérum en poudre, émulsifiants : lécithines'
            ],
            [
                'id' => '8000500267882',
                'name' => 'Nutella petit déjeuner pocket - 40g',
                'image' => 'https://images.openfoodfacts.org/images/products/800/050/026/7882/front_fr.35.400.jpg',
                'brand' => 'FERRERO, NUTELLA',
                'nutriscore' => 'e',
                'quantity' => '40 g',
                'categories' => 'Snacks, Goûters, Produits à tartiner, Produits à tartiner sucrés',
                'ingredients' => 'Sucre, huile de palme, noisettes 13%, cacao maigre 7,4%, lait écrémé en poudre 6,6%, petit-lait en poudre, émulsifiants : lécithines (soja), vanilline.'
            ],
            [
                'id' => '7622210585448',
                'name' => 'Biscuits Petit Beurre - Lu - 200g',
                'image' => 'https://images.openfoodfacts.org/images/products/762/221/058/5448/front_fr.427.400.jpg',
                'brand' => 'LU, PETIT BEURRE',
                'nutriscore' => 'c',
                'quantity' => '200 g',
                'categories' => 'Snacks, Snacks sucrés, Biscuits et gâteaux, Biscuits, Biscuits secs',
                'ingredients' => 'Farine de BLÉ 78,8%, sucre, BEURRE concentré 7,6%, LACTOSE et protéines de LAIT, poudre à lever, sel, OEUFS, arôme, poudre de LAIT écrémé.'
            ],
            [
                'id' => '7622210713780',
                'name' => 'LU Cracottes complètes - 250g',
                'image' => 'https://images.openfoodfacts.org/images/products/762/221/071/3780/front_fr.166.400.jpg',
                'brand' => 'LU',
                'nutriscore' => 'a',
                'quantity' => '250 g',
                'categories' => 'Céréales et dérivés, Pains, Pains croustillants, Cracottes',
                'ingredients' => 'Farine de blé 59,3%, farine de blé complet 29,8%, levain de blé 7% (farine complète de blé, eau), son de blé 2,1%, sel, levure, extrait de orge maltée.'
            ],
            [
                'id' => '7622200003471',
                'name' => 'Chocolat au lait - Milka - 100g',
                'image' => 'https://images.openfoodfacts.org/images/products/762/220/000/3471/front_fr.286.400.jpg',
                'brand' => 'MILKA',
                'nutriscore' => 'd',
                'quantity' => '100 g',
                'categories' => 'Snacks, Snacks sucrés, Confiseries, Chocolats, Tablettes de chocolat, Tablettes de chocolat au lait',
                'ingredients' => 'Sucre, beurre de cacao, LAIT écrémé en poudre, pâte de cacao, LACTOSÉRUM en poudre, matière grasse de LAIT, émulsifiants (lécithine de SOJA), arôme, NOISETTES.'
            ],
            [
                'id' => '7622200726516',
                'name' => 'Chocolat noisettes entières - Milka - 100g',
                'image' => 'https://images.openfoodfacts.org/images/products/762/220/072/6516/front_fr.59.400.jpg',
                'brand' => 'MILKA',
                'nutriscore' => 'd',
                'quantity' => '100 g',
                'categories' => 'Snacks, Snacks sucrés, Confiseries, Chocolats, Tablettes de chocolat, Tablettes de chocolat au lait',
                'ingredients' => 'Sucre, NOISETTES 21%, beurre de cacao, LAIT écrémé en poudre, pâte de cacao, matière grasse de LAIT, LACTOSERUM en poudre, émulsifiants (lécithine de SOJA), arôme.'
            ],
            [
                'id' => '7613034383648',
                'name' => 'Pizza Margherita - Buitoni - 335g',
                'image' => 'https://images.openfoodfacts.org/images/products/761/303/438/3648/front_fr.148.400.jpg',
                'brand' => 'BUITONI',
                'nutriscore' => 'c',
                'quantity' => '335 g',
                'categories' => 'Plats préparés, Pizzas',
                'ingredients' => 'Farine de BLÉ, eau, purée de tomates 14%, mozzarella 10%, huile de tournesol, levure, sel, sucre, herbes aromatiques, épices.'
            ],
            [
                'id' => '7613035220133',
                'name' => 'Pizza 4 fromages - Buitoni - 390g',
                'image' => 'https://images.openfoodfacts.org/images/products/761/303/522/0133/front_fr.84.400.jpg',
                'brand' => 'BUITONI',
                'nutriscore' => 'd',
                'quantity' => '390 g',
                'categories' => 'Plats préparés, Pizzas, Pizzas aux fromages',
                'ingredients' => 'Farine de BLÉ, eau, fromages 18% (mozzarella, emmental, gorgonzola, chèvre), tomates concassées, huile de tournesol, levure, sel, herbes aromatiques.'
            ],
            [
                'id' => '3428274370119',
                'name' => 'Lait demi-écrémé - Lactel - 1L',
                'image' => 'https://images.openfoodfacts.org/images/products/342/827/437/0119/front_fr.175.400.jpg',
                'brand' => 'LACTEL',
                'nutriscore' => 'a',
                'quantity' => '1 L',
                'categories' => 'Produits laitiers, Laits, Laits de vache, Laits demi-écrémés',
                'ingredients' => 'LAIT demi-écrémé de vache standardisé en matière grasse.'
            ],
            [
                'id' => '5449000000996',
                'name' => 'Coca-Cola Original - 1.5L',
                'image' => 'https://images.openfoodfacts.org/images/products/544/900/000/0996/front_fr.427.400.jpg',
                'brand' => 'COCA-COLA',
                'nutriscore' => 'e',
                'quantity' => '1,5 L',
                'categories' => 'Boissons, Boissons gazeuses, Sodas, Sodas au cola',
                'ingredients' => 'Eau gazéifiée, sucre, colorant (caramel E150d), acidifiants (acide phosphorique, acide citrique), arômes naturels (extraits végétaux), caféine.'
            ],
            [
                'id' => '5449000131805',
                'name' => 'Coca-Cola Zero - 1.5L',
                'image' => 'https://images.openfoodfacts.org/images/products/544/900/013/1805/front_fr.138.400.jpg',
                'brand' => 'COCA-COLA',
                'nutriscore' => 'b',
                'quantity' => '1,5 L',
                'categories' => 'Boissons, Boissons gazeuses, Sodas, Sodas au cola, Sodas light',
                'ingredients' => 'Eau gazéifiée, colorant (caramel E150d), acidifiants (acide phosphorique, acide citrique), édulcorants (aspartame, acésulfame K), arômes naturels, caféine. Contient une source de phénylalanine.'
            ],
            [
                'id' => '3068320070002',
                'name' => 'Eau minérale naturelle - Evian - 1.5L',
                'image' => 'https://images.openfoodfacts.org/images/products/306/832/007/0002/front_fr.298.400.jpg',
                'brand' => 'EVIAN',
                'nutriscore' => 'a',
                'quantity' => '1,5 L',
                'categories' => 'Boissons, Eaux, Eaux minérales, Eaux minérales naturelles',
                'ingredients' => 'Eau minérale naturelle.'
            ],
            [
                'id' => '3083680085304',
                'name' => 'Petits pois et carottes - Cassegrain - 400g',
                'image' => 'https://images.openfoodfacts.org/images/products/308/368/008/5304/front_fr.188.400.jpg',
                'brand' => 'CASSEGRAIN',
                'nutriscore' => 'a',
                'quantity' => '400 g (280 g net égoutté)',
                'categories' => 'Conserves, Légumes en conserve, Mélanges de légumes en conserve',
                'ingredients' => 'Petits pois 60%, eau, carottes 22%, sucre, sel.'
            ],
            [
                'id' => '3033490004521',
                'name' => 'Yaourt nature - Danone - 4x125g',
                'image' => 'https://images.openfoodfacts.org/images/products/303/349/000/4521/front_fr.295.400.jpg',
                'brand' => 'DANONE',
                'nutriscore' => 'a',
                'quantity' => '4 x 125 g',
                'categories' => 'Produits laitiers, Produits fermentés, Yaourts, Yaourts nature',
                'ingredients' => 'LAIT entier, protéines de LAIT, ferments lactiques.'
            ],
            [
                'id' => '3033490004842',
                'name' => 'Activia nature - Danone - 4x125g',
                'image' => 'https://images.openfoodfacts.org/images/products/303/349/000/4842/front_fr.106.400.jpg',
                'brand' => 'DANONE, ACTIVIA',
                'nutriscore' => 'a',
                'quantity' => '4 x 125 g',
                'categories' => 'Produits laitiers, Produits fermentés, Yaourts, Yaourts nature, Yaourts au bifidus',
                'ingredients' => 'LAIT écrémé, LAIT écrémé concentré ou en poudre, crème (LAIT), ferments lactiques (dont Bifidus).'
            ],
            [
                'id' => '5410673005427',
                'name' => 'Riz basmati - Uncle Ben\'s - 500g',
                'image' => 'https://images.openfoodfacts.org/images/products/541/067/300/5427/front_fr.178.400.jpg',
                'brand' => 'UNCLE BEN\'S',
                'nutriscore' => 'a',
                'quantity' => '500 g',
                'categories' => 'Aliments et boissons à base de végétaux, Aliments d\'origine végétale, Céréales et pommes de terre, Céréales et dérivés, Riz',
                'ingredients' => 'Riz basmati.'
            ],
            [
                'id' => '3038359008733',
                'name' => 'Pâtes Coquillettes - Panzani - 500g',
                'image' => 'https://images.openfoodfacts.org/images/products/303/835/900/8733/front_fr.77.400.jpg',
                'brand' => 'PANZANI',
                'nutriscore' => 'a',
                'quantity' => '500 g',
                'categories' => 'Aliments et boissons à base de végétaux, Aliments d\'origine végétale, Céréales et pommes de terre, Céréales et dérivés, Pâtes alimentaires',
                'ingredients' => 'Semoule de BLÉ dur de qualité supérieure.'
            ]
        ];
        
        // Résultats à partir des données de démo (recherche locale)
        $localResults = [];
        
        // Première passe : recherche directe
        foreach ($demoProducts as $product) {
            // Recherche insensible à la casse dans le nom ou la marque
            if (stripos($product['name'], $query) !== false || 
                stripos($product['brand'], $query) !== false) {
                $localResults[] = $product;
            }
        }
        
        // Deuxième passe si nécessaire : recherche par mots-clés
        if (empty($localResults) && strlen($query) > 2) {
            $keywords = explode(' ', $query);
            
            foreach ($demoProducts as $product) {
                foreach ($keywords as $keyword) {
                    if (strlen($keyword) > 2 && 
                        (stripos($product['name'], $keyword) !== false || 
                        stripos($product['brand'], $keyword) !== false ||
                        stripos($product['categories'], $keyword) !== false)) {
                        $localResults[] = $product;
                        break; // Éviter les doublons
                    }
                }
            }
        }
        
        // Si nous avons suffisamment de résultats locaux, les utiliser directement
        if (count($localResults) >= 5) {
            // Mettre en cache les résultats pour les futures requêtes
            Cache::put($cacheKey, $localResults, 3600); // Cache pendant 1 heure
            
            return view('products.search_results', [
                'products' => $localResults,
                'query' => $query
            ]);
        }
        
        // Sinon, essayer avec l'API externe
        try {
            $client = new Client();
            $response = $client->request('GET', 'https://fr.openfoodfacts.org/cgi/search.pl', [
                'timeout' => 5, // Timeout un peu plus long pour des résultats plus complets
                'query' => [
                    'search_terms' => $query,
                    'search_simple' => 1,
                    'action' => 'process',
                    'json' => 1,
                    'page_size' => 15
                ]
            ]);
            
            if ($response->getStatusCode() === 200) {
                $apiData = json_decode($response->getBody(), true);
                
                // Formater les résultats de l'API
                $apiResults = [];
                if (isset($apiData['products']) && is_array($apiData['products'])) {
                    foreach ($apiData['products'] as $product) {
                        // Ne pas inclure les produits sans nom ou sans image
                        if (empty($product['product_name']) || empty($product['image_url'])) {
                            continue;
                        }
                        
                        $apiResults[] = [
                            'id' => $product['code'],
                            'name' => $product['product_name'],
                            'image' => $product['image_url'] ?? null,
                            'brand' => $product['brands'] ?? 'Marque inconnue',
                            'nutriscore' => $product['nutriscore_grade'] ?? null,
                            'quantity' => $product['quantity'] ?? null,
                            'categories' => $product['categories'] ?? null,
                            'ingredients' => $product['ingredients_text'] ?? null,
                        ];
                    }
                }
                
                // Fusionner les résultats locaux et API, en évitant les doublons
                $mergedResults = $localResults;
                $existingIds = array_column($localResults, 'id');
                
                foreach ($apiResults as $apiProduct) {
                    if (!in_array($apiProduct['id'], $existingIds)) {
                        $mergedResults[] = $apiProduct;
                    }
                }
                
                // Mettre en cache les résultats fusionnés
                Cache::put($cacheKey, $mergedResults, 3600); // Cache pendant 1 heure
                
                return view('products.search_results', [
                    'products' => $mergedResults,
                    'query' => $query
                ]);
            }
        } catch (\Exception $e) {
            // En cas d'erreur avec l'API, utiliser uniquement les résultats locaux
            Log::error('Erreur API OpenFoodFacts: ' . $e->getMessage());
        }
        
        // Si l'API échoue, utiliser les résultats locaux
        Cache::put($cacheKey, $localResults, 3600); // Cache pendant 1 heure
        
        return view('products.search_results', [
            'products' => $localResults,
            'query' => $query
        ]);
    }

    /**
     * Affiche les détails d'un produit spécifique à partir de son identifiant.
     */
    public function show($id)
    {
        // Vérifier le cache d'abord
        $cacheKey = 'product_details_' . $id;
        if (Cache::has($cacheKey)) {
            $product = Cache::get($cacheKey);
            return view('products.show', ['product' => $product]);
        }
        
        // Rechercher d'abord dans nos données de démo
        $found = false;
        $demoProducts = [
            // ... (conserver les produits de démo existants)
        ];
        
        // Chercher le produit dans les données de démo par son ID
        foreach ($demoProducts as $demoProduct) {
            if ($demoProduct['id'] === $id) {
                $product = $demoProduct;
                
                // Ajouter des informations de prix simulées
                $product['recent_prices'] = [
                    [
                        'store' => 'Carrefour',
                        'price' => rand(150, 500) / 100,
                        'date' => date('Y-m-d', strtotime('-' . rand(1, 5) . ' days')),
                        'user' => 'utilisateur' . rand(1, 999),
                    ],
                    [
                        'store' => 'Leclerc',
                        'price' => rand(140, 490) / 100,
                        'date' => date('Y-m-d', strtotime('-' . rand(1, 10) . ' days')),
                        'user' => 'utilisateur' . rand(1, 999),
                    ],
                    [
                        'store' => 'Auchan',
                        'price' => rand(160, 510) / 100,
                        'date' => date('Y-m-d', strtotime('-' . rand(1, 7) . ' days')),
                        'user' => 'utilisateur' . rand(1, 999),
                    ],
                ];
                
                // Trier les prix par ordre croissant
                usort($product['recent_prices'], function($a, $b) {
                    return $a['price'] <=> $b['price'];
                });
                
                // Ajouter des valeurs nutritionnelles si elles n'existent pas
                if (!isset($product['nutrition'])) {
                    $product['nutrition'] = [
                        'energy' => rand(200, 500),
                        'fat' => rand(1, 30) / 10,
                        'saturated_fat' => rand(1, 15) / 10,
                        'carbohydrates' => rand(40, 75) / 10,
                        'sugars' => rand(1, 50) / 10,
                        'proteins' => rand(1, 30) / 10,
                        'salt' => rand(1, 20) / 100,
                        'fiber' => rand(1, 50) / 10,
                    ];
                }
                
                // Sauvegarder dans le cache pour 24 heures
                Cache::put($cacheKey, $product, 86400);
                $found = true;
                
                return view('products.show', ['product' => $product]);
            }
        }
        
        // Si non trouvé dans les données de démo, essayer l'API
        if (!$found) {
            try {
                // D'abord essayer l'API française
                $client = new Client();
                $response = $client->request('GET', "https://fr.openfoodfacts.org/api/v0/product/{$id}.json", [
                    'timeout' => 5
                ]);
                
                $data = json_decode($response->getBody(), true);
                
                // Si le produit n'est pas trouvé ou si le statut n'est pas 1 (succès), essayer l'API mondiale
                if (!isset($data['status']) || $data['status'] !== 1) {
                    $response = $client->request('GET', "https://world.openfoodfacts.org/api/v0/product/{$id}.json", [
                        'timeout' => 5
                    ]);
                    $data = json_decode($response->getBody(), true);
                }
                
                // Si le produit est trouvé
                if (isset($data['status']) && $data['status'] === 1 && isset($data['product'])) {
                    $productData = $data['product'];
                    
                    // Formater les données du produit
                    $product = [
                        'id' => $productData['code'],
                        'name' => $productData['product_name'] ?? $productData['product_name_fr'] ?? 'Produit sans nom',
                        'image' => $productData['image_url'] ?? $productData['image_front_url'] ?? null,
                        'brand' => $productData['brands'] ?? null,
                        'nutriscore' => $productData['nutriscore_grade'] ?? null,
                        'quantity' => $productData['quantity'] ?? null,
                        'categories' => $productData['categories'] ?? null,
                        'ingredients' => $productData['ingredients_text'] ?? $productData['ingredients_text_fr'] ?? null,
                        'allergens' => $productData['allergens'] ?? null,
                        'nutrition' => [
                            'energy' => $productData['nutriments']['energy-kcal_100g'] ?? $productData['nutriments']['energy_100g'] ?? null,
                            'fat' => $productData['nutriments']['fat_100g'] ?? null,
                            'saturated_fat' => $productData['nutriments']['saturated-fat_100g'] ?? null,
                            'carbohydrates' => $productData['nutriments']['carbohydrates_100g'] ?? null,
                            'sugars' => $productData['nutriments']['sugars_100g'] ?? null,
                            'proteins' => $productData['nutriments']['proteins_100g'] ?? null,
                            'salt' => $productData['nutriments']['salt_100g'] ?? null,
                            'fiber' => $productData['nutriments']['fiber_100g'] ?? null,
                        ],
                        'stores' => $productData['stores'] ?? null,
                        'countries' => $productData['countries'] ?? null,
                    ];
                    
                    // Simuler des données de prix récentes
                    $product['recent_prices'] = [
                        [
                            'store' => 'Carrefour',
                            'price' => rand(150, 500) / 100,
                            'date' => date('Y-m-d', strtotime('-' . rand(1, 5) . ' days')),
                            'user' => 'utilisateur' . rand(1, 999),
                        ],
                        [
                            'store' => 'Leclerc',
                            'price' => rand(140, 490) / 100,
                            'date' => date('Y-m-d', strtotime('-' . rand(1, 10) . ' days')),
                            'user' => 'utilisateur' . rand(1, 999),
                        ],
                        [
                            'store' => 'Auchan',
                            'price' => rand(160, 510) / 100,
                            'date' => date('Y-m-d', strtotime('-' . rand(1, 7) . ' days')),
                            'user' => 'utilisateur' . rand(1, 999),
                        ],
                    ];
                    
                    // Trier les prix par ordre croissant
                    usort($product['recent_prices'], function($a, $b) {
                        return $a['price'] <=> $b['price'];
                    });
                    
                    // Sauvegarder dans le cache pour 24 heures
                    Cache::put($cacheKey, $product, 86400);
                    
                    return view('products.show', ['product' => $product]);
                }
                
                // Si le produit n'est pas trouvé
                Log::warning("Produit non trouvé: {$id}");
                return redirect()->route('products.search')
                    ->with('error', 'Ce produit n\'existe pas dans notre base de données.');
                
            } catch (\Exception $e) {
                Log::error('OpenFoodFacts API error: ' . $e->getMessage());
                return redirect()->route('products.search')
                    ->with('error', 'Une erreur est survenue lors de la récupération des informations du produit.');
            }
        }
    }
}