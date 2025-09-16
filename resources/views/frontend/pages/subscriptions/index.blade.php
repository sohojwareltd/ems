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

    .subscription-container {
      width: 100%;
      max-width: 1200px;
      background: white;
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
      overflow: hidden;
    }

    .subscription-header {
      background: linear-gradient(90deg, #00b22d 0%, #00b22d 100%);
      color: white;
      text-align: center;
      padding: 30px;
    }

    .subscription-header h1 {
      font-size: 2.5rem;
      margin-bottom: 10px;
    }

    .subscription-header p {
      font-size: 1.1rem;
      opacity: 0.9;
    }

    .plans-container {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      padding: 40px 20px;
    }

    .plan {
      flex: 1;
      min-width: 280px;
      max-width: 350px;
      background: white;
      border-radius: 12px;
      margin: 15px;
      padding: 30px;
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
      text-align: center;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .plan:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    .plan.popular {
      border: 2px solid #00b22d;
      position: relative;
    }

    .popular-tag {
      position: absolute;
      top: -12px;
      left: 50%;
      transform: translateX(-50%);
      background: #00b22d;
      color: white;
      padding: 5px 15px;
      border-radius: 20px;
      font-size: 0.8rem;
      font-weight: 600;
    }

    .plan-name {
      font-size: 1.5rem;
      color: #333;
      margin-bottom: 15px;
    }

    .plan-price {
      font-size: 2.8rem;
      font-weight: 700;
      color: #00b22d;
      margin-bottom: 20px;
    }

    .plan-price span {
      font-size: 1rem;
      color: #777;
      font-weight: 400;
    }

    .plan-features {
      list-style: none;
      margin: 25px 0;
      text-align: left;
    }

    .plan-features li {
      margin-bottom: 15px;
      display: flex;
      align-items: center;
    }

    .plan-features i {
      color: #00b22d;
      margin-right: 10px;
      font-size: 1.1rem;
    }

    .plan-button {
      display: block;
      width: 100%;
      padding: 14px;
      border: none;
      border-radius: 8px;
      background: linear-gradient(90deg, #00b22d 0%, #8E54E9 100%);
      color: white;
      font-size: 1.1rem;
      font-weight: 600;
      cursor: pointer;
      transition: opacity 0.3s ease;
    }

    .plan-button:hover {
      opacity: 0.9;
    }

    .plan-button.outline {
      background: transparent;
      border: 2px solid #00b22d;
      color: #00b22d;
    }

    .payment-methods {
      text-align: center;
      padding: 20px;
      background: #f9f9f9;
      border-top: 1px solid #eee;
    }

    .payment-methods p {
      margin-bottom: 15px;
      color: #777;
    }

    .payment-icons {
      display: flex;
      justify-content: center;
      gap: 15px;
    }

    .payment-icons i {
      font-size: 2rem;
      color: #555;
    }

    @media (max-width: 900px) {
      .plans-container {
        flex-direction: column;
        align-items: center;
      }

      .plan {
        width: 100%;
        max-width: 400px;
      }
    }
  </style>
  @if ($months->isNotEmpty())
    <div class="subscription-container mb-5">
      <div class="subscription-header">
        <h1>Choose Your Monthly Plan</h1>
        <p>Select the perfect plan for your needs.</p>
      </div>

      <div class="plans-container">
        @foreach ($months as $month)
          <div class="plan">
            <h2 class="plan-name">{{ $month->name }}</h2>

            <div class="plan-price">${{ number_format($month->price) }}<span>/per month</span></div>

            <p class="plan-features">{{ $month->description }}</p>

            <button class="plan-button outline"><a href="{{ route('subscriptions.payment', $month->id) }}" class="text-decoration-none" style="color: #00b22d;">Get Started</a></button>
          </div>
        @endforeach


      </div>


    </div>
  @endif
  @if ($years->isNotEmpty())
    <div class="subscription-container">
      <div class="subscription-header">
        <h1>Choose Your Yearly Plan</h1>
        <p>Select the perfect plan for your needs.</p>
      </div>

      <div class="plans-container">
        @foreach ($years as $year)
          <div class="plan">
            <h2 class="plan-name">{{ $year->name }}</h2>

            <div class="plan-price">${{ number_format($year->price) }}<span>/per year</span></div>

            <p class="plan-features">{!! $year->description !!}</p>

            <button class="plan-button outline"><a href="{{ route('subscriptions.payment', $year->id) }}" class="text-decoration-none" style="color: #00b22d;">Get Started</a></button>
          </div>
        @endforeach


      </div>


    </div>
  @endif

  @if ($months->isEmpty() && $years->isEmpty())
      <h3 class="text-center">No plan available at the moment try again later</h3>
  @endif




@endsection
