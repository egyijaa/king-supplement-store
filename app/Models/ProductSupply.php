<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSupply extends Model
{
    use HasFactory;
    protected $table = 'product_supply';
    protected $casts = [
        'quantity' => 'double',
        'price' => 'double',
        'total_price' => 'double',
    ];
    protected $guarded = [];
    
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function supply()
    {
        return $this->belongsTo(Supply::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
