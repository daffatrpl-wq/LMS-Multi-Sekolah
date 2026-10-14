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
       Schema::create('schools', function (Blueprint $table) {
        $table->id();
        $table->string('name')->unique();
        $table->text('address');
        $table->string('contact_email')->unique();
        $table->string('status')->default('active'); // active, suspended, inactive
        $table->integer('max_users')->default(100);
        $table->integer('max_classes')->default(10);
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school');
    }
};
