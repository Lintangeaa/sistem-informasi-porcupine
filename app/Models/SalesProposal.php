<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesProposal extends Model
{
    use HasFactory;
    protected $table = 'sales_proposals';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $timestamps = true;
    public  $incrementing = true;
    protected $fillable = [
        'weight',
        'user_id',
        'total'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
