<form id="filterForm" method="GET" action="{{ route('model.index') }}">
    <div class="row g-3">
        <!-- Search -->
        <div class="col-12">
            <label class="form-label">Filter by Year</label>
            <div class="row">
                @foreach (range(2019, 2024) as $year)
                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="years[]" value="{{ $year }}"
                                id="year-{{ $year }}"
                                {{ in_array($year, (array) request('years')) ? 'checked' : '' }}>
                            <label class="form-check-label" for="year-{{ $year }}">
                                {{ $year }}
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="col-12">
            <label class="form-label">Filter by Month</label>
            <div class="row">
                @php
                    $months = ['January', 'June', 'November'];
                @endphp

                @foreach ($months as $month)
                    <div class="col-md-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="months[]" value="{{ $month }}"
                                id="month-{{ strtolower($month) }}"
                                {{ in_array($month, (array) request('months')) ? 'checked' : '' }}>
                            <label class="form-check-label" for="month-{{ strtolower($month) }}">
                                {{ $month }}
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="col-12">
            <label class="form-label">Filter by Marks</label>
            <div class="row">
                @php
                    $marks = [6, 9, 12];
                @endphp

                @foreach ($marks as $mark)
                    <div class="col-md-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="marks[]" value="{{ $mark }}"
                                id="mark-{{ $mark }}"
                                {{ in_array($mark, (array) request('marks')) ? 'checked' : '' }}>
                            <label class="form-check-label" for="mark-{{ $mark }}">
                                {{ $mark }} Marks
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="col-12">
            <label for="topics" class="form-label">Topics</label>
            <select class="form-select" id="topics" name="topics">
                <option value="">All Topics</option>
                @foreach ($topics as $topic)
                    <option value="{{ $topic->id }}" {{ request('topics') == $topic->id ? 'selected' : '' }}>
                        {{ $topic->name }}
                    </option>
                @endforeach
            </select>
        </div>
        {{-- <div class="col-12">
            <label for="qualifications" class="form-label">Qualifications</label>
            <select class="form-select" id="qualifications" name="qualification">
                <option value="">All Qualifications</option>
                @foreach ($qualifications as $qualification)
                    <option value="{{ $qualification->id }}"
                        {{ request('qualification') == $qualification->id ? 'selected' : '' }}>
                        {{ $qualification->title }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-12">
            <label for="subjects" class="form-label">Subjects</label>
            <select class="form-select" id="subjects" name="subject">
                <option value="">All Subjects</option>
                @foreach ($subjects as $subject)
                    <option value="{{ $subject->id }}" {{ request('subject') == $subject->id ? 'selected' : '' }}>
                        {{ $subject->title }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-12 mb-3">
            <label for="examBoards" class="form-label">Exam Boards</label>
            <select class="form-select" id="examBoards" name="exam_board">
                <option value="">All Exam Boards</option>
                @foreach ($examBoards as $examBoard)
              
                    <option value="{{ $examBoard->id }}" {{ request('exam_board') == $examBoard->id ? 'selected' : '' }}>
                        {{ $examBoard->title }}
                    </option>
                @endforeach
            </select>
        </div> --}}



        <div class="col-12 d-flex align-items-end">
            <div class="d-flex gap-2 w-100">
                <button type="submit" class="btn custom-btn flex-fill">
                    <i class="bi bi-search me-2"></i>Apply Filters
                </button>
                <a href="{{ route('model.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-clockwise me-2"></i>Clear
                </a>
            </div>
        </div>


    </div>
</form>
