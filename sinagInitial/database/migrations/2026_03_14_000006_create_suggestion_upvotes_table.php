<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('suggestion_upvotes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('suggestion_id');
            $table->unsignedBigInteger('user_id'); // or alias if needed
            $table->timestamps();
            $table->unique(['suggestion_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suggestion_upvotes');
    }
};
