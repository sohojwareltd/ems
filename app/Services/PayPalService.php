<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Exception;

class PayPalService
{
    protected $clientId;
    protected $clientSecret;
    protected $mode;
    protected $baseUrl;

    public function __construct($clientId = null, $clientSecret = null, $sandbox = true)
    {
        $this->clientId = $clientId ?: env('PAYPAL_CLIENT_ID');
        $this->clientSecret = $clientSecret ?: env('PAYPAL_CLIENT_SECRET');
        $this->mode = $sandbox ? 'sandbox' : 'live';
        $this->baseUrl = $this->mode === 'live'
            ? 'https://api.paypal.com'
            : 'https://api.sandbox.paypal.com';
    }

    /**
     * Get access token
     */
    protected function getAccessToken()
    {
        try {
            $ch = curl_init();
            
            curl_setopt($ch, CURLOPT_URL, $this->baseUrl . '/v1/oauth2/token');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Basic ' . base64_encode($this->clientId . ':' . $this->clientSecret),
                'Content-Type: application/x-www-form-urlencoded'
            ]);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            if ($httpCode !== 200) {
                Log::error('PayPal access token failed', [
                    'http_code' => $httpCode,
                    'response' => $response
                ]);
                return null;
            }
            
            $data = json_decode($response, true);
            return $data['access_token'] ?? null;
            
        } catch (Exception $e) {
            Log::error('PayPal access token error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Create PayPal payment
     */
    public function createPayment($orderData)
    {
        try {
            if (!$this->clientId || !$this->clientSecret) {
                return [
                    'success' => false,
                    'message' => 'PayPal is not configured.'
                ];
            }

            $accessToken = $this->getAccessToken();
            if (!$accessToken) {
                return [
                    'success' => false,
                    'message' => 'Failed to get PayPal access token.'
                ];
            }

            // Prepare payment data
            $paymentData = [
                'intent' => 'CAPTURE',
                'application_context' => [
                    'return_url' => $orderData['return_url'] ?? route('paypal.success'),
                    'cancel_url' => $orderData['cancel_url'] ?? route('paypal.cancel'),
                    'brand_name' => config('app.name'),
                    'shipping_preference' => 'NO_SHIPPING',
                    'user_action' => 'PAY_NOW'
                ],
                'purchase_units' => [
                    [
                        'reference_id' => 'order_' . $orderData['order_id'],
                        'description' => 'Order #' . $orderData['order_number'],
                        'amount' => [
                            'currency_code' => 'USD',
                            'value' => number_format($orderData['total'], 2, '.', '')
                        ]
                    ]
                ]
            ];
            
            Log::info('PayPal payment data:', $paymentData);

            // Create payment via PayPal API v2
            $ch = curl_init();
            
            curl_setopt($ch, CURLOPT_URL, $this->baseUrl . '/v2/checkout/orders');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($paymentData));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $accessToken,
                'Content-Type: application/json'
            ]);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            Log::info('PayPal API response:', [
                'http_code' => $httpCode,
                'response' => $response
            ]);
            
            if ($httpCode !== 201) {
                Log::error('PayPal payment creation failed', [
                    'http_code' => $httpCode,
                    'response' => $response,
                    'payment_data' => $paymentData
                ]);
                
                return [
                    'success' => false,
                    'message' => 'Failed to create PayPal payment. HTTP Code: ' . $httpCode
                ];
            }
            
            $data = json_decode($response, true);
            
            // Find approval URL in v2 API
            $approvalUrl = null;
            if (isset($data['links'])) {
                foreach ($data['links'] as $link) {
                    if ($link['rel'] === 'approve') {
                        $approvalUrl = $link['href'];
                        break;
                    }
                }
            }
            
            if (!$approvalUrl) {
                return [
                    'success' => false,
                    'message' => 'PayPal approval URL not found.'
                ];
            }
            
            return [
                'success' => true,
                'payment_id' => $data['id'],
                'approval_url' => $approvalUrl
            ];

        } catch (Exception $e) {
            Log::error('PayPal payment creation error: ' . $e->getMessage(), [
                'order_data' => $orderData
            ]);
            
            return [
                'success' => false,
                'message' => 'PayPal payment creation failed: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Execute PayPal payment
     */
    public function executePayment($paymentId, $payerId)
    {
        try {
            if (!$this->clientId || !$this->clientSecret) {
                return [
                    'success' => false,
                    'message' => 'PayPal is not configured.'
                ];
            }

            $accessToken = $this->getAccessToken();
            if (!$accessToken) {
                return [
                    'success' => false,
                    'message' => 'Failed to get PayPal access token.'
                ];
            }

            // Log the PayPal order ID being used for capture
            Log::info('PayPalService@executePayment - Capturing PayPal order', [
                'paypal_order_id' => $paymentId
            ]);

            // Execute payment (capture order) in v2 API
            $ch = curl_init();
            
            curl_setopt($ch, CURLOPT_URL, $this->baseUrl . '/v2/checkout/orders/' . $paymentId . '/capture');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $accessToken,
                'Content-Type: application/json'
            ]);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            Log::info('PayPal capture response:', [
                'http_code' => $httpCode,
                'response' => $response
            ]);
            
            if ($httpCode !== 201) {
                Log::error('PayPal payment capture failed', [
                    'http_code' => $httpCode,
                    'response' => $response,
                    'payment_id' => $paymentId
                ]);
                
                return [
                    'success' => false,
                    'message' => 'Failed to capture PayPal payment. HTTP Code: ' . $httpCode
                ];
            }
            
            $data = json_decode($response, true);
            
            if ($data['status'] === 'COMPLETED') {
                return [
                    'success' => true,
                    'payment_id' => $data['id'],
                    'payment_status' => 'paid'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Payment was not completed. Status: ' . $data['status']
                ];
            }

        } catch (Exception $e) {
            Log::error('PayPal payment execution error: ' . $e->getMessage(), [
                'payment_id' => $paymentId,
                'payer_id' => $payerId
            ]);
            
            return [
                'success' => false,
                'message' => 'PayPal payment execution failed: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Check if PayPal is configured
     */
    public function isConfigured()
    {
        return $this->clientId && $this->clientSecret;
    }

    /**
     * Get PayPal mode
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * Test PayPal connection
     */
    public function testConnection()
    {
        try {
            if (!$this->isConfigured()) {
                return [
                    'success' => false,
                    'message' => 'PayPal credentials not configured'
                ];
            }

            $accessToken = $this->getAccessToken();
            if (!$accessToken) {
                return [
                    'success' => false,
                    'message' => 'Failed to get PayPal access token'
                ];
            }

            return [
                'success' => true,
                'message' => 'PayPal connection test successful',
                'mode' => $this->mode,
                'access_token' => substr($accessToken, 0, 20) . '...'
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'PayPal connection test failed: ' . $e->getMessage()
            ];
        }
    }
} 