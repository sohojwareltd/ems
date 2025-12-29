@extends('frontend.layouts.app')

@php use Illuminate\Support\Arr; @endphp

@section('title', 'Essays - EMS')
@section('meta_description',
    'Explore our collection of books. Find your next great read or the perfect gift for a book
    lover.')
@section('meta_keywords', 'books, audiobooks, gift boxes, bookshop, online bookstore, reading, literature, book gifts')

<style>
    .locked-preview {
        height: 200px;
        position: relative;
    }

    .blurred-content {
        filter: blur(6px);
        pointer-events: none;
    }

    .locked-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.55);
        color: #fff;
        padding: 1rem;
        z-index: 10;
    }

    .animate-subscribe {
        animation: pulse 1.5s infinite;
    }

    @keyframes pulse {
        0% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.05);
        }

        100% {
            transform: scale(1);
        }
    }

    .whatsapp-float {
        padding: 13px 15px !important;
    }

    .notice-pill {
        display: inline-block;
        padding: 14px 18px;
        border-radius: 999px;
        background: linear-gradient(135deg, #1fa67a, #17a26f);
        color: white;
        font-weight: 600;
        box-shadow: 0 6px 20px rgba(23, 162, 111, 0.25);
        transform-origin: center;
        animation: notice-bounce 3.6s ease-in-out infinite;
        max-width: 900px;
        line-height: 1.4;
    }

    .notice-pill strong {
        margin-right: 8px;
    }

    @keyframes notice-bounce {

        0%,
        100% {
            transform: translateY(0)
        }

        50% {
            transform: translateY(-6px)
        }
    }
</style>

@php
    $qualificationId = request('qualification');
    $examBoardId = request('exam_board');
    $subjectId = request('subject');
    $topicIds = (array) request('topics');

    // Fetch all needed data once
    $qualificationsMap = \App\Models\Qualification::pluck('title', 'id')->toArray();
    $examBoardsMap = \App\Models\ExamBoard::pluck('title', 'id')->toArray();
    $subjectsMap = \App\Models\Subject::pluck('title', 'id')->toArray();
    $topicsMap = \App\Models\Topic::pluck('name', 'id')->toArray();
    $papers = \App\Models\Paper::orderBy('name', 'asc')->get();

    $qualification = $qualificationId ? $qualificationsMap[$qualificationId] ?? null : null;
    $examBoard = $examBoardId ? $examBoardsMap[$examBoardId] ?? null : null;
    $subject = $subjectId ? $subjectsMap[$subjectId] ?? null : null;
@endphp

@section('content')
    <div class="container">
        <!-- Hero Section -->
        <div class="section-header text-center">
            <h1 class="section-title display-4 fw-bold">
                {{ $qualification ?? '' }} {{ $examBoard ?? '' }} {{ $subject ?? '' }}
            </h1>
            <!-- Paste where you want the notice -->
            <div class="notice-pill mt-3">
                All variations are identical in content and structure, use any resource, regardless of paper code. <br>
                Differences only exist to manage exam timing, region, and format.

            </div>


        </div>

        <div class="row">
            <!-- Offcanvas Trigger (Mobile) -->
            <div class="col-12 d-lg-none mb-3">
                <button class="btn btn-outline-primary w-100" type="button" data-bs-toggle="offcanvas"
                    data-bs-target="#filtersOffcanvas" aria-controls="filtersOffcanvas">
                    <i class="bi bi-funnel me-2"></i>Filters & Search
                </button>
            </div>

            <!-- Offcanvas Filters (Mobile) -->
            <div class="offcanvas offcanvas-start" tabindex="-1" id="filtersOffcanvas"
                aria-labelledby="filtersOffcanvasLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="filtersOffcanvasLabel">
                        <i class="bi bi-funnel me-2"></i>Filters & Search
                    </h5>
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                        aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    @include('frontend.essays._filters', [
                        'topics' => $topics,
                        'qualifications' => $qualifications,
                        'examBoards' => $examBoards,
                        'subjects' => $subjects,
                        'papers' => $papers,
                        'essaysByYearByFilter' => $essaysByYearByFilter,
                    ])
                </div>
            </div>

            <!-- Desktop Filters -->
            <div class="col-md-4 mb-4 d-none d-lg-block">
                <div class="position-sticky" style="top: 90px;">
                    <div class="card">
                        <div class="card-header" style="background-color: var(--primary-dark); color: var(--white);">
                            <h5 class="mb-0"><i class="bi bi-funnel me-2"></i>Filters & Search</h5>
                        </div>
                        <div class="card-body">
                            @include('frontend.essays._filters', [
                                'topics' => $topics,
                                'qualifications' => $qualifications,
                                'examBoards' => $examBoards,
                                'subjects' => $subjects,
                                'papers' => $papers,
                                'essaysByYearByFilter' => $essaysByYearByFilter,
                            ])
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-lg-8 ">

                <div class="container pt-0 pb-5">
                    @php
                        $query = request()->except(['tab']);
                    @endphp

                    {{-- Main Tabs --}}
                    <ul class="nav nav-tabs border-0">
                        <li class="nav-item">
                            <a class="nav-link {{ $tab === 'sample' ? 'active' : '' }}"
                                href="{{ route('model.index', array_merge($query, ['tab' => 'sample', 'view' => $view])) }}">
                                Free Sample Essays
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $tab === 'essays' ? 'active' : '' }}"
                                href="{{ route('model.index', array_merge($query, ['tab' => 'essays', 'view' => $view])) }}">
                                Model Essays
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $tab === 'pastpapers' ? 'active' : '' }}"
                                href="{{ route('model.index', array_merge($query, ['tab' => 'pastpapers', 'view' => $view])) }}">
                                Past Papers
                            </a>
                        </li>
                    </ul>

                    {{-- Tab Content --}}
                    @if ($tab === 'essays')
                        @if ($view === 'topic')
                            <div class="accordion" id="essaysByTopic">
                                @foreach ($essaysByTopic as $topicName => $essays)
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingEssayTopic-{{ Str::slug($topicName) }}">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#collapseEssayTopic-{{ Str::slug($topicName) }}">
                                                {{ $topicName }}
                                            </button>
                                        </h2>
                                        <div id="collapseEssayTopic-{{ Str::slug($topicName) }}"
                                            class="accordion-collapse collapse" data-bs-parent="#essaysByTopic">
                                            <div class="accordion-body">
                                                @foreach ($essays as $essay)
                                                    <div class="mt-3 d-flex justify-content-between align-items-center">
                                                        <div><strong>{{ $essay->name }}</strong></div>
                                                        <a href="{{ asset('storage/' . $essay->file) }}"  target="_blank"
                                                            class="btn btn-sm btn-primary">Download</a>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="accordion" id="essaysByYear">
                                @foreach ($essaysByYear as $year => $essays)
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingEssayYear-{{ $year }}">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#collapseEssayYear-{{ $year }}">
                                                {{ $year }} Model Essays
                                            </button>
                                        </h2>
                                        <div id="collapseEssayYear-{{ $year }}" class="accordion-collapse collapse"
                                            data-bs-parent="#essaysByYear">
                                            <div class="accordion-body">
                                                @foreach ($essays as $essay)
                                                    <div class="mt-2 d-flex justify-content-between align-items-center">

                                                        <div>
                                                            @if (Auth::check() && Auth::user()->hasActiveSubscription())
                                                                <strong>{{ $essay->name }}</strong>
                                                            @else
                                                                <strong>
                                                                    <a
                                                                        href="{{ route('subscriptions.index') }}" style="color: var(--primary-color)">{{ $essay->name }}</a>
                                                                </strong>
                                                            @endif
                                                        </div>
                                                        @if (Auth::check() && Auth::user()->hasActiveSubscription())
                                                            <div class="d-flex gap-2">
                                                                <a href="{{ route('essay.pdf.view', $essay->slug) }}"
                                                                    target="_blank">See PDF</a>
                                                                @if ($essay->ppt_file)
                                                                    <a href="{{ asset('storage/' . $essay->ppt_file) }}"
                                                                        download>Download Documents</a>
                                                                @endif
                                                            </div>
                                                        @else
                                                            <div class="unloack-overly"></div>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @elseif($tab === 'sample')
                        @if ($view === 'topic')
                            <div class="accordion" id="samplesByTopic">
                                @foreach ($essaysByTopic as $topicName => $essays)
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingSampleTopic-{{ Str::slug($topicName) }}">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#collapseSampleTopic-{{ Str::slug($topicName) }}">
                                                {{ $topicName }}
                                            </button>
                                        </h2>
                                        <div id="collapseSampleTopic-{{ Str::slug($topicName) }}"
                                            class="accordion-collapse collapse" data-bs-parent="#samplesByTopic">
                                            <div class="accordion-body">
                                                @foreach ($essays as $essay)
                                                    <div class="mt-3 d-flex justify-content-between align-items-center">
                                                        <div><strong>{{ $essay->name }}</strong></div>
                                                        <a href="{{ asset('storage/' . $essay->file) }}"  target="_blank"
                                                            class="btn btn-sm btn-primary">Download</a>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="accordion" id="samplesByYear">
                                @foreach ($essaysByYear as $year => $essays)
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingSampleYear-{{ $year }}">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#collapseSampleYear-{{ $year }}">
                                                {{ $year }} Sample Essays
                                            </button>
                                        </h2>
                                        <div id="collapseSampleYear-{{ $year }}" class="accordion-collapse collapse"
                                            data-bs-parent="#samplesByYear">
                                            <div class="accordion-body">
                                                @foreach ($essays as $essay)
                                                    <div class="mt-2 d-flex justify-content-between align-items-center">
                                                        <div><strong>{{ $essay->name }}</strong></div>
                                                        <div class="d-flex gap-2">
                                                            <a href="{{ asset('storage/' . $essay->file) }}"
                                                                target="_blank">Download PDF</a>
                                                            @if ($essay->ppt_file)
                                                                <a href="{{ asset('storage/' . $essay->ppt_file) }}"
                                                                    download>Download Documents</a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @elseif($tab === 'pastpapers')
                        @if ($view === 'topic')
                            <div class="accordion" id="papersByTopic">
                                @foreach ($papersByTopic as $topicName => $papers)
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingTopic-{{ Str::slug($topicName) }}">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#collapseTopic-{{ Str::slug($topicName) }}">
                                                {{ $topicName }}
                                            </button>
                                        </h2>
                                        <div id="collapseTopic-{{ Str::slug($topicName) }}"
                                            class="accordion-collapse collapse" data-bs-parent="#papersByTopic">
                                            <div class="accordion-body">
                                                @foreach ($papers as $paper)
                                                    <li class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <strong>{{ $paper->month }} {{ $paper->year }}</strong>
                                                            {{ $paper->name }}
                                                        </div>
                                                        <div class="d-flex gap-3">

                                                            @if ($paper->file)
                                                                <a href="{{ asset('storage/' . $paper->file) }}"
                                                                    target="_blank">Question paper</a>
                                                            @endif
                                                            @if ($paper->mark)
                                                                <a href="{{ asset('storage/' . $paper->mark) }}"
                                                                    target="_blank">Mark scheme</a>
                                                            @endif
                                                            @if ($paper->power_point)
                                                                <a href="{{ asset('storage/' . $paper->power_point) }}"
                                                                    target="_blank">Examiner’s Report</a>
                                                            @endif
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="accordion" id="papersByYear">
                                @foreach ($papersByYear as $year => $papers)
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingYear-{{ $year }}">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#collapseYear-{{ $year }}">
                                                {{ $year }} Past Papers
                                            </button>
                                        </h2>
                                        <div id="collapseYear-{{ $year }}" class="accordion-collapse collapse"
                                            data-bs-parent="#papersByYear">
                                            <div class="accordion-body">
                                                @foreach ($papers as $paper)
                                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                                        <div><strong>{{ $paper->month }} {{ $paper->year }}</strong>
                                                            {{ $paper->name }}</div>
                                                        <div class="d-flex gap-3">

                                                            @if ($paper->file)
                                                                <a href="{{ asset('storage/' . $paper->file) }}"
                                                                    target="_blank" style="color: var(--primary-color)">Question paper</a>
                                                            @endif
                                                            @if ($paper->mark)
                                                                <a href="{{ asset('storage/' . $paper->mark) }}"
                                                                    target="_blank" style="color: var(--primary-color)">Mark scheme</a>
                                                            @endif
                                                            @if ($paper->power_point)
                                                                <a href="{{ asset('storage/' . $paper->power_point) }}"
                                                                    target="_blank" style="color: var(--primary-color)">Examiner’s Report</a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @endif

                </div>
            </div>
        </div>
    </div>
@endsection
