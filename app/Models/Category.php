<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = [
        'title', 'description', 'avatar', 'content', 'parent_id','ordering', 'meta_video', 'description_detail', 'price',
        'alias', 'created_by', 'meta_title', 'meta_description', 'meta_key_word', 'album'
    ];

    public function parent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id', 'id');
    }

    public function child(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Category::class, 'parent_id', 'id');
    }

    public function createdBy() {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function products() {
        return $this->belongsToMany(Product::class, 'product_categories', 'category_id', 'product_id');
    }
}
