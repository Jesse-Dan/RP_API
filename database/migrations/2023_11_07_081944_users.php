<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        // Schema::create('users', function (Blueprint $table) {
            // $table->id();
            // $table->string('first_name');
            // $table->string('last_name');
            // $table->string('email')->unique();
            // $table->string('country');
            // $table->integer('followers')->default(0);
            // $table->integer('following')->default(0);
            // $table->integer('posts')->default(0);
            // $table->string('account_type')->default('PLAIN');
            // //
            // $table->string('active_status'); 
            // //
            // $table->string('state'); 
            // $table->string('password');
            // $table->rememberToken();
            // $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};