<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class ClassSubjectTeacher extends Model
{
    use HasFactory;

    protected $table = 'class_subject_teacher';

    protected $primaryKey = 'cst_id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'cst_id',
        'class_id',
        'subject_code',
        'teacher_id',
        'academic_year',
        'semester',
        'join_code',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            // Generate primary key cst_id jika belum ada
            if (empty($model->cst_id)) {
                $model->cst_id = 'CST-' . strtoupper(Str::random(6));
            }
            self::generateJoinCode($model);
        });

        static::updating(function ($model) {
            // Otomatis update join_code saat data diedit
            self::generateJoinCode($model);
        });
    }

    private static function generateJoinCode($model)
    {
        $class = SchoolClass::where('class_id', $model->class_id)->first();
        $subject = Subject::where('code', $model->subject_code)->first();

        if ($class && $subject) {
            $className = strtoupper($class->name); // Contoh: "XII IPS 2"

            // Ubah angka Romawi ke Angka biasa (XII -> 12, XI -> 11, X -> 10)
            $className = str_replace(['XII', 'XI', 'X'], ['12', '11', '10'], $className);

            // Ubah IPS jadi 'B' dan IPA jadi 'A'
            $className = str_replace(['IPS', 'IPA'], ['B', 'A'], $className);

            // Bersihkan spasi
            $cleanClass = preg_replace('/\s+/', '', $className);

            // Hasil akhir ringkas: KIM + 12B2 = KIM12B2
            $model->join_code = strtoupper($subject->code . $cleanClass);
        } else {
            if (empty($model->join_code)) {
                $model->join_code = strtoupper(Str::random(6));
            }
        }
    }

    public function schoolClass()
    {
        return $this->belongsTo(
            SchoolClass::class,
            'class_id',
            'class_id'
        );
    }

    public function subject()
    {
        return $this->belongsTo(
            Subject::class,
            'subject_code',
            'code'
        );
    }

    public function teacher()
    {
        return $this->belongsTo(
            Teacher::class,
            'teacher_id',
            'nip'
        );
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'cst_id', 'cst_id');
    }

    public function materials()
    {
        return $this->hasMany(Material::class, 'cst_id', 'cst_id');
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'cst_id', 'cst_id');
    }

    public function exams()
    {
        return $this->hasMany(Exam::class, 'cst_id', 'cst_id');
    }

    // Siswa yang sudah bergabung lewat kode kelas
    public function joinedStudents()
    {
        return $this->belongsToMany(
            Student::class,
            'cst_students',
            'cst_id',
            'students_id',
            'cst_id',
            'nis'
        )->withPivot('joined_at');
    }

    public function label(): string
    {
        return ($this->subject->name ?? $this->subject_code).' — '.($this->schoolClass->name ?? '-');
    }

    public function enrollments()
    {
        return $this->hasMany(ClassEnrollment::class, 'cst_id', 'cst_id');
    }
}