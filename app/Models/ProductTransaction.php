<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductTransaction extends Model
{
    use HasFactory;
    protected $table = 'product_transaction';
    protected $casts = [
        'quantity' => 'double',
        'price' => 'double',
        'disc_rp' => 'double',
        'disc_prc' => 'double',
    ];
    protected $guarded = [];
    
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');

    }
}
