<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('jenjang');
            $table->string('status')->default('aktif');
            $table->timestamps();
        });

        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('kelompok');
            $table->timestamps();
        });

        Schema::create('kelompoks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained()->cascadeOnDelete();
            $table->string('code')->unique();
            $table->string('name');
            $table->unsignedSmallInteger('capacity')->default(25);
            $table->string('status')->default('aktif');
            $table->timestamps();
        });

        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelompok_id')->nullable()->constrained()->nullOnDelete();
            $table->string('nis')->unique();
            $table->string('name');
            $table->string('gender')->nullable();
            $table->date('birth_date')->nullable();
            $table->text('address')->nullable();
            $table->string('status')->default('aktif');
            $table->date('registered_at')->nullable();
            $table->timestamps();
        });

        Schema::create('guardians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('relationship');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->timestamps();
        });

        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('nik')->unique();
            $table->string('name');
            $table->string('position');
            $table->string('department');
            $table->string('status')->default('aktif');
            $table->date('joined_at')->nullable();
            $table->timestamps();
        });

        Schema::create('tutors', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('subject');
            $table->unsignedSmallInteger('experience_years')->default(0);
            $table->string('status')->default('aktif');
            $table->date('joined_at')->nullable();
            $table->timestamps();
        });

        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->string('day');
            $table->string('start_time');
            $table->string('end_time');
            $table->foreignId('kelompok_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tutor_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('teaching_journals', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignId('kelompok_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tutor_id')->nullable()->constrained()->nullOnDelete();
            $table->string('material');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('daily_reports', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignId('kelompok_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tutor_id')->nullable()->constrained()->nullOnDelete();
            $table->text('summary');
            $table->text('homework')->nullable();
            $table->unsignedSmallInteger('photo_count')->default(0);
            $table->unsignedSmallInteger('video_count')->default(0);
            $table->string('status')->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 12, 2);
            $table->string('status')->default('pending');
            $table->date('paid_at')->nullable();
            $table->timestamps();
        });

        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('description');
            $table->string('category');
            $table->decimal('amount', 12, 2);
            $table->date('expense_date');
            $table->timestamps();
        });

        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->string('check_in')->nullable();
            $table->string('status')->default('hadir');
            $table->timestamps();
        });

        Schema::create('landing_slides', function (Blueprint $table) {
            $table->id();
            $table->string('tag')->nullable();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('cta_text')->nullable();
            $table->string('cta_link')->nullable();
            $table->unsignedTinyInteger('slide_style')->default(1);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('landing_programs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('icon');
            $table->string('color_variant');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('landing_programs');
        Schema::dropIfExists('landing_slides');
        Schema::dropIfExists('attendances');
        Schema::dropIfExists('expenses');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('daily_reports');
        Schema::dropIfExists('teaching_journals');
        Schema::dropIfExists('schedules');
        Schema::dropIfExists('tutors');
        Schema::dropIfExists('employees');
        Schema::dropIfExists('guardians');
        Schema::dropIfExists('students');
        Schema::dropIfExists('kelompoks');
        Schema::dropIfExists('subjects');
        Schema::dropIfExists('programs');
    }
};
