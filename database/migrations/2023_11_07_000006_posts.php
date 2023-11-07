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
        Schema::create('posts', function (Blueprint $table) {
            $table->id()->nullable();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');

            $table->unsignedBigInteger('categories');
            $table->foreign('categories')->references('id')->on('categories'); 

            $table->unsignedBigInteger('sub_categories');
            $table->foreign('sub_categories')->references('id')->on('sub_categories');

            $table->unsignedBigInteger('sub_categories_child')->default(0);
            $table->foreign('sub_categories_child')->references('id')->on('sub_categories_child');
            $table->string('subject');
            $table->string('content');
            $table->string('file')->nullable();           
            $table->text('cv_path')->nullable();
            $table->string('logo')->nullable();
            $table->string('link')->nullable();
            $table->tinyInteger('all_countries')->default(0);
            $table->string('email')->nullable();
            $table->string('phone_code');
            $table->string('phone_number');
            $table->string('currency')->nullable();
            $table->integer('price')->nullable();
            $table->string('action');
            $table->date('plan')->nullable();
            $table->string('package')->nullable();
            $table->string('period')->nullable();
            $table->string('top_ad')->default('0');
            $table->integer('job_ad')->default(0);
            $table->integer('state')->default(0);
            $table->integer('city')->default(0);
            $table->tinyInteger('paid_status')->default(0);
            $table->tinyInteger('expiry')->default(0);
            $table->string('device')->nullable();

            $table->timestamps();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
