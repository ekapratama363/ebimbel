<?php

namespace App\Services;

use App\Models\Payment;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class DuitkuService
{
    public function isConfigured(): bool
    {
        return filled(config('duitku.merchant_code')) && filled(config('duitku.api_key'));
    }

    public function inquiry(Payment $payment, array $customer): array
    {
        if (! $this->isConfigured()) {
            throw new RuntimeException('Duitku belum dikonfigurasi. Isi DUITKU_MERCHANT_CODE dan DUITKU_API_KEY di .env');
        }

        $merchantCode = config('duitku.merchant_code');
        $apiKey = config('duitku.api_key');
        $amount = (int) round((float) $payment->amount);
        $merchantOrderId = $payment->generateMerchantOrderId();
        $stringToSign = $merchantCode.$merchantOrderId.$amount;
        $signature = hash_hmac('sha256', $stringToSign, $apiKey);

        $productDetails = trim(($payment->paymentType?->name ?? 'Pembayaran').' — '.$payment->student->name);
        if ($payment->period_label) {
            $productDetails .= ' ('.$payment->period_label.')';
        }

        $payload = [
            'merchantCode' => $merchantCode,
            'paymentAmount' => $amount,
            'paymentMethod' => config('duitku.default_payment_method'),
            'merchantOrderId' => $merchantOrderId,
            'productDetails' => $productDetails,
            'additionalParam' => (string) $payment->id,
            'merchantUserInfo' => $payment->student->nis,
            'customerVaName' => $customer['name'] ?? $payment->student->name,
            'email' => $customer['email'] ?? 'noreply@'.$this->hostFromUrl(config('app.url')),
            'phoneNumber' => $customer['phone'] ?? '08123456789',
            'itemDetails' => [[
                'name' => $payment->paymentType?->name ?? 'Pembayaran siswa',
                'price' => $amount,
                'quantity' => 1,
            ]],
            'customerDetail' => [
                'firstName' => $customer['first_name'] ?? $payment->student->name,
                'lastName' => $customer['last_name'] ?? '',
                'email' => $customer['email'] ?? 'noreply@'.$this->hostFromUrl(config('app.url')),
                'phoneNumber' => $customer['phone'] ?? '08123456789',
            ],
            'callbackUrl' => $this->callbackUrl(),
            'returnUrl' => $this->returnUrl(),
            'signature' => $signature,
            'expiryPeriod' => config('duitku.expiry_period'),
        ];

        $response = Http::acceptJson()
            ->asJson()
            ->timeout(30)
            ->post($this->baseUrl().'/merchant/v2/inquiry', $payload);

        if (! $response->successful()) {
            throw new RuntimeException('Duitku inquiry gagal: '.$response->body());
        }

        $data = $response->json();

        if (empty($data['paymentUrl'])) {
            throw new RuntimeException('Duitku tidak mengembalikan paymentUrl: '.json_encode($data));
        }

        return [
            'merchant_order_id' => $merchantOrderId,
            'payment_url' => $data['paymentUrl'],
            'reference' => $data['reference'] ?? null,
            'va_number' => $data['vaNumber'] ?? null,
            'amount' => $data['amount'] ?? $amount,
            'status_code' => $data['statusCode'] ?? null,
            'status_message' => $data['statusMessage'] ?? null,
            'raw' => $data,
        ];
    }

    public function validateCallback(array $payload): bool
    {
        if (! $this->isConfigured()) {
            return false;
        }

        $merchantCode = $payload['merchantCode'] ?? '';
        $amount = $payload['amount'] ?? '';
        $merchantOrderId = $payload['merchantOrderId'] ?? '';
        $signature = $payload['signature'] ?? '';

        if ($merchantCode === '' || $amount === '' || $merchantOrderId === '' || $signature === '') {
            return false;
        }

        $stringToSign = $merchantCode.$amount.$merchantOrderId;
        $calc = hash_hmac('sha256', $stringToSign, config('duitku.api_key'));

        return hash_equals($calc, $signature);
    }

    public function callbackUrl(): string
    {
        return config('duitku.callback_url') ?: url('/callback');
    }

    public function returnUrl(): string
    {
        return config('duitku.return_url') ?: url('/return');
    }

    private function baseUrl(): string
    {
        return config('duitku.sandbox')
            ? 'https://sandbox.duitku.com/webapi/api'
            : 'https://passport.duitku.com/webapi/api';
    }

    private function hostFromUrl(string $url): string
    {
        $host = parse_url($url, PHP_URL_HOST);

        return $host ?: 'localhost';
    }
}
