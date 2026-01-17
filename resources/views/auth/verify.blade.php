{{-- @extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Verify Your Email Address') }}</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('A fresh verification link has been sent to your email address.') }}
                        </div>
                    @endif

                    {{ __('Before proceeding, please check your email for a verification link.') }}
                    {{ __('If you did not receive the email') }},
                    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('click here to request another') }}</button>.
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection --}}

@extends('frontend.layouts.app')

@section('title', 'Verify Email - EMS')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-body p-5 text-center">
                        <div class="mb-4">
                            <i class="fas fa-envelope-circle-check fa-4x " style="color: var(--primary-color)"></i>
                        </div>

                        <h2 class="mb-3">Verify Your Email Address</h2>

                        <p class="text-muted">
                            Before proceeding, please check your inbox/junk for a verification link.
                        </p>
                        <p class="mb-4 text-muted">
                            If you did not receive the email, click the button below to request another.
                        </p>

                        <div class="mt-4">
                            <form class="d-inline" method="POST" action="{{ route('verification.resend') }}" id="resendForm">
                                @csrf
                                <button type="submit" class="btn custom-btn" id="resendBtn">
                                    <i class="fas fa-paper-plane me-2"></i>
                                    <span id="btnText">Click here to request another</span>
                                </button>
                            </form>
                            <div class="mt-3">
                                <small class="text-muted" id="countdownText" style="display: none;">
                                    <i class="fas fa-clock me-1"></i>
                                    You can request another email in <strong id="countdown">60</strong> seconds
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Button and countdown management
        const resendForm = document.getElementById('resendForm');
        const resendBtn = document.getElementById('resendBtn');
        const btnText = document.getElementById('btnText');
        const countdownText = document.getElementById('countdownText');
        const countdownNumber = document.getElementById('countdown');
        
        let countdownInterval;
        let timeRemaining = 60;

        // Check if there's a stored countdown in localStorage
        const storedTime = localStorage.getItem('verificationCountdown');
        const storedTimestamp = localStorage.getItem('verificationTimestamp');
        
        if (storedTime && storedTimestamp) {
            const elapsed = Math.floor((Date.now() - parseInt(storedTimestamp)) / 1000);
            const remaining = parseInt(storedTime) - elapsed;
            
            if (remaining > 0) {
                timeRemaining = remaining;
                startCountdown();
            } else {
                localStorage.removeItem('verificationCountdown');
                localStorage.removeItem('verificationTimestamp');
            }
        }

        function startCountdown() {
            resendBtn.disabled = true;
            resendBtn.classList.add('disabled');
            countdownText.style.display = 'block';
            countdownNumber.textContent = timeRemaining;

            countdownInterval = setInterval(function() {
                timeRemaining--;
                countdownNumber.textContent = timeRemaining;

                if (timeRemaining <= 0) {
                    clearInterval(countdownInterval);
                    resendBtn.disabled = false;
                    resendBtn.classList.remove('disabled');
                    countdownText.style.display = 'none';
                    localStorage.removeItem('verificationCountdown');
                    localStorage.removeItem('verificationTimestamp');
                }
            }, 1000);
        }

        if (resendForm && resendBtn) {
            resendForm.addEventListener('submit', function(e) {
                if (resendBtn.disabled) {
                    e.preventDefault();
                    return false;
                }
                
                // Set countdown on submit
                localStorage.setItem('verificationCountdown', '60');
                localStorage.setItem('verificationTimestamp', Date.now().toString());
                
                resendBtn.disabled = true;
                btnText.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Sending...';
            });
        }

        // Show success toast when a new verification link is sent
        @if(session('resent'))
            document.addEventListener('DOMContentLoaded', function() {
                setTimeout(function() {
                    const verificationToastEl = document.getElementById('verificationToast');
                    if (verificationToastEl) {
                        if (typeof bootstrap !== 'undefined' && bootstrap.Toast) {
                            const toast = new bootstrap.Toast(verificationToastEl, { 
                                delay: 5000,
                                autohide: true 
                            });
                            toast.show();
                        } else {
                            // Fallback if Bootstrap is not loaded
                            verificationToastEl.classList.add('show');
                            setTimeout(() => {
                                verificationToastEl.classList.remove('show');
                            }, 5000);
                        }
                    }
                }, 200);
            });
        @endif
    </script>
    @endpush

    @if (session('resent'))
        <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;">
            <div id="verificationToast" class="toast show align-items-center text-white border-0" role="alert"
                aria-live="assertive" aria-atomic="true"
                style="background: linear-gradient(135deg, var(--primary-color), var(--primary-dark)); border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); min-width: 350px;">
                <div class="d-flex align-items-center p-3">
                    <div class="toast-body d-flex align-items-start" style="flex: 1;">
                        <i class="fas fa-check-circle me-3" style="font-size: 1.2rem; margin-top: 2px;"></i>
                        <div>
                            <strong style="display: block; margin-bottom: 4px;">Verification Email Sent!</strong>
                            <span style="font-size: 0.9rem; opacity: 0.95;">Please check your inbox/junk in a few minutes.</span>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white ms-2" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>
        </div>
    @endif

    <style>
        .toast {
            animation: slideInRight 0.4s ease-out;
        }

        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .btn-close-white {
            filter: brightness(0) invert(1);
        }
    </style>
@endsection
