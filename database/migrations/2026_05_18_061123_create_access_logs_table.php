<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('access_logs', function (Blueprint $table) {

            $table->id();

            $table->string('ip')->nullable();

            $table->string('user_email')->nullable();

            $table->string('method');

            $table->string('path');

            $table->integer('status')->nullable();

            $table->boolean('failed_login')->default(false);

            $table->timestamp('access_time');

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('access_logs');
    }
};
