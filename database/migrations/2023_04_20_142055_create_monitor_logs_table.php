<?php

use App\Constants\ErrorLogStatus;
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
        Schema::create('monitor_logs', function (Blueprint $table) {
            $table->uuid('id')->unique();
            $table->unsignedBigInteger('transaction_type_id');
            $table->foreign('transaction_type_id')->references('id')->on('transaction_types');
            $table->uuid('monitor_loggable_id');
            $table->string('monitor_loggable_type');
            $table->string('reference_id');
            // $table->double('difference_amount')->nullable();
            $table->unsignedDecimal('difference_amount', 12, 4)->nullable();
            $table->string('error_text')->nullable();
            $table->enum("error_status", [ErrorLogStatus::PENDING, ErrorLogStatus::SLOVED])->default(ErrorLogStatus::PENDING);
            $table->boolean('read')->default(0);
            $table->dateTime('transaction_at');
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
        Schema::dropIfExists('monitor_logs');
    }
};
