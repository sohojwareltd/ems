<form id="filterForm" method="GET" action="{{ route('model.index') }}">
    <div class="row g-3">

        @php
            // Get current request query parameters except 'view'
            $query = request()->except(['view']);
        @endphp

        {{-- <div class="btn-group mb-4" role="group" aria-label="View Toggle">
            <a href="{{ route('model.index', array_merge($query, ['tab' => $tab, 'view' => 'year'])) }}"
                class="btn custom-btn-outline {{ $view === 'year' ? 'active' : '' }}">
                By Year
            </a>
            <a href="{{ route('model.index', array_merge($query, ['tab' => $tab, 'view' => 'topic'])) }}"
                class="btn custom-btn-outline {{ $view === 'topic' ? 'active' : '' }}">
                By Topic
            </a>
        </div> --}}
        <!-- Search -->
        @php
            $paperCodes = \App\Models\PaperCode::orderBy('name')->get();
        @endphp

        <div class="col-12 topic-wrapper">
            <label class="form-label">Paper</label>
            <select class="form-select paper-code" name="paper">
                <option value="">Select Paper</option>
                @foreach ($papers as $paper)
                    <option value="{{ $paper->id }}" {{ request('paper') == $paper->id ? 'selected' : '' }}>
                        {{ $paper->name }}
                    </option>
                @endforeach
            </select>
            <label class="form-label mt-2">Paper Code</label>
            <select class="form-select paper-code" name="paper_code">
                <option value="">Select Paper Code</option>
                @foreach ($paperCodes as $paperCode)
                    <option value="{{ $paperCode->id }}"
                        {{ request('paper_code') == $paperCode->id ? 'selected' : '' }}>
                        {{ $paperCode->name }}
                    </option>
                @endforeach
            </select>

            @if (request('tab') !== 'pastpapers')
                <label class="form-label mt-2">Topics</label>
                <select class="form-select topic-select" name="topic">
                    <option value="">Select Topic</option>
                </select>
            @endif
        </div>


        <!-- Repeat this div anywhere on the page if needed -->
        

        <div class="col-12">
            <label class="form-label">Year</label>
            <div class="row">
                @foreach ($essaysByYearByFilter as $year)
                    <div class="col-md-3">
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
            <label class="form-label">Month</label>
            <div class="row">
                @php
                    $months = ['January', 'June', 'November'];
                @endphp

                @foreach ($months as $month)
                    <div class="col-md-4">
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
        @if (request('tab') !== 'pastpapers')

            <div class="col-12">
                <label class="form-label">Marks</label>
                <div class="row">
                    @php
                        $marks = [6, 9, 12];
                    @endphp

                    @foreach ($marks as $mark)
                        <div class="col-md-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="marks[]"
                                    value="{{ $mark }}" id="mark-{{ $mark }}"
                                    {{ in_array($mark, (array) request('marks')) ? 'checked' : '' }}>
                                <label class="form-check-label" for="mark-{{ $mark }}">
                                    {{ $mark }} Marks
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        @endif
        <input type="hidden" name="qualification" value="{{ request('qualification') }}">
        <input type="hidden" name="subject" value="{{ request('subject') }}">
        <input type="hidden" name="tab" value="{{ request('tab') ?? 'essays' }}">

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
                <a href="{{ route('model.index', ['qualification' => request('qualification'), 'subject' => request('subject'), 'exam_board' => request('exam_board')]) }}"
                    class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-clockwise me-2"></i>Clear
                </a>
            </div>
        </div>


    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.topic-wrapper').forEach(wrapper => {
                const topicSelect = wrapper.querySelector('select[name="topic"]'); // may be null
                const paperCodeSelect = wrapper.querySelector('select[name="paper_code"]');
                const paperSelect = wrapper.querySelector('select[name="paper"]');

                if (!paperCodeSelect || !paperSelect) {
                    console.warn('Missing paper or paper_code select');
                    return;
                }

                // Fetch Paper Codes based on selected Paper
                paperSelect.addEventListener('change', function() {
                    const paperId = this.value;
                    paperCodeSelect.innerHTML = '<option value="">Loading...</option>';

                    if (topicSelect) topicSelect.innerHTML =
                        '<option value="">Select Topic</option>';

                    if (!paperId) {
                        paperCodeSelect.innerHTML = '<option value="">Select Paper Code</option>';
                        return;
                    }

                    const selectedPaperCode = "{{ request('paper_code') }}";

                    fetch(`/get-paper-codes-by-paper/${paperId}`)
                        .then(res => res.json())
                        .then(data => {
                            let options = '<option value="">Select Paper Code</option>';
                            data.forEach(code => {
                                const selected = selectedPaperCode == code.id ?
                                    'selected' : '';
                                options +=
                                    `<option value="${code.id}" ${selected}>${code.name}</option>`;
                            });
                            paperCodeSelect.innerHTML = options;
                        })
                        .catch(err => {
                            console.error('Error loading paper codes:', err);
                            paperCodeSelect.innerHTML =
                                '<option value="">Error loading paper codes</option>';
                        });

                    // ✅ Only fetch topics if topicSelect exists (not pastpapers tab)
                    if (topicSelect) {
                        const selectedTopic = "{{ request('topic') }}";

                        fetch(`/get-topics-by-paper/${paperId}/{{ request('subject') }}`)
                            .then(res => res.json())
                            .then(data => {
                                let optionsHtml = '<option value="">Select Topic</option>';
                                data.forEach(topic => {
                                    const selected = selectedTopic == topic.id ?
                                        'selected' : '';
                                    optionsHtml +=
                                        `<option value="${topic.id}" ${selected}>${topic.name}</option>`;
                                });
                                topicSelect.innerHTML = optionsHtml;

                                if (data.length === 1) topicSelect.value = data[0].id;
                            })
                            .catch(e => {
                                console.error('Error loading topics:', e);
                                topicSelect.innerHTML =
                                    '<option value="">Error loading topics</option>';
                            });
                    }
                });

                // Auto-load on page load if paper already selected
                if (paperSelect.value) {
                    paperSelect.dispatchEvent(new Event('change'));
                } else if (paperCodeSelect.value) {
                    paperCodeSelect.dispatchEvent(new Event('change'));
                }
            });
        });
    </script>





</form>
