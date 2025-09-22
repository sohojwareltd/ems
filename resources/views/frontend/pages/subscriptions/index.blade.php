@extends('frontend.layouts.app')

@section('title', 'EMS - Your Literary Haven')

@section('content')

    <style>
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

        .container-3 {
            width: 100%;
            max-width: 940px;
            margin-left: auto;
            margin-right: auto;
        }

        .pricing-wrapper {
            grid-column-gap: 40px;
            grid-row-gap: 50px;
            grid-template-rows: auto;
            grid-template-columns: 1fr 1fr;
            grid-auto-columns: 1fr;
            /* align-items: center; */
            display: grid;
        }

        .heading-2 {
            color: var(--primary);
            text-align: center;
            margin-bottom: 30px;
            font-family: Plus Jakarta Sans, sans-serif;
            font-size: 50px;
            line-height: 70px;
        }

        .flex-wrap {
            justify-content: center;
            align-items: center;
            margin-bottom: 60px;
            display: flex;
        }

        .switch-wrap {
            grid-column-gap: 20px;
            grid-row-gap: 20px;
            justify-content: center;
            display: flex;
            position: relative;
            overflow: visible;
        }

        .percentage-wrap {
            grid-column-gap: 5px;
            grid-row-gap: 5px;
            align-items: flex-end;
            display: flex;
            position: absolute;
            inset: -22px -100px auto auto;
        }

        .percentage {
            background-color: var(--secondary-bg-light);
            color: var(--secondary-very-light);
            text-transform: uppercase;
            border-radius: 5px;
            padding: 5px;
        }

        .paragraph-10 {
            color: var(--secondary);
            margin-bottom: 0;
            font-weight: 700;
            line-height: 1em;
        }

        .highlight-yellow {
            background-image: linear-gradient(90deg, var(--pale-yellow), #fff0);
            -webkit-text-fill-color: inherit;
            background-clip: border-box;
            padding-left: 3px;
            padding-right: 3px;
        }

        .w-layout-blockcontainer {
            max-width: 940px;
            margin-left: auto;
            margin-right: auto;
            display: block;
        }

        .w-container:before,
        .w-container:after {
            content: " ";
            grid-area: 1 / 1 / 2 / 2;
            display: table;
        }

        .paragraph-3 {
            margin-bottom: 20px;
            font-size: 1.1rem;
            line-height: 1.9rem;
        }

        .pricing-feature-list {
            align-self: stretch;
            margin-bottom: 25px;
        }

        .w-list-unstyled {
            padding-left: 0;
            list-style: none;
        }

        .transformation-list-item-bordered {
            border-bottom: 1px solid #e5e7eb;
            color: var(--blue-shade-2);
            justify-content: flex-start;
            align-items: flex-start;
            margin-bottom: 20px;
            margin-left: 0;
            padding-bottom: 20px;
            padding-left: 0;
            line-height: 24px;
            display: flex;
        }

        .checkmark-green {
            color: var(--standard-green);
            font-size: 1.5rem;
            line-height: 1.5em;
            display: inline;
        }

        .w-embed:before,
        .w-embed:after {
            content: " ";
            grid-area: 1 / 1 / 2 / 2;
            display: table;
        }

        .w-layout-blockcontainer {
            max-width: 940px;
            margin-left: auto;
            margin-right: auto;
            display: block;
        }

        .w-container:before,
        .w-container:after {
            content: " ";
            grid-area: 1 / 1 / 2 / 2;
            display: table;
        }

        #Annual-pricing-card.w-node-_574b3d40-8b40-810a-3abf-631d6f677ea9-836f1a9d {
            grid-area: span 1 / span 1 / span 1 / span 1;
            align-self: start;
        }

        .pricing-card.featured-pricing {
            z-index: 2;
            border-radius: 20px;
            flex-flow: column;
            margin-top: 11px;
            padding: 40px 30px;
            display: flex;
            box-shadow: 0 4px 130px #96a3b54d;
        }

        .pricing-card {
            background-color: #fff;
            flex-direction: column;
            justify-content: flex-start;
            align-items: center;
            padding: 32px 24px;
            display: flex;
            position: relative;
            box-shadow: 0 4px 130px #96a3b51f;
        }

        .container-11 {
            min-height: 270px;
        }

        .w-layout-blockcontainer {
            max-width: 940px;
            margin-left: auto;
            margin-right: auto;
            display: block;
        }

        .w-container:before,
        .w-container:after {
            content: " ";
            grid-area: 1 / 1 / 2 / 2;
            display: table;
        }

        .pricing-container {
            flex-flow: column;
            justify-content: flex-start;
            align-items: center;
            display: flex;
        }

        .w-layout-blockcontainer {
            max-width: 940px;
            margin-left: auto;
            margin-right: auto;
            display: block;
        }

        .w-container:before,
        .w-container:after {
            content: " ";
            grid-area: 1 / 1 / 2 / 2;
            display: table;
        }

        .w-layout-blockcontainer {
            max-width: 940px;
            margin-left: auto;
            margin-right: auto;
            display: block;
        }

        .w-container:before,
        .w-container:after {
            content: " ";
            grid-area: 1 / 1 / 2 / 2;
            display: table;
        }

        .pricing-title-discount {
            color: var(--medium-light-grey);
            margin-left: -11px;
            margin-right: 9px;
            font-family: Plus Jakarta Sans, sans-serif;
            font-size: 1.4rem;
            font-weight: 700;
            line-height: 1em;
            text-decoration: line-through;
            display: inline-flex;
        }

        .pricing-title.highlight-dark {
            font-family: Plus Jakarta Sans, sans-serif;
            display: inline-flex;
        }

        .pricing-title {
            color: var(--primary);
            margin-top: 10px;
            margin-bottom: 8px;
            font-size: 75px;
            font-weight: 700;
            line-height: 90px;
        }

        .w-container:after {
            clear: both;
        }

        .subtitle {
            color: var(--primary);
            text-align: center;
            margin-top: 0;
            margin-bottom: 40px;
            font-size: 18px;
            line-height: 1.5em;
        }

        .button-primary {
            background-color: var(--secondary);
            color: #fff;
            letter-spacing: .5px;
            text-transform: capitalize;
            border-radius: 9px;
            margin-top: 0;
            padding: 20px 35px;
            font-family: DM Sans, sans-serif;
            font-size: 1.2rem;
            font-weight: 400;
            line-height: 1rem;
            transition: all .2s;
        }

        .w-button {
            color: #fff;
            line-height: inherit;
            cursor: pointer;
            background-color: #3898ec;
            border: 0;
            border-radius: 0;
            padding: 9px 15px;
            text-decoration: none;
            display: inline-block;
        }

        .w-container:after {
            clear: both;
        }

        .w-container:after {
            content: " ";
            grid-area: 1 / 1 / 2 / 2;
            display: table;
        }

        .pricing-container {
            flex-flow: column;
            justify-content: flex-start;
            align-items: center;
            display: flex;
        }

        .w-layout-blockcontainer {
            max-width: 940px;
            margin-left: auto;
            margin-right: auto;
            display: block;
        }

        .pricing-title {
            color: var(--primary);
            margin-top: 10px;
            margin-bottom: 8px;
            font-size: 75px;
            font-weight: 700;
            line-height: 90px;
        }

        .pricing-subtitle {
            color: var(--primary);
            text-align: center;
            margin-top: 0;
            margin-bottom: 40px;
            font-size: 18px;
            line-height: 1.5em;
        }

        .button-primary {
            background-color: var(--secondary);
            color: #fff;
            letter-spacing: .5px;
            text-transform: capitalize;
            border-radius: 9px;
            margin-top: 0;
            padding: 20px 35px;
            font-family: DM Sans, sans-serif;
            font-size: 1.2rem;
            font-weight: 400;
            line-height: 1rem;
            transition: all .2s;
        }

        .pricing-divider {
            background-color: #76879d1a;
            align-self: stretch;
            height: 1px;
            margin: 40px -24px 20px;
        }

        .money-back-wrapper {
            justify-content: space-between;
            align-items: flex-start;
            margin-top: 0;
            display: flex;
        }

        .money-back-icon {
            flex-direction: column;
            justify-content: flex-start;
            align-items: flex-start;
            display: flex;
        }

        .money-back-text {
            margin-left: 20px;
        }

        .explore-card-title.highlight-yellow {
            display: inline-block;
        }

        .explore-card-title {
            color: #ffffff;
            margin-top: 0;
            font-size: 1.1rem;
            font-weight: 700;
            line-height: 1.5em;
        }

        .highlight-yellow {
            background-image: linear-gradient(90deg, #00b22d, #19390b) !important;
            -webkit-text-fill-color: inherit;
            background-clip: border-box;
            padding-left: 3px;
            padding-right: 3px;
        }

        .margin-bottom-24px-2 {
            margin-bottom: 24px;
            font-size: .9rem;
        }

        .pricing-tag {
            color: #3a4554;
            background-color: #fff;
            border-radius: 24px;
            padding: 7px 16px;
            position: absolute;
            top: -19px;
            box-shadow: 0 3px 10px #96a3b533;
        }

        .highlight-dark {
            /* background-image: linear-gradient(90deg, var(--primary-accent-pink), var(--primary)); */
            background-image: linear-gradient(90deg, #00b22d, #19390b) !important;
            -webkit-text-fill-color: transparent;
            -webkit-background-clip: text;
            background-clip: text;
        }

        .tp-widget-wrapper {
            height: 100%;
            margin: 0 auto;
            max-width: 750px;
            position: relative;
        }

        .tp-widget-wrapper a {
            display: block;
        }

        .tp-widget-wrapper a,
        .tp-widget-wrapper #wrapper-company-stars {
            position: relative;
            outline: none;
        }

        @media screen and (max-width: 479px) {
            .pricing-wrapper {
                flex-flow: column-reverse;
                display: flex;
            }
        }

        @media screen and (max-width: 767px) {
            .pricing-wrapper {
                flex-flow: column-reverse;
                justify-items: stretch;
                display: flex;
            }
        }

        @media screen and (max-width: 767px) {
            .w-layout-blockcontainer {
                max-width: none;
            }
        }

        @media screen and (max-width: 991px) {
            .w-layout-blockcontainer {
                max-width: 728px;
            }
        }
    </style>
    {{-- @if ($months->isNotEmpty())
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

                        <button class="plan-button outline"><a href="{{ route('subscriptions.payment', $month->id) }}"
                                class="text-decoration-none" style="color: #00b22d;">Get Started</a></button>
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

                        <button class="plan-button outline"><a href="{{ route('subscriptions.payment', $year->id) }}"
                                class="text-decoration-none" style="color: #00b22d;">Get Started</a></button>
                    </div>
                @endforeach


            </div>


        </div>
    @endif

    @if ($months->isEmpty() && $years->isEmpty())
        <h3 class="text-center">No plan available at the moment try again later</h3>
    @endif --}}


    <section id="pricing" class="pricing-comparison">
        <h1 class="heading-2"><span class="">Pricing</span></h1>
        <div class="flex-wrap">
            <div class="switch-wrap">
                <div class="percentage-wrap"><img loading="lazy"
                        src="https://cdn.prod.website-files.com/682d03999cd54259836f1a8e/683646e8e5ee0a1d4256228d_arrow.svg"
                        alt="" class="arrow-img">
                    <div class="percentage">
                        <p class="paragraph-10">20% off</p>
                    </div>
                </div>
                <p class="p-17"><strong>4-Monthly</strong></p>
                <p class="p-17"><strong>Yearly</strong></p>
            </div>
        </div>
        <div class="container-3">
            <div class="pricing-wrapper">
                <div id="w-node-_05512a31-f544-76da-064a-75e0b3a58023-836f1a9d"
                    class="w-layout-blockcontainer container-4 w-container">
                    <p class="paragraph-3"><strong>Join to get immediate access to our </strong><span
                            class="highlight-dark"><strong>step-by-step, guided curriculum</strong></span><strong>,
                            including:</strong></p>
                    <ul role="list" class="pricing-feature-list w-list-unstyled">
                        <li class="transformation-list-item-bordered">
                            <div class="checkmark-green w-embed me-2"><i class="bi bi-check-circle" style="color: #00b22d"
                                    aria-hidden="true"></i></div>
                            <div class="standard-list-item">Diagnostic assessment of your learning, time management, focus,
                                and skill acquisition habits</div>
                        </li>
                        <li class="transformation-list-item-bordered">
                            <div class="checkmark-green w-embed me-2"><i class="bi bi-check-circle" style="color: #00b22d"
                                    aria-hidden="true"></i>
                            </div>
                            <div class="standard-list-item">104 video lessons covering high-yield learning and
                                self-management principles, organised in the ideal order and structure for efficient
                                upskilling</div>
                        </li>
                        <li class="transformation-list-item-bordered">
                            <div class="checkmark-green w-embed me-2"><i class="bi bi-check-circle" style="color: #00b22d"
                                    aria-hidden="true"></i>
                            </div>
                            <div class="standard-list-item">Actionable techniques with step-by-step instructions to apply
                                what you learn to your real content</div>
                        </li>
                        <li class="transformation-list-item-bordered">
                            <div class="checkmark-green w-embed me-2"><i class="bi bi-check-circle" style="color: #00b22d"
                                    aria-hidden="true"></i>
                            </div>
                            <div class="standard-list-item">Interactive quizzes and drills</div>
                        </li>
                        <li class="transformation-list-item-bordered">
                            <div class="checkmark-green w-embed me-2"><i class="bi bi-check-circle" style="color: #00b22d"
                                    aria-hidden="true"></i>
                            </div>
                            <div class="standard-list-item">Exclusive monthly interactive workshops with Dr.
                                Justin&nbsp;Sung</div>
                        </li>
                        <li class="transformation-list-item-bordered">
                            <div class="checkmark-green w-embed me-2"><i class="bi bi-check-circle" style="color: #00b22d"
                                    aria-hidden="true"></i>
                            </div>
                            <div class="standard-list-item">50+ hours of previous workshops solving the most common problems
                                from real learners, fully searchable with our AI-powered search engine</div>
                        </li>
                        <li class="transformation-list-item-bordered">
                            <div class="checkmark-green w-embed me-2"><i class="bi bi-check-circle" style="color: #00b22d"
                                    aria-hidden="true"></i>
                            </div>
                            <div class="standard-list-item">Dozens of sample learning systems, study schedules and
                                subject-specific walkthroughs and examples</div>
                        </li>
                        <li class="transformation-list-item-bordered">
                            <div class="checkmark-green w-embed me-2"><i class="bi bi-check-circle" style="color: #00b22d"
                                    aria-hidden="true"></i>
                            </div>
                            <div class="standard-list-item">Global community of over 10,000 like-minded learners to keep you
                                accountable</div>
                        </li>
                        <li class="transformation-list-item-bordered">
                            <div class="checkmark-green w-embed me-2"><i class="bi bi-check-circle" style="color: #00b22d"
                                    aria-hidden="true"></i>
                            </div>
                            <div class="standard-list-item">Support and feedback from expert coaches</div>
                        </li>
                    </ul>
                </div>
                <div id="w-node-ddd32dc0-2bdc-f1e0-106d-db04735ad834-836f1a9d" class="w-layout-blockcontainer w-container">
                    @if ($months->isNotEmpty())
                        @foreach ($months as $month)
                            <div id="Annual-pricing-card"
                                class="pricing-card featured-pricing w-node-_574b3d40-8b40-810a-3abf-631d6f677ea9-836f1a9d">
                                <div class="w-layout-blockcontainer container-11 w-container">
                                    <div data-w-id="553d4b10-9c88-50bb-c556-0d41f96d192c" style="opacity: 1; display: flex;"
                                        class="w-layout-blockcontainer pricing-container w-container">
                                        <div class="w-layout-blockcontainer annual-price-value-container w-container">
                                            {{-- <div class="pricing-title-discount"><i class="bi bi-currency-euro"></i>507</div> --}}
                                            <div class="pricing-title highlight-dark"><i class="bi bi-currency-euro"></i>
                                                {{ number_format($month->price) }}
                                            </div>
                                        </div>
                                        <div class="pricing-subtitle">Billed Yearly (Save 20%)<br>Cancel anytime.</div>

                                        <a href="{{ route('subscriptions.payment', $month->id) }}" class="custom-btn btn">Get Started</a>
                                    </div>
                                </div>
                                <div class="pricing-divider"></div>
                                <section>
                                    <div class="container-3">
                                        <div class="money-back-wrapper">
                                            <div class="money-back-icon"><img width="80" loading="lazy" alt=""
                                                    src="{{ asset('images/PHOTO1.png') }}" class="image"></div>
                                            <div class="money-back-text">
                                                <div class="explore-card-title highlight-yellow">Money-back guarantee</div>
                                                <p class="margin-bottom-24px-2">If you aren’t 100% satisfied in the first
                                                    30
                                                    days,
                                                    we’ll refund your entire payment. Read our <a
                                                        href="#"
                                                        data-wf-native-id-path="44a8fc3b-18b1-d53e-ecb1-45708abdf5b7"
                                                        data-wf-ao-click-engagement-tracking="true"
                                                        data-wf-element-id="44a8fc3b-18b1-d53e-ecb1-45708abdf5b7"
                                                        target="_blank">terms and conditions</a>.</p>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                                <div data-w-id="574b3d40-8b40-810a-3abf-631d6f677ecf" class="pricing-tag"
                                    style="opacity: 1;">
                                    <span class="highlight-dark">{{ $month->name }}</span>
                                </div>
                            </div>
                        @endforeach
                    @endif
                    @if ($years->isNotEmpty())
                        @foreach ($years as $year)
                            <div id="Annual-pricing-card"
                                class="pricing-card featured-pricing w-node-_574b3d40-8b40-810a-3abf-631d6f677ea9-836f1a9d">
                                <div class="w-layout-blockcontainer container-11 w-container">
                                    <div data-w-id="553d4b10-9c88-50bb-c556-0d41f96d192c" style="opacity: 1; display: flex;"
                                        class="w-layout-blockcontainer pricing-container w-container">
                                        <div class="w-layout-blockcontainer annual-price-value-container w-container">
                                            {{-- <div class="pricing-title-discount"><i class="bi bi-currency-euro"></i>507</div> --}}
                                            <div class="pricing-title highlight-dark"><i class="bi bi-currency-euro"></i>
                                                {{ number_format($year->price) }}
                                            </div>
                                        </div>
                                        <div class="pricing-subtitle">Billed Yearly (Save 20%)<br>Cancel anytime.</div>

                                        <a href="{{ route('subscriptions.payment', $year->id) }}" class="custom-btn btn">Get Started</a>
                                    </div>
                                </div>
                                <div class="pricing-divider"></div>
                                <section>
                                    <div class="container-3">
                                        <div class="money-back-wrapper">
                                            <div class="money-back-icon"><img width="80" loading="lazy" alt=""
                                                    src="{{ asset('images/PHOTO1.png') }}" class="image"></div>
                                            <div class="money-back-text">
                                                <div class="explore-card-title highlight-yellow">Money-back guarantee</div>
                                                <p class="margin-bottom-24px-2">If you aren’t 100% satisfied in the first
                                                   {{ $year->trial_period_days}}
                                                    {{ $year->interval }},
                                                    we’ll refund your entire payment. Read our <a
                                                        href="#"
                                                        data-wf-native-id-path="44a8fc3b-18b1-d53e-ecb1-45708abdf5b7"
                                                        data-wf-ao-click-engagement-tracking="true"
                                                        data-wf-element-id="44a8fc3b-18b1-d53e-ecb1-45708abdf5b7"
                                                        target="_blank">terms and conditions</a>.</p>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                                <div data-w-id="574b3d40-8b40-810a-3abf-631d6f677ecf" class="pricing-tag"
                                    style="opacity: 1;">
                                    <span class="highlight-dark">{{ $year->name }}</span>
                                </div>
                            </div>
                        @endforeach
                    @endif

                </div>
            </div>
        </div>
    </section>




@endsection
