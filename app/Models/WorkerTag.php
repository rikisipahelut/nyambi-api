<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkerTag extends Model
{
    public $timestamps = false;

    protected $fillable = ['worker_id', 'tag'];

    public function worker()
    {
        return $this->belongsTo(WorkerProfile::class, 'worker_id');
    }
}
