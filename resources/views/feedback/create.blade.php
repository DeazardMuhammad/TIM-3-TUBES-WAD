@extends('layouts.app')

@section('title', 'Berikan Feedback')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0"><i class="bi bi-star"></i> Berikan Rating & Feedback</h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <strong>Barang:</strong> {{ $claim->foundItem->nama }}<br>
                        <strong>Kategori:</strong> {{ $claim->foundItem->kategori->nama }}
                    </div>

                    <form action="{{ route('feedback.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="claim_id" value="{{ $claim->id }}">

                        <div class="mb-4">
                            <label class="form-label">Rating *</label>
                            <div class="d-flex gap-2 justify-content-center" id="ratingStars">
                                <i class="bi bi-star star-rating" data-rating="1" style="font-size: 2.5rem; cursor: pointer;"></i>
                                <i class="bi bi-star star-rating" data-rating="2" style="font-size: 2.5rem; cursor: pointer;"></i>
                                <i class="bi bi-star star-rating" data-rating="3" style="font-size: 2.5rem; cursor: pointer;"></i>
                                <i class="bi bi-star star-rating" data-rating="4" style="font-size: 2.5rem; cursor: pointer;"></i>
                                <i class="bi bi-star star-rating" data-rating="5" style="font-size: 2.5rem; cursor: pointer;"></i>
                            </div>
                            <input type="hidden" name="rating" id="ratingInput" required>
                            @error('rating')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="komentar" class="form-label">Komentar (Opsional)</label>
                            <textarea name="komentar" id="komentar" class="form-control @error('komentar') is-invalid @enderror" rows="4" placeholder="Bagaimana pengalaman Anda dengan sistem Lost & Found ini?">{{ old('komentar') }}</textarea>
                            @error('komentar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('dashboard') }}" class="btn btn-secondary">Lewati</a>
                            <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
                                <i class="bi bi-send"></i> Kirim Feedback
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let selectedRating = 0;

document.querySelectorAll('.star-rating').forEach(star => {
    star.addEventListener('click', function() {
        selectedRating = parseInt(this.dataset.rating);
        document.getElementById('ratingInput').value = selectedRating;
        document.getElementById('submitBtn').disabled = false;
        
        // Update star display
        document.querySelectorAll('.star-rating').forEach(s => {
            const starRating = parseInt(s.dataset.rating);
            if (starRating <= selectedRating) {
                s.classList.remove('bi-star');
                s.classList.add('bi-star-fill');
                s.style.color = '#ffc107';
            } else {
                s.classList.remove('bi-star-fill');
                s.classList.add('bi-star');
                s.style.color = '#000';
            }
        });
    });
    
    // Hover effect
    star.addEventListener('mouseenter', function() {
        const hoverRating = parseInt(this.dataset.rating);
        document.querySelectorAll('.star-rating').forEach(s => {
            const starRating = parseInt(s.dataset.rating);
            if (starRating <= hoverRating) {
                s.style.color = '#ffc107';
            } else {
                if (starRating > selectedRating) {
                    s.style.color = '#000';
                }
            }
        });
    });
});

document.getElementById('ratingStars').addEventListener('mouseleave', function() {
    document.querySelectorAll('.star-rating').forEach(s => {
        const starRating = parseInt(s.dataset.rating);
        if (starRating <= selectedRating) {
            s.style.color = '#ffc107';
        } else {
            s.style.color = '#000';
        }
    });
});
</script>
@endpush

