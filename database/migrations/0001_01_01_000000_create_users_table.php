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
     Schema::create('users', function (Blueprint $table) {

        $table->id();
        $table->enum('role', ['user', 'admin'])->default('user');
        $table->string('admin_type')->nullable();
        $table->string('name', 100);
        $table->string('username', 100)->nullable()->unique();
        $table->string('phone', 20)->nullable()->unique();
        $table->string('email', 100)->unique();
        $table->date('birthday')->nullable();
        $table->string('gender', 100)->nullable();
        $table->text('present_address')->nullable();
        $table->text('permanent_address')->nullable();
        $table->unsignedBigInteger('country_id')->nullable();
        $table->string('referral_code')->unique()->nullable();
        $table->timestamp('email_verified_at')->nullable();
        $table->string('password')->nullable()->change();
        $table->string('photo', 191)->nullable();
        $table->decimal('total_earning', 15, 2)->default(0);
        $table->decimal('current_earning', 15, 2)->default(0);
        $table->decimal('total_deposit', 15, 2)->default(0);
        $table->decimal('current_deposit', 15, 2)->default(0);
        $table->foreignId('referred_by')->nullable()->constrained('users')->nullOnDelete();
        $table->integer('total_refer')->default(0);
        $table->integer('total_job_post')->default(0);
        $table->decimal('deposit_commission_from_refer', 15, 2)->default(0);
        $table->decimal('earning_commission_from_refer', 15, 2)->default(0);
        $table->integer('satisfied_tasks')->default(0);
        $table->boolean('status')->default(1);
        $table->boolean('activity')->default(0);
        $table->text('reason')->nullable();
        $table->text('verified_reason')->nullable();
        $table->boolean('is_verified')->default(0);
        $table->text('ip_address')->nullable();
        $table->boolean('is_ban')->default(0);
        $table->text('ban_reason')->nullable();
        $table->rememberToken();
        $table->timestamps();
    });

    Schema::create('password_reset_tokens', function (Blueprint $table) {
        $table->string('email')->primary();
        $table->string('token');
        $table->timestamp('created_at')->nullable();
    });

    Schema::create('sessions', function (Blueprint $table) {
        $table->string('id')->primary();

        $table->foreignId('user_id')
            ->nullable()
            ->constrained('users')
            ->nullOnDelete();

        $table->string('ip_address', 45)->nullable();
        $table->text('user_agent')->nullable();
        $table->longText('payload');
        $table->integer('last_activity')->index();
     });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
