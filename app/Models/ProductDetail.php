<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductDetail extends Model
{
    use HasFactory;

    protected $table = 'product_details';

    protected $fillable = ['product_id', 'size_id', 'color_id', 'quantity','price', 'price_discount'];

}
