<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Constants\MigrationLength;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('name', MigrationLength::NAME)->index();
            $table->string('phone_number', MigrationLength::IDENTIFIER)->index();
            $table->string('password');
            $table->string('reference_id', MigrationLength::REFERENCE_ID)->unique();
            $table->string('role')->nullable();
            // $table->double('amount')->default(0.00);
            $table->unsignedDecimal('amount', 15, 4)->default(0);
            // security things
            $table->string('device_id', MigrationLength::DEVICE_ID)->nullable();
            $table->string('user_agent')->nullable();
            $table->mediumText('mfa_secret');

            // backend user account statuses
            $table->dateTime('frozen_at_bk')->nullable();
            $table->dateTime('bk_otp_mistook_at')->nullable();
            $table->tinyInteger('bk_otp_mistake_count')->default(0);
            $table->dateTime('password_mistook_at')->nullable();
            $table->tinyInteger('password_mistake_count')->default(0);
            $table->dateTime('mfa_mistook_at')->nullable();
            $table->tinyInteger('mfa_mistake_count')->default(0);

            $table->dateTime('last_logged_in_at')->nullable();
            $table->dateTime('first_logged_in_at')->nullable();
            $table->dateTime('password_changed_at')->nullable();
            $table->dateTime('registered_at');
            $table->string('noti_token', MigrationLength::NOTI_TOKEN)->nullable();

            $table->string('language', MigrationLength::LANGUAGE)->default('en');
            $table->string('ip_address', MigrationLength::IP_ADDRESS)->nullable();

            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admins');
    }
};
