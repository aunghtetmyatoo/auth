<?php

use App\Constants\MigrationLength;
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
        Schema::create('cash_gls', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name', MigrationLength::NAME);
            $table->double('amount',34)->default(0.00);
            $table->boolean('status')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cash_gls');
    }
};