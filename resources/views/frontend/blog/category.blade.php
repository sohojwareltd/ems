@extends('frontend.layouts.app')

@section('title', $category->name . ' - Blog')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('blog.index') }}">Blog</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $category->name }}</li>
                </ol>
            </nav>

            <!-- Category Header -->
            <div class="text-center mb-5">
                <h1 class="display-4 mb-3">{{ $category->name }}</h1>
                @if($category->description)
                <p class="lead text-muted">{{ $category->description }}</p>
                @endif
                <div class="d-flex justify-content-center align-items-center mt-3">
                    <span class="badge bg-primary me-2">{{ $posts->total() }} posts</span>
                    <span class="text-muted">in this category</span>
                </div>
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
                <h3 class="mt-3">No posts found in this category</h3>
                <p class="text-muted">Check back later for new content in {{ $category->name }}!</p>
                <a href="{{ route('blog.index') }}" class="btn btn-primary">Browse All Posts</a>
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

    <!-- Other Categories -->
    <div class="row mt-5 pt-5 border-top">
        <div class="col-12">
            <h3 class="mb-4">Other Categories</h3>
            <div class="d-flex flex-wrap gap-2">
                @foreach($otherCategories as $otherCategory)
                <a href="{{ route('blog.index', ['category' => $otherCategory->slug]) }}" 
                   class="btn btn-outline-secondary">
                    {{ $otherCategory->name }}
                    <span class="badge bg-secondary ms-1">{{ $otherCategory->posts_count }}</span>
                </a>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection 