<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Time extends Model
{
    use HasFactory;
    protected $table = 'times';
    protected $fillable = [
        'start_time', 'end_time', 'day'
    ];
    protected $appends = ['has_relation'];

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, "student_times");
    }

    public function classes()
    {
        return $this->hasMany(ClassModel::class);
    }

    public function getDayAttribute($value)
    {
        switch ($value) {
            case 1: return "Senin";
            case 2: return "Selasa";
            case 3: return "Rabu";
            case 4: return "Kamis";
            case 5: return "Jumat";
            case 6: return "Sabtu";
            case 7: return "Minggu";
        }
    }

    public function getHasRelationAttribute()
    {
        $hasRelationWithClass = count($this->classes()->get()) > 0;
        $hasRelationWithStudentTime = count($this->students()->get()) > 0;
        return $hasRelationWithClass || $hasRelationWithStudentTime;
    }
}
