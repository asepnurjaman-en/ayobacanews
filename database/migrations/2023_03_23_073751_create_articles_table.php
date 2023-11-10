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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('title', 110);
            $table->string('slug', 110);
            $table->longText('content');
            $table->string('file', 110);
            $table->enum('file_type', ['image', 'video', 'audio', 'pdf'])->default('image');
            $table->string('file_source', 255)->nullable();
            $table->string('author', 50);
            $table->dateTime('datetime');
            $table->text('description');
            $table->enum('publish', ['publish', 'draft', 'schedule', 'archive'])->default('draft');
            $table->dateTime('schedule_time')->nullable();
            $table->json('tags')->nullable();
            $table->json('related')->nullable();
            $table->json('vote')->nullable();
            $table->bigInteger('article_category_id')->index()->nullable()->unsigned();
			$table->foreign('article_category_id')->references('id')->on('article_categories');
            $table->string('ip_addr', 30)->nullable();
            $table->bigInteger('user_id')->index()->nullable()->unsigned();
			$table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
