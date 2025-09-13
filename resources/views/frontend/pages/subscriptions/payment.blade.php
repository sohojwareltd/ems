@extends('frontend.layouts.app')

@section('title', 'Eterna Reads - Your Literary Haven')

@section('content')
    <style>
        /* * {
                                                                                                margin: 0;
                                                                                                padding: 0;
                                                                                                box-sizing: border-box;
                                                                                                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                                                                                            }

                                                                                            body {
                                                                                                background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
                                                                                                min-height: 100vh;
                                                                                                display: flex;
                                                                                                justify-content: center;
                                                                                                align-items: center;
                                                                                                padding: 20px;
                                                                                            } */

        .container_pay {
            display: flex;
            flex-wrap: wrap;
            max-width: 1200px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .plan-summary {
            flex: 1;
            background: linear-gradient(135deg, #00b22d 0%, #00b22d 100%);
            color: white;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 100%;
        }

        .payment-form {
            flex: 2;
            min-width: 400px;
            padding: 40px;
        }

        .plan-header {
            margin-bottom: 30px;
        }

        .plan-header h2 {
            font-size: 1.8rem;
            margin-bottom: 10px;
        }

        .plan-header p {
            opacity: 0.9;
        }

        .plan-details {
            background: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
        }

        .plan-price {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .plan-price span {
            font-size: 1.2rem;
            font-weight: 400;
            opacity: 0.9;
        }

        .plan-features {
            list-style: none;
        }

        .plan-features {
            margin-bottom: 12px;
            display: flex;
            align-items: center;
        }

        .plan-features i {
            margin-right: 10px;
            font-size: 1.1rem;
        }

        .secure-notice {
            display: flex;
            align-items: center;
            font-size: 0.9rem;
            opacity: 0.9;
        }

        .secure-notice i {
            margin-right: 8px;
            font-size: 1.2rem;
        }

        .form-header {
            margin-bottom: 30px;
        }

        .form-header h2 {
            font-size: 1.8rem;
            color: #333;
            margin-bottom: 10px;
        }

        .form-header p {
            color: #777;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #444;
        }

        .form-control {
            width: 100%;
            padding: 15px;
            border: 2px solid #e1e5ee;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        .form-control:focus {
            border-color: #00b22d;
            outline: none;
        }

        .form-row {
            display: flex;
            gap: 20px;
        }

        .form-row .form-group {
            flex: 1;
        }

        .payment-methods {
            display: flex;
            gap: 15px;
            margin-bottom: 25px;
        }

        .payment-method {
            flex: 1;
            border: 2px solid #e1e5ee;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }

        .payment-method:hover {
            border-color: #00b22d;
        }

        .payment-method.active {
            border-color: #00b22d;
            background: #f0f4ff;
        }

        .payment-method i {
            font-size: 2rem;
            margin-bottom: 10px;
            color: #555;
        }

        .payment-method p {
            font-size: 0.9rem;
            color: #666;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
        }

        .checkbox-group input {
            margin-right: 10px;
        }

        .checkbox-group label {
            font-size: 0.9rem;
            color: #666;
        }

        .checkbox-group a {
            color: #00b22d;
            text-decoration: none;
        }

        .submit-button {
            width: 100%;
            padding: 16px;
            border: none;
            border-radius: 8px;
            background: linear-gradient(90deg, #00b22d 0%, #00b22d 100%);
            color: white;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: opacity 0.3s;
        }

        .submit-button:hover {
            opacity: 0.8;
        }

        @media (max-width: 900px) {
            .container_pay {
                flex-direction: column;
            }

            .plan-summary,
            .payment-form {
                width: 100%;
            }
        }
    </style>


    <div class="container_pay">
        <div class="row">
            <div class="col-sm-4">


                <div class="plan-summary">
                    <div>
                        <div class="plan-header">
                            <h2>{{ $plan->name }}</h2>
                            <p>Get access to all {{ $plan->name }} features</p>
                        </div>

                        <div class="plan-details">
                            <div class="plan-price">$ {{ $plan->price }} <span>/per month</span></div>
                            <p class="plan-features">{{ $plan->description }}</p>
                            {{-- <ul class="plan-features">
                                <li><i class="fas fa-check"></i> Unlimited projects</li>
                                <li><i class="fas fa-check"></i> 100GB storage</li>
                                <li><i class="fas fa-check"></i> Premium support</li>
                                <li><i class="fas fa-check"></i> Advanced analytics</li>
                                <li><i class="fas fa-check"></i> Cancel anytime</li>
                            </ul> --}}
                        </div>
                    </div>

                    <div class="secure-notice">
                        <i class="fas fa-lock"></i>
                        <p>Your payment information is encrypted and secure</p>
                    </div>
                </div>
            </div>

            <div class="col-8">

                <div class="payment-form">
                    <div class="form-header">
                        <h2>Payment Details</h2>
                        <p>Complete your subscription with your payment details</p>
                    </div>

                    {{-- <form action="{{ route('payment.method', $plan->id) }}" method="post" id="payment-form">
                        @csrf

                        <div class="form-group">
                            <label for="cardholder">Cardholder Name</label>
                            <input type="text" id="cardholder" name="cardholder" class="form-control"
                                placeholder="John Doe" required>
                        </div>

                        <div class="form-group">
                            <label for="cardnumber">Card Number</label>
                            <input type="text" id="cardnumber" name="cardnumber" class="form-control"
                                placeholder="1234 5678 9012 3456" required>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="expiry">Expiry Date</label>
                                <input type="text" id="expiry" name="expiry" class="form-control" placeholder="MM/YY"
                                    required>
                            </div>

                            <div class="form-group">
                                <label for="cvv">CVV</label>
                                <input type="text" id="cvv" name="cvv" class="form-control" placeholder="123"
                                    required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Payment Method</label>
                            <div class="payment-methods">
                                <div class="payment-method active" data-gateway="credit_card">
                                    <i class="fab fa-cc-visa"></i>
                                    <p>Credit Card</p>
                                </div>
                                <div class="payment-method" data-gateway="paypal">
                                    <i class="fab fa-paypal"></i>
                                    <p>PayPal</p>
                                </div>
                                <div class="payment-method" data-gateway="apple_pay">
                                    <i class="fab fa-apple"></i>
                                    <p>Apple Pay</p>
                                </div>
                            </div>
                            <input type="hidden" name="gateway" id="gateway" value="credit_card">
                        </div>

                        <div class="checkbox-group">
                            <input type="checkbox" id="terms" name="terms" required>
                            <label for="terms">I agree to the <a href="#">Terms of Service</a> and <a
                                    href="#">Privacy
                                    Policy</a></label>
                        </div>

                        <button type="submit" class="submit-button">Complete Subscription</button>
                    </form> --}}

                    <form id="subscription-form" action="{{ route('subscribe.create', $plan) }}" method="POST">
                        @csrf
                        <div id="payment-element"></div>
                        <input type="hidden" name="payment_method" id="paymentmethod">
                        <input id="card-holder-name" type="hidden" value="{{ auth()->user()->name }}">
                        <button type="submit" class="btn btn-primary mt-3">Subscribe</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
    {{-- 

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Payment method selection
            const paymentMethods = document.querySelectorAll('.payment-method');
            const gatewayInput = document.getElementById('gateway');

            paymentMethods.forEach(method => {
                method.addEventListener('click', function() {
                    paymentMethods.forEach(m => m.classList.remove('active'));
                    this.classList.add('active');
                    gatewayInput.value = this.getAttribute('data-gateway');
                });
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Payment method selection
            const paymentMethods = document.querySelectorAll('.payment-method');

            paymentMethods.forEach(method => {
                method.addEventListener('click', function() {
                    paymentMethods.forEach(m => m.classList.remove('active'));
                    this.classList.add('active');
                });
            });

            // Form validation
            const form = document.getElementById('payment-form');

            // form.addEventListener('submit', function (e) {
            //     e.preventDefault();

            //     // Simple validation
            //     const cardholder = document.getElementById('cardholder').value;
            //     const cardnumber = document.getElementById('cardnumber').value;
            //     const expiry = document.getElementById('expiry').value;
            //     const cvv = document.getElementById('cvv').value;
            //     const terms = document.getElementById('terms').checked;

            //     if (!cardholder || !cardnumber || !expiry || !cvv || !terms) {
            //         alert('Please fill in all required fields');
            //         return;
            //     }

            //     // If validation passes, show success message
            //     alert('Payment processed successfully! Thank you for your subscription.');
            //     form.reset();
            // });

            // Format card number input
            const cardNumberInput = document.getElementById('cardnumber');

            cardNumberInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                value = value.replace(/(\d{4})/g, '$1 ').trim();
                e.target.value = value.substring(0, 19);
            });

            // Format expiry date input
            const expiryInput = document.getElementById('expiry');

            expiryInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 2) {
                    value = value.substring(0, 2) + '/' + value.substring(2, 4);
                }
                e.target.value = value.substring(0, 5);
            });
        });
    </script> --}}
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        const stripe = Stripe("{{ config('cashier.key') }}");

        document.addEventListener("DOMContentLoaded", async function() {
            const paymentMethodInput = document.getElementById("paymentmethod");
            const form = document.getElementById('subscription-form');
            const clientSecret = "{{ $clientSecret }}";

            const elements = stripe.elements({
                clientSecret,
                paymentMethodCreation: 'manual',
            });

            const paymentElement = elements.create('payment');
            paymentElement.mount('#payment-element');

            // Enable button once payment element is fully mounted
            const submitButton = document.querySelector('#subscription-form button[type="submit"]');
            submitButton.addEventListener("click", async (e) => {
                e.preventDefault();
                elements.submit();
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
                paymentMethodInput.value = paymentMethod.id;

                form.submit();

                if (error) {
                    toastr.error(error.message || "Something went wrong. Try again.");
                } else {

                    document.getElementById('paymentmethod').value = paymentIntent.payment_method;
                    toastr.success("Payment submitted successfully!");

                    form.submit();
                }
            });
        });
    </script>



@endsection
