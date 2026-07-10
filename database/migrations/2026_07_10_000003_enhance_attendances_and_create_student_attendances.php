<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->string('check_out')->nullable()->after('check_in');
            $table->text('notes')->nullable()->after('status');
            $table->unique(['employee_id', 'date']);
        });

        Schema::create('student_attendances', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignId('kelompok_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->string('status')->default('hadir');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['date', 'student_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_attendances');

        Schema::table('attendances', function (Blueprint $table) {
            $table->dropUnique(['employee_id', 'date']);
            $table->dropColumn(['check_out', 'notes']);
        });
    }
};
