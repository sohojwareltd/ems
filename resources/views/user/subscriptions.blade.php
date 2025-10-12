@extends('frontend.layouts.app')

@section('title', 'My Subscriptions')

@section('content')
    <div class="container py-5">
        <h2 class="mb-4 fw-bold text-dark">
            <i class="fas fa-receipt  me-2" style="color: var(--primary-color)"></i>My Subscriptions
        </h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @elseif(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if ($subscriptions->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Plan</th>
                            <th>Status</th>
                            <th>Trial Ends</th>
                            <th>Ends At</th>
                            <th>Amount</th>
                            <th>Card</th>
                            <th>Default</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($subscriptions as $subscription)
                            <tr>
                                <td>{{ $subscription->plan->name }}</td>
                                <td>
                                    <span class="badge bg-{{ $subscription->status->color() }}">
                                        {{ $subscription->status->label() }}
                                    </span>
                                </td>
                                <td>{{ $subscription->trial_ends_at ? $subscription->trial_ends_at->format('M d, Y') : '-' }}
                                </td>
                                <td>{{ $subscription->ends_at ? $subscription->ends_at->format('M d, Y') : '-' }}</td>
                                <td>${{ number_format($subscription->amount, 2) }}</td>
                                <td>
                                    @if ($subscription->metadata)
                                        @php
                                            $metadata = json_decode($subscription->metadata, true);
                                            $brand = $metadata['brand'] ?? 'Card';
                                            $last4 = $metadata['last4'] ?? '****';
                                            $expMonth = $metadata['expiry_month'] ?? '';
                                            $expYear = $metadata['expiry_year'] ?? '';
                                        @endphp
                                        <span class="d-block"><strong>{{ $brand }}</strong></span>
                                        <span>**** **** **** {{ $last4 }}</span>
                                        @if ($expMonth && $expYear)
                                            <small>Exp: {{ $expMonth }}/{{ $expYear }}</small>
                                        @endif
                                    @else
                                        <span class="text-muted small">No Card Info</span>
                                    @endif
                                </td>
                                <td>
                                    @if (!$subscription->status->isCanceled())
                                        @if ($subscription->type === 'default')
                                            <span class="badge custom-btn-outline">Default</span>
                                        @else
                                            <form action="{{ route('user.subscription.set-default', $subscription->id) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm custom-btn-outline">Set as
                                                    Default</button>
                                            </form>
                                        @endif
                                    @else
                                        <span class="text-muted small">Canceled</span>
                                    @endif
                                </td>

                                <td>
                                    @if ($subscription->status->isValid())
                                        <form action="{{ route('user.subscription.cancel', $subscription->id) }}"
                                            method="POST" class="d-inline"
                                            onsubmit="return confirm('Are you sure to cancel this subscription?');">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-times"></i> Cancel
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted small">No Actions</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle me-2"></i>No subscriptions found.
            </div>
        @endif
    </div>

    <style>
        .badge {
            font-size: 0.75rem;
            padding: 0.5rem 0.75rem;
        }

        .table td,
        .table th {
            vertical-align: middle;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.825rem;
        }
    </style>
@endsection
