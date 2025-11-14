<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class VNPayService
{
    private string $tmnCode;
    private string $hashSecret;
    private string $url;
    private string $returnUrl;
    private string $apiUrl;

    public function __construct()
    {
        $this->tmnCode = config('services.vnpay.tmn_code');
        $this->hashSecret = config('services.vnpay.hash_secret');
        $this->url = config('services.vnpay.url');
        $this->returnUrl = config('services.vnpay.return_url');
        $this->apiUrl = config('services.vnpay.api_url');
    }

    /**
     * Create payment URL for VNPay
     */
    public function createPaymentUrl(array $data): string
    {
        // Remove special characters from order info (VNPay requirement: Vietnamese without accents and no special chars)
        $orderInfo = str_replace('-', ' ', \Illuminate\Support\Str::slug($data['order_info'], ' '));
        
        $vnpData = [
            'vnp_Version' => '2.1.0',
            'vnp_Command' => 'pay',
            'vnp_TmnCode' => $this->tmnCode,
            'vnp_Amount' => $data['amount'] * 100, // VNPay uses smallest currency unit
            'vnp_CurrCode' => 'VND',
            'vnp_TxnRef' => $data['txn_ref'],
            'vnp_OrderInfo' => $orderInfo,
            'vnp_OrderType' => $data['order_type'] ?? 'other',
            'vnp_Locale' => $data['locale'] ?? 'vn',
            'vnp_ReturnUrl' => $this->returnUrl,
            'vnp_IpAddr' => $data['ip_addr'],
            'vnp_CreateDate' => date('YmdHis'),
        ];

        // Add optional fields if provided (ONLY if not empty)
        if (!empty($data['bank_code'])) {
            $vnpData['vnp_BankCode'] = $data['bank_code'];
        }

        if (!empty($data['bill_email'])) {
            $vnpData['vnp_Bill_Email'] = $data['bill_email'];
        }

        if (!empty($data['bill_mobile'])) {
            $vnpData['vnp_Bill_Mobile'] = $data['bill_mobile'];
        }

        if (!empty($data['bill_firstname'])) {
            $vnpData['vnp_Bill_FirstName'] = $data['bill_firstname'];
        }

        if (!empty($data['bill_lastname'])) {
            $vnpData['vnp_Bill_LastName'] = $data['bill_lastname'];
        }

        // Sort data by key
        ksort($vnpData);

        // Build hash data and query string
        $hashdata = "";
        $query = "";
        $i = 0;
        
        foreach ($vnpData as $key => $value) {
            // Skip empty values
            if (strlen($value) > 0) {
                if ($i == 1) {
                    $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
                } else {
                    $hashdata .= urlencode($key) . "=" . urlencode($value);
                    $i = 1;
                }
                $query .= urlencode($key) . "=" . urlencode($value) . '&';
            }
        }

        // Generate secure hash
        $vnpSecureHash = hash_hmac('sha512', $hashdata, $this->hashSecret);
        $vnpUrl = $this->url . "?" . $query . 'vnp_SecureHash=' . $vnpSecureHash;

        // Debug logging
        Log::info('VNPay Payment URL Creation', [
            'tmn_code' => $this->tmnCode,
            'hash_secret' => substr($this->hashSecret, 0, 10) . '...',
            'hash_data' => $hashdata,
            'secure_hash' => $vnpSecureHash,
            'full_url' => $vnpUrl,
        ]);

        return $vnpUrl;
    }

    /**
     * Validate VNPay callback response
     */
    public function validateCallback(array $inputData): array
    {
        $result = [
            'success' => false,
            'message' => '',
            'data' => [],
        ];

        // Get secure hash
        $vnpSecureHash = $inputData['vnp_SecureHash'] ?? '';
        unset($inputData['vnp_SecureHash']);
        unset($inputData['vnp_SecureHashType']);

        // Sort data
        ksort($inputData);

        // Build hash data
        $i = 0;
        $hashData = '';
        foreach ($inputData as $key => $value) {
            // Skip empty values
            if (strlen($value) > 0) {
                if ($i == 1) {
                    $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
                } else {
                    $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
                    $i = 1;
                }
            }
        }

        // Calculate secure hash
        $secureHash = hash_hmac('sha512', $hashData, $this->hashSecret);
        
        // Debug log
        Log::info('VNPay Callback Validation', [
            'hash_data' => $hashData,
            'calculated_hash' => $secureHash,
            'received_hash' => $vnpSecureHash,
            'match' => $secureHash === $vnpSecureHash,
        ]);

        // Validate hash
        if ($secureHash !== $vnpSecureHash) {
            $result['message'] = 'Invalid signature';
            Log::error('VNPay: Invalid signature', [
                'expected' => $secureHash,
                'received' => $vnpSecureHash,
            ]);
            return $result;
        }

        // Check response code
        $responseCode = $inputData['vnp_ResponseCode'] ?? '';
        $transactionStatus = $inputData['vnp_TransactionStatus'] ?? '';

        if ($responseCode === '00' && $transactionStatus === '00') {
            $result['success'] = true;
            $result['message'] = 'Payment successful';
            $result['data'] = [
                'transaction_id' => $inputData['vnp_TransactionNo'] ?? '',
                'txn_ref' => $inputData['vnp_TxnRef'] ?? '',
                'amount' => isset($inputData['vnp_Amount']) ? ($inputData['vnp_Amount'] / 100) : 0,
                'bank_code' => $inputData['vnp_BankCode'] ?? '',
                'bank_tran_no' => $inputData['vnp_BankTranNo'] ?? '',
                'card_type' => $inputData['vnp_CardType'] ?? '',
                'pay_date' => $inputData['vnp_PayDate'] ?? '',
                'order_info' => $inputData['vnp_OrderInfo'] ?? '',
            ];
        } else {
            $result['message'] = $this->getResponseMessage($responseCode);
            $result['data'] = [
                'response_code' => $responseCode,
                'transaction_status' => $transactionStatus,
            ];
            
            Log::warning('VNPay: Payment failed', [
                'response_code' => $responseCode,
                'transaction_status' => $transactionStatus,
                'message' => $result['message'],
            ]);
        }

        return $result;
    }

    /**
     * Get response message from VNPay response code
     */
    private function getResponseMessage(string $code): string
    {
        $messages = [
            '00' => 'Giao dịch thành công',
            '07' => 'Trừ tiền thành công. Giao dịch bị nghi ngờ (liên quan tới lừa đảo, giao dịch bất thường).',
            '09' => 'Giao dịch không thành công do: Thẻ/Tài khoản của khách hàng chưa đăng ký dịch vụ InternetBanking tại ngân hàng.',
            '10' => 'Giao dịch không thành công do: Khách hàng xác thực thông tin thẻ/tài khoản không đúng quá 3 lần',
            '11' => 'Giao dịch không thành công do: Đã hết hạn chờ thanh toán. Xin quý khách vui lòng thực hiện lại giao dịch.',
            '12' => 'Giao dịch không thành công do: Thẻ/Tài khoản của khách hàng bị khóa.',
            '13' => 'Giao dịch không thành công do Quý khách nhập sai mật khẩu xác thực giao dịch (OTP). Xin quý khách vui lòng thực hiện lại giao dịch.',
            '24' => 'Giao dịch không thành công do: Khách hàng hủy giao dịch',
            '51' => 'Giao dịch không thành công do: Tài khoản của quý khách không đủ số dư để thực hiện giao dịch.',
            '65' => 'Giao dịch không thành công do: Tài khoản của Quý khách đã vượt quá hạn mức giao dịch trong ngày.',
            '75' => 'Ngân hàng thanh toán đang bảo trì.',
            '79' => 'Giao dịch không thành công do: KH nhập sai mật khẩu thanh toán quá số lần quy định. Xin quý khách vui lòng thực hiện lại giao dịch',
            '99' => 'Các lỗi khác (lỗi còn lại, không có trong danh sách mã lỗi đã liệt kê)',
        ];

        return $messages[$code] ?? 'Lỗi không xác định';
    }

    /**
     * Query transaction status from VNPay
     */
    public function queryTransaction(string $txnRef, string $transDate): array
    {
        $vnpData = [
            'vnp_Version' => '2.1.0',
            'vnp_Command' => 'querydr',
            'vnp_TmnCode' => $this->tmnCode,
            'vnp_TxnRef' => $txnRef,
            'vnp_OrderInfo' => 'Truy van giao dich: ' . $txnRef,
            'vnp_TransactionDate' => $transDate,
            'vnp_CreateDate' => date('YmdHis'),
            'vnp_IpAddr' => request()->ip(),
        ];

        ksort($vnpData);

        $hashData = '';
        $i = 0;
        foreach ($vnpData as $key => $value) {
            if ($i == 1) {
                $hashData .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $vnpSecureHash = hash_hmac('sha512', $hashData, $this->hashSecret);
        $vnpData['vnp_SecureHash'] = $vnpSecureHash;

        // Make API request
        try {
            $response = \Illuminate\Support\Facades\Http::post($this->apiUrl, $vnpData);
            return $response->json();
        } catch (\Exception $e) {
            Log::error('VNPay query transaction failed: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to query transaction',
            ];
        }
    }

    /**
     * Refund transaction
     */
    public function refundTransaction(array $data): array
    {
        $vnpData = [
            'vnp_Version' => '2.1.0',
            'vnp_Command' => 'refund',
            'vnp_TmnCode' => $this->tmnCode,
            'vnp_TransactionType' => $data['transaction_type'] ?? '02', // 02: Hoàn trả toàn phần, 03: Hoàn trả một phần
            'vnp_TxnRef' => $data['txn_ref'],
            'vnp_Amount' => $data['amount'] * 100,
            'vnp_OrderInfo' => $data['order_info'],
            'vnp_TransactionNo' => $data['transaction_no'] ?? '',
            'vnp_TransactionDate' => $data['transaction_date'],
            'vnp_CreateBy' => $data['created_by'],
            'vnp_CreateDate' => date('YmdHis'),
            'vnp_IpAddr' => $data['ip_addr'],
        ];

        ksort($vnpData);

        $hashData = '';
        $i = 0;
        foreach ($vnpData as $key => $value) {
            if ($i == 1) {
                $hashData .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $vnpSecureHash = hash_hmac('sha512', $hashData, $this->hashSecret);
        $vnpData['vnp_SecureHash'] = $vnpSecureHash;

        // Make API request
        try {
            $response = \Illuminate\Support\Facades\Http::post($this->apiUrl, $vnpData);
            return $response->json();
        } catch (\Exception $e) {
            Log::error('VNPay refund transaction failed: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to refund transaction',
            ];
        }
    }
}
