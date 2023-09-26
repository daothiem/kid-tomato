<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class News extends Model
{
    use HasFactory;
    protected $table = 'news';

    protected $fillable = [
        'title', 'description', 'keywords', 'avatar', 'content', 'category_id',
        'ordering','alias', 'created_by', 'meta_title', 'meta_description', 'meta_key_word'
    ];

    public function news_category(){
        return $this->belongsTo(NewsCategory::class,'category_id');
    }
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tags::class, 'tag_news', 'news_id', 'tag_id');
    }

    public function middleTags() {
        return $this->hasMany(TagNews::class, 'news_id', 'id');
    }
}
