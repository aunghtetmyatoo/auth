<?php

use App\Constants\MigrationLength;
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
        Schema::create('histories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger("transaction_type_id")->index();
            $table->foreign("transaction_type_id")->references("id")->on("transaction_types");
            $table->morphs('historiable');
            $table->uuidMorphs('transactionable');
            $table->string('reference_id');
            $table->string('transaction_amount');
            $table->string('amount_before_transaction');
            $table->string('amount_after_transaction');
            $table->boolean("is_from")->nullable();
            $table->timestamps(6);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('histories');
    }
};
