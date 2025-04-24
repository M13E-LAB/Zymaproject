@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card h-100 position-relative overflow-hidden">
                <div class="card-body p-5">
                    <h2 class="display-5 mb-4">Rejoignez ZYMA</h2>
                    <p class="text-secondary mb-4">Découvrez, partagez et économisez sur vos produits préférés.</p>

                    <form method="POST" action="{{ route('register') }}" class="mb-4">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="name" class="form-label">Nom complet</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Votre nom">
                            </div>
                            @error('name')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="email" class="form-label">Adresse email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="votre@email.com">
                            </div>
                            @error('email')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label">Mot de passe</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="8 caractères minimum">
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div class="password-strength mt-2 d-none" id="passwordStrength">
                                <div class="progress" style="height: 5px;">
                                    <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <small class="text-secondary" id="passwordStrengthText">Faible</small>
                            </div>
                            @error('password')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password-confirm" class="form-label">Confirmer le mot de passe</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="Répétez votre mot de passe">
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="terms" id="terms" required>
                                <label class="form-check-label" for="terms">
                                    J'accepte les <a href="#" class="text-decoration-none">conditions d'utilisation</a> et la <a href="#" class="text-decoration-none">politique de confidentialité</a>
                                </label>
                            </div>
                        </div>

                        <div class="mb-4">
                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                <i class="fas fa-user-plus me-2"></i> Créer mon compte
                            </button>
                        </div>
                        
                        <div class="text-center">
                            <p class="mb-0">Vous avez déjà un compte ? <a href="{{ route('login') }}" class="text-decoration-none">Connectez-vous</a></p>
                        </div>
                    </form>
                </div>
                
                <div class="position-absolute bottom-0 end-0 p-4 d-none d-lg-block">
                    <img src="https://cdn.pixabay.com/photo/2017/12/09/08/18/pizza-3007395_960_720.png" alt="Food" style="width: 200px; opacity: 0.1;">
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card h-100 p-0 overflow-hidden">
                <div class="card-body p-5">
                    <h3 class="mb-4">Pourquoi rejoindre ZYMA ?</h3>
                    
                    <div class="mb-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="badge-item earned me-3">
                                <i class="fas fa-trophy"></i>
                            </div>
                            <div>
                                <h5 class="mb-1">Gagnez des points</h5>
                                <p class="mb-0 text-secondary">Recevez des récompenses pour chaque contribution au sein de la communauté.</p>
                            </div>
                        </div>
                        
                        <div class="d-flex align-items-center mb-3">
                            <div class="badge-item earned me-3">
                                <i class="fas fa-store"></i>
                            </div>
                            <div>
                                <h5 class="mb-1">Découvrez les meilleures offres</h5>
                                <p class="mb-0 text-secondary">Trouvez les promotions près de chez vous grâce aux partages de la communauté.</p>
                            </div>
                        </div>
                        
                        <div class="d-flex align-items-center mb-3">
                            <div class="badge-item earned me-3">
                                <i class="fas fa-camera"></i>
                            </div>
                            <div>
                                <h5 class="mb-1">Partagez vos trouvailles</h5>
                                <p class="mb-0 text-secondary">Prenez des photos de vos produits préférés et partagez-les instantanément.</p>
                            </div>
                        </div>
                        
                        <div class="d-flex align-items-center">
                            <div class="badge-item earned me-3">
                                <i class="fas fa-users"></i>
                            </div>
                            <div>
                                <h5 class="mb-1">Rejoignez une communauté</h5>
                                <p class="mb-0 text-secondary">Connectez-vous avec d'autres passionnés de bonnes affaires et de découvertes culinaires.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card" style="background: var(--bg-tertiary);">
                        <div class="card-body p-3">
                            <h5 class="mb-2">Progression des nouveaux membres</h5>
                            <div class="progress mb-2" style="height: 10px;">
                                <div class="progress-bar" role="progressbar" style="width: 75%;" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <small>Déjà <span class="text-success">+2500</span> membres</small>
                                <small>Objectif: 5000</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="position-relative">
                    <div class="card-img-overlay d-flex align-items-center justify-content-center" style="background-color: rgba(0, 0, 0, 0.4); height: 250px;">
                        <div class="text-center">
                            <h3 class="text-white mb-0">Commencez votre aventure dès maintenant</h3>
                        </div>
                    </div>
                    <img src="https://images.unsplash.com/photo-1550989460-0adf9ea622e2?ixlib=rb-1.2.1&auto=format&fit=crop&w=1000&q=80" class="card-img-bottom" alt="Food Market" style="height: 250px; object-fit: cover;">
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle password visibility
        const togglePassword = document.getElementById('togglePassword');
        const password = document.getElementById('password');
        
        togglePassword.addEventListener('click', function() {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });
        
        // Password strength meter
        const passwordInput = document.getElementById('password');
        const strengthBar = document.querySelector('#passwordStrength .progress-bar');
        const strengthText = document.getElementById('passwordStrengthText');
        const strengthContainer = document.getElementById('passwordStrength');
        
        passwordInput.addEventListener('input', function() {
            const value = passwordInput.value;
            
            if (value.length > 0) {
                strengthContainer.classList.remove('d-none');
                
                // Calculate strength
                let strength = 0;
                
                // Length
                if (value.length >= 8) strength += 25;
                
                // Uppercase
                if (/[A-Z]/.test(value)) strength += 25;
                
                // Lowercase
                if (/[a-z]/.test(value)) strength += 25;
                
                // Numbers or special chars
                if (/[0-9!@#$%^&*(),.?":{}|<>]/.test(value)) strength += 25;
                
                // Update UI
                strengthBar.style.width = strength + '%';
                
                if (strength < 25) {
                    strengthBar.className = 'progress-bar bg-danger';
                    strengthText.textContent = 'Très faible';
                } else if (strength < 50) {
                    strengthBar.className = 'progress-bar bg-warning';
                    strengthText.textContent = 'Faible';
                } else if (strength < 75) {
                    strengthBar.className = 'progress-bar bg-info';
                    strengthText.textContent = 'Moyen';
                } else {
                    strengthBar.className = 'progress-bar bg-success';
                    strengthText.textContent = 'Fort';
                }
            } else {
                strengthContainer.classList.add('d-none');
            }
        });
        
        // Animated entrance
        document.querySelectorAll('.card').forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            
            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 100 * (index + 1));
        });
    });
</script>
@endsection
