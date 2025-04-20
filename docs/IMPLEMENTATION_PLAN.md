# Plan d'Implémentation Progressive - Zyma Social

Ce document détaille le plan d'évolution de l'application Zyma existante vers la vision complète du hub social de la nutrition et des prix.

## État Actuel de l'Application

L'application Zyma possède déjà des bases solides avec:
- Comparaison de prix entre différents magasins
- Affichage détaillé des produits
- Interface utilisateur responsive et moderne
- Connexion avec l'API OpenFoodFacts
- Backend Laravel fonctionnel

## Stratégie d'Évolution

Plutôt que de reconstruire l'application, nous adopterons une approche d'extension progressive qui permettra:
1. De conserver l'expérience utilisateur existante
2. D'ajouter des fonctionnalités par sprints de 2-4 semaines
3. De tester l'adoption de chaque nouvelle fonctionnalité
4. D'itérer rapidement sur les retours utilisateurs

## Phase 1: Compte Utilisateur & Contributions Basiques (4 semaines)

### Sprint 1: Authentification & Profils (2 semaines)
- [x] Implémenter le système d'authentification Laravel Breeze/Sanctum
- [ ] Créer la table users avec champs essentiels
- [ ] Concevoir le profil utilisateur simple avec:
  - Avatar
  - Nom/Pseudo
  - Localisation
  - Préférences de magasins
- [ ] Ajouter la barre de navigation avec menu utilisateur

```php
// Migration pour ajouter les champs au modèle User
Schema::table('users', function (Blueprint $table) {
    $table->string('username')->nullable()->unique();
    $table->string('avatar')->nullable();
    $table->json('location')->nullable();
    $table->json('favorite_stores')->nullable();
    $table->json('preferences')->nullable();
});
```

### Sprint 2: Système de Contribution (2 semaines)
- [ ] Créer la fonctionnalité "Signaler un prix"
  - Formulaire simple pour produit, prix, magasin, date
- [ ] Implémentation de géolocalisation pour la sélection automatique du magasin
- [ ] Historique des contributions dans le profil utilisateur
- [ ] Badge "Contributeur" pour premiers apports

## Phase 2: Fonctionnalités Sociales Basiques (6 semaines)

### Sprint 3: Feed & Likes (3 semaines)
- [ ] Créer la table "posts" pour les partages
- [ ] Implémenter un feed simple sur la page d'accueil
- [ ] Système de like basique
- [ ] Option de partage de prix/produits trouvés

```php
// Exemple de modèle Post
class Post extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'store_id',
        'price',
        'description',
        'image_url',
        'expires_at',
    ];
    
    protected $casts = [
        'expires_at' => 'datetime',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    
    public function likes()
    {
        return $this->hasMany(Like::class);
    }
}
```

### Sprint 4: Commentaires & Bons Plans (3 semaines)
- [ ] Système de commentaires sur les produits/prix
- [ ] Template de post "Bon Plan" avec:
  - Photo optionnelle
  - Durée de validité
  - Localisation du magasin
  - Pourcentage d'économie calculé
- [ ] Page dédiée aux bons plans récents

## Phase 3: Système de Points & Gamification (4 semaines)

### Sprint 5: Infrastructure Points (2 semaines)
- [ ] Conception du modèle de données pour les points et niveaux
- [ ] Attribution de points pour les actions:
  - Partage de prix: +5 points
  - Prix vérifié par un autre utilisateur: +2 points
  - Partage de bon plan: +10 points
- [ ] Affichage de points dans le profil utilisateur

```php
// Migration pour la table des points
Schema::create('points_transactions', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained();
    $table->string('action_type');
    $table->integer('points');
    $table->text('description')->nullable();
    $table->json('metadata')->nullable();
    $table->timestamps();
});
```

### Sprint 6: Badges & Niveaux (2 semaines)
- [ ] Mise en place des niveaux utilisateur:
  - Débutant (0-100 points)
  - Éclaireur (101-500 points)
  - Expert (501-2000 points)
  - Maître (2001+ points)
