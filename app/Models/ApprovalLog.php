<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApprovalLog extends Model
{
    protected $fillable = [
        'budget_id',
        'user_id',
        'action',
        'motivo'
    ];

    public function budget()
    {
        return $this->belongsTo(Budget::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 