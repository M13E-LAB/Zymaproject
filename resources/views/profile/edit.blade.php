@extends('layouts.app')

@section('content')
<div class="profile-container">
    <div class="container">
        <!-- En-tête avec navigation de retour -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="section-title">
                <i class="fas fa-user-edit"></i> Modifier mon profil
            </h1>
            <a href="{{ route('profile.show') }}" class="btn btn-back">
                <i class="fas fa-arrow-left me-2"></i> Retour au profil
            </a>
        </div>
        
        @if ($errors->any())
        <div class="alert alert-danger mb-4">
            <h5 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i> Des erreurs sont présentes dans le formulaire</h5>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        
        <div class="row">
            <div class="col-lg-12">
                <div class="card profile-card">
                    <div class="card-header">
                        <h3 class="card-title">Informations du profil</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="avatar-upload-container">
                                        <div class="avatar-preview mb-4">
                                            @if($user->avatar)
                                                <img id="avatar-preview" src="{{ $user->avatar }}" alt="Avatar" class="profile-avatar">
                                            @else
                                                <div id="avatar-preview" class="profile-avatar-placeholder">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                            @endif
                                            
                                            <div class="avatar-overlay">
                                                <i class="fas fa-camera"></i>
                                                <span>Changer</span>
                                            </div>
                                            
                                            <input type="file" id="avatar" name="avatar" class="avatar-input" accept="image/*" onchange="previewAvatar(this)">
                                        </div>
                                        
                                        <div class="avatar-tips">
                                            <h4><i class="fas fa-info-circle me-2"></i> Conseils pour l'avatar</h4>
                                            <ul>
                                                <li>Image carrée recommandée</li>
                                                <li>Format JPG, PNG ou GIF</li>
                                                <li>Taille maximum: 2 MB</li>
                                                <li>Dimensions: 400×400 pixels minimum</li>
                                            </ul>
                                            
                                            <div class="form-check mt-3">
                                                <input class="form-check-input" type="checkbox" id="removeAvatar" name="remove_avatar">
                                                <label class="form-check-label" for="removeAvatar">
                                                    Supprimer mon avatar actuel
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="profile-completion">
                                        <h4>Progression du profil</h4>
                                        @php
                                            $completionSteps = [
                                                'avatar' => $user->avatar ? true : false,
                                                'name' => $user->name ? true : false,
                                                'username' => $user->username ? true : false,
                                                'bio' => $user->bio ? true : false
                                            ];
                                            
                                            $completionCount = count(array_filter($completionSteps));
                                            $completionPercent = ($completionCount / count($completionSteps)) * 100;
                                        @endphp
                                        
                                        <div class="progress level-progress mb-3">
                                            <div class="progress-bar" role="progressbar" style="width: {{ $completionPercent }}%;" 
                                                aria-valuenow="{{ $completionPercent }}" aria-valuemin="0" aria-valuemax="100">
                                                {{ round($completionPercent) }}%
                                            </div>
                                        </div>
                                        
                                        <div class="completion-checklist">
                                            <div class="completion-item {{ $completionSteps['name'] ? 'completed' : '' }}">
                                                <i class="fas {{ $completionSteps['name'] ? 'fa-check-circle' : 'fa-circle' }}"></i>
                                                <span>Nom complet</span>
                                            </div>
                                            <div class="completion-item {{ $completionSteps['username'] ? 'completed' : '' }}">
                                                <i class="fas {{ $completionSteps['username'] ? 'fa-check-circle' : 'fa-circle' }}"></i>
                                                <span>Nom d'utilisateur</span>
                                            </div>
                                            <div class="completion-item {{ $completionSteps['bio'] ? 'completed' : '' }}">
                                                <i class="fas {{ $completionSteps['bio'] ? 'fa-check-circle' : 'fa-circle' }}"></i>
                                                <span>Bio</span>
                                            </div>
                                            <div class="completion-item {{ $completionSteps['avatar'] ? 'completed' : '' }}">
                                                <i class="fas {{ $completionSteps['avatar'] ? 'fa-check-circle' : 'fa-circle' }}"></i>
                                                <span>Photo de profil</span>
                                            </div>
                                        </div>
                                        
                                        @if($completionPercent < 100)
                                            <div class="completion-bonus">
                                                <i class="fas fa-star"></i>
                                                <p>Complétez votre profil à 100% et gagnez <strong>+15 points</strong> !</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="col-md-8">
                                    <div class="form-group mb-4">
                                        <label for="name" class="form-label">Nom complet <span class="required">*</span></label>
                                        <input type="text" class="form-control custom-input @error('name') is-invalid @enderror" 
                                               id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                        <div class="form-text">Votre nom complet tel qu'il apparaîtra sur votre profil</div>
                                    </div>
                                    
                                    <div class="form-group mb-4">
                                        <label for="username" class="form-label">Nom d'utilisateur</label>
                                        <div class="input-group">
                                            <span class="input-group-text">@</span>
                                            <input type="text" class="form-control custom-input @error('username') is-invalid @enderror" 
                                                   id="username" name="username" value="{{ old('username', $user->username) }}">
                                        </div>
                                        <div class="form-text">Choisissez un nom d'utilisateur unique (sans espaces ni caractères spéciaux)</div>
                                    </div>
                                    
                                    <div class="form-group mb-4">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control custom-input" id="email" value="{{ $user->email }}" disabled>
                                        <div class="form-text">Vous ne pouvez pas modifier votre adresse email. Contactez l'administrateur pour tout changement.</div>
                                    </div>
                                    
                                    <div class="form-group mb-4">
                                        <label for="bio" class="form-label">Biographie</label>
                                        <textarea class="form-control custom-textarea @error('bio') is-invalid @enderror" 
                                                  id="bio" name="bio" rows="4">{{ old('bio', $user->bio) }}</textarea>
                                        <div class="d-flex justify-content-between">
                                            <div class="form-text">Partagez quelques informations sur vous</div>
                                            <div class="char-counter"><span id="bioCharCount">0</span>/500</div>
                                        </div>
                                    </div>
                                    
                                    <h3 class="settings-section-title mt-5">Préférences</h3>
                                    
                                    <div class="preferences-section">
                                        @php
                                            $preferences = $user->preferences ?? [];
                                        @endphp
                                        
                                        <div class="form-group mb-3">
                                            <label class="form-label">Notifications</label>
                                            <div class="preference-toggle-group">
                                                <div class="preference-toggle">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" id="pref_email_comments" 
                                                               name="preferences[email_comments]" 
                                                               {{ isset($preferences['email_comments']) && $preferences['email_comments'] ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="pref_email_comments">
                                                            Recevoir des emails pour les commentaires
                                                        </label>
                                                    </div>
                                                </div>
                                                
                                                <div class="preference-toggle">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" id="pref_email_points" 
                                                               name="preferences[email_points]" 
                                                               {{ isset($preferences['email_points']) && $preferences['email_points'] ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="pref_email_points">
                                                            Recevoir des emails pour les points gagnés
                                                        </label>
                                                    </div>
                                                </div>
                                                
                                                <div class="preference-toggle">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" id="pref_email_badges" 
                                                               name="preferences[email_badges]" 
                                                               {{ isset($preferences['email_badges']) && $preferences['email_badges'] ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="pref_email_badges">
                                                            Recevoir des emails pour les badges
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group mb-3">
                                            <label class="form-label">Confidentialité</label>
                                            <div class="preference-toggle-group">
                                                <div class="preference-toggle">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" id="pref_profile_public" 
                                                               name="preferences[profile_public]" 
                                                               {{ isset($preferences['profile_public']) && $preferences['profile_public'] ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="pref_profile_public">
                                                            Profil public
                                                        </label>
                                                    </div>
                                                </div>
                                                
                                                <div class="preference-toggle">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" id="pref_show_points" 
                                                               name="preferences[show_points]" 
                                                               {{ isset($preferences['show_points']) && $preferences['show_points'] ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="pref_show_points">
                                                            Afficher mes points publiquement
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-actions mt-5">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-2"></i> Enregistrer les modifications
                                        </button>
                                        <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary ms-2">
                                            <i class="fas fa-times me-2"></i> Annuler
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Utilisation des styles existants de la page de profil */
.profile-container {
    background-color: #000;
    color: #fff;
    padding: 2rem 0;
    min-height: calc(100vh - 70px);
}

.section-title {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0;
    color: #fff;
}

.section-title i {
    color: #E67E22;
    margin-right: 0.5rem;
}

.btn-back {
    background-color: #333;
    color: #fff;
    border: none;
    padding: 0.5rem 1.2rem;
    border-radius: 8px;
    font-size: 0.9rem;
    transition: all 0.3s;
}

.btn-back:hover {
    background-color: #E67E22;
    color: #fff;
}

.profile-card {
    background-color: #111;
    border: 1px solid #222;
    border-radius: 10px;
    overflow: hidden;
    margin-bottom: 1.5rem;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
}

.card-header {
    background-color: #191919;
    border-bottom: 1px solid #333;
    padding: 1rem 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-title {
    margin: 0;
    font-size: 1.2rem;
    font-weight: 600;
    color: #fff;
}

.card-body {
    padding: 1.5rem;
}

/* Avatar */
.avatar-preview {
    position: relative;
    width: 160px;
    height: 160px;
    margin: 0 auto 1.5rem;
    overflow: hidden;
    cursor: pointer;
}

.profile-avatar, .avatar-edit {
    width: 160px;
    height: 160px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #E67E22;
}

.profile-avatar-placeholder {
    width: 160px;
    height: 160px;
    border-radius: 50%;
    background-color: #333;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #666;
    font-size: 2.5rem;
    border: 3px solid #E67E22;
}

.avatar-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: all 0.3s;
    color: #fff;
    border-radius: 50%;
}

.avatar-overlay i {
    font-size: 2rem;
    margin-bottom: 0.5rem;
}

.avatar-preview:hover .avatar-overlay {
    opacity: 1;
}

.avatar-input {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    cursor: pointer;
    z-index: 10;
}

.avatar-tips {
    background-color: #1a1a1a;
    border-radius: 8px;
    padding: 1rem;
    font-size: 0.9rem;
    color: #ccc;
    margin-bottom: 1.5rem;
}

.avatar-tips h4 {
    font-size: 1rem;
    font-weight: 600;
    color: #E67E22;
    margin-bottom: 0.8rem;
}

.avatar-tips ul {
    padding-left: 1.5rem;
    margin-bottom: 0.8rem;
}

/* Progression du profil */
.profile-completion {
    background-color: #1a1a1a;
    border-radius: 8px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
}

.profile-completion h4 {
    font-size: 1.1rem;
    font-weight: 600;
    color: #E67E22;
    margin-bottom: 1rem;
}

.level-progress {
    height: 8px;
    background-color: #333;
    border-radius: 4px;
    margin-bottom: 1rem;
    overflow: hidden;
}

.progress-bar {
    background-color: #E67E22;
    border-radius: 4px;
}

.completion-checklist {
    margin-bottom: 1rem;
}

.completion-item {
    display: flex;
    align-items: center;
    margin-bottom: 0.8rem;
    color: #999;
    transition: all 0.3s;
}

.completion-item i {
    margin-right: 0.8rem;
    font-size: 1rem;
}

.completion-item.completed {
    color: #fff;
}

.completion-item.completed i {
    color: #4CAF50;
}

.completion-bonus {
    background: linear-gradient(135deg, #E67E22, #F39C12);
    padding: 1rem;
    border-radius: 8px;
    color: #fff;
    display: flex;
    align-items: center;
}

.completion-bonus i {
    font-size: 1.5rem;
    margin-right: 1rem;
    color: #fff;
}

.completion-bonus p {
    margin-bottom: 0;
    font-weight: 500;
}

/* Formulaire */
.form-label {
    color: #fff;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.required {
    color: #ff5757;
    margin-left: 0.2rem;
}

.custom-input, .custom-textarea {
    background-color: #1a1a1a;
    border: 1px solid #333;
    border-radius: 8px;
    color: #fff;
    padding: 0.8rem 1rem;
    transition: all 0.3s;
}

.custom-input:focus, .custom-textarea:focus {
    background-color: #222;
    border-color: #E67E22;
    box-shadow: 0 0 0 0.25rem rgba(230, 126, 34, 0.25);
    color: #fff;
}

.input-group-text {
    background-color: #E67E22;
    border: none;
    color: #fff;
    border-radius: 8px 0 0 8px;
}

.form-text {
    color: #999;
    font-size: 0.85rem;
    margin-top: 0.5rem;
}

.char-counter {
    color: #999;
    font-size: 0.85rem;
    margin-top: 0.5rem;
}

.settings-section-title {
    font-size: 1.3rem;
    font-weight: 700;
    color: #E67E22;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid #333;
}

.preferences-section {
    background-color: #1a1a1a;
    border-radius: 8px;
    padding: 1.5rem;
}

.preference-toggle-group {
    margin-top: 0.8rem;
}

.preference-toggle {
    margin-bottom: 0.8rem;
}

.form-check-input {
    background-color: #333;
    border-color: #555;
}

.form-check-input:checked {
    background-color: #E67E22;
    border-color: #E67E22;
}

.form-check-label {
    color: #ccc;
}

.form-actions {
    display: flex;
    align-items: center;
}

.btn-primary {
    background-color: #E67E22;
    border: none;
    padding: 0.7rem 1.5rem;
    font-weight: 600;
    transition: all 0.3s;
    border-radius: 8px;
}

.btn-primary:hover {
    background-color: #D35400;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(230, 126, 34, 0.3);
}

.btn-outline-secondary {
    background-color: transparent;
    border: 1px solid #555;
    color: #ccc;
    padding: 0.7rem 1.5rem;
    font-weight: 600;
    transition: all 0.3s;
    border-radius: 8px;
}

.btn-outline-secondary:hover {
    background-color: #333;
    color: #fff;
}

.alert-danger {
    background-color: rgba(255, 87, 87, 0.1);
    border: 1px solid rgba(255, 87, 87, 0.3);
    color: #ff5757;
    border-radius: 8px;
}

.alert-danger ul {
    padding-left: 1.5rem;
}

.alert-heading {
    display: flex;
    align-items: center;
    font-size: 1.1rem;
    font-weight: 600;
}

.alert-heading i {
    margin-right: 0.5rem;
}

/* Responsive */
@media (max-width: 767px) {
    .level-info {
        margin-top: 1.5rem;
    }
}
</style>

<script>
// Prévisualisation de l'avatar
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        
        reader.onload = function(e) {
            if (document.querySelector('#avatar-preview').tagName === 'IMG') {
                document.querySelector('#avatar-preview').src = e.target.result;
            } else {
                // Si c'est un placeholder, créer une image à la place
                const placeholder = document.querySelector('#avatar-preview');
                const parent = placeholder.parentNode;
                
                const img = document.createElement('img');
                img.id = 'avatar-preview';
                img.src = e.target.result;
                img.className = 'profile-avatar';
                
                parent.replaceChild(img, placeholder);
            }
            
            // Désactiver la case à cocher "Supprimer avatar"
            document.querySelector('#removeAvatar').checked = false;
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}

// Supprimer l'avatar
document.querySelector('#removeAvatar').addEventListener('change', function() {
    if (this.checked) {
        // Si une image est sélectionnée dans l'input file, la supprimer
        document.querySelector('#avatar').value = '';
        
        // Remplacer l'image par un placeholder si ce n'est pas déjà le cas
        if (document.querySelector('#avatar-preview').tagName === 'IMG') {
            const img = document.querySelector('#avatar-preview');
            const parent = img.parentNode;
            
            const placeholder = document.createElement('div');
            placeholder.id = 'avatar-preview';
            placeholder.className = 'profile-avatar-placeholder';
            placeholder.innerHTML = '<i class="fas fa-user"></i>';
            
            parent.replaceChild(placeholder, img);
        }
    }
});

// Compteur de caractères pour la bio
document.querySelector('#bio').addEventListener('input', function() {
    const maxLength = 500;
    const currentLength = this.value.length;
    
    document.querySelector('#bioCharCount').textContent = currentLength;
    
    if (currentLength > maxLength) {
        this.value = this.value.substring(0, maxLength);
        document.querySelector('#bioCharCount').textContent = maxLength;
    }
});

// Initialiser le compteur
document.addEventListener('DOMContentLoaded', function() {
    const bioLength = document.querySelector('#bio').value.length;
    document.querySelector('#bioCharCount').textContent = bioLength;
});
</script>
@endsection