- [ ] Système de badges débloquables:
  - Chasseur de Prix (10 signalements)
  - Partageur (5 bons plans)
  - Communauté (20 commentaires)
- [ ] Barre de progression dans le profil

## Phase 4: Module OCR Tickets (6 semaines)

### Sprint 7: Upload & Analyse (3 semaines)
- [ ] Interface de prise de photo/upload de ticket
- [ ] Intégration OCR basique (Google Cloud Vision ou Tesseract)
- [ ] Stockage des images et résultats d'analyse
- [ ] Visualisation basique des résultats

### Sprint 8: Extraction & Validation (3 semaines)
- [ ] Amélioration de l'extraction de données structurées:
  - Nom magasin
  - Date
  - Liste produits
  - Prix individuels
- [ ] Interface d'édition manuelle des résultats
- [ ] Attribution de points pour les uploads
- [ ] Historique des tickets dans le profil

## Phase 5: Module Nutritionnel (5 semaines)

### Sprint 9: Analyse Nutritionnelle (3 semaines)
- [ ] Récupération et stockage des données nutritionnelles depuis OpenFoodFacts
- [ ] Calcul de scores nutritionnels basiques
- [ ] Dashboard personnel avec statistiques et scores
- [ ] Analyse des habitudes d'achat

### Sprint 10: Recommandations (2 semaines)
- [ ] Suggestions de substitution de produits
- [ ] Conseils nutritionnels simples
- [ ] Système d'alerte sur produits ultra-transformés
- [ ] Objectifs personnalisables

## Phase 6: Monétisation & Cashback (5 semaines)

### Sprint 11: Infrastructure Monétisation (3 semaines)
- [ ] Système de portefeuille virtuel
- [ ] Intégration avec PayPal/Stripe pour retraits
- [ ] Modèle de revenus publicitaires (magasins partenaires)
- [ ] Attribution de cashback symbolique pour testing

### Sprint 12: Partenariats & Scaling (2 semaines)
- [ ] Dashboard administrateur pour gestion des partenariats
- [ ] Système d'offres exclusives
- [ ] Campagnes promotionnelles pour partenaires
- [ ] Analytics pour suivi des performances

## Considérations Techniques

### Bases de Données
- Conserver la structure actuelle
- Ajouter des tables progressivement:
  - users (extension)
  - posts
  - comments
  - likes
  - points_transactions
  - badges
  - receipts
  - receipt_items

### Architecture Backend
- Maintenir l'architecture Laravel existante
- Ajouter des contrôleurs spécifiques par fonctionnalité
- Utiliser les API resources pour standardiser les réponses
- Mettre en place des jobs en file d'attente pour l'OCR

### Frontend
- Conserver l'expérience utilisateur actuelle
- Étendre progressivement avec de nouveaux composants
- Utiliser Vue.js pour les interactions dynamiques
- Maintenir la compatibilité mobile

### Tests & Déploiement
- Tests unitaires pour chaque nouvelle fonctionnalité
- Déploiement continu après chaque sprint
- Période beta avec utilisateurs test
- Mécanisme de feedback intégré à l'application

## Prochaines Étapes Immédiates

1. **Configuration du système utilisateur**
   ```bash
   php artisan make:migration add_profile_fields_to_users_table
   php artisan make:controller ProfileController
   ```

2. **Mise en place du système de contributions**
   ```bash
   php artisan make:model Price -mc
   php artisan make:model Post -mc
   ```

3. **Intégration de géolocalisation**
   ```bash
   composer require stevebauman/location
   ```

4. **Préparation des vues**
   ```bash
   php artisan make:component UserProfile
   php artisan make:component PriceForm
   ```

---

Ce plan d'implémentation permettra d'évoluer progressivement vers la vision complète tout en gardant l'application fonctionnelle à chaque étape, et en recueillant des retours utilisateurs précieux pour affiner la direction. 