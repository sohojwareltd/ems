@extends('frontend.layouts.app')

@section('title', 'Blog')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h1 class="display-4 mb-4">Our Blog</h1>
            <p class="lead text-muted mb-5">Stay updated with the latest news, tips, and insights from our team.</p>
        </div>
    </div>

    <!-- Featured Post -->
    @if($featuredPost)
    <div class="row mb-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="row g-0">
                    <div class="col-md-6">
                        <img src="{{ $featuredPost->featured_image_url }}" 
                             class="img-fluid rounded-start h-100 object-fit-cover" 
                             alt="{{ $featuredPost->title }}">
                    </div>
                    <div class="col-md-6">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-2">
                                <span class="badge bg-primary me-2">{{ $featuredPost->category->name }}</span>
                                <small class="text-muted">{{ $featuredPost->created_at->format('M d, Y') }}</small>
                            </div>
                            <h2 class="card-title h3 mb-3">{{ $featuredPost->title }}</h2>
                            <p class="card-text text-muted">{{ Str::limit($featuredPost->excerpt, 150) }}</p>
                            <a href="{{ route('blog.show', $featuredPost->slug) }}" class="btn btn-primary">Read More</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Categories Filter -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('blog.index') }}" 
                   class="btn btn-outline-primary {{ request()->routeIs('blog.index') && !request('category') ? 'active' : '' }}">
                    All Posts
                </a>
                @foreach($categories as $category)
                <a href="{{ route('blog.index', ['category' => $category->slug]) }}" 
                   class="btn btn-outline-primary {{ request('category') == $category->slug ? 'active' : '' }}">
                    {{ $category->name }}
                </a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Blog Posts Grid -->
    <div class="row g-4">
        @forelse($posts as $post)
        <div class="col-md-6 col-lg-4">
            <article class="card h-100 border-0 shadow-sm">
                <img src="{{ $post->featured_image_url }}" 
                     class="card-img-top" 
                     alt="{{ $post->title }}">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <span class="badge bg-secondary me-2">{{ $post->category->name }}</span>
                        <small class="text-muted">{{ $post->created_at->format('M d, Y') }}</small>
                    </div>
                    <h3 class="card-title h5 mb-3">{{ $post->title }}</h3>
                    <p class="card-text text-muted">{{ Str::limit($post->excerpt, 100) }}</p>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="{{ route('blog.show', $post->slug) }}" class="btn btn-outline-primary btn-sm">Read More</a>
                </div>
            </article>
        </div>
        @empty
        <div class="col-12">
            <div class="text-center py-5">
                <i class="bi bi-journal-text display-1 text-muted"></i>
                <h3 class="mt-3">No posts found</h3>
                <p class="text-muted">Check back later for new content!</p>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($posts->hasPages())
    <div class="row mt-5">
        <div class="col-12">
            <nav aria-label="Blog pagination">
                {{ $posts->links() }}
            </nav>
        </div>
    </div>
    @endif
</div>
@endsection 