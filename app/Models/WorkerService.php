<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkerService extends Model
{
    public $timestamps = false;

    protected $fillable = ['worker_id', 'name', 'price', 'unit'];

    public function worker()
    {
        return $this->belongsTo(WorkerProfile::class, 'worker_id');
    }
}
