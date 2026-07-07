<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('landing_sections', function (Blueprint $table) {
            $table->id();
            $table->string('section_key')->unique();
            $table->string('kicker')->nullable();
            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();
            $table->text('body')->nullable();
            $table->text('body_secondary')->nullable();
            $table->string('cta_primary_text')->nullable();
            $table->string('cta_primary_link')->nullable();
            $table->string('cta_secondary_text')->nullable();
            $table->string('cta_secondary_link')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('extra_text')->nullable();
            $table->timestamps();
        });

        Schema::create('landing_items', function (Blueprint $table) {
            $table->id();
            $table->string('group');
            $table->string('icon')->nullable();
            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedInteger('value')->nullable();
            $table->string('suffix', 10)->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('landing_items');
        Schema::dropIfExists('landing_sections');
    }
};
