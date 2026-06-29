<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasUuids;

    protected $fillable = [
        'order_id', 'filed_by', 'filed_as', 'tipe', 'deskripsi', 'status', 'admin_note',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function filer()
    {
        return $this->belongsTo(User::class, 'filed_by');
    }

    public function responses()
    {
        return $this->hasMany(ComplaintResponse::class)->oldest();
    }
}
