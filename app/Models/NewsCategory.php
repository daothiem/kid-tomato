<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsCategory extends Model
{
    use HasFactory;

    protected $table = 'news_category';

    protected $fillable = ['title', 'parent_id', 'ordering', 'alias', 'created_by', 'description', 'url'];

    public function createdBy() {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function parent() {
        return $this->belongsTo(NewsCategory::class, 'parent_id', 'id');
    }
}
