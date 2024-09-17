<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectUser extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'project_user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'project_id',
        'user_id',
        'role',
        'contribution_hours',
        'last_activity',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'last_activity' => 'datetime',
    ];

    /**
     * Define the relationship to the Project model.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Define the relationship to the User model.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Update contribution_hours & last_activity attributes
     * 
     * Calculate all task hours for a user in a specific project
     * Get the latest activity from tasks assigned to the user
     * Then update the pivot table with the new data
     * @return void
     */
    public function updateTableData()
    {
        $contributionHours = Task::where('project_id', $this->project_id)
            ->where('assigned_to', $this->user_id)
            ->sum('hours');

        $lastActivity = Task::where('project_id', $this->project_id)
            ->where('assigned_to', $this->user_id)
            ->latest('updated_at')
            ->value('updated_at') ?? now();

        $this->update([
            'contribution_hours' => $contributionHours,
            'last_activity' => $lastActivity,
        ]);
    }
}
