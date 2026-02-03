<?php

declare(strict_types = 1);

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
        Schema::create(config('laravel-exceptions.channels_settings.database.table_name'), function (Blueprint $table) {
            $table->id();
            $table->string('exception_class')->index();
            $table->longText('message')->nullable();
            $table->longText('user_message')->nullable();
            $table->text('file')->nullable();
            $table->unsignedBigInteger('line')->nullable()->index();
            $table->string('code', 20)->default(0)->index();
            $table->unsignedInteger('status_code')->nullable()->index();
            $table->string('error_id')->nullable()->index();
            $table->string('app_env')->nullable()->index();
            $table->boolean('app_debug')->nullable()->index();
            $table->string('host_name')->nullable();
            $table->string('host_ip')->nullable();
            $table->foreignId('user_id')->nullable()->constrained(config('laravel-exceptions.channels_settings.database.user_model_table', 'users'))->nullOnDelete();
            $table->boolean('is_retryable')->nullable()->index();
            $table->longText('stack_trace')->nullable();
            $table->string('previous_exception_class')->nullable()->index();
            $table->longText('previous_message')->nullable();
            $table->text('previous_file')->nullable();
            $table->unsignedBigInteger('previous_line')->nullable()->index();
            $table->string('previous_code', 20)->nullable()->default(0)->index();
            $table->longText('previous_stack_trace')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(config('laravel-exceptions.channels_settings.database.table_name'));
    }
};
