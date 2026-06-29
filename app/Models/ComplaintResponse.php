<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class ComplaintResponse extends Model
{
    use HasUuids;

    protected $fillable = ['complaint_id', 'user_id', 'sent_as', 'pesan'];

    public function complaint()
    {
        return $this->belongsTo(Complaint::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
