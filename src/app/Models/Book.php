<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    // 保存を許可するカラムを指定（ホワイトリスト）

    protected $fillable = [
        'title', 'author', 'genre_id', // genre を genre_id に変更
        'summary', 'affiliate_url', 'image_path', 'diagram_path'
    ];

    // ジャンルとのつながりを定義
    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }
}