<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductRecommendation;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class ProductRecommendationService
{
    private const HEALTH_WEIGHT = 0.6;
    private const PRICE_WEIGHT = 0.4;
    private const MAX_RECOMMENDATIONS = 3;

    public function getRecommendations(string $searchTerm): Collection
    {
        // Récupérer les produits similaires
        $products = Product::where('name', 'like', "%{$searchTerm}%")
            ->orWhere('brand', 'like', "%{$searchTerm}%")
            ->get();

        if ($products->isEmpty()) {
            return collect();
        }

        // Calculer le prix moyen de la catégorie
        $averagePrice = $products->avg('price');

        // Calculer les scores pour chaque produit
        $recommendations = $products->map(function ($product) use ($averagePrice) {
            $healthScore = $this->calculateHealthScore($product);
            $priceScore = $this->calculatePriceScore($product->price, $averagePrice);
            $finalScore = $this->calculateFinalScore($healthScore, $priceScore);

            return new ProductRecommendation([
                'product_id' => $product->id,
                'health_score' => $healthScore,
                'price_score' => $priceScore,
                'final_score' => $finalScore,
                'average_price' => $product->price,
                'barcode' => $product->barcode,
                'brand' => $product->brand,
                'name' => $product->name
            ]);
        });

        // Trier par score final et retourner les 3 meilleurs
        return $recommendations->sortByDesc('final_score')
            ->take(self::MAX_RECOMMENDATIONS);
    }

    private function calculateHealthScore(Product $product): float
    {
        $score = 100;
        
        // Analyse des ingrédients
        $ingredients = $product->ingredients ?? [];
        
        // Pénalités pour les additifs controversés
        $controversialAdditives = ['E621', 'E951', 'E250', 'E211'];
        foreach ($controversialAdditives as $additive) {
            if (in_array($additive, $ingredients)) {
                $score -= 10;
            }
        }

        // Pénalités pour les sucres ajoutés
        if (str_contains(strtolower($product->ingredients_text ?? ''), 'sucre') ||
            str_contains(strtolower($product->ingredients_text ?? ''), 'sirop')) {
            $score -= 15;
        }

        // Pénalités pour le sel
        if ($product->sodium_per_100g > 1.5) {
            $score -= 10;
        }

        // Bonus pour les produits bio
        if ($product->is_bio) {
            $score += 10;
        }

        return max(0, min(100, $score));
    }

    private function calculatePriceScore(float $price, float $averagePrice): float
    {
        if ($averagePrice === 0) {
            return 50; // Score neutre si pas de prix moyen
        }

        $priceRatio = $price / $averagePrice;
        
        // Un produit est bien noté s'il est moins cher que la moyenne
        if ($priceRatio <= 0.8) {
            return 100; // 20% ou plus moins cher
        } elseif ($priceRatio <= 0.9) {
            return 80; // 10-20% moins cher
        } elseif ($priceRatio <= 1.0) {
            return 60; // 0-10% moins cher
        } elseif ($priceRatio <= 1.2) {
            return 40; // 0-20% plus cher
        } else {
            return 20; // Plus de 20% plus cher
        }
    }

    private function calculateFinalScore(float $healthScore, float $priceScore): float
    {
        return ($healthScore * self::HEALTH_WEIGHT) + ($priceScore * self::PRICE_WEIGHT);
    }
} 