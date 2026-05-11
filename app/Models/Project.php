<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory;

    // The attributes related to the DB table.
    protected $fillable = [
        'name',
        'description'
    ];

    // Define the relationship with the Task model.
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    // Relationships for completed and incomplete tasks.
    public function completedTasks()
    {
        return $this->hasMany(Task::class)->where('is_completed', true);
    }

    //Get all incomplete tasks for a project.
    public function incompleteTasks()
    {
        return $this->hasMany(Task::class)->where('is_completed', false);
    }

    //Check if the project has any incomplete tasks and return a boolean value.
    public function hasIncompleteTasks():bool{
        return $this->incompleteTasks()->exists();
    }
}
