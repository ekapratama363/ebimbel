<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Services\DuitkuService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class DuitkuController extends Controller
{
    public function callback(Request $request, DuitkuService $duitku): Response
    {
        $payload = $request->all();

        if (! $duitku->validateCallback($payload)) {
            return response('Invalid signature', 403);
        }

        $payment = Payment::query()
            ->where('merchant_order_id', $payload['merchantOrderId'] ?? null)
            ->first();

        if (! $payment) {
            return response('Payment not found', 404);
        }

        $resultCode = $payload['resultCode'] ?? '';
        $previousStatus = $payment->status;

        if ($resultCode === '00') {
            $payment->update([
                'status' => 'lunas',
                'paid_at' => now(),
                'payment_channel' => 'duitku',
                'payment_method_code' => $payload['paymentCode'] ?? $payment->payment_method_code,
                'duitku_reference' => $payload['reference'] ?? $payment->duitku_reference,
                'duitku_result_code' => $resultCode,
            ]);

            $payment->logHistory(
                'duitku_callback',
                'Pembayaran berhasil dikonfirmasi Duitku.',
                $previousStatus,
                'lunas',
                $payload,
            );
        } else {
            $payment->update([
                'status' => 'gagal',
                'duitku_result_code' => $resultCode,
                'payment_method_code' => $payload['paymentCode'] ?? $payment->payment_method_code,
            ]);

            $payment->logHistory(
                'duitku_callback',
                'Pembayaran gagal atau dibatalkan di Duitku.',
                $previousStatus,
                'gagal',
                $payload,
            );
        }

        return response('OK', 200);
    }

    public function return(Request $request): View
    {
        $merchantOrderId = $request->query('merchantOrderId');
        $payment = $merchantOrderId
            ? Payment::with(['student', 'paymentType'])->where('merchant_order_id', $merchantOrderId)->first()
            : null;

        return view('pages.duitku-return', [
            'payment' => $payment,
            'resultCode' => $request->query('resultCode'),
        ]);
    }

    public function pay(Payment $payment, DuitkuService $duitku): RedirectResponse
    {
        if ($payment->status === 'lunas') {
            return back()->with('status', 'Pembayaran ini sudah lunas.');
        }

        $guardian = $payment->student->guardians()->first();
        $customer = [
            'name' => $payment->student->name,
            'first_name' => $payment->student->name,
            'last_name' => '',
            'email' => $guardian?->email,
            'phone' => $guardian?->phone,
        ];

        try {
            $result = $duitku->inquiry($payment, $customer);
        } catch (\Throwable $e) {
            return back()->withErrors(['duitku' => $e->getMessage()]);
        }

        $previousStatus = $payment->status;

        $payment->update([
            'status' => 'pending',
            'payment_channel' => 'duitku',
            'merchant_order_id' => $result['merchant_order_id'],
            'duitku_payment_url' => $result['payment_url'],
            'duitku_reference' => $result['reference'],
        ]);

        $payment->logHistory(
            'duitku_inquiry',
            'Link pembayaran Duitku dibuat.',
            $previousStatus,
            'pending',
            $result,
        );

        return redirect()->away($result['payment_url']);
    }

    public function createLink(Payment $payment, DuitkuService $duitku): RedirectResponse
    {
        if ($payment->status === 'lunas') {
            return back()->with('status', 'Pembayaran ini sudah lunas.');
        }

        $guardian = $payment->student->guardians()->first();
        $customer = [
            'name' => $payment->student->name,
            'first_name' => $payment->student->name,
            'last_name' => '',
            'email' => $guardian?->email,
            'phone' => $guardian?->phone,
        ];

        try {
            $result = $duitku->inquiry($payment, $customer);
        } catch (\Throwable $e) {
            return back()->withErrors(['duitku' => $e->getMessage()]);
        }

        $previousStatus = $payment->status;

        $payment->update([
            'status' => 'pending',
            'payment_channel' => 'duitku',
            'merchant_order_id' => $result['merchant_order_id'],
            'duitku_payment_url' => $result['payment_url'],
            'duitku_reference' => $result['reference'],
        ]);

        $payment->logHistory(
            'duitku_inquiry',
            'Link pembayaran dibuat untuk disalin / dikirim ke wali.',
            $previousStatus,
            'pending',
            $result,
        );

        return back()->with('status', 'Link pembayaran berhasil dibuat.');
    }
}
