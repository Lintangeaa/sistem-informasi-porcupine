<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Production extends Model
{
    use HasFactory;

    protected $table = 'productions';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $timestamps = true;
    public  $incrementing = true;
    protected $fillable = [
        'raw_weight',
        'produced_weight',
        'user_id',
        'produced_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
