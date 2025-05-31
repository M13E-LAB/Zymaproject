@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center bg-white py-3">
                    <h5 class="mb-0 fw-bold text-primary">
                        <i class="fas fa-trophy me-2"></i>Créer une nouvelle ligue
                    </h5>
                    <a href="{{ route('leagues.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Retour
                    </a>
                </div>
                <div class="card-body p-4">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('leagues.store') }}">
                        @csrf
                        <div class="mb-4">
                            <label for="name" class="form-label fw-semibold">Nom de la ligue <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required maxlength="50">
                            <div class="form-text">Choisissez un nom unique pour votre ligue (50 caractères max.)</div>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label fw-semibold">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" maxlength="255">{{ old('description') }}</textarea>
                            <div class="form-text">Décrivez brièvement l'objectif de votre ligue (255 caractères max.)</div>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="max_members" class="form-label fw-semibold">Nombre maximum de membres</label>
                            <input type="number" class="form-control @error('max_members') is-invalid @enderror" id="max_members" name="max_members" value="{{ old('max_members', 50) }}" min="3" max="100">
                            <div class="form-text">Limitez le nombre de personnes pouvant rejoindre votre ligue (entre 3 et 100)</div>
                            @error('max_members')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input @error('is_private') is-invalid @enderror" type="checkbox" id="is_private" name="is_private" value="1" {{ old('is_private') ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="is_private">
                                    Ligue privée
                                </label>
                            </div>
                            <div class="form-text">Les ligues privées nécessitent un code d'invitation pour être rejointes</div>
                            @error('is_private')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('leagues.index') }}" class="btn btn-light">Annuler</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i> Créer la ligue
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        border-radius: 12px;
        border: none;
    }
    
    .card-header {
        border-radius: 12px 12px 0 0 !important;
        border-bottom: 1px solid rgba(0,0,0,0.05);
    }
    
    .btn-primary {
        background-color: #4F46E5;
        border-color: #4F46E5;
    }
    
    .btn-primary:hover {
        background-color: #4338CA;
        border-color: #4338CA;
    }
    
    .btn-outline-secondary {
        color: #6B7280;
        border-color: #D1D5DB;
    }
    
    .btn-outline-secondary:hover {
        background-color: #F3F4F6;
        color: #374151;
        border-color: #D1D5DB;
    }
    
    .text-primary {
        color: #4F46E5 !important;
    }
    
    .form-control:focus {
        border-color: #A5B4FC;
        box-shadow: 0 0 0 0.25rem rgba(79, 70, 229, 0.1);
    }
    
    .form-check-input:checked {
        background-color: #4F46E5;
        border-color: #4F46E5;
    }
    
    .form-text {
        color: #6B7280;
        font-size: 0.85rem;
    }
</style>
@endsection 