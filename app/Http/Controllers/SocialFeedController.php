<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PointTransaction;

class SocialFeedController extends Controller
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
     * Display the social feed.
     */
    public function index()
    {
        // Récupérer les posts récents pour le feed
        $posts = Post::with(['user', 'likes'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('social.index', compact('posts'));
    }

    /**
     * Show the form for creating a new post.
     */
    public function create()
    {
        return view('social.create');
    }

    /**
     * Store a newly created post.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_name' => 'required|string|max:255',
            'store_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'regular_price' => 'nullable|numeric|min:0',
            'description' => 'nullable|string|max:1000',
            'post_type' => 'required|in:price,deal,meal,review',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // Max 5MB
        ]);
        
        $post = new Post();
        $post->user_id = Auth::id();
        $post->product_name = $request->product_name;
        $post->store_name = $request->store_name;
        $post->price = $request->price;
        $post->regular_price = $request->regular_price;
        $post->description = $request->description;
        $post->post_type = $request->post_type;
        
        // Traitement de l'image
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/posts'), $filename);
            $post->image = '/uploads/posts/' . $filename;
        }
        
        // Utilisation facultative de la géolocalisation
        if ($request->has('latitude') && $request->has('longitude')) {
            $post->location = [
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'address' => $request->address
            ];
        }
        
        // Date d'expiration pour les offres
        if ($request->post_type === 'deal' && $request->has('expires_at')) {
            $post->expires_at = $request->expires_at;
        }
        
        $post->save();
        
        // Attribution de points pour la publication
        PointTransaction::awardPoints(Auth::id(), 'post_created', 'Publication dans le feed social');
        
        // Analyse IA automatique pour les repas
        if ($post->post_type === 'meal') {
            $mealScoreController = new \App\Http\Controllers\MealScoreController();
            $mealScoreController->autoAnalyzeMeal($post);
        }
        
        return redirect()->route('social.feed')
            ->with('success', $post->post_type === 'meal' ? 'Votre repas a été partagé et analysé par notre IA ! 📸🍽️🤖' : 'Votre publication a été partagée avec succès !');
    }

    /**
     * Display a specific post.
     */
    public function show(Post $post)
    {
        $post->load(['user', 'comments.user', 'mealScore']);
        return view('social.show', compact('post'));
    }

    /**
     * Show the form for editing the specified post.
     */
    public function edit(Post $post)
    {
        // Vérifier que l'utilisateur est le propriétaire du post
        if ($post->user_id !== Auth::id()) {
            return redirect()->route('social.feed')
                ->with('error', 'Vous ne pouvez pas modifier cette publication.');
        }
        
        return view('social.edit', compact('post'));
    }

    /**
     * Update the specified post.
     */
    public function update(Request $request, Post $post)
    {
        // Vérifier que l'utilisateur est le propriétaire du post
        if ($post->user_id !== Auth::id()) {
            return redirect()->route('social.feed')
                ->with('error', 'Vous ne pouvez pas modifier cette publication.');
        }
        
        $request->validate([
            'product_name' => 'required|string|max:255',
            'store_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'regular_price' => 'nullable|numeric|min:0',
            'description' => 'nullable|string|max:1000',
            'post_type' => 'required|in:price,deal,meal,review',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // Max 5MB
        ]);
        
        $post->product_name = $request->product_name;
        $post->store_name = $request->store_name;
        $post->price = $request->price;
        $post->regular_price = $request->regular_price;
        $post->description = $request->description;
        $post->post_type = $request->post_type;
        
        // Traitement de l'image si une nouvelle est uploadée
        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image
            if ($post->image && file_exists(public_path($post->image))) {
                unlink(public_path($post->image));
            }
            
            $image = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/posts'), $filename);
            $post->image = '/uploads/posts/' . $filename;
        }
        
        // Date d'expiration pour les offres
        if ($request->post_type === 'deal' && $request->has('expires_at')) {
            $post->expires_at = $request->expires_at;
        }
        
        $post->save();
        
        // Re-analyser le repas si c'est un meal et qu'il y a eu des changements
        if ($post->post_type === 'meal') {
            // Supprimer l'ancien score pour re-analyser
            if ($post->mealScore) {
                $post->mealScore->delete();
            }
            
            $mealScoreController = new \App\Http\Controllers\MealScoreController();
            $mealScoreController->autoAnalyzeMeal($post);
        }
        
        return redirect()->route('social.show', $post)
            ->with('success', 'Publication mise à jour avec succès !');
    }

    /**
     * Remove the specified post from storage.
     */
    public function destroy(Post $post)
    {
        // Vérifier que l'utilisateur est le propriétaire du post
        if ($post->user_id !== Auth::id()) {
            return redirect()->route('social.feed')
                ->with('error', 'Vous ne pouvez pas supprimer cette publication.');
        }
        
        // Supprimer l'image associée
        if ($post->image && file_exists(public_path($post->image))) {
            unlink(public_path($post->image));
        }
        
        // Supprimer le score de repas s'il existe
        if ($post->mealScore) {
            $post->mealScore->delete();
        }
        
        // Supprimer la publication
        $post->delete();
        
        return redirect()->route('social.feed')
            ->with('success', 'Publication supprimée avec succès.');
    }

    /**
     * Like or unlike a post.
     */
    public function like(Post $post)
    {
        $user_id = Auth::id();
        
        // Vérifier si l'utilisateur a déjà aimé ce post
        $liked = $post->likes()->where('user_id', $user_id)->exists();
        
        if ($liked) {
            // Unlike
            $post->unlikeBy($user_id);
            $message = 'Like retiré';
        } else {
            // Like
            $post->likeBy($user_id);
            
            // Si ce n'est pas son propre post, attribuer des points au créateur
            if ($post->user_id !== $user_id) {
                PointTransaction::awardPoints($post->user_id, 'post_liked', 'Publication aimée');
            }
            
            $message = 'Publication aimée';
        }
        
        if (request()->ajax()) {
            return response()->json([
                'liked' => !$liked,
                'likes_count' => $post->likes_count,
                'message' => $message
            ]);
        }
        
        return back()->with('success', $message);
    }

    /**
     * Add a comment to a post.
     */
    public function comment(Request $request, Post $post)
    {
        $request->validate([
            'content' => 'required|string|max:500',
        ]);
        
        $comment = new Comment();
        $comment->user_id = Auth::id();
        $comment->content = $request->content;
        
        $post->comments()->save($comment);
        $post->increment('comments_count');
        
        // Si ce n'est pas son propre post, attribuer des points au créateur
        if ($post->user_id !== Auth::id()) {
            PointTransaction::awardPoints($post->user_id, 'post_commented', 'Commentaire reçu sur une publication');
        }
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'comment' => $comment->load('user'),
                'comments_count' => $post->comments_count
            ]);
        }
        
        return back()->with('success', 'Commentaire ajouté');
    }
}
