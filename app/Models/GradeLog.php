<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GradeLog extends Model
{
    protected $connection = 'mysql_admin';
    protected $table = 'grade_logs';
    protected $primaryKey = 'log_id';
    public $timestamps = false;

    protected $casts = [
        'old_score'  => 'decimal:2',
        'new_score'  => 'decimal:2',
        'changed_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'students_id', 'nis');
    }
}