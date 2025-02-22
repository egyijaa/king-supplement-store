<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryProduct extends Model
{
    use HasFactory;
    
    protected $table = 'history_products';
    protected $guarded = [];
    
    public function belong_product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function belong_user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function belong_category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function belong_old_category()
    {
        return $this->belongsTo(Category::class, 'old_category_id', 'id');
    }
}
