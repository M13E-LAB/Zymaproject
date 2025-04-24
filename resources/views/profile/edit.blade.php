@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="page-title">
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
            
            <div class="card profile-edit-card">
                <div class="card-body p-lg-5">
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="avatar-upload-container">
                                    <div class="avatar-preview mb-4">
                                        @if($user->avatar)
                                            <img id="avatar-preview" src="{{ $user->avatar }}" alt="Avatar" class="avatar-edit">
                                        @else
                                            <div id="avatar-preview" class="avatar-placeholder">
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
                                    
                                    <div class="progress progress-lg mb-3">
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
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-save me-2"></i> Enregistrer les modifications
                                    </button>
                                    <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary btn-lg ms-2">
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

<style>
.page-title {
    font-size: 2rem;
    font-weight: 800;
    color: var(--text-primary);
    display: flex;
    align-items: center;
}

.page-title i {
    color: var(--accent-primary);
    margin-right: 1rem;
    font-size: 1.8rem;
}

.btn-back {
    background: rgba(255, 255, 255, 0.05);
    color: var(--text-primary);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    padding: 0.5rem 1.2rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-back:hover {
    background: var(--accent-primary);
    color: var(--bg-primary);
    transform: translateX(-5px);
}

.profile-edit-card {
    border: none;
    border-radius: 24px;
    overflow: hidden;
    background: linear-gradient(135deg, var(--bg-tertiary) 0%, var(--bg-secondary) 100%);
    animation: fadeIn 0.5s ease-out;
}

.avatar-upload-container {
    margin-bottom: 2rem;
}

.avatar-preview {
    position: relative;
    width: 200px;
    height: 200px;
    margin: 0 auto 1.5rem;
    border-radius: 50%;
    overflow: hidden;
    cursor: pointer;
    transition: all 0.3s ease;
    border: 4px solid var(--accent-primary);
    box-shadow: 0 0 30px rgba(0, 209, 178, 0.3);
}

.avatar-edit {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.avatar-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--accent-gradient);
    font-size: 4rem;
    color: var(--bg-primary);
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
    transition: all 0.3s ease;
    color: #fff;
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
    background: rgba(0, 0, 0, 0.2);
    border-radius: 12px;
    padding: 1rem;
    font-size: 0.9rem;
    color: var(--text-secondary);
}

.avatar-tips h4 {
    font-size: 1rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.8rem;
}

.avatar-tips ul {
    padding-left: 1.5rem;
    margin-bottom: 0.8rem;
}

.profile-completion {
    background: rgba(0, 0, 0, 0.2);
    border-radius: 12px;
    padding: 1.5rem;
    margin-top: 2rem;
}

.profile-completion h4 {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 1rem;
}

.progress-lg {
    height: 1rem;
    border-radius: 0.5rem;
    background: rgba(255, 255, 255, 0.1);
    margin-bottom: 1.5rem;
}

.completion-checklist {
    margin-bottom: 1rem;
}

.completion-item {
    display: flex;
    align-items: center;
    margin-bottom: 0.8rem;
    color: var(--text-secondary);
    transition: all 0.3s ease;
}

.completion-item i {
    margin-right: 0.8rem;
    font-size: 1.1rem;
}

.completion-item.completed {
    color: var(--text-primary);
}

.completion-item.completed i {
    color: var(--accent-primary);
}

.completion-bonus {
    background: var(--accent-gradient);
    padding: 1rem;
    border-radius: 12px;
    color: var(--bg-primary);
    margin-top: 1.5rem;
    display: flex;
    align-items: center;
}

.completion-bonus i {
    font-size: 1.5rem;
    margin-right: 1rem;
}

.completion-bonus p {
    margin-bottom: 0;
    font-weight: 500;
}

.form-label {
    color: var(--text-primary);
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.required {
    color: #ff5757;
    margin-left: 0.2rem;
}

.custom-input, .custom-textarea {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    color: var(--text-primary);
    padding: 0.8rem 1rem;
    transition: all 0.3s ease;
}

.custom-input:focus, .custom-textarea:focus {
    background: rgba(255, 255, 255, 0.1);
    border-color: var(--accent-primary);
    box-shadow: 0 0 0 0.25rem rgba(0, 209, 178, 0.25);
    color: var(--text-primary);
}

.input-group-text {
    background: rgba(0, 209, 178, 0.2);
    border: 1px solid rgba(0, 209, 178, 0.3);
    color: var(--accent-primary);
    border-radius: 12px 0 0 12px;
}

.form-text {
    color: var(--text-secondary);
    font-size: 0.85rem;
    margin-top: 0.5rem;
}

.char-counter {
    color: var(--text-secondary);
    font-size: 0.85rem;
    margin-top: 0.5rem;
}

.settings-section-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 1.5rem;
    padding-bottom: 0.8rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.preferences-section {
    background: rgba(0, 0, 0, 0.2);
    border-radius: 12px;
    padding: 1.5rem;
}

.preference-toggle-group {
    margin-top: 0.8rem;
}

.preference-toggle {
    margin-bottom: 0.8rem;
}

.form-check-input {
    background-color: rgba(255, 255, 255, 0.1);
    border-color: rgba(255, 255, 255, 0.2);
    width: 2.5rem;
    height: 1.25rem;
}

.form-check-input:checked {
    background-color: var(--accent-primary);
    border-color: var(--accent-primary);
}

.form-check-label {
    color: var(--text-primary);
    font-weight: 500;
}

.form-actions {
    display: flex;
    align-items: center;
}

.btn-primary {
    background: var(--accent-gradient);
    border: none;
    padding: 0.8rem 1.5rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 20px rgba(0, 209, 178, 0.3);
}

.btn-outline-secondary {
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: var(--text-primary);
    padding: 0.8rem 1.5rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-outline-secondary:hover {
    background: rgba(255, 255, 255, 0.1);
    color: var(--text-primary);
}

.alert-danger {
    background: rgba(255, 87, 87, 0.1);
    border: 1px solid rgba(255, 87, 87, 0.3);
    border-radius: 12px;
    color: #ff5757;
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
                img.className = 'avatar-edit';
                
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
            placeholder.className = 'avatar-placeholder';
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