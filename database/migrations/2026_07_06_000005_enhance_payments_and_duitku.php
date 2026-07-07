<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->decimal('default_amount', 12, 2)->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->foreignId('payment_type_id')->nullable()->after('student_id')->constrained()->nullOnDelete();
            $table->string('invoice_number')->nullable()->unique()->after('payment_type_id');
            $table->string('period_label')->nullable()->after('amount');
            $table->text('description')->nullable()->after('period_label');
            $table->date('due_date')->nullable()->after('description');
            $table->string('payment_channel')->default('manual')->after('due_date');
            $table->string('payment_method_code')->nullable()->after('payment_channel');
            $table->string('proof_path')->nullable()->after('payment_method_code');
            $table->string('merchant_order_id')->nullable()->unique()->after('proof_path');
            $table->string('duitku_reference')->nullable()->after('merchant_order_id');
            $table->string('duitku_payment_url')->nullable()->after('duitku_reference');
            $table->string('duitku_result_code')->nullable()->after('duitku_payment_url');
            $table->text('notes')->nullable()->after('duitku_result_code');
            $table->foreignId('recorded_by')->nullable()->after('notes')->constrained('users')->nullOnDelete();
        });

        Schema::create('payment_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->constrained()->cascadeOnDelete();
            $table->string('event');
            $table->string('status_from')->nullable();
            $table->string('status_to')->nullable();
            $table->text('message');
            $table->json('meta')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_histories');

        Schema::table('payments', function (Blueprint $table) {
            $table->dropConstrainedForeignId('payment_type_id');
            $table->dropConstrainedForeignId('recorded_by');
            $table->dropColumn([
                'invoice_number',
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
            ]);
        });

        Schema::dropIfExists('payment_types');
    }
};
