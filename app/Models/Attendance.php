<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attendance extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'attendances';
    protected $fillable = ['student_id','class_id'];
    protected $softDelete = true;

    public function class()
    {
        return $this->belongsTo(ClassModel::class);
    }
}
