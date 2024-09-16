<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'priority',
        'due_date',
        'status',
        'assigned_to',
        'project_id',
        'hours',
    ];

    /**
     * The attributes that should be cast.
     * 
     * @var array
     */
    protected $casts = [
        'due_date' => 'datetime',
    ];

    /**
     * Accessor for formatted due date.
     * 
     * @return string
     */
    public function getDueDateAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y H:i');
    }

    /**
     * Mutator for setting due date.
     * 
     * @param string $value
     * @return void
     */
    public function setDueDateAttribute($value)
    {
        $this->attributes['due_date'] = Carbon::createFromFormat('d-m-Y H:i', $value)->format('Y-m-d H:i:s');
    }

    /**
     * The user who is assigned to this task.     
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    #FUXME assignedUser if needed
    public function user()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * The project that this task belongs to.     
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * The notes associated with this task.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function notes()
    {
        return $this->hasMany(Note::class, 'task_id');
    }
}
