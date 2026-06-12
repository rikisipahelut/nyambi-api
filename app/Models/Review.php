<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory, HasUuids;

    public $timestamps      = false;
    const CREATED_AT        = 'created_at';

    protected $fillable = ['order_id', 'user_id', 'worker_id', 'rating', 'comment'];

    protected function casts(): array
    {
        return ['created_at' => 'datetime'];
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function worker()
    {
        return $this->belongsTo(WorkerProfile::class, 'worker_id');
    }
}
