<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentOnlineExamStatus extends Model
{
    use HasFactory;
    protected $hidden = ["deleted_at", "created_at", "updated_at"];

    public function online_exam() {
        return $this->belongsTo(OnlineExam::class,'online_exam_id');
    }
}
