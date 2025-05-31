<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Badge;
use Illuminate\Support\Str;

class BadgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tableau des badges à créer
        $badges = [
            [
                'name' => 'Bienvenue',
                'description' => 'S\'être inscrit sur ZYMA',
                'icon' => 'user-plus',
                'points' => 10,
                'rarity_class' => 'common',
                'slug' => 'welcome'
            ],
            [
                'name' => 'Profil complet',
                'description' => 'Avoir complété son profil à 100%',
                'icon' => 'id-card',
                'points' => 20,
                'rarity_class' => 'common',
                'slug' => 'profile_complete'
            ],
            [
                'name' => 'Premier partage',
                'description' => 'Avoir partagé un premier produit',
                'icon' => 'share-alt',
                'points' => 15,
                'rarity_class' => 'common',
                'slug' => 'first_share'
            ],
            [
                'name' => 'Contributeur',
                'description' => 'Avoir partagé 5 produits',
                'icon' => 'share-square',
                'points' => 30,
                'rarity_class' => 'rare',
                'slug' => 'five_shares'
            ],
            [
                'name' => 'Expert produits',
                'description' => 'Avoir partagé 20 produits',
                'icon' => 'award',
                'points' => 100,
                'rarity_class' => 'epic',
                'slug' => 'twenty_shares'
            ],
            [
                'name' => 'Premier commentaire',
                'description' => 'Avoir commenté sur un produit',
                'icon' => 'comment',
                'points' => 15,
                'rarity_class' => 'common',
                'slug' => 'first_comment'
            ],
            [
                'name' => 'Communicant',
                'description' => 'Avoir commenté sur 10 produits',
                'icon' => 'comments',
                'points' => 50,
                'rarity_class' => 'rare',
                'slug' => 'ten_comments'
            ],
            [
                'name' => 'Premier like',
                'description' => 'Avoir aimé un produit',
                'icon' => 'heart',
                'points' => 5,
                'rarity_class' => 'common',
                'slug' => 'first_like'
            ],
            [
                'name' => 'Niveau Éclaireur',
                'description' => 'Avoir atteint le niveau Éclaireur',
                'icon' => 'user-graduate',
                'points' => 25,
                'rarity_class' => 'rare',
                'slug' => 'level_eclaireur'
            ],
            [
                'name' => 'Niveau Expert',
                'description' => 'Avoir atteint le niveau Expert',
                'icon' => 'user-tie',
                'points' => 75,
                'rarity_class' => 'epic',
                'slug' => 'level_expert'
            ],
            [
                'name' => 'Niveau Maître',
                'description' => 'Avoir atteint le niveau Maître',
                'icon' => 'crown',
                'points' => 150,
                'rarity_class' => 'legendary',
                'slug' => 'level_maitre'
            ]
        ];

        // Création des badges
        foreach ($badges as $badgeData) {
            // Vérifier si le badge existe déjà
            $exists = Badge::where('slug', $badgeData['slug'])->exists();
            
            if (!$exists) {
                Badge::create($badgeData);
            }
        }
    }
}
