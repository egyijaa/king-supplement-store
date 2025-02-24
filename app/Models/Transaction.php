<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $table = 'transaction';
    protected $guarded = [];
    protected $casts = [
        'pay' => 'double',
        'return' => 'double',
        'purchase_order' => 'double',
        'disc_total_rp' => 'double',
        'disc_total_prc' => 'double',
        'totalSementara' => 'double',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function productTransaction()
    {
        return $this->hasMany(ProductTransaction::class);
    }
}
