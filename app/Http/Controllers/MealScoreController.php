<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\MealScore;
use App\Models\User;
use App\Models\League;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MealScoreController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Automatically analyze and score a meal using AI.
     */
    public function autoAnalyzeMeal(Post $post)
    {
        // Vérifier que le post est de type repas
        if ($post->post_type !== 'meal') {
            return false;
        }
        
        // Vérifier si le repas n'a pas déjà été analysé
        if ($post->mealScore) {
            return $post->mealScore;
        }
        
        // Simulation d'analyse IA avancée basée sur l'image et la description
        $analysis = $this->performAIAnalysis($post);
        
        // Créer le score
        $mealScore = MealScore::create([
            'post_id' => $post->id,
            'health_score' => $analysis['health_score'],
            'visual_score' => $analysis['visual_score'],
            'diversity_score' => $analysis['diversity_score'],
            'feedback' => $analysis['feedback'],
            'is_ai_scored' => true,
            'ai_analysis' => $analysis['ai_data']
        ]);
        
        // Calculer le score total
        $mealScore->calculateTotalScore();
        $mealScore->save();
        
        // Accorder des points à l'auteur du repas
        if ($mealScore->total_score >= 60) {
            $points = min(floor($mealScore->total_score / 10), 10); // Max 10 points
            $post->user->awardPoints($points, 'meal_ai_scored', 'Repas bien noté par l\'IA', ['score' => $mealScore->total_score]);
            
            // Mettre à jour les scores dans les ligues
            $this->updateUserLeagueScores($post->user, $points);
        }
        
        return $mealScore;
    }
    
    /**
     * Perform AI analysis of the meal image and description.
     */
    private function performAIAnalysis(Post $post)
    {
        // Simulation d'analyse IA basée sur le nom du produit et la description
        $productName = strtolower($post->product_name);
        $description = strtolower($post->description ?? '');
        
        // Mots-clés pour l'analyse nutritionnelle
        $healthyKeywords = ['salade', 'légumes', 'fruits', 'quinoa', 'avocat', 'saumon', 'poulet', 'tomate', 'brocoli', 'épinards', 'carotte', 'concombre', 'poivron', 'courgette', 'aubergine', 'chou', 'haricot', 'lentille', 'pois chiche', 'noix', 'amande'];
        $unhealthyKeywords = ['frites', 'burger', 'pizza', 'soda', 'gâteau', 'bonbon', 'chocolat', 'chips', 'nuggets', 'kebab', 'mcdo', 'kfc', 'donuts'];
        $colorfulKeywords = ['rouge', 'vert', 'jaune', 'orange', 'violet', 'coloré', 'variété', 'couleur', 'arc-en-ciel'];
        $visualKeywords = ['belle', 'jolie', 'appétissant', 'présentation', 'dressage', 'décoration', 'instagram'];
        
        // Calcul du score santé (40-95)
        $healthScore = 70; // Score de base
        foreach ($healthyKeywords as $keyword) {
            if (str_contains($productName, $keyword) || str_contains($description, $keyword)) {
                $healthScore += rand(5, 15);
            }
        }
        foreach ($unhealthyKeywords as $keyword) {
            if (str_contains($productName, $keyword) || str_contains($description, $keyword)) {
                $healthScore -= rand(15, 25);
            }
        }
        $healthScore = max(30, min(95, $healthScore + rand(-10, 10)));
        
        // Calcul du score visuel (50-95)
        $visualScore = 65; // Score de base
        foreach ($visualKeywords as $keyword) {
            if (str_contains($description, $keyword)) {
                $visualScore += rand(10, 20);
            }
        }
        foreach ($colorfulKeywords as $keyword) {
            if (str_contains($productName, $keyword) || str_contains($description, $keyword)) {
                $visualScore += rand(5, 15);
            }
        }
        $visualScore = max(40, min(95, $visualScore + rand(-8, 12)));
        
        // Calcul du score diversité (30-95)
        $diversityScore = 60; // Score de base
        $diversityKeywords = ['varié', 'mélange', 'plusieurs', 'différent', 'complet', 'équilibré'];
        foreach ($diversityKeywords as $keyword) {
            if (str_contains($productName, $keyword) || str_contains($description, $keyword)) {
                $diversityScore += rand(8, 18);
            }
        }
        // Bonus si plusieurs types d'aliments détectés
        $foodTypeCount = 0;
        if (preg_match('/légume|salade|tomate|carotte|brocoli/i', $productName . ' ' . $description)) $foodTypeCount++;
        if (preg_match('/viande|poulet|porc|bœuf|saumon|poisson/i', $productName . ' ' . $description)) $foodTypeCount++;
        if (preg_match('/féculent|riz|pâtes|pain|pomme.de.terre/i', $productName . ' ' . $description)) $foodTypeCount++;
        if (preg_match('/fruit|pomme|banane|orange/i', $productName . ' ' . $description)) $foodTypeCount++;
        
        $diversityScore += $foodTypeCount * 8;
        $diversityScore = max(25, min(95, $diversityScore + rand(-12, 8)));
        
        // Génération du feedback personnalisé
        $feedback = $this->generateFeedback($healthScore, $visualScore, $diversityScore, $productName);
        
        // Données d'analyse IA simulées
        $aiData = [
            'detected_foods' => $this->detectFoods($productName, $description),
            'nutrition_estimation' => [
                'calories' => $this->estimateCalories($productName, $healthScore),
                'proteins' => rand(10, 40),
                'carbs' => rand(20, 80),
                'fats' => rand(5, 35),
                'fiber' => rand(2, 15),
                'sugar' => rand(0, 25)
            ],
            'analysis_confidence' => rand(75, 95),
            'improvement_suggestions' => $this->getSuggestions($healthScore, $visualScore, $diversityScore)
        ];
        
        return [
            'health_score' => $healthScore,
            'visual_score' => $visualScore,
            'diversity_score' => $diversityScore,
            'feedback' => $feedback,
            'ai_data' => $aiData
        ];
    }
    
    /**
     * Generate personalized feedback based on scores.
     */
    private function generateFeedback($healthScore, $visualScore, $diversityScore, $productName)
    {
        $feedback = "🤖 Analyse IA de votre " . $productName . " :\n\n";
        
        // Feedback santé
        if ($healthScore >= 80) {
            $feedback .= "🫀 **Excellent choix nutritionnel !** Votre repas est très équilibré et sain.\n";
        } elseif ($healthScore >= 65) {
            $feedback .= "🫀 **Bon équilibre nutritionnel** avec quelques possibilités d'amélioration.\n";
        } else {
            $feedback .= "🫀 **Attention à l'équilibre :** essayez d'ajouter plus de légumes ou d'aliments nutritifs.\n";
        }
        
        // Feedback visuel
        if ($visualScore >= 80) {
            $feedback .= "👁️ **Présentation magnifique !** Votre plat est très appétissant visuellement.\n";
        } elseif ($visualScore >= 65) {
            $feedback .= "👁️ **Belle présentation** qui donne envie de déguster.\n";
        } else {
            $feedback .= "👁️ **Présentation à améliorer :** pensez aux couleurs et au dressage pour plus d'attrait.\n";
        }
        
        // Feedback diversité
        if ($diversityScore >= 80) {
            $feedback .= "🌱 **Excellente diversité !** Vous avez un très bon mélange de groupes alimentaires.\n";
        } elseif ($diversityScore >= 65) {
            $feedback .= "🌱 **Bonne variété** dans les aliments choisis.\n";
        } else {
            $feedback .= "🌱 **Diversifiez davantage :** ajoutez plus de variété dans les couleurs et types d'aliments.\n";
        }
        
        return $feedback;
    }
    
    /**
     * Detect foods in the meal.
     */
    private function detectFoods($productName, $description)
    {
        $foods = [];
        $text = strtolower($productName . ' ' . $description);
        
        $foodMap = [
            'légumes' => ['légume', 'salade', 'tomate', 'carotte', 'brocoli', 'épinard', 'courgette'],
            'protéines' => ['poulet', 'saumon', 'poisson', 'viande', 'œuf', 'tofu', 'lentille'],
            'féculents' => ['riz', 'pâtes', 'pain', 'pomme de terre', 'quinoa', 'avoine'],
            'fruits' => ['pomme', 'banane', 'orange', 'fraise', 'avocat', 'tomate'],
            'produits laitiers' => ['fromage', 'yaourt', 'lait', 'crème']
        ];
        
        foreach ($foodMap as $category => $keywords) {
            foreach ($keywords as $keyword) {
                if (str_contains($text, $keyword)) {
                    $foods[] = $category;
                    break;
                }
            }
        }
        
        return array_unique($foods);
    }
    
    /**
     * Estimate calories based on meal type and health score.
     */
    private function estimateCalories($productName, $healthScore)
    {
        $baseCalories = 400;
        
        if (str_contains(strtolower($productName), 'salade')) {
            $baseCalories = rand(250, 400);
        } elseif (str_contains(strtolower($productName), 'burger') || str_contains(strtolower($productName), 'pizza')) {
            $baseCalories = rand(600, 900);
        } elseif (str_contains(strtolower($productName), 'soupe')) {
            $baseCalories = rand(150, 300);
        }
        
        // Ajustement basé sur le score santé
        if ($healthScore >= 80) {
            $baseCalories = $baseCalories * 0.9; // Repas sains généralement moins caloriques
        } elseif ($healthScore < 50) {
            $baseCalories = $baseCalories * 1.3; // Repas moins sains plus caloriques
        }
        
        return round($baseCalories);
    }
    
    /**
     * Get improvement suggestions based on scores.
     */
    private function getSuggestions($healthScore, $visualScore, $diversityScore)
    {
        $suggestions = [];
        
        if ($healthScore < 70) {
            $suggestions[] = "Ajoutez plus de légumes verts à votre repas";
            $suggestions[] = "Réduisez les aliments transformés";
        }
        
        if ($visualScore < 70) {
            $suggestions[] = "Travaillez la présentation avec plus de couleurs";
            $suggestions[] = "Soignez le dressage de l'assiette";
        }
        
        if ($diversityScore < 70) {
            $suggestions[] = "Incorporez plus de variété dans les groupes alimentaires";
            $suggestions[] = "Mélangez différentes textures et saveurs";
        }
        
        if (empty($suggestions)) {
            $suggestions[] = "Continuez ainsi, votre repas est excellent !";
        }
        
        return $suggestions;
    }
    
    /**
     * Update user's scores in all leagues they are part of.
     */
    private function updateUserLeagueScores(User $user, $points)
    {
        foreach ($user->leagues as $league) {
            $league->updateMemberScore(
                $user, 
                $points, // weekly score
                $points, // monthly score
                $points  // total score
            );
        }
    }
} 