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
        Schema::create('cash_out_requests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger("transaction_type_id")->index();
            $table->foreign("transaction_type_id")->references("id")->on("transaction_types");
            $table->string('account_name');
            $table->string('account_number');
            $table->string('amount');
            $table->uuid("user_id")->index();
            $table->foreign("user_id")->references("id")->on("users");
            $table->enum("status", [Status::REQUESTED, Status::COMPLETED, Status::REJECTED])->default(Status::REQUESTED);
            $table->unsignedBigInteger("admin_id")->nullable();
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
        Schema::dropIfExists('cash_out_requests');
    }
};
