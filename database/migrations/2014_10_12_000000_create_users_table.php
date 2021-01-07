<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('mobile',20);
            $table->string('otp',10);
            $table->string('otpvalidtill',25);
            $table->string('address');
            $table->string('city');
            $table->string('pincode',10);
            $table->string('gender',10)->comment('Male,Female');
            $table->date('dob');
            $table->date('anniversary');
            $table->string('marital',50)->comment('Married, Single');
            $table->string('image',200)->comment('User Profile Picture')->default(url('image/default.png'));
            $table->tinyInteger('status')->comment('1:Active, 0:Blocked')->default(1);
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
