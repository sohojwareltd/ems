<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\FaqCategory;
use App\Models\FaqItem;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create FAQ Categories
        $categories = [
            [
                'name' => 'Ordering',
                'slug' => 'ordering',
                'description' => 'Questions about placing and managing orders',
                'icon' => 'bi-cart-check',
                'color' => '#007bff',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Shipping & Delivery',
                'slug' => 'shipping',
                'description' => 'Information about shipping times, costs, and tracking',
                'icon' => 'bi-truck',
                'color' => '#6c757d',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Returns & Refunds',
                'slug' => 'returns',
                'description' => 'Our return policy and refund process',
                'icon' => 'bi-arrow-return-left',
                'color' => '#28a745',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Payment',
                'slug' => 'payment',
                'description' => 'Payment methods and security information',
                'icon' => 'bi-credit-card',
                'color' => '#17a2b8',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Products',
                'slug' => 'products',
                'description' => 'Information about our book collection and gift boxes',
                'icon' => 'bi-book',
                'color' => '#ffc107',
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Account',
                'slug' => 'account',
                'description' => 'Account management and security',
                'icon' => 'bi-person',
                'color' => '#dc3545',
                'sort_order' => 6,
                'is_active' => true,
            ],
        ];

        foreach ($categories as $categoryData) {
            $category = FaqCategory::create($categoryData);
            
            // Create FAQ items for each category
            $this->createFaqItemsForCategory($category);
        }
    }

    private function createFaqItemsForCategory(FaqCategory $category)
    {
        $faqItems = [];

        switch ($category->slug) {
            case 'ordering':
                $faqItems = [
                    [
                        'question' => 'How do I place an order?',
                        'answer' => '<p>Placing an order is easy! Simply browse our collection, add items to your cart, and proceed to checkout. You\'ll need to create an account or sign in, provide your shipping and billing information, and complete your payment.</p>',
                        'sort_order' => 1,
                        'is_active' => true,
                        'is_featured' => true,
                    ],
                    [
                        'question' => 'Can I modify or cancel my order?',
                        'answer' => '<p>You can modify or cancel your order within 1 hour of placing it by contacting our customer service team. Once your order has been processed and shipped, modifications may not be possible.</p>',
                        'sort_order' => 2,
                        'is_active' => true,
                        'is_featured' => false,
                    ],
                    [
                        'question' => 'Do you offer gift wrapping?',
                        'answer' => '<p>Yes! We offer beautiful gift wrapping for an additional $5. You can select this option during checkout. Our gift boxes are also perfect for special occasions and come beautifully packaged.</p>',
                        'sort_order' => 3,
                        'is_active' => true,
                        'is_featured' => false,
                    ],
                ];
                break;

            case 'shipping':
                $faqItems = [
                    [
                        'question' => 'How long does shipping take?',
                        'answer' => '<p>Standard shipping typically takes 3-5 business days within the continental US. Express shipping (1-2 business days) is available for an additional fee. International shipping times vary by location.</p>',
                        'sort_order' => 1,
                        'is_active' => true,
                        'is_featured' => true,
                    ],
                    [
                        'question' => 'How much does shipping cost?',
                        'answer' => '<p>Standard shipping is free for orders over $50. For orders under $50, standard shipping is $5.99. Express shipping is available for $12.99. International shipping rates vary by location.</p>',
                        'sort_order' => 2,
                        'is_active' => true,
                        'is_featured' => false,
                    ],
                    [
                        'question' => 'How do I track my order?',
                        'answer' => '<p>Once your order ships, you\'ll receive a tracking number via email. You can also track your order by logging into your account and visiting the "My Orders" section.</p>',
                        'sort_order' => 3,
                        'is_active' => true,
                        'is_featured' => true,
                    ],
                    [
                        'question' => 'Do you ship internationally?',
                        'answer' => '<p>Yes, we ship to most countries worldwide. International shipping rates and delivery times vary by location. Some restrictions may apply to certain products.</p>',
                        'sort_order' => 4,
                        'is_active' => true,
                        'is_featured' => false,
                    ],
                ];
                break;

            case 'returns':
                $faqItems = [
                    [
                        'question' => 'What\'s your return policy?',
                        'answer' => '<p>We accept returns within 30 days of delivery for books in their original condition. Digital products (audiobooks) are non-refundable once downloaded. Gift boxes can be returned if unopened and in original packaging.</p>',
                        'sort_order' => 1,
                        'is_active' => true,
                        'is_featured' => true,
                    ],
                    [
                        'question' => 'How do I return an item?',
                        'answer' => '<p>To return an item, log into your account and go to "My Orders." Select the order containing the item you want to return and follow the return process. You\'ll receive a return label to print and attach to your package.</p>',
                        'sort_order' => 2,
                        'is_active' => true,
                        'is_featured' => false,
                    ],
                    [
                        'question' => 'When will I receive my refund?',
                        'answer' => '<p>Once we receive your return, we\'ll process it within 3-5 business days. Refunds are typically issued to your original payment method within 5-10 business days, depending on your bank or credit card company.</p>',
                        'sort_order' => 3,
                        'is_active' => true,
                        'is_featured' => false,
                    ],
                ];
                break;

            case 'payment':
                $faqItems = [
                    [
                        'question' => 'What payment methods do you accept?',
                        'answer' => '<p>We accept all major credit cards (Visa, MasterCard, American Express, Discover), PayPal, and Apple Pay. All payments are processed securely through our payment partners.</p>',
                        'sort_order' => 1,
                        'is_active' => true,
                        'is_featured' => true,
                    ],
                    [
                        'question' => 'Is my payment information secure?',
                        'answer' => '<p>Yes, your payment information is secure. We use industry-standard SSL encryption and never store your credit card information on our servers. All payments are processed through secure, PCI-compliant payment gateways.</p>',
                        'sort_order' => 2,
                        'is_active' => true,
                        'is_featured' => false,
                    ],
                    [
                        'question' => 'Do you offer payment plans?',
                        'answer' => '<p>Currently, we don\'t offer payment plans, but we do accept PayPal which may offer its own payment options. We also frequently run promotions and discounts that can help make your purchase more affordable.</p>',
                        'sort_order' => 3,
                        'is_active' => true,
                        'is_featured' => false,
                    ],
                ];
                break;

            case 'products':
                $faqItems = [
                    [
                        'question' => 'What types of books do you carry?',
                        'answer' => '<p>We carry a wide variety of books across all genres including fiction, non-fiction, children\'s books, academic texts, and more. Our collection is carefully curated to ensure quality and relevance for our readers.</p>',
                        'sort_order' => 1,
                        'is_active' => true,
                        'is_featured' => true,
                    ],
                    [
                        'question' => 'How do your gift boxes work?',
                        'answer' => '<p>Our gift boxes are thoughtfully curated collections that include a book, related accessories, and sometimes additional items like bookmarks, candles, or tea. They\'re perfect for any book lover and make excellent gifts.</p>',
                        'sort_order' => 2,
                        'is_active' => true,
                        'is_featured' => false,
                    ],
                    [
                        'question' => 'Can I get book recommendations?',
                        'answer' => '<p>Absolutely! Our team loves helping readers find their next favorite book. You can contact us through our contact form, call us, or visit our blog for regular book recommendations and reviews.</p>',
                        'sort_order' => 3,
                        'is_active' => true,
                        'is_featured' => true,
                    ],
                ];
                break;

            case 'account':
                $faqItems = [
                    [
                        'question' => 'How do I create an account?',
                        'answer' => '<p>Creating an account is easy! Click the "Register" link in the top navigation, fill out the form with your information, and you\'ll be ready to start shopping. You can also create an account during checkout.</p>',
                        'sort_order' => 1,
                        'is_active' => true,
                        'is_featured' => true,
                    ],
                    [
                        'question' => 'How do I reset my password?',
                        'answer' => '<p>If you\'ve forgotten your password, click the "Forgot Password" link on the login page. Enter your email address, and we\'ll send you a link to reset your password.</p>',
                        'sort_order' => 2,
                        'is_active' => true,
                        'is_featured' => false,
                    ],
                    [
                        'question' => 'Can I save my payment information?',
                        'answer' => '<p>For security reasons, we don\'t store your credit card information on our servers. However, you can save your shipping addresses and other account information for faster checkout.</p>',
                        'sort_order' => 3,
                        'is_active' => true,
                        'is_featured' => false,
                    ],
                ];
                break;
        }

        foreach ($faqItems as $itemData) {
            $itemData['faq_category_id'] = $category->id;
            FaqItem::create($itemData);
        }
    }
}
