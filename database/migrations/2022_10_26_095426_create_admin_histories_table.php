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
        Schema::create('admin_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("admin_id")->nullable()->index();
            $table->foreign("admin_id")->references("id")->on("admins");
            $table->unsignedBigInteger("transaction_type_id")->nullable()->index();
            $table->foreign("transaction_type_id")->references("id")->on("transaction_types");
            $table->string("reference_id", MigrationLength::REFERENCE_ID)->unique()->index();
            $table->double("amount")->default(0.00);
            $table->unsignedBigInteger("coin")->default(0);
            $table->uuid("user_id")->nullable()->index();
            $table->foreign("user_id")->references("id")->on("users");
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
        Schema::dropIfExists('admin_histories');
    }
};
