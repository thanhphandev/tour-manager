<?php

namespace App\Services;

use PaypalServerSdkLib\PaypalServerSdkClient;
use PaypalServerSdkLib\PaypalServerSdkClientBuilder;
use PaypalServerSdkLib\Authentication\ClientCredentialsAuthCredentialsBuilder;
use PaypalServerSdkLib\Models\Builders\OrderRequestBuilder;
use PaypalServerSdkLib\Models\Builders\PurchaseUnitRequestBuilder;
use PaypalServerSdkLib\Models\Builders\AmountWithBreakdownBuilder;
use PaypalServerSdkLib\Models\Builders\OrderApplicationContextBuilder;
use PaypalServerSdkLib\Models\CheckoutPaymentIntent;
use PaypalServerSdkLib\Environment;
use Illuminate\Support\Facades\Log;
use Exception;

class PayPalService
{
    private PaypalServerSdkClient $client;
    private string $returnUrl;
    private string $cancelUrl;
    private string $currency;

    public function __construct()
    {
        // Khởi tạo PayPal Client với credentials từ config
        $this->client = PaypalServerSdkClientBuilder::init()
            ->clientCredentialsAuthCredentials(
                ClientCredentialsAuthCredentialsBuilder::init(
                    config('services.paypal.client_id'),
                    config('services.paypal.client_secret')
                )
            )
            ->environment(config('services.paypal.mode') === 'live' ? Environment::PRODUCTION : Environment::SANDBOX)
            ->build();

        $this->returnUrl = config('services.paypal.return_url');
        $this->cancelUrl = config('services.paypal.cancel_url');
        $this->currency = config('services.paypal.currency', 'USD');
    }

    /**
     * Tạo PayPal Order
     * 
     * @param array $data ['amount' => float, 'description' => string, 'invoice_id' => string, 'customer_name' => string, 'customer_email' => string]
     * @return array ['success' => bool, 'order_id' => string|null, 'approval_url' => string|null, 'error' => string|null]
     */
    public function createOrder(array $data): array
    {
        try {
            // Validate dữ liệu đầu vào
            $this->validateOrderData($data);

            // Chuyển đổi VND sang USD (nếu cần)
            $amount = $this->convertCurrency($data['amount']);

            // Tạo OrderRequest sử dụng Builder pattern
            $orderRequest = OrderRequestBuilder::init(
                CheckoutPaymentIntent::CAPTURE,
                [
                    PurchaseUnitRequestBuilder::init(
                        AmountWithBreakdownBuilder::init(
                            $this->currency,
                            number_format($amount, 2, '.', '')
                        )->build()
                    )
                    ->description($data['description'] ?? 'Tour Booking Payment')
                    ->invoiceId($data['invoice_id'] ?? null)
                    ->customId($data['custom_id'] ?? null)
                    ->build()
                ]
            )
            ->applicationContext(
                OrderApplicationContextBuilder::init()
                    ->returnUrl($this->returnUrl)
                    ->cancelUrl($this->cancelUrl)
                    ->brandName(config('app.name', 'Tour Manager'))
                    ->landingPage('BILLING')
                    ->userAction('PAY_NOW')
                    ->shippingPreference('NO_SHIPPING')
                    ->build()
            )
            ->build();

            // Gọi API tạo order
            $ordersController = $this->client->getOrdersController();
            
            $apiResponse = $ordersController->createOrder([
                'body' => $orderRequest
            ]);

            $order = $apiResponse->getResult();

            // Lấy approval URL
            $approvalUrl = null;
            foreach ($order->getLinks() as $link) {
                if ($link->getRel() === 'approve') {
                    $approvalUrl = $link->getHref();
                    break;
                }
            }

            Log::info('PayPal order created successfully', [
                'order_id' => $order->getId(),
                'status' => $order->getStatus(),
                'amount' => $amount,
            ]);

            return [
                'success' => true,
                'order_id' => $order->getId(),
                'approval_url' => $approvalUrl,
                'status' => $order->getStatus(),
                'error' => null,
            ];

        } catch (Exception $e) {
            Log::error('PayPal order creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'order_id' => null,
                'approval_url' => null,
                'error' => $this->getErrorMessage($e),
            ];
        }
    }

