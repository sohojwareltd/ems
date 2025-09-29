@extends('frontend.layouts.app')

@section('title', 'Verify Email - MyShop')

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
                            A fresh verification link has been sent to your email address.
                        </p>
                        <p class="mb-4 text-muted">
                            If you did not receive the email, click the button below to request another.
                        </p>


                        <div class="mt-4">
                            {{-- <a href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                class="btn custom-btn">
                                Logout
                            </a> --}}
                            <form method="POST" action="{{ route('verification.send') }}">
                                @csrf
                                <button type="submit" class="btn btn-primary">Resend Verification Email</button>
                            </form>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
