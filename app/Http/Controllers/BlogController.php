<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\BlogCategory;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Display a listing of blog posts
     */
    public function index(Request $request)
    {
        $query = BlogPost::with(['category', 'user'])
            ->published()
            ->orderBy('published_at', 'desc');

        // Filter by category
        if ($request->has('category')) {
            $category = BlogCategory::where('slug', $request->category)->first();
            if ($category) {
                $query->where('blog_category_id', $category->id);
            }
        }

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $query->search($request->search);
        }

        $posts = $query->paginate(12);
        $categories = BlogCategory::active()->ordered()->withCount('publishedPosts')->get();

        // Get featured post (first published post)
        $featuredPost = BlogPost::with(['category', 'user'])
            ->published()
            ->orderBy('published_at', 'desc')
            ->first();

        return view('frontend.blog.index', compact('posts', 'categories', 'featuredPost'));
    }

    /**
     * Display the specified blog post
     */
    public function show($slug)
    {
        $post = BlogPost::with(['category', 'user'])
            ->where('slug', $slug)
            ->published()
            ->firstOrFail();

        // Increment view count
        $post->incrementViewCount();

        // Get related posts from the same category
        $relatedPosts = BlogPost::with(['category', 'user'])
            ->where('blog_category_id', $post->blog_category_id)
            ->where('id', '!=', $post->id)
            ->published()
            ->orderBy('published_at', 'desc')
            ->limit(3)
            ->get();

        // Get all categories for sidebar
        $categories = BlogCategory::active()->ordered()->withCount('publishedPosts')->get();

        // Get recent posts for sidebar
        $recentPosts = BlogPost::with(['category', 'user'])
            ->published()
            ->orderBy('published_at', 'desc')
            ->limit(5)
            ->get();

        return view('frontend.blog.show', compact('post', 'relatedPosts', 'categories', 'recentPosts'));
    }

    /**
     * Display posts by category
     */
    public function category($slug)
    {
        $category = BlogCategory::where('slug', $slug)->firstOrFail();
        
        $posts = BlogPost::with(['category', 'user'])
            ->where('blog_category_id', $category->id)
            ->published()
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        $categories = BlogCategory::active()->ordered()->withCount('publishedPosts')->get();

        // Get other categories for the bottom section
        $otherCategories = BlogCategory::active()
            ->where('id', '!=', $category->id)
            ->ordered()
            ->withCount('publishedPosts')
            ->get();

        return view('frontend.blog.category', compact('posts', 'categories', 'category', 'otherCategories'));
    }
}
