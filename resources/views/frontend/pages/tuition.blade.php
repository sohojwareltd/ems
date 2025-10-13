@extends('frontend.layouts.app')
@section('title', 'Tuition - EMS')

@section('content')
    <style>
        .pricing-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .pricing-header h2 {
            font-weight: bold;
            color: var(--text-dark);
            margin-bottom: 10px;
        }

        .pricing-header p {
            color: var(--text-muted);
            font-size: 1.1rem;
        }

        .divider {
            height: 1px;
            background-color: var(--border-color);
            margin: 30px 0;
        }

        .card-pricing {
            border: 1px solid var(--border-color);
            border-radius: 12px;
            background: var(--white);
            box-shadow: var(--shadow-soft);
            transition: 0.3s;
            height: 100%;
            overflow: hidden;
        }

        .card-pricing:hover {
            box-shadow: var(--shadow-medium);
        }

        .card-standard .features li::before {
            content: '✘';
            background-color: #ea4e1d;
            color: white;
        }

        .card-header {
            font-weight: bold;
            font-size: 1.1rem;
            text-align: center;
            color: var(--white);
            padding: 15px;
            border-radius: 12px 12px 0 0;
        }

        .card-standard .card-header {
            background-color: #ea4e1d;
        }

        .card-pro .card-header {
            background-color: #e41d71;

        }

        .card-pro {
            border: 1px solid #e41d71;

        }

        .card-business .card-header {
            background-color: #543eba;
        }

        .card-body {
            padding: 25px;
        }

        .features {
            list-style: none;
            padding: 0;
            margin: 20px 0;
        }

        .features li {
            padding: 8px 0;
            font-size: 0.95rem;
            display: flex;
            align-items: flex-start;
        }

        .features li::before {
            content: '✔';
            color: var(--primary-color);
            margin-right: 10px;
            /* font-weight: bold; */
            flex-shrink: 0;
            background-color: #b8e1ca;
            font-size: 12px;
            padding: 0px 5px;
            border-radius: 50%;
            margin-top: 5px;
        }

        . .btn-choose {
            border: none;
            padding: 12px 20px;
            font-weight: 500;
            width: 100%;
            border-radius: 5px;
            margin-top: 20px;
            transition: all 0.3s;
        }

        .card-standard .btn-choose {
            background-color: var(--primary-color);
            color: white;
        }

        .card-standard .btn-choose:hover {
            background-color: var(--primary-dark);
        }

        .card-pro .btn-choose {
            background-color: var(--info-color);
            color: white;
        }

        .card-pro .btn-choose:hover {
            background-color: #6c96a5;
        }

        .card-business .btn-choose {
            background-color: #543eba;
            color: white;
        }

        .card-business .btn-choose:hover {
            background-color: #e41d71;
        }

        .card-business li {
            counter-increment: step-counter;
            position: relative;
            padding-left: 2em;
        }

        .card-business {
            counter-reset: step-counter;
        }

        .card-business li::before {
            content: counter(step-counter) ".";

        }

        @media (max-width: 768px) {
            .card-pricing {
                margin-bottom: 20px;
            }
        }
    </style>
    <div class="container py-5">
        <div class="text-center pricing-header mb-4">
            <h2>Specialist Economics & Business Tuition</h2>
            <p class="section-subtitle lead">GCSE, IGCSE, A-Level, IAL & IB across all exam boards</p>
        </div>

        <div class="row g-4 mt-2">
            <!-- The Problem -->
            <div class="col-md-4">
                <div class="card-pricing card-standard h-100">
                    <div class="card-header">The Problem</div>
                    <div class="card-body text-center">
                        <ul class="features text-start mt-3 p-2">
                            <li>Last-minute tutoring never works</li>
                            <li>Students disengage in dull sessions</li>
                            <li>Lessons lack structure and resources</li>
                            <li>Parents are left in the dark on progress</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- The Solution -->
            <div class="col-md-4">
                <div class="card-pricing card-pro h-100">
                    <div class="card-header">The Solution</div>
                    <div class="card-body text-center">
                        <ul class="features text-start mt-3 p-2">
                            <li>Personalised curriculum and plan</li>
                            <li>Interactive sessions to engage students</li>
                            <li>Long term guidance, not quick fixes</li>
                            <li>Access to premium exam resources</li>
                            <li>Insider knowledge from real examiners</li>
                            <li>Open and proactive communication</li>
                        </ul>

                    </div>
                </div>
            </div>

            <!-- The Process -->
            <div class="col-md-4">
                <div class="card-pricing card-business h-100">
                    <div class="card-header">The Process</div>
                    <div class="card-body text-center">
                        <ol class="features text-start mt-3 p-2">
                            <li>Complete enquiry form</li>
                            <li>Free consultation to set goals</li>
                            <li>Baseline tests to inform strategy</li>
                            <li>Finalise individual course</li>
                            <li>Online folder for full transparency</li>
                            <li>Regular reports and progress meetings</li>
                        </ol>
                        <a href="{{ route('contact') }}#contact-form" class="btn btn-choose text-decoration-none">Book
                            today</a>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
