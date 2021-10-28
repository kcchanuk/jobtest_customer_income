<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIncomesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Incomes need the fields:
        // description (required), amount (required), income date (required),
        // tax year (required) and income file
        Schema::create('incomes', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();

            $table->string('description');
            $table->unsignedDecimal('amount', 14, 2); // Max: 999,999,999,999.99
            $table->date('income_date');
            $table->string('income_filename')->unique()->nullable();

            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('tax_year_id')->constrained();

            $table->index('income_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('incomes');
    }
}
