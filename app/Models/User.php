<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        // 'password' => 'hashed',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * The attributes that are not mass assignable.
     * 
     * @var array
     */
    protected $guarded = ['type'];

    /**
     * Set the password attribute after hashing it.
     *
     * @param string $value 
     * @return void
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    /**
     * Check if the user type is  admin.
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->type === 'admin';
    }

    /**
     * Check if the user type is user.
     *
     * @return bool
     */
    public function isUser(): bool
    {
        return $this->type === 'user';
    }

    /**
     * The notes that are written by the user.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function notes()
    {
        return $this->hasMany(Note::class, 'written_by');
    }

    /**
     * Get the tasks assigned to this user.
     * This will be by one to many relationship between user-task
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tasks()
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }

    /**
     * The tasks that are assigned to this user through projects.
     * This will be by many to many relationship between user-project
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function tasksByProjects()
    {
        return $this->hasManyThrough(
            Task::class,
            ProjectUser::class,
            'user_id',         // Foreign key on ProjectUser table
            'project_id',      // Foreign key on Task table
            'id',              // Local key on User table
            'project_id'       // Local key on ProjectUser table
        );
        #TODO re-check the kyes again
    }

    /**
     * The projects that the user is involved in.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_user')
            ->withPivot('role', 'contribution_hours', 'last_activity')
            ->withTimestamps();
    }

    /**
     * Check if the user has a specific role in a given project.
     *
     * @param int $projectId
     * @param string $role
     * @return bool
     */
    public function hasRoleInProject(int $projectId, string $role): bool
    {
        return $this->projects()
            ->wherePivot('role', $role)
            ->wherePivot('project_id', $projectId)
            ->exists();
    }

    /**
     * Check if the user has any of the specified roles in a given project.
     *
     * @param int $projectId 
     * @param array $roles 
     * @return bool 
     */
    public function hasAnyRoleInProject(int $projectId, array $roles): bool
    {
        return $this->projects()
            ->where('projects.id', $projectId)
            ->whereIn('project_user.role', $roles)->exists();
    }

    /**
     * Check if the user has a specific role for a given task & if he assigned to it
     * 
     * @param int $taskId 
     * @param string $role 
     * @return bool 
     */
    public function hasRoleInTask(int $taskId, string $role): bool
    {
        $task = Task::findOrFail($taskId);

        $isAssignedToTask = $task->assigned_to === $this->id;
        $hasRoleInProject = $this->hasRoleInProject($task->project_id, $role);

        return $isAssignedToTask && $hasRoleInProject;
    }

    /**
     * Check if the user is working on tasks within a given project.
     *
     * @param int $projectId 
     * @return bool 
     */
    public function isWorkingInProject(int $projectId): bool
    {
        return $this->tasks()->where('project_id', $projectId)->exists();
    }

    /**
     * Filter tasks by status and priority using whereRelation.
     *
     * @param string|null $status
     * @param string|null $priority
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function filterTasks($status = null, $priority = null)
    {
        $query = $this->tasks();

        if ($status) {
            $query->whereRelation('tasks', 'status', '=', $status);
        }

        if ($priority) {
            $query->whereRelation('tasks', 'priority', '=', $priority);
        }

        return $query->get();
    }

    /**
     * Get the oldest task based on the creation date.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function oldestTask()
    {
        return $this->hasOne(Task::class)->oldestOfMany('created_at');
    }

    /**
     * Get the oldest task based on the create date or updated date.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function latestTask()
    {
        return $this->hasOne(Task::class)->latestOfMany(['created_at', 'updated_at']);
    }
}
