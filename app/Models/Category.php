<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    public $timestamps    = false;
    public $incrementing  = false;
    protected $keyType    = 'string';

    protected $fillable = ['id', 'icon', 'title'];

    protected function casts(): array
    {
        return ['created_at' => 'datetime'];
    }

    public function workers()
    {
        return $this->belongsToMany(WorkerProfile::class, 'worker_categories', 'category_id', 'worker_id');
    }
}
