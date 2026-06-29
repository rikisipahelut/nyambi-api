<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class OrderProofPhoto extends Model
{
    use HasUuids;

    protected $fillable = ['order_id', 'path'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
