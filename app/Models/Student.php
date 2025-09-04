<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'students';

    protected $primaryKey = 'id';

    protected $fillable = [
        'last_name',
        'first_name',
        'middle_name',
        'birth_date',
        'student_number',
        'program',
        'year_graduated',
        'filename',
        'doc_id',
        'date_time_created',
        'date_time_updated',
    ];

    protected $casts = [
        'date_time_created' => 'datetime',
        'date_time_updated' => 'datetime',
    ];

    public function document()
    {
        return $this->belongsTo(Document::class, 'doc_id');
    }
}
