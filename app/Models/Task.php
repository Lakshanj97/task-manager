<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
   use HasFactory;

    // The attributes related to the "tasks" table in DB.
    protected $fillable = [
        'project_id',
        'title',
        'is_completed'
    ];

    // Define the relationship with the Project model.
    public function project(): BelongsTo {
        return $this->belongsTo(Project::class);
    }
}
