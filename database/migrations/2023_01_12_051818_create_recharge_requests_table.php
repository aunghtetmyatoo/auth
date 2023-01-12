<?php

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
        Schema::create('recharge_requests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid("user_id")->index();
            $table->foreign("user_id")->references("id")->on("users");
            $table->string('transaction_screenshot');
            $table->enum("status", [Status::REQUESTED, Status::COMPLETED, Status::REJECTED])->default(Status::REQUESTED);
            $table->unsignedBigInteger("admin_id");
            $table->foreign("admin_id")->references("id")->on("admins");
            $table->unsignedBigInteger("payment_type_id")->index();
            $table->foreign("payment_type_id")->references("id")->on("payment_types");
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
        Schema::dropIfExists('recharge_requests');
    }
};
