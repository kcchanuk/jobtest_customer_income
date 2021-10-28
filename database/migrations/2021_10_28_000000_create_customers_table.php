<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Customers need the fields:
        // name (required), email (required), utr (required), dob, phone and profile pic
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();

            $table->string('name');
            $table->string('email')->unique();
            $table->string('utr')->unique(); // Unique Taxpayer Reference. It is a 10-digit string.
            $table->date('dob')->nullable();
            $table->string('phone')->unique()->nullable();
            $table->string('profile_pic_filename')->unique()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
