<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BackupSchedule extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'frequency',
        'time',
        'day',
        'active',
        'last_backup',
        'next_backup'
    ];

    protected $casts = [
        'active' => 'boolean',
        'time' => 'datetime:H:i',
        'last_backup' => 'datetime',
        'next_backup' => 'datetime'
    ];
}
