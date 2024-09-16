<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

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
     * Check if the user is an admin.
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->type === 'admin';
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
     * Check if the user is a manager in the given project.
     * 
     * @param int $projectId
     * @return bool
     */
    public function isManager($projectId)
    {
        return $this->projects()
            ->wherePivot('role', 'manager')
            ->where('project_id', $projectId)
            ->exists();
    }

    /**
     * Check if the user is a tester in the given project.
     * 
     * @param int $projectId
     * @return bool
     */
    public function isTester($projectId)
    {
        return $this->projects()
            ->wherePivot('role', 'tester')
            ->where('project_id', $projectId)
            ->exists();
    }

    /**
     * Check if the user is a developer in the given project.
     * 
     * @param int $projectId
     * @return bool
     */
    public function isDeveloper($projectId)
    {
        return $this->projects()
            ->wherePivot('role', 'developer')
            ->where('project_id', $projectId)
            ->exists();
    }

    /**
     * Get tasks assigned to the user.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function tasks()
    {
        return $this->hasManyThrough(Task::class, Project::class)->where('assigned_to', $this->id);
    }
}
