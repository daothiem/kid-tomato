<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TagProduct extends Model
{
    use HasFactory;

    protected $table = 'tag_products';

    protected $fillable = ['product_id', 'tag_id'];
}
