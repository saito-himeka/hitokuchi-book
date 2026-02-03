<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeGenreColumnOnBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table) {
            // 1. まず、古い genre（文字列）を削除します
            $table->dropColumn('genre');

            // 2. 次に、新しい genre_id（数字）を追加します
            // constrained() をつけることで、genresテーブルに実在するIDしか入らないように制限をかけます
            $table->foreignId('genre_id')->after('author')->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            // ロールバック（元に戻す）したときの設定
            $table->dropForeign(['genre_id']);
            $table->dropColumn('genre_id');
            $table->string('genre')->after('author')->nullable();
        });
    }
}
