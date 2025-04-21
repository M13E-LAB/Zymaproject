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
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $user = Auth::user();
        return view('profile.show', compact('user'));
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
}
