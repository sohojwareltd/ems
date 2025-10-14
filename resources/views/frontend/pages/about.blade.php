@php
    $teams = App\Models\Team::all();

@endphp
@extends('frontend.layouts.app')

@section('title', 'About Us - EMS')

@section('content')
    <!-- Page Header -->
    <section class="page-header py-5" style="background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="text-white display-4 fw-bold mb-3">About Economics Made Simple (EMS)</h1>
                    <p class="text-white lead mb-0">Our story, mission and the team behind it all</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Story Section -->
    <section class="our-story py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="story-content">
                        <h2 class="section-title mb-4">Our Story</h2>
                        <p class="lead mb-4">
                            Economics Made Simple didn’t start with a plan or a name. It began in the classroom with a
                            shared goal to help our students truly understand Economics. Just a couple of teachers
                            determined to make lessons clearer, and more effective. The official materials and mark schemes
                            left much to be desired, so we started building our own from scratch with model essays, slides,
                            notes, and explanations that actually made sense to our students.

                        </p>
                        <p class="mb-4">
                            We wanted the answers straight from the horse’s mouth, no guesswork, so we trained as official
                            examiners. We wanted to see exactly how marks were awarded, where our students lost them, and
                            what separated a good answer from a great one. These insights changed everything! It shaped how
                            we structured our lessons, how we designed resources, and how we explained complex ideas. We
                            rewrote, refined, and rebuilt everything again, from the ground up.


                        </p>
                        <p class="mb-4">
                            As our resources improved, so did the results. Word spread quickly, other teachers began using
                            our materials, our students started seeing Economics differently, and we realised we all had the
                            same problems. That was when the idea of EMS began to take shape.


                        </p>
                        <div class="mb-4">
                            The name came naturally. Our purpose was simple. We wanted learners to find Economics simple.
                            EMS grew from that shared vision, a community of educators and learners working together to make
                            education clearer, more consistent, and more rewarding for everyone.



                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="story-image text-center">
                        <img src="https://images.unsplash.com/photo-1481627834876-b7833e8f5570?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80"
                            alt=" Story" class="img-fluid rounded-3 shadow-lg"
                            style="max-height: 400px; object-fit: cover;">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Mission & Values Section -->
    <section class="mission-values py-5" style="background: var(--light-bg);">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 mb-5">
                    <div class="mission-content">
                        <h2 class="section-title mb-4">Our Mission</h2>
                        <p class="lead mb-4">
                            To raise the standard of education. We aim to create precise, exam-board-aligned resources that
                            empower teachers to deliver lessons with confidence and help learners achieve outstanding
                            results. By combining clarity, structure, and accessibility, EMS turns complex and chaotic
                            learning into simple, measurable success.


                        </p>
                        <p class="mb-4">
                            We truly believe great education changes lives; it shapes how learners think, make decisions,
                            and understand the world around them. Our goal is not just to help learners pass exams, but to
                            equip them with the knowledge and perspective to navigate their futures with confidence,
                            curiosity, and purpose. Through EMS, we aim to build a generation of independent thinkers, ready
                            to understand the world, and improve it.



                        </p>

                        <div class="mission-stats row text-center">
                            <div class="col-4">
                                <div class="stat-item">
                                    @if (setting('store.learners_impacted'))
                                        <h3 class="fw-bold" style="color: var(--primary-color);">
                                            {{ setting('store.learners_impacted') }}</h3>
                                        <p class="text-muted">Learners Impacted</p>
                                    @endif
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="stat-item">
                                    @if (setting('store.teachers_empowered'))
                                        <h3 class="fw-bold" style="color: var(--primary-color);">
                                            {{ setting('store.teachers_empowered') }}</h3>
                                        <p class="text-muted">Teachers Empowered</p>
                                    @endif
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="stat-item">
                                    @if (setting('store.resources_created'))
                                        <h3 class="fw-bold" style="color: var(--primary-color);">
                                            {{ setting('store.resources_created') }}</h3>
                                        <p class="text-muted">Resources Created</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="values-content">
                        <h2 class="section-title mb-4">Our Focus</h2>
                        <div class="values-list">
                            <div class="value-item d-flex align-items-start mb-4">
                                <div class="value-icon me-3">
                                    <i class="bi bi-bullseye fs-4" style="color: var(--primary-color);"></i>

                                </div>
                                <div class="value-content">
                                    <h4 class="h5 mb-2">Curriculum Precision</h4>
                                    <p class="text-muted mb-0">Every EMS resource is built and trimmed in alignment with
                                        exact exam board specifications, ensuring accuracy, consistency, and complete
                                        syllabus coverage.
                                    </p>
                                </div>
                            </div>
                            <div class="value-item d-flex align-items-start mb-4">
                                <div class="value-icon me-3">
                                    <i class="bi bi-person-check-fill fs-4" style="color: var(--secondary-color);"></i>

                                </div>
                                <div class="value-content">
                                    <h4 class="h5 mb-2">Teacher Empowerment</h4>
                                    <p class="text-muted mb-0">We give teachers ready-to-use, time-saving materials that
                                        enhance lesson delivery and boost classroom confidence.</p>
                                </div>
                            </div>
                            <div class="value-item d-flex align-items-start mb-4">
                                <div class="value-icon me-3">
                                    <i class="bi bi-trophy-fill fs-4" style="color: var(--success-color);"></i>

                                </div>
                                <div class="value-content">
                                    <h4 class="h5 mb-2">Learner Achievement</h4>
                                    <p class="text-muted mb-0">Our ultimate aim! Our structured resources simplify complex
                                        content, helping learners see the bigger picture, master content and excel in exams.
                                    </p>
                                </div>
                            </div>
                            {{-- <div class="value-item d-flex align-items-start">
                                <div class="value-icon me-3">
                                    <i class="bi bi-lightbulb-fill fs-4" style="color: var(--warning-color);"></i>
                                </div>
                                <div class="value-content">
                                    <h4 class="h5 mb-2">Innovation</h4>
                                    <p class="text-muted mb-0">We continuously evolve to meet the changing needs of our
                                        readers while staying true to our core mission.</p>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="team-section py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title mb-3">Meet The Team</h2>
                <p class="section-subtitle">The passionate individuals behind EMS</p>
            </div>

            <div class="row g-4 justify-content-center">
                @foreach ($teams as $team)
                    <div class="col-lg-4 col-md-6">
                        <div class="team-member text-center">
                            <div class="team-photo mb-3">
                                <img src="{{ asset('storage/' . $team->picture) }}" alt="{{ $team->name }}"
                                    class="img-fluid rounded-circle shadow"
                                    style="width: 200px; height: 200px; object-fit: cover;">
                            </div>
                            <h4 class="h5 mb-2">{{ $team->name }}</h4>
                            <p class="text-muted mb-2">{{ $team->title }}</p>
                            <p class="small text-muted">
                                {{ $team->description }}
                            </p>
                        </div>
                    </div>
                @endforeach


            </div>
        </div>
    </section>

    <!-- Why Choose Us Section -->
    <section class="why-choose-us py-5" style="background: var(--light-bg);">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title mb-3">Why Choose EMS?</h2>
                <p class="section-subtitle">What makes us different?</p>
            </div>

            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="feature-card text-center p-4 bg-white rounded-3 shadow-sm h-100">
                        <div class="feature-icon mb-3">
                            <i class="bi bi-award fs-1" style="color: var(--primary-color);"></i>
                        </div>
                        <h4 class="h5 mb-3">Quality</h4>
                        <p class="text-muted">Every resource is created and curated by experienced teachers and examiners,
                            nothing less than the best.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="feature-card text-center p-4 bg-white rounded-3 shadow-sm h-100">
                        <div class="feature-icon mb-3">
                            <i class="bi bi-lightbulb fs-1" style="color: var(--secondary-color);"></i>
                        </div>
                        <h4 class="h5 mb-3">Clarity</h4>
                        <p class="text-muted">Content is trimmed, focused, and aligned perfectly with exam requirements, no
                            fluff, no filler.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="feature-card text-center p-4 bg-white rounded-3 shadow-sm h-100">
                        <div class="feature-icon mb-3">
                            <i class="bi bi-cpu fs-1" style="color: var(--info-color);"></i>
                        </div>
                        <h4 class="h5 mb-3">Innovation</h4>
                        <p class="text-muted">We are constantly rethinking how learning can be clearer, smarter, and more
                            effective.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="feature-card text-center p-4 bg-white rounded-3 shadow-sm h-100">
                        <div class="feature-icon mb-3">
                            <i class="bi bi-people fs-1" style="color: var(--danger-color);"></i>

                        </div>
                        <h4 class="h5 mb-3">Community</h4>
                        <p class="text-muted">We’re building a space for those who love learning and have the courage to
                            aim higher.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="cta-section py-5"
        style="background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <h2 class="text-white mb-3">Explore Our Collection</h2>
                    <p class="text-white mb-4">Start Your Journey</p>
                    <div class="cta-buttons">
                        <a href="{{ route('products.index') }}" class="btn btn-light btn-lg me-3 mb-2">
                            <i class="bi bi-book me-2"></i>Browse Shop
                        </a>
                        <a href="{{ route('contact') }}" class="btn btn-outline-light btn-lg mb-2">
                            <i class="bi bi-envelope me-2"></i>Get in Touch
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
