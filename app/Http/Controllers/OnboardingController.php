<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PointTransaction;
use Illuminate\Support\Facades\Auth;

class OnboardingController extends Controller
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
     * Affiche l'écran d'onboarding
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('onboarding.index');
    }
    
    /**
     * Enregistre les informations d'onboarding
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'favorite_categories' => 'required|array|min:1',
            'bio' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        // Mise à jour de la bio
        if ($request->has('bio')) {
            $user->bio = $request->bio;
        }
        
        // Mise à jour de l'avatar
        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $filename = time() . '_' . uniqid() . '.' . $avatar->getClientOriginalExtension();
            $avatar->move(public_path('uploads/avatars'), $filename);
            $user->avatar = '/uploads/avatars/' . $filename;
        }
        
        // Enregistrer les catégories préférées (à implémenter plus tard avec une relation many-to-many)
        $user->preferences = $request->favorite_categories;
        
        $user->save();
        
        // Attribution de points pour avoir complété le profil
        PointTransaction::awardPoints($user->id, 'profile_completed', 'Profil complété');
        
        // Redirection vers le feed social
        return redirect()->route('social.feed')
            ->with('success', 'Bienvenue dans la communauté ZYMA ! Vous avez reçu 100 points pour avoir complété votre profil.');
    }
}
