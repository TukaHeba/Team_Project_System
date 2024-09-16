<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
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
     * The notes that are written by the user.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function notes()
    {
        return $this->hasMany(Note::class, 'written_by');
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
     * i want to use this to handel permissions based on roles
     * @param int $projectId 
     * @param string $role 
     * @return bool 
     */
    public function hasRoleInProject(int $projectId, string $role): bool
    {
        return $this->projects()
            ->where('id', $projectId)
            ->where('pivot_role', $role)->exists();
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
            ->where('id', $projectId)
            ->whereIn('pivot_role', $roles)->exists();
    }

    /**
     * Check if the user has a specific role for a given task & if he assigned to it
     * 
     * @param int $taskId 
     * @param string $role 
     * @return bool 
     */
    public function hasRoleForTask(int $taskId, string $role): bool
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
     * Check if the user is a tester for a given task.
     *
     * @param int $taskId 
     * @return bool 
     */
    public function isTesterInTask(int $taskId): bool
    {
        return $this->hasRoleForTask($taskId, 'tester');
    }

    /**
     * Check if the user is a tester in a given project.
     *
     * @param int $projectId 
     * @return bool 
     */
    public function isTesterInProject(int $projectId): bool
    {
        return $this->projects()
            ->where('id', $projectId)
            ->where('pivot_role', 'tester')->exists();
    }

    /**
     * Check if the user is a manager for a given task.
     *
     * @param int $taskId 
     * @return bool 
     */
    public function isManagerInTask(int $taskId): bool
    {
        return $this->hasRoleForTask($taskId, 'manager');
    }

    /**
     * Check if the user is a manager in a given project.
     *
     * @param int $projectId 
     * @return bool 
     */
    public function isManagerInProject(int $projectId): bool
    {
        return $this->projects()
            ->where('id', $projectId)
            ->where('pivot_role', 'manager')->exists();
    }

    /**
     * Check if the user is a developer for a given task.
     *
     * @param int $taskId 
     * @return bool 
     */
    public function isDeveloperInTask(int $taskId): bool
    {
        return $this->hasRoleForTask($taskId, 'developer');
    }

    /**
     * Check if the user is a developer in a given project.
     *
     * @param int $projectId 
     * @return bool 
     */
    public function isDeveloperInProject(int $projectId): bool
    {
        return $this->projects()
            ->where('id', $projectId)
            ->where('pivot_role', 'developer')->exists();
    }
}
