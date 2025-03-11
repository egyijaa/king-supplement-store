<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryProduct extends Model
{
    use HasFactory;
    
    protected $table = 'history_products';
    protected $casts = [
        'update_qty' => 'double',
        'update_modal' => 'double',
        'update_price' => 'double',
        'update_price3' => 'double',
        'update_price6' => 'double',
        'update_in' => 'double',
        'update_out' => 'double',
        'old_qty' => 'double',
        'old_modal' => 'double',
        'old_price' => 'double',
        'old_price3' => 'double',
        'old_price6' => 'double',
        'barang_masuk' => 'double',
        'barang_keluar' => 'double',
    ];
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

    public function belong_trans()
    {
        return $this->belongsTo(Transaction::class, 'trans_id', 'id');
    }

    public function belong_supply()
    {
        return $this->belongsTo(Supply::class, 'supply_id', 'id');
    }
}
