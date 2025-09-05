@extends('frontend.layouts.app')

@section('title', 'My Audiobooks - MyShop')

@section('content')
    <div class="container py-5">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h2 mb-1 fw-bold text-dark">
                            <i class="fas fa-headphones me-2 text-primary"></i>
                            My Audiobooks
                        </h1>
                        <p class="text-muted mb-0">Listen to your purchased audiobooks anytime, anywhere</p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Audio Player Section -->
        @if ($audiobooks->count() > 0)

            @foreach ($audiobooks as $audiobook)
                <div id="player-section" class="mb-4 hidden">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="row align-items-center">
                                <div class="col-md-2 text-center">
                                    <img id="player-cover" src="{{ $audiobook->getCoverImageUrl() }}" alt="Cover"
                                        class="img-fluid rounded shadow-sm" style="max-width: 120px; height: auto;">
                                </div>
                                <div class="col-md-10">
                                    <h5 class="fw-bold text-dark mb-1" id="player-title">{{ $audiobook->title }}</h5>
                                    <p class="text-muted mb-2" id="player-author">{{ $audiobook->author }}</p>
                                    <p class="text-muted mb-3">
                                        {{ $audiobook->description }}
                                    </p>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-primary"
                                            onclick="viewPlaylist({{ json_encode($audiobook) }})">
                                            <i class="fas fa-list me-2"></i>View Playlist
                                        </button>
                                        <a id="player-download-zip"
                                            href="{{ route('user.audiobooks.download-zip', $audiobook) }}"
                                            class="btn btn-success">
                                            <i class="fas fa-download me-2"></i>Download Full Audiobook
                                        </a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center py-5">
                            <div class="mb-4">
                                <i class="fas fa-headphones text-muted" style="font-size: 4rem;"></i>
                            </div>
                            <h4 class="fw-bold text-dark mb-3">No Audiobooks Yet</h4>
                            <p class="text-muted mb-4">You haven't purchased any audiobooks yet. Start exploring our
                                collection!</p>
                            <div class="d-flex justify-content-center gap-3">
                                <a href="{{ route('products.index') }}" class="btn btn-primary">
                                    <i class="fas fa-shopping-cart me-2"></i>Browse Products
                                </a>
                                <a href="{{ route('dashboard') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif




    </div>

    <!-- Playlist Modal -->
    <div class="modal fade" id="playlistModal" tabindex="-1" aria-labelledby="playlistModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="playlistModalLabel">
                        <i class="fas fa-list me-2"></i>Playlist
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="playlist-content">
                        <!-- Playlist content will be loaded here -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Custom Styles for Audio Player -->
    <style>
        .audio-container {
            background: var(--light-bg);
            border-radius: 8px;
            padding: 0.5rem;
            border: 1px solid var(--border-color);
        }

        .audio-container audio {
            border-radius: 6px;
        }

        #player-tracklist button {
            transition: all 0.3s ease;
            border-radius: 6px;
            font-size: 0.875rem;
        }

        #player-tracklist button:hover {
            transform: translateY(-1px);
        }

        .product-card {
            transition: all 0.3s ease;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--shadow-soft);
            background: var(--white);
        }

        .product-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-medium);
            border-color: var(--accent-color);
        }

        .product-image {
            height: 280px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .product-card:hover .product-image {
            transform: scale(1.02);
        }
    </style>
@endsection

@push('scripts')
    <script src="https://cdn.plyr.io/3.7.8/plyr.polyfilled.js"></script>
    <link rel="stylesheet" href="https://cdn.plyr.io/3.7.8/plyr.css" />
    <script>
        let currentFiles = [];



        function playTrialAudioBook(data) {
            document.getElementById('player-section').classList.remove('hidden');
            document.getElementById('player-cover').src = data.cover;
            document.getElementById('player-title').textContent = data.title;
            document.getElementById('player-author').textContent = data.author;

            // Scroll to player
            document.getElementById('player-section').scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }

        function closePlayer() {
            document.getElementById('player-section').classList.add('hidden');
        }

        function viewPlaylist(data = null) {

            const playlistContent = document.getElementById('playlist-content');
            playlistContent.innerHTML = '';

            // Use passed data or fall back to currentFiles
            const files = data ? data.audio_files : currentFiles;
            const title = data ? data.title : 'Current Audiobook';

            // Update modal title with audiobook info
            const modalTitle = document.getElementById('playlistModalLabel');
            modalTitle.innerHTML = `<i class="fas fa-list me-2"></i>Playlist - ${title}`;

            if (files && files.length > 0) {
                const table = document.createElement('table');
                table.className = 'table table-hover';
                table.innerHTML = `
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Track Title</th>
                        <th>Duration</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    ${files.map((file, index) => `
                                    <tr>
                                        <td>${index + 1}</td>
                                        <td>${file.title}</td>
                                        <td><span class="text-muted">${(file.duration / 60).toFixed(1)} minutes</span></td>
                                        <td>
                                            <a href="{{ env('APP_URL') }}${`/dashboard/audiobooks/${data.id}/stream?file=${file.file}`}" class="btn btn-sm btn-outline-primary" target="_blank">
                                                <i class="fas fa-play me-1"></i>Play
                                            </a>
                                            <a href="{{ env('APP_URL') }}${`/dashboard/audiobooks/${data.id}/download?file=${file.file}`}" class="btn btn-sm btn-outline-success" download>
                                                <i class="fas fa-download me-1"></i>Download
                                            </a>
                                        </td>
                                    </tr>
                                `).join('')}
                </tbody>
            `;
                playlistContent.appendChild(table);
            } else {
                playlistContent.innerHTML = '<p class="text-muted text-center">No tracks available</p>';
            }

            // Show the modal
            const modal = new bootstrap.Modal(document.getElementById('playlistModal'));
            modal.show();
        }
    </script>
@endpush
