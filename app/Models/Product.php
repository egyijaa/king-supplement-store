<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    
    protected $table = 'product';
    protected $casts = [
        'quantity' => 'double',
        'price' => 'double',
        'price3' => 'double',
        'price6' => 'double',
        'modal' => 'double',
    ];
    protected $guarded = [];
    
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function categoryItem()
    {
        return $this->belongsTo(CategoryItem::class);
    }    
    public function productSupply()
    {
        return $this->hasMany(ProductSupply::class);
    }
    public function productTransactions()
    {
        return $this->hasMany(ProductTransaction::class, 'product_id', 'id');
    }

    public function histories()
    {
        return $this->hasMany(HistoryProduct::class, 'product_id');
    }
}
