<?php

use App\Constants\Status;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('withdraw_transactions', function (Blueprint $table) {
            $table->uuid('id')->unique();
            $table->uuid("user_id")->index();
            $table->foreign("user_id")->references("id")->on("users");
            $table->unsignedBigInteger("transaction_type_id")->index();
            $table->foreign("transaction_type_id")->references("id")->on("transaction_types");
            $table->uuid("withdraw_request_id")->index();
            $table->foreign("withdraw_request_id")->references("id")->on("withdraw_requests");
            $table->string("reference_id")->index();
            // $table->double('amount')->nullable();
            $table->unsignedDecimal('amount', 12, 4)->nullable();
            $table->double('handling_fees')->nullable();
            $table->enum("from_amount_status", [Status::DEBIT, Status::CREDIT])->default(Status::CREDIT);
            $table->enum("to_amount_status", [Status::DEBIT, Status::CREDIT])->default(Status::DEBIT);
            $table->text('remark')->nullable();
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
        Schema::dropIfExists('withdraw_transactions');
    }
};
