<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'leave_application_id',
        'user_id',
        'action',
        'action_at',
        'remarks',
    ];

    /**
     * A leave record belongs to a leave application.
     */
    public function leaveApplication()
    {
        return $this->belongsTo(LeaveApplication::class);
    }

    /**
     * A leave record belongs to a user (the user who took the action).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
