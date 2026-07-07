<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Payment;
use App\Models\PaymentType;
use App\Models\Student;
use App\Services\DuitkuService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class KeuanganController extends Controller
{
    public function index(Request $request): View
    {
        $typeFilter = $request->query('jenis');
        $statusFilter = $request->query('status');

        $paymentsQuery = Payment::with(['student.kelompok', 'paymentType'])
            ->orderByDesc('created_at');

        if ($typeFilter) {
            $paymentsQuery->where('payment_type_id', $typeFilter);
        }
        if ($statusFilter) {
            $paymentsQuery->where('status', $statusFilter);
        }

        $payments = $paymentsQuery->get();
        $expenses = Expense::orderByDesc('expense_date')->get();
        $paymentTypes = PaymentType::where('is_active', true)->orderBy('sort_order')->get();

        $totalIncome = $payments->where('status', 'lunas')->sum('amount');
        $totalExpense = $expenses->sum('amount');
        $netBalance = $totalIncome - $totalExpense;

        return view('pages.keuangan', [
            'payments' => $payments,
            'expenses' => $expenses,
            'paymentTypes' => $paymentTypes,
            'allPaymentTypes' => PaymentType::orderBy('sort_order')->get(),
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpense,
            'netBalance' => $netBalance,
            'transactions' => $this->buildTransactions($payments, $expenses),
            'students' => Student::with('kelompok')->orderBy('name')->get(),
            'typeFilter' => $typeFilter,
            'statusFilter' => $statusFilter,
            'duitkuConfigured' => app(DuitkuService::class)->isConfigured(),
        ]);
    }

    public function showPayment(Payment $payment): View
    {
        $payment->load(['student.kelompok', 'student.guardians', 'paymentType', 'recorder', 'histories.user']);

        return view('pages.pembayaran-detail', [
            'payment' => $payment,
            'duitkuConfigured' => app(DuitkuService::class)->isConfigured(),
        ]);
    }

    private function buildTransactions(Collection $payments, Collection $expenses): Collection
    {
        $items = collect();

        foreach ($payments->where('status', 'lunas') as $p) {
            $items->push([
                'date' => $p->paid_at,
                'description' => ($p->paymentType?->name ?? 'Pembayaran').' — '.$p->student->name,
                'type' => 'pemasukan',
                'amount' => $p->amount,
                'invoice' => $p->invoice_number,
            ]);
        }

        foreach ($expenses as $e) {
            $items->push([
                'date' => $e->expense_date,
                'description' => $e->description,
                'type' => 'pengeluaran',
                'amount' => $e->amount,
                'invoice' => null,
            ]);
        }

        return $items->sortByDesc('date')->values();
    }

    public function storePayment(Request $request): RedirectResponse
    {
        $data = $this->validatePayment($request);
        $data['invoice_number'] = Payment::generateInvoiceNumber();
        $data['recorded_by'] = auth()->id();
        $data['status'] = $this->resolveStatus($data);
        $data['payment_channel'] = 'manual';

        if ($data['status'] === 'lunas' && empty($data['paid_at'])) {
            $data['paid_at'] = now()->toDateString();
        }

        $payment = Payment::create($data);

        if ($request->hasFile('proof')) {
            $this->storeProof($payment, $request->file('proof'));
        }

        $payment->logHistory(
            'created',
            'Tagihan pembayaran dibuat.',
            null,
            $payment->status,
            ['invoice_number' => $payment->invoice_number],
        );

        return back()->with('status', 'Pembayaran berhasil dicatat.');
    }

    public function updatePayment(Request $request, Payment $payment): RedirectResponse
    {
        $previousStatus = $payment->status;
        $data = $this->validatePayment($request);
        $data['status'] = $this->resolveStatus($data);

        if ($data['status'] === 'lunas' && empty($data['paid_at'])) {
            $data['paid_at'] = now()->toDateString();
        }
        if ($data['status'] !== 'lunas') {
            $data['paid_at'] = $data['paid_at'] ?? null;
        }

        $payment->update($data);

        if ($request->hasFile('proof')) {
            $payment->deleteProofFile();
            $this->storeProof($payment, $request->file('proof'));
            $payment->logHistory('proof_uploaded', 'Bukti pembayaran diperbarui.', $previousStatus, $payment->status);
        }

        if ($previousStatus !== $payment->status) {
            $payment->logHistory(
                'status_changed',
                'Status pembayaran diubah menjadi '.$payment->statusLabel().'.',
                $previousStatus,
                $payment->status,
            );
        } else {
            $payment->logHistory('updated', 'Data pembayaran diperbarui.', $previousStatus, $payment->status);
        }

        return back()->with('status', 'Pembayaran berhasil diperbarui.');
    }

    public function uploadProof(Request $request, Payment $payment): RedirectResponse
    {
        $request->validate(['proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120']);
        $previousStatus = $payment->status;

        $payment->deleteProofFile();
        $this->storeProof($payment, $request->file('proof'));

        if ($payment->status === 'belum_bayar') {
            $payment->update(['status' => 'pending']);
        }

        $payment->logHistory(
            'proof_uploaded',
            'Bukti transfer diunggah oleh admin.',
            $previousStatus,
            $payment->status,
        );

        return back()->with('status', 'Bukti pembayaran berhasil diunggah.');
    }

    public function confirmPayment(Payment $payment): RedirectResponse
    {
        if ($payment->status === 'lunas') {
            return back()->with('status', 'Pembayaran sudah lunas.');
        }

        $previousStatus = $payment->status;
        $payment->update([
            'status' => 'lunas',
            'paid_at' => now(),
            'payment_channel' => $payment->payment_channel === 'duitku' ? 'duitku' : 'manual',
        ]);

        $payment->logHistory(
            'confirmed',
            'Pembayaran dikonfirmasi lunas oleh admin.',
            $previousStatus,
            'lunas',
        );

        return back()->with('status', 'Pembayaran dikonfirmasi lunas.');
    }

    public function destroyPayment(Payment $payment): RedirectResponse
    {
        $payment->deleteProofFile();
        $payment->delete();

        return back()->with('status', 'Pembayaran dihapus.');
    }

    public function storePaymentType(Request $request): RedirectResponse
    {
        PaymentType::create($request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:payment_types,code',
            'description' => 'nullable|string',
            'default_amount' => 'nullable|numeric|min:0',
            'sort_order' => 'nullable|integer|min:0',
        ]));

        return back()->with('status', 'Jenis pembayaran berhasil ditambahkan.');
    }

    public function updatePaymentType(Request $request, PaymentType $paymentType): RedirectResponse
    {
        $paymentType->update($request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:payment_types,code,'.$paymentType->id,
            'description' => 'nullable|string',
            'default_amount' => 'nullable|numeric|min:0',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]) + ['is_active' => $request->boolean('is_active', true)]);

        return back()->with('status', 'Jenis pembayaran berhasil diperbarui.');
    }

    public function destroyPaymentType(PaymentType $paymentType): RedirectResponse
    {
        if ($paymentType->payments()->exists()) {
            return back()->withErrors(['payment_type' => 'Jenis pembayaran masih dipakai di tagihan.']);
        }

        $paymentType->delete();

        return back()->with('status', 'Jenis pembayaran dihapus.');
    }

    public function storeExpense(Request $request): RedirectResponse
    {
        Expense::create($request->validate([
            'description' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
        ]));

        return back()->with('status', 'Pengeluaran berhasil dicatat.');
    }

    public function updateExpense(Request $request, Expense $expense): RedirectResponse
    {
        $expense->update($request->validate([
            'description' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
        ]));

        return back()->with('status', 'Pengeluaran berhasil diperbarui.');
    }

    public function destroyExpense(Expense $expense): RedirectResponse
    {
        $expense->delete();

        return back()->with('status', 'Pengeluaran dihapus.');
    }

    private function validatePayment(Request $request): array
    {
        $request->validate([
            'proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        return $request->validate([
            'student_id' => 'required|exists:students,id',
            'payment_type_id' => 'required|exists:payment_types,id',
            'amount' => 'required|numeric|min:0',
            'period_label' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'paid_at' => 'nullable|date',
            'notes' => 'nullable|string',
            'status' => 'nullable|in:belum_bayar,pending,lunas,dibatalkan',
        ]);
    }

    private function resolveStatus(array $data): string
    {
        if (! empty($data['paid_at'])) {
            return 'lunas';
        }

        return $data['status'] ?? 'belum_bayar';
    }

    private function storeProof(Payment $payment, $file): void
    {
        $path = 'storage/'.$file->store('payments/'.$payment->id, 'public');
        $payment->update(['proof_path' => $path]);
    }
}
