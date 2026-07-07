<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Payment extends Model
{
    protected $fillable = [
        'student_id',
        'payment_type_id',
        'invoice_number',
        'amount',
        'period_label',
        'description',
        'due_date',
        'payment_channel',
        'payment_method_code',
        'proof_path',
        'merchant_order_id',
        'duitku_reference',
        'duitku_payment_url',
        'duitku_result_code',
        'notes',
        'recorded_by',
        'status',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'paid_at' => 'date',
            'due_date' => 'date',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function paymentType(): BelongsTo
    {
        return $this->belongsTo(PaymentType::class);
    }

    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function histories(): HasMany
    {
        return $this->hasMany(PaymentHistory::class)->orderByDesc('created_at');
    }

    public function proofUrl(): ?string
    {
        return $this->proof_path ? asset($this->proof_path) : null;
    }

    public function deleteProofFile(): void
    {
        if (! $this->proof_path || ! str_starts_with($this->proof_path, 'storage/')) {
            return;
        }

        Storage::disk('public')->delete(str_replace('storage/', '', $this->proof_path));
    }

    public function logHistory(
        string $event,
        string $message,
        ?string $statusFrom = null,
        ?string $statusTo = null,
        ?array $meta = null,
        ?int $userId = null,
    ): PaymentHistory {
        return $this->histories()->create([
            'event' => $event,
            'status_from' => $statusFrom,
            'status_to' => $statusTo,
            'message' => $message,
            'meta' => $meta,
            'user_id' => $userId ?? auth()->id(),
            'created_at' => now(),
        ]);
    }

    public static function generateInvoiceNumber(): string
    {
        $prefix = 'INV-'.now()->format('Ym').'-';
        $last = static::query()
            ->where('invoice_number', 'like', $prefix.'%')
            ->orderByDesc('invoice_number')
            ->value('invoice_number');

        $seq = $last ? ((int) substr($last, -4)) + 1 : 1;

        return $prefix.str_pad((string) $seq, 4, '0', STR_PAD_LEFT);
    }

    public function generateMerchantOrderId(): string
    {
        return 'PAY-'.$this->id.'-'.now()->format('YmdHis');
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'lunas' => 'Lunas',
            'pending' => 'Pending',
            'belum_bayar' => 'Belum bayar',
            'expired' => 'Kedaluwarsa',
            'gagal' => 'Gagal',
            'dibatalkan' => 'Dibatalkan',
            default => ucfirst(str_replace('_', ' ', $this->status)),
        };
    }
}
