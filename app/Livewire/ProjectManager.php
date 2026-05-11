<?php

namespace App\Livewire;

use App\Models\Project;
use Livewire\Component;

class ProjectManager extends Component
{
    public string $name = '';
    public string $description = '';

    public ?int $editingProjectId = null;
    public string $editName = '';
    public string $editDescription = '';

    public ?string $errorMessage = null;
    public ?string $successMessage = null;

    public ?int $selectedProjectId = null;

    public function createProject(): void
    {
        $this->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ], [
            'name.required' => 'Project name is required.',
        ]);

        Project::create([
            'name'        => trim($this->name),
            'description' => trim($this->description),
        ]);

        $this->reset(['name', 'description']);
        $this->successMessage = 'Project created successfully!';
    }

    public function startEditing(int $projectId): void
    {
        $project = Project::findOrFail($projectId);
        $this->editingProjectId = $projectId;
        $this->editName         = $project->name;
        $this->editDescription  = $project->description ?? '';
        $this->errorMessage     = null;
        $this->successMessage   = null;
    }

    public function updateProject(): void
    {
        $this->validate([
            'editName'        => 'required|string|max:255',
            'editDescription' => 'nullable|string|max:1000',
        ], [
            'editName.required' => 'Project name is required.',
        ]);

        $project = Project::findOrFail($this->editingProjectId);
        $project->update([
            'name'        => trim($this->editName),
            'description' => trim($this->editDescription),
        ]);

        $this->cancelEditing();
        $this->successMessage = 'Project updated successfully!';
    }

    public function cancelEditing(): void
    {
        $this->reset(['editingProjectId', 'editName', 'editDescription']);
    }

    public function deleteProject(int $projectId): void
    {
        $project = Project::withCount([
            'tasks as incomplete_tasks_count' => fn($q) => $q->where('is_completed', false),
        ])->findOrFail($projectId);

        if ($project->incomplete_tasks_count > 0) {
            $this->errorMessage   = 'Cannot delete project: There are uncompleted tasks. Please complete or remove all tasks first.';
            $this->successMessage = null;
            return;
        }

        if ($this->selectedProjectId === $projectId) {
            $this->selectedProjectId = null;
        }

        $project->tasks()->delete();
        $project->delete();

        $this->errorMessage   = null;
        $this->successMessage = 'Project deleted successfully!';
    }

    public function selectProject(int $projectId): void
    {
        $this->selectedProjectId = ($this->selectedProjectId === $projectId) ? null : $projectId;
        $this->errorMessage      = null;
        $this->successMessage    = null;
    }

    public function clearMessages(): void
    {
        $this->errorMessage   = null;
        $this->successMessage = null;
    }

    public function render()
    {
        $projects = Project::withCount([
            'tasks',
            'completedTasks as completed_tasks_count',
        ])->latest()->get();

        $selectedProject = $this->selectedProjectId
            ? Project::query()->find($this->selectedProjectId)
            : null;

        return view('livewire.project-manager', compact('projects', 'selectedProject'));
    }
}
