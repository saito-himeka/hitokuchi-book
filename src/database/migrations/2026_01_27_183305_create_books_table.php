<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');        // タイトル（255文字まで）
            $table->string('author');       // 著者名
            $table->text('summary');         // 要約本文（長い文章が入るのでtext型）
            
            // 図解画像が1枚なら string、複数なら json でもOK
            $table->string('image_path')->nullable(); 
            
            // アフィリエイトURL用
            $table->text('affiliate_url')->nullable(); 
            
            // 本のジャンル（後で検索に使うため）
            $table->string('genre')->nullable();
            
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
        Schema::dropIfExists('books');
    }
}
