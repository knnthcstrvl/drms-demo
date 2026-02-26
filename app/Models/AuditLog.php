<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'audit_logs';

    protected $primaryKey = 'id';

    protected $fillable = [
        'user',
        'date_time',
        'activity_type',
        'username',
        'full_name',
        'student_number',
        'filename',
        'first_name',
        'middle_name',
        'last_name',
    ];

    protected $casts = [
        'date_time' => 'datetime',
    ];
}