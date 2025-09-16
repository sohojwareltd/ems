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
use App\Models\Product;
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
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:25',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'newsletter' => 'nullable|in:on,true,1,0,false',
        ]);

        $data['newsletter'] = $request->has('newsletter');

        $admins = User::where('role_id', 1)->get();

        foreach ($admins as $admin) {
            Mail::to(setting('store.email', $admin->email))->send(new ContactFormNotification($data));
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
            ->with([
                'activeFaqItems' => function ($query) {
                    $query->ordered();
                }
            ])
            ->get();

        return view('frontend.pages.faq', compact('faqCategories'));
    }


    public function model(Request $request)
    {

        $query = Essay::with(['category', 'brand', 'resource', 'qualiification', 'subject', 'examboard'])->where('status', 'active');
        

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
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
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
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'popular':
                $query->orderBy('views', 'desc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            default:
                $query->latest();
                break;
        }

        $products = $query->paginate(12);

        $resources = Resource::all();
        $qualiifications = Qualification::all();
        $subjects = Subject::all();
        $examboards = Examboard::all();

        // Get current category for display
        $currentCategory = null;
        if ($request->has('category') && $request->category) {
            $currentCategory = Category::where('slug', $request->category)->first();
        }

        return view('frontend.essays.index', compact('products',  'currentCategory', 'resources', 'qualiifications', 'subjects', 'examboards'));

    }


    public function show(Essay $product)
    {
        // Increase product view count
        $product->increment('views');

        // Load relations (no category, brand here)
        $product->load(['resource', 'qualiification', 'subject', 'examboard']);

        // Get related products - try subject, then examboard, then qualification
        $relatedProducts = Essay::with(['subject', 'examboard', 'qualiification', 'resource'])
            ->where('status', 'active')
            ->where('id', '!=', $product->id)
            ->where(function ($query) use ($product) {
                $query->where('subject_id', $product->subject_id)
                    ->orWhere('resource_type_id', $product->resource_type_id)
                    ->orWhere('examboard_id', $product->examboard_id)
                    ->orWhere('qualiification_id', $product->qualiification_id);
            })
            ->limit(4)
            ->get();

        // Fill remaining slots with random products if not enough
        if ($relatedProducts->count() < 4) {
            $randomProducts = Essay::with(['subject', 'examboard', 'qualiification', 'resource'])
                ->where('status', 'active')
                ->where('id', '!=', $product->id)
                ->whereNotIn('id', $relatedProducts->pluck('id'))
                ->inRandomOrder()
                ->limit(4 - $relatedProducts->count())
                ->get();

            $relatedProducts = $relatedProducts->merge($randomProducts);
        }

        // Return product details page
        return view('frontend.essays.show', compact('product', 'relatedProducts'));
    }


    // public function show(Essay $product)
    // {
    //     $product->increment('views');

    //     $product->load(['category', 'brand', 'resource', 'qualiification', 'subject', 'examboard']);


    //     $relatedProducts = Essay::with(['category', 'brand'])
    //         ->where('status', 'active')
    //         ->where('id', '!=', $product->id) 
    //         ->where(function ($query) use ($product) {
    //             $query->where(function ($q) use ($product) {

    //                 $q->where('category_id', $product->category_id)
    //                     ->where('brand_id', $product->brand_id);
    //             })->orWhere(function ($q) use ($product) {

    //                 $q->where('category_id', $product->category_id)
    //                     ->where('brand_id', '!=', $product->brand_id);
    //             })->orWhere(function ($q) use ($product) {

    //                 $q->where('brand_id', $product->brand_id)
    //                     ->where('category_id', '!=', $product->category_id);
    //             });
    //         })
    //         ->orderByRaw('
    //         CASE
    //             WHEN category_id = ? AND brand_id = ? THEN 1
    //             WHEN category_id = ? THEN 2
    //             WHEN brand_id = ? THEN 3
    //             ELSE 4
    //         END
    //     ', [$product->category_id, $product->brand_id, $product->category_id, $product->brand_id])
    //         ->limit(4)
    //         ->get();

    //     if ($relatedProducts->count() < 4) {
    //         $additionalProducts = Essay::where('status', 'active')
    //             ->where('id', '!=', $product->id)
    //             ->whereNotIn('id', $relatedProducts->pluck('id'))
    //             ->where('category_id', $product->category_id)
    //             ->limit(4 - $relatedProducts->count())
    //             ->get();

    //         $relatedProducts = $relatedProducts->merge($additionalProducts);
    //     }

    //     if ($relatedProducts->count() < 4) {
    //         $randomProducts = Essay::where('status', 'active')
    //             ->where('id', '!=', $product->id)
    //             ->whereNotIn('id', $relatedProducts->pluck('id'))
    //             ->inRandomOrder()
    //             ->limit(4 - $relatedProducts->count())
    //             ->get();

    //         $relatedProducts = $relatedProducts->merge($randomProducts);
    //     }

    //     return view('frontend.essays.show', compact('product', 'relatedProducts'));
    // }

    public function pdfView($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();
        return view('frontend.products.pdfview', compact('product'));
    }

}
