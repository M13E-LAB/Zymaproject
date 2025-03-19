<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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
}