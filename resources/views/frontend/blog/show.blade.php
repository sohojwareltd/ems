@extends('frontend.layouts.app')

@section('title', $post->title)

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('blog.index') }}">Blog</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('blog.index', ['category' => $post->category->slug]) }}">{{ $post->category->name }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $post->title }}</li>
                </ol>
            </nav>

            <!-- Article Header -->
            <header class="mb-5">
                <div class="d-flex align-items-center mb-3">
                    <span class="badge bg-primary me-3">{{ $post->category->name }}</span>
                    <small class="text-muted">{{ $post->created_at->format('F d, Y') }}</small>
                </div>
                <h1 class="display-4 mb-3">{{ $post->title }}</h1>
                <p class="lead text-muted mb-4">{{ $post->excerpt }}</p>
                
                <!-- Meta Information -->
                <div class="d-flex align-items-center text-muted small">
                    <span class="me-3">
                        <i class="bi bi-clock me-1"></i>
                        {{ $post->reading_time }} min read
                    </span>
                    <span class="me-3">
                        <i class="bi bi-eye me-1"></i>
                        {{ $post->view_count }} views
                    </span>
                    <span>
                        <i class="bi bi-person me-1"></i>
                        By Admin
                    </span>
                </div>
            </header>

            <!-- Featured Image -->
            @if($post->featured_image)
            <div class="mb-5">
                <img src="{{ $post->featured_image_url }}" 
                     class="img-fluid rounded shadow" 
                     alt="{{ $post->title }}">
            </div>
            @endif

            <!-- Article Content -->
            <article class="blog-content">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        {!! $post->content !!}
                    </div>
                </div>
            </article>

            <!-- Tags -->
            @if($post->tags)
            <div class="mt-5">
                <h5 class="mb-3">Tags:</h5>
                <div class="d-flex flex-wrap gap-2">
                    @foreach(explode(',', $post->tags) as $tag)
                    <span class="badge bg-light text-dark border">{{ trim($tag) }}</span>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Share Buttons -->
            <div class="mt-5 pt-4 border-top">
                <h5 class="mb-3">Share this post:</h5>
                <div class="d-flex gap-2">
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" 
                       target="_blank" 
                       class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-facebook me-1"></i>Facebook
                    </a>
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($post->title) }}" 
                       target="_blank" 
                       class="btn btn-outline-info btn-sm">
                        <i class="bi bi-twitter me-1"></i>Twitter
                    </a>
                    <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(request()->url()) }}" 
                       target="_blank" 
                       class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-linkedin me-1"></i>LinkedIn
                    </a>
                </div>
            </div>

            <!-- Related Posts -->
            @if($relatedPosts->count() > 0)
            <div class="mt-5 pt-5 border-top">
                <h3 class="mb-4">Related Posts</h3>
                <div class="row g-4">
                    @foreach($relatedPosts as $relatedPost)
                    <div class="col-md-6">
                        <div class="card h-100 border-0 shadow-sm">
                            <img src="{{ $relatedPost->featured_image_url }}" 
                                 class="card-img-top" 
                                 alt="{{ $relatedPost->title }}">
                            <div class="card-body">
                                <span class="badge bg-secondary mb-2">{{ $relatedPost->category->name }}</span>
                                <h5 class="card-title">{{ $relatedPost->title }}</h5>
                                <p class="card-text text-muted">{{ Str::limit($relatedPost->excerpt, 100) }}</p>
                                <a href="{{ route('blog.show', $relatedPost->slug) }}" class="btn btn-outline-primary btn-sm">Read More</a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <div class="sticky-top" style="top: 2rem;">
                <!-- Categories -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Categories</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            @foreach($categories as $category)
                            <a href="{{ route('blog.index', ['category' => $category->slug]) }}" 
                               class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ request('category') == $category->slug ? 'active' : '' }}">
                                {{ $category->name }}
                                <span class="badge bg-primary rounded-pill">{{ $category->posts_count }}</span>
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Recent Posts -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0">Recent Posts</h5>
                    </div>
                    <div class="card-body">
                        @foreach($recentPosts as $recentPost)
                        <div class="d-flex mb-3">
                            <img src="{{ $recentPost->featured_image_url }}" 
                                 class="rounded me-3" 
                                 style="width: 60px; height: 60px; object-fit: cover;" 
                                 alt="{{ $recentPost->title }}">
                            <div>
                                <h6 class="mb-1">
                                    <a href="{{ route('blog.show', $recentPost->slug) }}" class="text-decoration-none">
                                        {{ Str::limit($recentPost->title, 50) }}
                                    </a>
                                </h6>
                                <small class="text-muted">{{ $recentPost->created_at->format('M d, Y') }}</small>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.blog-content {
    font-size: 1.1rem;
    line-height: 1.8;
}

.blog-content h2 {
    margin-top: 2rem;
    margin-bottom: 1rem;
    color: #333;
}

.blog-content h3 {
    margin-top: 1.5rem;
    margin-bottom: 0.75rem;
    color: #444;
}

.blog-content p {
    margin-bottom: 1.5rem;
}

.blog-content ul, .blog-content ol {
    margin-bottom: 1.5rem;
    padding-left: 2rem;
}

.blog-content blockquote {
    border-left: 4px solid #007bff;
    padding-left: 1rem;
    margin: 1.5rem 0;
    font-style: italic;
    color: #666;
}

.blog-content img {
    max-width: 100%;
    height: auto;
    border-radius: 0.375rem;
    margin: 1.5rem 0;
}
</style>
@endsection 