<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Post;
use App\Models\PointTransaction;

class ProfileController extends Controller
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
     * Show the user profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User|null  $user
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, ?User $user = null)
    {
        // Si aucun utilisateur n'est spécifié, utilisez l'utilisateur connecté
        if (!$user) {
            $user = Auth::user();
        }
        
        // Ne pas vérifier l'autorisation pour l'instant
        // $this->authorize('view', $user);
        
        // Récupère les badges de l'utilisateur
        $earnedBadges = $user->badges;
        $allBadges = \App\Models\Badge::all();
        
        // Badges non obtenus
        $availableBadges = $allBadges->reject(function ($badge) use ($earnedBadges) {
            return $earnedBadges->contains('id', $badge->id);
        });
        
        // Récupérer les points et calculer le niveau
        $user->points = $user->points ?? 0;
        $user->level_title = $this->calculateLevelTitle($user->points);
        $user->level_progress = $this->calculateLevelProgress($user->points);
        $user->next_level_title = $this->getNextLevelTitle($user->level_title);
        $user->points_to_next_level = $this->getPointsToNextLevel($user->points);
        
        // Récupérer les statistiques
        $user->posts_count = $user->posts()->count();
        $user->badges_count = $user->badges()->count() ?? 0;
        
        // Récupérer les transactions de points récentes
        $pointTransactions = PointTransaction::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        
        // Récupérer les publications récentes
        $recentPosts = $user->posts()
            ->with('likes', 'comments')
            ->latest()
            ->take(3)
            ->get();
            
        // Ajouter les compteurs aux publications récentes
        foreach ($recentPosts as $post) {
            $post->likes_count = $post->likes->count();
            $post->comments_count = $post->comments->count();
        }
        
        return view('profile.show', compact('user', 'pointTransactions', 'recentPosts', 'earnedBadges', 'availableBadges'));
    }

    /**
     * Show the user's posts.
     *
     * @return \Illuminate\Http\Response
     */
    public function posts()
    {
        $user = Auth::user();
        $posts = $user->posts()->latest()->paginate(10);
        return view('profile.posts', compact('user', 'posts'));
    }

    /**
     * Show the user's points history.
     *
     * @return \Illuminate\Http\Response
     */
    public function points()
    {
        $user = Auth::user();
        $transactions = $user->pointTransactions()->latest()->paginate(20);
        return view('profile.points', compact('user', 'transactions'));
    }

    /**
     * Show the form for editing the user profile.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Update the user profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'nullable|string|max:255|unique:users,username,' . $user->id,
            'bio' => 'nullable|string|max:500',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $user->name = $request->name;
        $user->username = $request->username;
        $user->bio = $request->bio;
        
        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $filename = time() . '.' . $avatar->getClientOriginalExtension();
            $avatar->move(public_path('avatars'), $filename);
            $user->avatar = '/avatars/' . $filename;
        }
        
        // Check if profile is being completed for the first time
        if ($user->username && $user->bio && $user->avatar && !$user->hasBadge('profile_complete')) {
            PointTransaction::awardPoints($user->id, 'profile_completed', 'Profil complété');
            // In a real implementation, also add the profile_complete badge
        }
        
        $user->save();
        
        return redirect()->route('profile.show')->with('success', 'Profil mis à jour avec succès');
    }

    /**
     * Calcule le titre du niveau en fonction des points
     *
     * @param int $points
     * @return string
     */
    private function calculateLevelTitle($points)
    {
        if ($points < 101) {
            return 'Débutant';
        } elseif ($points < 501) {
            return 'Éclaireur';
        } elseif ($points < 2001) {
            return 'Expert';
        } else {
            return 'Maître';
        }
    }
    
    /**
     * Calcule le pourcentage de progression vers le niveau suivant
     *
     * @param int $points
     * @return int
     */
    private function calculateLevelProgress($points)
    {
        if ($points < 101) {
            return ($points / 101) * 100;
        } elseif ($points < 501) {
            return (($points - 101) / (501 - 101)) * 100;
        } elseif ($points < 2001) {
            return (($points - 501) / (2001 - 501)) * 100;
        } else {
            return 100;
        }
    }
    
    /**
     * Récupère le titre du prochain niveau
     *
     * @param string $currentLevel
     * @return string
     */
    private function getNextLevelTitle($currentLevel)
    {
        switch ($currentLevel) {
            case 'Débutant':
                return 'Éclaireur';
            case 'Éclaireur':
                return 'Expert';
            case 'Expert':
                return 'Maître';
            default:
                return 'Niveau Max';
        }
    }
    
    /**
     * Calcule le nombre de points restants jusqu'au prochain niveau
     *
     * @param int $points
     * @return int
     */
    private function getPointsToNextLevel($points)
    {
        if ($points < 101) {
            return 101 - $points;
        } elseif ($points < 501) {
            return 501 - $points;
        } elseif ($points < 2001) {
            return 2001 - $points;
        } else {
            return 0;
        }
    }

    /**
     * Show the user's badges.
     *
     * @return \Illuminate\Http\Response
     */
    public function badges()
    {
        $user = Auth::user();
        
        // Récupère les badges de l'utilisateur
        $earnedBadges = $user->badges;
        $allBadges = \App\Models\Badge::all();
        
        // Badges non obtenus
        $availableBadges = $allBadges->reject(function ($badge) use ($earnedBadges) {
            return $earnedBadges->contains('id', $badge->id);
        });
        
        return view('profile.badges', compact('user', 'earnedBadges', 'availableBadges'));
    }
}
