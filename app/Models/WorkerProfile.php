<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkerProfile extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id', 'specialty', 'bio', 'location', 'status',
        'experience_years', 'response_time', 'completed_jobs', 'rating', 'image_url',
    ];

    protected function casts(): array
    {
        return [
            'rating'           => 'float',
            'completed_jobs'   => 'integer',
            'experience_years' => 'integer',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'worker_categories', 'worker_id', 'category_id');
    }

    public function tags()
    {
        return $this->hasMany(WorkerTag::class, 'worker_id');
    }

    public function services()
    {
        return $this->hasMany(WorkerService::class, 'worker_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'worker_id');
    }

    public function favoritedBy()
    {
        return $this->hasMany(Favorite::class, 'worker_id');
    }
}
