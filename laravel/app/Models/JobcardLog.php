<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobcardLog extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'jobcard_id', 'user_id', 'description'
    ];

    /**
     * Get the jobcard that owns the JobcardLog.
     */
    public function jobcard()
    {
        return $this->belongsTo(Jobcard::class);
    }

    /**
     * Get the user that owns the JobcardLog.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
