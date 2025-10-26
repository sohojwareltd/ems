<?php

namespace App\Filament\Pages\Settings;

use Closure;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Outerweb\FilamentSettings\Filament\Pages\Settings as BaseSettings;

class StoreSettings extends BaseSettings

{
    protected static ?string $navigationLabel = 'Store Settings';
    protected static ?string $title = 'Store Settings';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationGroup = 'Settings';
    protected static ?string $navigationIcon = '';

    public function schema(): array|Closure
    {
        return [
            Tabs::make('Settings')
                ->schema([
                    Tabs\Tab::make('General')
                        ->schema([
                            Section::make('Store Information')
                                ->description('Basic information about your store.')
                                ->schema([
                                    TextInput::make('store.name')
                                        ->label('Store Name')
                                        ->required()
                                        ->helperText('The name of your ecommerce store.'),
                                    TextInput::make('store.email')
                                        ->label('Contact Email')
                                        ->email()
                                        ->helperText('Customer support or contact email.'),
                                    TextInput::make('store.phone')
                                        ->label('Contact Phone')
                                        ->helperText('Customer support phone number.'),
                                    TextInput::make('store.tax_id')
                                        ->label('Tax ID')
                                        ->helperText('Tax ID for your store.'),
                                    Textarea::make('store.address')
                                        ->label('Store Address')
                                        ->helperText('Physicaimage.pngl address of your store (for invoices, shipping, etc).'),
                                    \Filament\Forms\Components\FileUpload::make('store.logo')
                                        ->label('Shop Logo')
                                        ->image()
                                        ->directory('settings/logo')
                                        ->helperText('Upload your shop logo (shown in header, emails, etc).'),
                                    \Filament\Forms\Components\FileUpload::make('store.footer_logo')
                                        ->label('Footer Logo')
                                        ->image()
                                        ->directory('settings/footer_logo')
                                        ->helperText('Upload your footer logo (shown in footer).'),
                                    \Filament\Forms\Components\FileUpload::make('store.favicon')
                                        ->label('Favicon')
                                        ->image()
                                        ->directory('settings/favicon')
                                        ->helperText('Upload your favicon (browser tab icon).'),
                                    TextInput::make('store.map_embed_url')
                                        ->label('Google Maps Embed URL')
                                        ->default('https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3024.2219901290355!2d-74.00369368400567!3d40.71312937933185!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c25a23e28c1191%3A0x49f75d3281df052a!2s150%20Park%20Row%2C%20New%20York%2C%20NY%2010007%2C%20USA!5e0!3m2!1sen!2sin!4v1640995200000!5m2!1sen!2sin')
                                        ->helperText('Paste your Google Maps embed URL here. Example: https://www.google.com/maps/embed?pb=...'),
                                    TextInput::make('store.facebook')
                                        ->label('Facebook Page URL')
                                        ->helperText('Link to your Facebook page.'),
                                    TextInput::make('store.instagram')
                                        ->label('Instagram Profile URL')
                                        ->helperText('Link to your Instagram profile.'),
                                    TextInput::make('store.twitter')
                                        ->label('Twitter Profile URL')
                                        ->helperText('Link to your Twitter/X profile.'),

                                    Textarea::make('store.footer_text')
                                        ->label('Footer Text')
                                        ->helperText('Footer text displayed at the bottom of each page.'),
                                    TextInput::make('store.learners_impacted')
                                        ->label('Learners Impacted')
                                        ->helperText('Number of learners impacted by your store.'),
                                    TextInput::make('store.teachers_empowered')
                                        ->label('Teachers Empowered')
                                        ->helperText('Number of teachers empowered by your store.'),
                                    TextInput::make('store.resources_created')
                                        ->label('Resources Created')
                                        ->helperText('Number of resources created by your store.'),
                                    // TextInput::make('store.currency')
                                    //     ->label('Store Currency')
                                    //     ->default('USD')
                                    //     ->helperText('The default currency for your store (e.g., USD, EUR, GBP).'),
                                ]),
                            Section::make('Store Hours')
                                ->description('Business hours for your store.')
                                ->schema([
                                    TextInput::make('store.hours_weekdays')
                                        ->label('Weekdays (Monday - Friday)')
                                        ->default('9:00 AM - 6:00 PM')
                                        ->helperText('Example: 9:00 AM - 6:00 PM'),
                                    TextInput::make('store.hours_saturday')
                                        ->label('Saturday')
                                        ->default('10:00 AM - 4:00 PM')
                                        ->helperText('Example: 10:00 AM - 4:00 PM'),
                                    TextInput::make('store.hours_sunday')
                                        ->label('Sunday')
                                        ->default('Closed')
                                        ->helperText('Example: Closed'),
                                    Textarea::make('store.hours_holiday')
                                        ->label('Holiday Hours')
                                        ->rows(2)
                                        ->default('We may have modified hours during holidays. Please call ahead or check our social media for updates.')
                                        ->helperText('Example: We may have modified hours during holidays. Please call ahead or check our social media for updates.'),
                                    Textarea::make('store.hours_special')
                                        ->label('Special Events')
                                        ->rows(2)
                                        ->default('We host book clubs, author readings, and other literary events. Check our blog for upcoming events.')
                                        ->helperText('Example: We host book clubs, author readings, and other literary events. Check our blog for upcoming events.'),
                                ]),
                            Section::make('Contact Page Call to Action')
                                ->description('Customize the call to action at the bottom of the contact page.')
                                ->schema([
                                    TextInput::make('contact.cta_heading')
                                        ->label('CTA Heading')
                                        ->default('Still Have Questions?')
                                        ->helperText('Example: Still Have Questions?'),
                                    TextInput::make('contact.cta_subheading')
                                        ->label('CTA Subheading')
                                        ->default('Our friendly team is here to help you find the perfect book or answer any questions you might have.')
                                        ->helperText('Example: Our friendly team is here to help you find the perfect book or answer any questions you might have.'),
                                    TextInput::make('contact.cta_phone_text')
                                        ->label('Phone Button Text')
                                        ->default('Call Us Now')
                                        ->helperText('Example: Call Us Now'),
                                    TextInput::make('contact.cta_email_text')
                                        ->label('Email Button Text')
                                        ->default('Email Us')
                                        ->helperText('Example: Email Us'),
                                    TextInput::make('contact.cta_phone')
                                        ->label('Phone Number')
                                        ->default('+1 (555) 123-4567')
                                        ->helperText('Example: +1 (555) 123-4567'),
                                    TextInput::make('contact.cta_email')
                                        ->label('Email Address')
                                        ->default('hello@eternareads.com')
                                        ->helperText('Example: hello@eternareads.com'),
                                ]),
                        ]),
                    Tabs\Tab::make('SEO')
                        ->schema([
                            Section::make('SEO Information')
                                ->description('Meta tags and SEO settings for your shop.')
                                ->schema([
                                    TextInput::make('seo.meta_title')
                                        ->label('Meta Title')
                                        ->maxLength(255)
                                        ->helperText('Title for search engines and browser tabs.'),
                                    Textarea::make('seo.meta_description')
                                        ->label('Meta Description')
                                        ->maxLength(255)
                                        ->helperText('Description for search engines.'),
                                    TextInput::make('seo.meta_keywords')
                                        ->label('Meta Keywords')
                                        ->maxLength(255)
                                        ->helperText('Comma-separated keywords for SEO.'),
                                    TextInput::make('seo.google_analytics_id')
                                        ->label('Google Analytics ID')
                                        ->helperText('Your Google Analytics Measurement ID (e.g., G-XXXXXXXXXX).'),
                                    TextInput::make('seo.facebook_pixel_id')
                                        ->label('Facebook Pixel ID')
                                        ->helperText('Your Facebook Pixel ID for tracking conversions.'),
                                ]),
                        ]),
                    Tabs\Tab::make('Payments')
                        ->visible(false)
                        ->schema([
                            Section::make('Payment Methods')
                                ->description('Enable or disable payment methods for your store.')
                                ->schema([
                                    Toggle::make('payments.enable_stripe')
                                        ->label('Enable Stripe')
                                        ->visible(false)
                                        ->helperText('Allow customers to pay using Stripe.'),
                                    Toggle::make('payments.stripe_sandbox')
                                        ->label('Enable Sandbox')
                                        ->helperText('Enable Stripe Sandbox Mode.')
                                        ->visible(false),
                                    TextInput::make('payments.stripe_key')
                                        ->label('Stripe Public Key')
                                        ->helperText('Your Stripe publishable key.')->visible(false),
                                    TextInput::make('payments.stripe_secret')
                                        ->label('Stripe Secret Key')

                                        ->helperText('Your Stripe secret key.')
                                        ->visible(false),
                                    Toggle::make('payments.enable_paypal')
                                        ->label('Enable PayPal')
                                        ->helperText('Allow customers to pay using PayPal.')->visible(false),
                                    Toggle::make('payments.paypal_sandbox')
                                        ->label('Enable Sandbox')
                                        ->helperText('Enable PayPal Sandbox Mode.')->visible(false),
                                    TextInput::make('payments.paypal_client_id')
                                        ->label('PayPal Client ID')
                                        ->helperText('Your PayPal client ID.')->visible(false),
                                    TextInput::make('payments.paypal_secret')
                                        ->label('PayPal Secret')

                                        ->helperText('Your PayPal secret.')->visible(false),
                                ]),
                        ]),


                ]),
        ];
    }
}
