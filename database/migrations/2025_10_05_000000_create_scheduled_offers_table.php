<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('scheduled_offers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->string('title');
            $table->text('description');
            $table->decimal('price', 10, 2);
            $table->string('currency', 3)->default('BRL');
            $table->enum('status', ['draft', 'active', 'expired'])->default('draft');
            $table->boolean('active')->default(true);
            $table->timestamp('start_date');
            $table->timestamp('end_date')->nullable();
            $table->timestamp('scheduled_for');
            $table->timestamp('processed_at')->nullable();
            $table->foreignId('offer_id')
                ->nullable()
                ->constrained('offers')
                ->nullOnDelete();
            $table->timestamps();

            $table->index(['scheduled_for', 'processed_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scheduled_offers');
    }
};
