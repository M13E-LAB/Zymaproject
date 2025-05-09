<?php

namespace App\Http\Controllers;

use App\Services\ProductRecommendationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductRecommendationController extends Controller
{
    private ProductRecommendationService $recommendationService;

    public function __construct(ProductRecommendationService $recommendationService)
    {
        $this->recommendationService = $recommendationService;
    }

    public function getRecommendations(Request $request): JsonResponse
    {
        $searchTerm = $request->input('search_term');
        
        if (empty($searchTerm)) {
            return response()->json([
                'error' => 'Le terme de recherche est requis'
            ], 400);
        }

        $recommendations = $this->recommendationService->getRecommendations($searchTerm);

        return response()->json([
            'recommendations' => $recommendations->map(function ($recommendation) {
                return [
                    'name' => $recommendation->name,
                    'brand' => $recommendation->brand,
                    'barcode' => $recommendation->barcode,
                    'health_score' => round($recommendation->health_score, 1),
                    'price_score' => round($recommendation->price_score, 1),
                    'final_score' => round($recommendation->final_score, 1),
                    'average_price' => round($recommendation->average_price, 2)
                ];
            })
        ]);
    }
} 