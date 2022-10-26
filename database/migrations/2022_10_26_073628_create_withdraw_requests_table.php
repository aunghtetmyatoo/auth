<?php

use App\Constants\MigrationLength;
use App\Constants\Status;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('withdraw_requests', function (Blueprint $table) {
            $table->uuid('id')->unique();
            $table->string("reference_id", MigrationLength::REFERENCE_ID)->unique()->index();
            $table->uuid("user_id")->nullable()->index();
            $table->foreign("user_id")->references("id")->on("users");
            $table->unsignedBigInteger("payment_type_id")->nullable()->index();
            $table->foreign("payment_type_id")->references("id")->on("payment_types");
            $table->string("account_name", MigrationLength::NAME)->nullable();
            $table->string("account_number", MigrationLength::NAME)->nullable();
            $table->double("amount")->default(0.00);
            $table->enum("withdraw_status", [Status::REQUESTED, Status::DONE, Status::REJECTED])->default(Status::REQUESTED);
            $table->unsignedBigInteger("admin_id")->nullable()->index();
            $table->foreign("admin_id")->references("id")->on("admins");
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
        Schema::dropIfExists('withdraw_requests');
    }
};
