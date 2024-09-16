<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * The tasks associated with this project.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    /**
     * The users that belong to the project.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'project_user')
            ->withPivot('role', 'contribution_hours', 'last_activity')
            ->withTimestamps();
    }

    /**
     * Get the latest task based on creation date.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function latestTask()
    {
        return $this->hasOne(Task::class)->latestOfMany('created_at');
    }

    /**
     * Get the oldest task based on creation date.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function oldestTask()
    {
        return $this->hasOne(Task::class)->oldestOfMany('created_at');
    }

    /**
     * Get the latest updated task.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function latestUpdatedTask()
    {
        return $this->hasOne(Task::class)->latestOfMany('updated_at');
    }

    /**
     * Get the oldest updated task.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function oldestUpdatedTask()
    {
        return $this->hasOne(Task::class)->oldestOfMany('updated_at');
    }
}
