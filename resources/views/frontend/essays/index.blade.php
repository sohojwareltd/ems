@extends('frontend.layouts.app')

@php use Illuminate\Support\Arr; @endphp

@section('title', 'Essays - EMS')
@section('meta_description', 'Explore our collection of books. Find your next great read or the perfect gift for a book
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
        </div>

        <div class="row">
            <!-- Offcanvas Trigger (Mobile) -->
            <div class="col-12 d-md-none mb-3">
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
                    ])
                </div>
            </div>

            <!-- Desktop Filters -->
            <div class="col-md-4 mb-4 d-none d-md-block">
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
                            ])
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-8">

                {{-- Active Filters --}}
                @if (request()->except(['page']))
                    <div class="mb-3">
                        <h6 class="mb-2">Active Filters:</h6>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach (request()->except(['page']) as $key => $value)
                                @php
                                    $titles = [];
                                    if ($key === 'qualification') {
                                        $titles = $qualificationsMap;
                                    } elseif ($key === 'subject') {
                                        $titles = $subjectsMap;
                                    } elseif ($key === 'exam_board') {
                                        $titles = $examBoardsMap;
                                    } elseif ($key === 'topics') {
                                        $titles = $topicsMap;
                                    }
                                @endphp

                                @if (is_array($value))
                                    @foreach ($value as $v)
                                        <a href="{{ route('model.index', array_diff_key(request()->all(), [$key => '']) + [$key => array_diff((array) $value, [$v])]) }}"
                                            class="badge bg-primary text-white text-decoration-none">
                                            {{ ucfirst($key) }}: {{ $titles[$v] ?? $v }} <i class="bi bi-x ms-1"></i>
                                        </a>
                                    @endforeach
                                @else
                                    <a href="{{ route('model.index', Arr::except(request()->all(), [$key])) }}"
                                        class="badge bg-primary text-white text-decoration-none">
                                        {{ ucfirst($key) }}: {{ $titles[$value] ?? $value }} <i class="bi bi-x ms-1"></i>
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="container py-5">
                    @php
                        $query = request()->except(['tab']);
                    @endphp

                    {{-- Main Tabs --}}
                    <ul class="nav nav-tabs border-0">
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
                                                        <a href="{{ asset('storage/' . $essay->file) }}" target="_blank"
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
                                                        <div><strong>{{ $essay->name }}</strong></div>
                                                        @if (Auth::check() && Auth::user()->hasActiveSubscription())
                                                            <div class="d-flex gap-2">
                                                                <a href="{{ asset('storage/' . $essay->file) }}"
                                                                    target="_blank">See PDF</a>
                                                                <a href="{{ asset('storage/' . $essay->file) }}"
                                                                    download>Download PowerPoint</a>
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
                                                            @if ($paper->power_point)
                                                                <a href="{{ asset('storage/' . $paper->power_point) }}"
                                                                    target="_blank">Insert</a>
                                                            @endif
                                                            @if ($paper->file)
                                                                <a href="{{ asset('storage/' . $paper->file) }}"
                                                                    target="_blank">Question paper</a>
                                                            @endif
                                                            @if ($paper->mark)
                                                                <a href="{{ asset('storage/' . $paper->mark) }}"
                                                                    target="_blank">Mark scheme</a>
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
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div><strong>{{ $paper->month }} {{ $paper->year }}</strong>
                                                            {{ $paper->name }}</div>
                                                        <div class="d-flex gap-3">
                                                            @if ($paper->power_point)
                                                                <a href="{{ asset('storage/' . $paper->power_point) }}"
                                                                    target="_blank">Insert</a>
                                                            @endif
                                                            @if ($paper->file)
                                                                <a href="{{ asset('storage/' . $paper->file) }}"
                                                                    target="_blank">Question paper</a>
                                                            @endif
                                                            @if ($paper->mark)
                                                                <a href="{{ asset('storage/' . $paper->mark) }}"
                                                                    target="_blank">Mark scheme</a>
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
