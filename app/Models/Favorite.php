<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    public $timestamps   = false;
    public $incrementing = false;
    protected $primaryKey = null;

    protected $fillable = ['user_id', 'worker_id'];

    protected function casts(): array
    {
        return ['created_at' => 'datetime'];
    }

    public function worker()
    {
        return $this->belongsTo(WorkerProfile::class, 'worker_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
