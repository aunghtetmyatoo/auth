<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Constants\MigrationLength;
use App\Constants\Status;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->unique();
            $table->string('name', MigrationLength::NAME)->index();
            $table->string('phone_number', MigrationLength::IDENTIFIER)->unique()->index();
            $table->string('password');
            $table->string('reference_id', MigrationLength::REFERENCE_ID)->unique()->index();
            $table->string('device_id')->index();
            // $table->double('amount')->default(0.00);
            $table->unsignedDecimal('amount', 15, 4)->default(0);
            $table->string('user_agent')->nullable();
            $table->text('photo')->nullable();

            // use account statuses
            $table->dateTime('frozen_at')->nullable();
            $table->dateTime('otp_mistook_at')->nullable();
            $table->tinyInteger('otp_mistake_count')->default(0);
            $table->dateTime('password_mistook_at')->nullable();
            $table->tinyInteger('password_mistake_count')->default(0);
            $table->dateTime('password_changed_at')->nullable();

            $table->dateTime('registered_at');
            $table->enum('payment_method_status', [Status::NEED_PMETHOD, Status::ADDED_PMETHOD, Status::REGISTERED])->default(Status::REGISTERED);
            $table->dateTime('last_logged_in_at')->nullable();
            $table->string('noti_token', MigrationLength::NOTI_TOKEN)->nullable();

            $table->string('ip_address', MigrationLength::IP_ADDRESS)->nullable();
            $table->string('language', MigrationLength::LANGUAGE)->default('en');

            $table->tinyInteger('level', MigrationLength::LEVEL);
            $table->enum("bluemark", [Status::BLUEMARK, Status::VIPBLUEMARK, Status::NORMAL])->default(Status::NORMAL);
            $table->string("payment_account_number")->nullable();
            $table->string("payment_account_name", MigrationLength::NAME)->nullable();
            $table->unsignedBigInteger("payment_type_id")->nullable()->index();
            $table->enum("play", [Status::PLAYING, Status::FREE])->default(Status::FREE);
            $table->string('role', 15)->default(Status::USER);
            $table->string('secret_key')->nullable();
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
        Schema::dropIfExists('users');
    }
};
