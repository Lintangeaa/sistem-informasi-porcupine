<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistorySales extends Model
{
    use HasFactory;
    protected $table = 'history_sales';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $timestamps = true;
    public  $incrementing = true;
    protected $fillable = [
        'accepted_weight',
        'user_id',
        'sale_id',
        'accepted_total',
        'accepted_price',
        'status'
    ];

    public function sale()
    {
        return $this->belongsTo(SalesProposal::class, 'sale_id', 'id');
    }
}