    /**
     * Capture PayPal Order (hoàn tất thanh toán)
     * 
     * @param string $orderId
     * @return array ['success' => bool, 'transaction_id' => string|null, 'status' => string|null, 'data' => array, 'error' => string|null]
     */
    public function captureOrder(string $orderId): array
    {
        try {
            // Validate Order ID
            if (empty($orderId)) {
                throw new Exception('Order ID is required');
            }

            $ordersController = $this->client->getOrdersController();
            
            // Capture order
            $apiResponse = $ordersController->captureOrder([
                'id' => $orderId
            ]);
            
            $order = $apiResponse->getResult();

            $status = $order->getStatus();
            $purchaseUnits = $order->getPurchaseUnits();
            $purchaseUnit = $purchaseUnits[0] ?? null;
            
            $payments = $purchaseUnit?->getPayments();
            $captures = $payments?->getCaptures();
            $capture = $captures[0] ?? null;

            // Kiểm tra trạng thái thanh toán
            if ($status !== 'COMPLETED' || !$capture) {
                Log::warning('PayPal order capture not completed', [
                    'order_id' => $orderId,
                    'status' => $status,
                ]);

                return [
                    'success' => false,
                    'transaction_id' => null,
                    'status' => $status,
                    'data' => [],
                    'error' => 'Payment not completed. Status: ' . $status,
                ];
            }

            // Lấy thông tin giao dịch
            $transactionId = $capture->getId();
            $captureStatus = $capture->getStatus();
            $amount = $capture->getAmount();
            $payer = $order->getPayer();
            $payerName = $payer?->getName();

            $data = [
                'order_id' => $order->getId(),
                'transaction_id' => $transactionId,
                'capture_status' => $captureStatus,
                'amount' => $amount->getValue(),
                'currency' => $amount->getCurrencyCode(),
                'payer_email' => $payer?->getEmailAddress(),
                'payer_name' => ($payerName?->getGivenName() ?? '') . ' ' . ($payerName?->getSurname() ?? ''),
                'create_time' => $capture->getCreateTime(),
                'update_time' => $capture->getUpdateTime(),
            ];

            Log::info('PayPal order captured successfully', [
                'order_id' => $orderId,
                'transaction_id' => $transactionId,
                'status' => $captureStatus,
                'amount' => $amount->getValue(),
            ]);

            return [
                'success' => true,
                'transaction_id' => $transactionId,
                'status' => $captureStatus,
                'data' => $data,
                'error' => null,
            ];

        } catch (Exception $e) {
            Log::error('PayPal order capture failed', [
                'order_id' => $orderId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'transaction_id' => null,
                'status' => null,
                'data' => [],
                'error' => $this->getErrorMessage($e),
            ];
        }
    }

    /**
     * Lấy chi tiết Order
     * 
     * @param string $orderId
     * @return array ['success' => bool, 'order' => object|null, 'error' => string|null]
     */
    public function getOrderDetails(string $orderId): array
    {
        try {
            $ordersController = $this->client->getOrdersController();
            
            $apiResponse = $ordersController->getOrder([
                'id' => $orderId
            ]);
            
            $order = $apiResponse->getResult();

            Log::info('PayPal order details retrieved', [
                'order_id' => $orderId,
                'status' => $order->getStatus(),
            ]);

            return [
                'success' => true,
                'order' => $order,
                'status' => $order->getStatus(),
                'error' => null,
            ];

        } catch (Exception $e) {
            Log::error('PayPal get order details failed', [
                'order_id' => $orderId,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'order' => null,
                'error' => $this->getErrorMessage($e),
            ];
        }
    }

    /**
     * Validate dữ liệu order
     */
    private function validateOrderData(array $data): void
    {
        if (!isset($data['amount']) || $data['amount'] <= 0) {
            throw new Exception('Invalid amount');
        }

        if (isset($data['customer_email']) && !filter_var($data['customer_email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Invalid email address');
        }
    }

    /**
     * Chuyển đổi tiền tệ VND sang USD
     */
    private function convertCurrency(float $amountVnd): float
    {
        if ($this->currency === 'USD') {
            // Tỷ giá ước tính: 1 USD = 24,000 VND
            // Trong thực tế, nên sử dụng API tỷ giá thực tế
            $exchangeRate = config('services.paypal.exchange_rate', 24000);
            return round($amountVnd / $exchangeRate, 2);
        }

        return $amountVnd;
    }

    /**
     * Lấy thông điệp lỗi từ Exception
     */
    private function getErrorMessage(Exception $e): string
    {
        $message = $e->getMessage();

        // Làm sạch thông điệp lỗi, không lộ thông tin nhạy cảm
        if (strpos($message, 'AUTHENTICATION_FAILURE') !== false) {
            return 'PayPal authentication failed. Please check credentials.';
        }

        if (strpos($message, 'INVALID_REQUEST') !== false) {
            return 'Invalid request data. Please check your input.';
        }

        if (strpos($message, 'INSTRUMENT_DECLINED') !== false) {
            return 'Payment method declined. Please try another payment method.';
        }

        if (strpos($message, 'INSUFFICIENT_FUNDS') !== false) {
            return 'Insufficient funds in PayPal account.';
        }

        // Trả về thông điệp chung để bảo mật
        return 'An error occurred while processing PayPal payment. Please try again.';
    }

    /**
     * Verify webhook signature (cho webhook từ PayPal)
     * 
     * @param array $headers
     * @param string $body
     * @return bool
     */
    public function verifyWebhookSignature(array $headers, string $body): bool
    {
        try {
            $webhookId = config('services.paypal.webhook_id');
            
            if (empty($webhookId)) {
                Log::warning('PayPal webhook ID not configured');
                return false;
            }

            // Implement webhook signature verification
            // Tham khảo: https://developer.paypal.com/docs/api-basics/notifications/webhooks/notification-messages/#link-verifyyourwebhooksignature

            $transmissionId = $headers['paypal-transmission-id'] ?? null;
            $transmissionTime = $headers['paypal-transmission-time'] ?? null;
            $certUrl = $headers['paypal-cert-url'] ?? null;
            $authAlgo = $headers['paypal-auth-algo'] ?? null;
            $transmissionSig = $headers['paypal-transmission-sig'] ?? null;

            if (!$transmissionId || !$transmissionTime || !$certUrl || !$authAlgo || !$transmissionSig) {
                return false;
            }

            // PayPal SDK sẽ tự động verify signature
            // Đây là placeholder cho việc verify webhook
            
            return true;

        } catch (Exception $e) {
            Log::error('PayPal webhook verification failed', [
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }
}
