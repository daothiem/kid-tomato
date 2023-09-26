<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TagNews extends Model
{
    use HasFactory;

    protected $table = 'tag_news';
    protected $fillable = ['tag_id', 'news_id'];
}
