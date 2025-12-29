<?php

namespace App\Http\Controllers;

use App\Mail\ContactFormNotification;
use App\Models\User;
use App\Models\Essay;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Contact;
use App\Models\Resource;
use App\Models\Qualification;
use App\Models\Subject;
use App\Models\Examboard;
use App\Models\FaqCategory;
use App\Models\PastPaper;
use App\Models\Product;
use App\Models\Topic;
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
        $contactCategories = \App\Models\ContactCategory::active()->ordered()->get();
        return view('frontend.pages.contact', compact('faqPreview', 'contactCategories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:25',
            'contact_category_id' => 'required|exists:contact_categories,id',
            'message' => 'required|string',
            // 'newsletter' => 'nullable|in:on,true,1,0,false',
        ]);

        // Set default status for new enquiries
        $data['status'] = Contact::STATUS_NEW;

        Contact::create($data);

        // $data['newsletter'] = $request->has('newsletter');

        // $admins = User::where('role_id', 1)->get();


        Mail::to(setting('store.email'))->send(new ContactFormNotification($data));


        return redirect()->back()->with('success', 'Your enquiry has been submitted successfully! We will respond shortly.');
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
        $tab = $request->get('tab', 'essays'); // default: essays
        $view = $request->get('view', 'year'); // default: year

        $filters = $request->only([
            'years',
            'months',
            'marks',
            'topic',
            'qualification',
            'subject',
            'exam_board',
            'search',
            'paper_code',
            'paper',
        ]);
        $topics = Topic::all();
        $qualifications = Qualification::all();
        $examBoards = Examboard::all();
        $subjects = Subject::all();

        $essaysQuery = Essay::with('topics')->filter($filters);

        // Apply sample filter if on sample tab
        if ($tab === 'sample') {
            $essaysQuery->sample();
        }

        $essays = $essaysQuery->latest()->get();

        $essaysByYear = $essays->groupBy('year');
        if (request('tab') == 'pastpapers') {
            $essaysByYearByFilter = PastPaper::groupBy('year')->pluck('year');
        } elseif (request('tab') == 'sample') {
            $essaysByYearByFilter = Essay::sample()->groupBy('year')->pluck('year');
        } else {
            $essaysByYearByFilter = Essay::groupBy('year')->pluck('year');
        }


        $essaysByTopic = $essays->groupBy(fn($e) => optional($e->topic)->name ?? 'Unknown Topic');

        $papers = PastPaper::with('topic')
            ->filter($filters)
            ->latest()
            ->get();


        $papersByYear = $papers->groupBy('year');
        $papersByTopic = $papers->groupBy(fn($p) => optional($p->topic)->name ?? 'Unknown Topic');

        return view('frontend.essays.index', compact(
            'tab',
            'view',
            'essaysByYear',
            'essaysByTopic',
            'papersByYear',
            'papersByTopic',
            'topics',
            'qualifications',
            'examBoards',
            'subjects',
            'essaysByYearByFilter'
        ));
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

    public function essayPdfView($slug)
    {
        if (!auth()->user()->hasActiveSubscription()) {
            return abort(403, 'You must have an active subscription to access this content.');
        }
        $essay = Essay::where('slug', $slug)->firstOrFail();

        return view('frontend.essays.pdfview', compact('essay'));
    }
    public function tuition()
    {
        return view('frontend.pages.tuition');
    }
}
