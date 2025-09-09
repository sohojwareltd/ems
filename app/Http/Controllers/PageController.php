<?php
namespace App\Http\Controllers;

use App\Mail\ContactFormNotification;
use App\Models\User;
use App\Models\Essay;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Resource;
use App\Models\Qualification;
use App\Models\Subject;
use App\Models\Examboard;
use App\Models\FaqCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PageController extends Controller
{
    /**
     * Display the About Us page
     */
    public function about()
    {
        return view('frontend.pages.about');
    }

    /**
     * Display the Contact Us page
     */
    public function contact()
    {
        $faqPreview = \App\Models\FaqItem::active()->featured()->ordered()->limit(4)->get();
        return view('frontend.pages.contact', compact('faqPreview'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|max:255',
            'phone'      => 'nullable|string|max:25',
            'subject'    => 'required|string|max:255',
            'message'    => 'required|string',
            'newsletter' => 'nullable|in:on,true,1,0,false',
        ]);

        $data['newsletter'] = $request->has('newsletter');
        
        $admins = User::where('role_id', 1)->get();

        foreach ($admins as $admin) {
            Mail::to( setting('store.email', $admin->email))->send(new ContactFormNotification($data));
        }

        return redirect()->back()->with('success', 'Your message has been sent successfully!');
    }
    
    /**
     * Display the FAQ page
     */
    public function faq()
    {
        $faqCategories = FaqCategory::active()
            ->ordered()
            ->with(['activeFaqItems' => function ($query) {
                $query->ordered();
            }])
            ->get();

        return view('frontend.pages.faq', compact('faqCategories'));
    }


    public function model(Request $request)
    {

                $query = Essay::with(['category', 'brand', 'resource', 'qualiification', 'subject', 'examboard'])->where('status', 'active');

        // Filter by category (by slug only)
        if ($request->has('category') && $request->category) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Filter by brand
        if ($request->has('brand') && $request->brand) {
            $query->where('brand_id', $request->brand);
        }
        // Filter by resource
        if ($request->has('resource') && $request->resource) {
            $query->where('resource_type_id', $request->resource);
        }
        // Filter by brand
        if ($request->has('qualiification') && $request->qualiification) {
            $query->where('qualiification_id', $request->qualiification);
        }
        // Filter by brand
        if ($request->has('subject') && $request->subject) {
            $query->where('subject_id', $request->subject);
        }
        // Filter by brand
        if ($request->has('examboard') && $request->examboard) {
            $query->where('examboard_id', $request->examboard);
        }

        // Search by name or description
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        // Price range filter
        if ($request->has('min_price') && $request->min_price) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price') && $request->max_price) {
            $query->where('price', '<=', $request->max_price);
        }

        // Sort products
        $sort = $request->get('sort', 'name');

        // Handle sorting with _desc suffix
        if (str_ends_with($sort, '_desc')) {
            $sort = str_replace('_desc', '', $sort);
            $direction = 'desc';
        } else {
            $direction = 'asc';
        }

        switch ($sort) {
            case 'price':
                $query->orderBy('price', $direction);
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'popular':
                $query->orderBy('views', 'desc');
                break;
            case 'name':
            default:
                $query->latest();
                break;
        }

        $products = $query->paginate(12);
        $categories = Category::all();
        $brands = Brand::all();
        $resources = Resource::all();
        $qualiifications = Qualification::all();
        $subjects = Subject::all();
        $examboards = Examboard::all();

        // Get current category for display
        $currentCategory = null;
        if ($request->has('category') && $request->category) {
            $currentCategory = Category::where('slug', $request->category)->first();
        }

        return view('frontend.essays.index', compact('products', 'categories', 'brands', 'currentCategory', 'resources', 'qualiifications', 'subjects', 'examboards'));
        
    }


        public function show(Essay $product)
    {
        $product->increment('views');
        // Get related products - first try same category and brand, then same category, then same brand
        $relatedProducts = Essay::where('status', 'active')
            ->where('id', '!=', $product->id) // Exclude current product
            ->where(function($query) use ($product) {
                $query->where(function($q) use ($product) {
                    // Same category and brand
                    $q->where('category_id', $product->category_id)
                      ->where('brand_id', $product->brand_id);
                })->orWhere(function($q) use ($product) {
                    // Same category only
                    $q->where('category_id', $product->category_id)
                      ->where('brand_id', '!=', $product->brand_id);
                })->orWhere(function($q) use ($product) {
                    // Same brand only
                    $q->where('brand_id', $product->brand_id)
                      ->where('category_id', '!=', $product->category_id);
                });
            })
            ->orderByRaw('
                CASE
                    WHEN category_id = ? AND brand_id = ? THEN 1
                    WHEN category_id = ? THEN 2
                    WHEN brand_id = ? THEN 3
                    ELSE 4
                END
            ', [$product->category_id, $product->brand_id, $product->category_id, $product->brand_id])
            ->limit(4)
            ->get();

        // If we don't have enough related products, get more from the same category
        if ($relatedProducts->count() < 4) {
            $additionalProducts = Essay::where('status', 'active')
                ->where('id', '!=', $product->id)
                ->whereNotIn('id', $relatedProducts->pluck('id'))
                ->where('category_id', $product->category_id)
                ->limit(4 - $relatedProducts->count())
                ->get();

            $relatedProducts = $relatedProducts->merge($additionalProducts);
        }

        // If still not enough, get random products
        if ($relatedProducts->count() < 4) {
            $randomProducts = Essay::where('status', 'active')
                ->where('id', '!=', $product->id)
                ->whereNotIn('id', $relatedProducts->pluck('id'))
                ->inRandomOrder()
                ->limit(4 - $relatedProducts->count())
                ->get();

            $relatedProducts = $relatedProducts->merge($randomProducts);
        }

        // return view('frontend.products.show-digital', compact('product', 'relatedProducts'));

        // Default (physical) product view
        return view('frontend.essays.show', compact('product', 'relatedProducts'));
    }
}
