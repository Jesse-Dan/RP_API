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
        // Schema::dropIfExists('react_types');

        //         Schema::create('react_types', function (Blueprint $table) {
        //             $table->id()->nullable();
        //             $table->string('name')->nullable();

        //         });
        //   Schema::dropIfExists('reactions');

        Schema::create('reactions', function (Blueprint $table) {
            $table->id()->nullable();
            $table->unsignedBigInteger('user_id');
            
            $table->foreign('user_id')->references('id')->on('users');

            $table->integer('react_id')->nullable();
            $table->foreign('react_id')
            ->references('id')
            ->on('react_types');
            
            $table->unsignedBigInteger('post_id')->nullable();
            $table->foreign('post_id')
            ->references('id')
            ->on('posts');
            
            $table->unsignedBigInteger('parent_comment_id')->nullable();
            $table->foreign('parent_comment_id')
            ->references('id')
            ->on('comments');

            $table->unsignedBigInteger('ref_type');
            $table->foreign('ref_type')
            ->references('id')
            ->on('comment_types');

            $table->timestamps();                       
            // 0
            // LOVE
            // 1
            // LAUGH
            // 2
            // ANGRY
            // 3
            // LIKE
            



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
