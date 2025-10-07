@extends('frontend.layouts.app')

@section('title', 'Payment - ' . $order->order_number)

@section('content')
    <style>
        .payment-wrapper {
            max-width: 900px;
            margin: 40px auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .summary-box {
            background: linear-gradient(135deg, #00b22d 0%, #00b22d 100%);
            padding: 30px;
            height: auto;
            color: #fff
        }

        .summary-box h4 {
            margin-bottom: 20px;
            font-weight: 600;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px dashed #ddd;
        }

        .summary-item.total {
            font-weight: 700;
            font-size: 1.2rem;
            border-top: 2px solid #00b22d;
            margin-top: 15px;
            padding-top: 12px;
            border-bottom: none;
        }

        .payment-box {
            padding: 30px;
        }

        .stripe-button {
            background: #00b22d;
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 12px 20px;
            font-weight: 600;
            font-size: 1rem;
            width: 100%;
        }

        .stripe-button:hover {
            background: #019c26;
        }
    </style>

    <div class="payment-wrapper row g-0">
        <!-- Order Summary -->
        <div class="col-md-4 summary-box">
            <h4>Order Summary</h4>

            <div class="summary-item">
                <span>Order Number:</span>
                <span>#{{ $order->order_number }}</span>
            </div>
            <div class="summary-item">
                <span>Subtotal:</span>
                <span>£{{ number_format($order->subtotal, 2) }}</span>
            </div>
            {{-- <div class="summary-item">
            <span>Tax:</span>
            <span>${{ number_format($order->tax_amount, 2) }}</span>
        </div> --}}
            {{-- <div class="summary-item">
            <span>Shipping:</span>
            <span>${{ number_format($order->shipping_amount, 2) }}</span>
        </div> --}}
            @if ($order->discount_amount > 0)
                <div class="summary-item">
                    <span>Discount:</span>
                    <span>-£{{ number_format($order->discount_amount, 2) }}</span>
                </div>
            @endif
            @if ($order->coupon_code)
                <div class="summary-item">
                    <span>Coupon:</span>
                    <span>{{ $order->coupon_code }}</span>
                </div>
            @endif
            <div class="summary-item total">
                <span>Total:</span>
                <span>£{{ number_format($order->total, 2) }}</span>
            </div>
        </div>

        <!-- Payment Form -->
        <div class="col-md-8 payment-box">
            <h4 class="mb-4">Complete Payment</h4>

            <form id="subscription-form" method="POST" action="{{ route('checkout.payment.process', $order) }}">
                @csrf
                <input type="hidden" name="payment_method" id="paymentmethod">
                <input type="hidden" id="card-holder-name" value="{{ auth()->user()->name }}">

                <div id="payment-element" class="mb-4"></div>

                <button type="submit" class="stripe-button">Pay £{{ number_format($order->total, 2) }}</button>
            </form>
        </div>
    </div>

    <script src="https://js.stripe.com/v3/"></script>
    <script>
        const stripe = Stripe("{{ env('STRIPE_KEY') }}");

        document.addEventListener("DOMContentLoaded", async function() {
            const form = document.getElementById('subscription-form');
            const clientSecret = "{{ $clientSecret }}";
            const paymentMethodInput = document.getElementById("paymentmethod");

            const elements = stripe.elements({
                clientSecret,
                paymentMethodCreation: 'manual'
            });
            const paymentElement = elements.create('payment');
            paymentElement.mount('#payment-element');

            form.addEventListener("submit", async (e) => {
                e.preventDefault();

                // ✅ Step 1: Submit elements to validate all fields
                await elements.submit();

                // ✅ Step 2: Now create the payment method
                const {
                    error,
                    paymentMethod
                } = await stripe.createPaymentMethod({
                    elements,
                    params: {
                        billing_details: {
                            name: document.getElementById("card-holder-name").value,
                        },
                    },
                });

                if (error) {
                    toastr.error(error.message || "Payment failed.");
                } else {
                    document.getElementById("paymentmethod").value = paymentMethod.id;
                    form.submit();
                }
            });

        });
    </script>
@endsection
