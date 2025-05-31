<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
require_once __DIR__ . '/../../zyma_github/app/Models/League.php';
use App\Models\User;
use Illuminate\Support\Str;
use App\Models\League;

class LeagueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Vérifier que nous avons des utilisateurs
        $users = User::all();
        
        if ($users->isEmpty()) {
            $this->command->info('Aucun utilisateur trouvé. Création de 10 utilisateurs pour les tests...');
            // Créer des utilisateurs de test si nécessaire
            \App\Models\User::factory(10)->create();
            $users = User::all();
        }
        
        // Créer 3 ligues de test
        $leagues = [
            [
                'name' => 'Ligue des Gourmets',
                'description' => 'Pour les amateurs de gastronomie et de bonne cuisine saine',
                'is_private' => false,
                'max_members' => 20,
            ],
            [
                'name' => 'Team Healthy Food',
                'description' => 'Focus sur les repas équilibrés et nutritifs',
                'is_private' => true,
                'max_members' => 15,
            ],
            [
                'name' => 'Budget Foodies',
                'description' => 'Manger bien sans se ruiner',
                'is_private' => false,
                'max_members' => 30,
            ],
        ];
        
        foreach ($leagues as $leagueData) {
            // Choisir un créateur aléatoire
            $creator = $users->random();
            
            // Créer la ligue
            $league = League::create([
                'name' => $leagueData['name'],
                'slug' => Str::slug($leagueData['name']) . '-' . Str::random(5),
                'description' => $leagueData['description'],
                'created_by' => $creator->id,
                'invite_code' => Str::random(10),
                'is_private' => $leagueData['is_private'],
                'max_members' => $leagueData['max_members'],
            ]);
            
            // Ajouter le créateur comme admin
            $league->members()->attach($creator->id, [
                'role' => 'admin',
                'weekly_score' => rand(50, 200),
                'monthly_score' => rand(200, 500),
                'total_score' => rand(500, 2000),
                'last_score_update' => now(),
            ]);
            
            // Ajouter des membres aléatoires (entre 5 et 10)
            $membersCount = rand(5, 10);
            $potentialMembers = $users->where('id', '!=', $creator->id)->random(min($membersCount, $users->count() - 1));
            
            foreach ($potentialMembers as $member) {
                $league->members()->attach($member->id, [
                    'role' => rand(0, 5) > 4 ? 'admin' : 'member', // 1/6 chance d'être admin
                    'weekly_score' => rand(20, 180),
                    'monthly_score' => rand(100, 450),
                    'total_score' => rand(300, 1800),
                    'last_score_update' => now()->subDays(rand(0, 14)),
                ]);
            }
            
            // Recalculer les positions
            $league->recalculatePositions();
            
            $this->command->info("Ligue '{$league->name}' créée avec " . ($potentialMembers->count() + 1) . " membres");
        }
    }
} 