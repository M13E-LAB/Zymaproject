<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OpenFoodFactsController extends Controller
{
    protected $baseUrl = 'https://prices.openfoodfacts.org/api/v1/prices';

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
            $response = Http::withOptions([
                'verify' => false, // Disable SSL verification
            ])->get($this->baseUrl, [
                'product_code' => $productCode
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $prices = $this->processPrices($data['items']);
                return view('products.show', compact('prices', 'productCode'));
            } else {
                return back()->with('error', 'Failed to fetch product data');
            }
        } catch (\Exception $e) {
            dd($e->getMessage());
            return back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    private function processPrices($items)
    {
        return collect($items)->map(function ($item) {
            return [
                'store' => $item['location']['osm_name'] ?? 'Unknown',
                'price' => $item['price'],
                'date' => $item['date'],
                'location' => $item['location']['osm_address_city'] . ', ' . $item['location']['osm_address_country'],
            ];
        })->sortByDesc('date')->values()->all();
    }
}