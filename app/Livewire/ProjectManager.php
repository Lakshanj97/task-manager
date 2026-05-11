<?php

namespace App\Livewire;

use App\Models\Project;
use Livewire\Component;

class ProjectManager extends Component
{
    // Properties for creating a new project
    public string $name = '';
    public string $description = '';

    // Properties for editing a project
    public ?int $editingProjectId = null;
    public string $editName = '';
    public string $editDescription = '';

    // Properties for displaying messages
    public ?string $errorMessage = null;
    public ?string $successMessage = null;

    // Properties for project selection
    public ?int $selectedProjectId = null;

    // Create a new project
    public function createProject(): void
    {
        // Validate the input data
        $this->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ], [
            'name.required' => 'Project name is required.',
        ]);

        // Create the project in the database
        Project::create([
            'name'        => trim($this->name),
            'description' => trim($this->description),
        ]);

        // Reset the input fields
        $this->reset(['name', 'description']);

        // Set the success message
        $this->successMessage = 'Project created successfully!';
    }

    // Edit a project
    public function startEditing(int $projectId): void
    {
        // Find the project to be edited
        $project = Project::findOrFail($projectId);

        // Set the properties for editing
        $this->editingProjectId = $projectId;
        $this->editName         = $project->name;
        $this->editDescription  = $project->description ?? '';
        $this->errorMessage     = null;
        $this->successMessage   = null;
    }

    // Update a project
    public function updateProject(): void
    {
        // Validate the input data
        $this->validate([
            'editName'        => 'required|string|max:255',
            'editDescription' => 'nullable|string|max:1000',
        ], [
            'editName.required' => 'Project name is required.',
        ]);

        // Update the project in the database
        $project = Project::findOrFail($this->editingProjectId);
        $project->update([
            'name'        => trim($this->editName),
            'description' => trim($this->editDescription),
        ]);

        // Cancel the editing state
        $this->cancelEditing();

        // Set the success message
        $this->successMessage = 'Project updated successfully!';
    }

    // Cancel editing a project
    public function cancelEditing(): void
    {
        $this->reset(['editingProjectId', 'editName', 'editDescription']);
    }

    // Delete a project
    public function deleteProject(int $projectId): void
    {
        // Find the project to be deleted
        $project = Project::withCount([
            'tasks as incomplete_tasks_count' => fn($q) => $q->where('is_completed', false),
        ])->findOrFail($projectId);

        // Check if the project has any incomplete tasks
        if ($project->incomplete_tasks_count > 0) {
            $this->errorMessage   = 'Cannot delete project: There are uncompleted tasks. Please complete or remove all tasks first.';
            $this->successMessage = null;
            return;
        }

        // If the project is currently selected, deselect it
        if ($this->selectedProjectId === $projectId) {
            $this->selectedProjectId = null;
        }

        // Delete the project and its tasks
        $project->tasks()->delete();
        $project->delete();

        // Set the success message and clear any error message
        $this->errorMessage   = null;
        $this->successMessage = 'Project deleted successfully!';
    }

    // Select a project
    public function selectProject(int $projectId): void
    {
        $this->selectedProjectId = ($this->selectedProjectId === $projectId) ? null : $projectId;
        $this->errorMessage      = null;
        $this->successMessage    = null;
    }

    // Clear messages
    public function clearMessages(): void
    {
        $this->errorMessage   = null;
        $this->successMessage = null;
    }

    // Render the component
    public function render()
    {
        // Get all projects with counts of total and completed tasks
        $projects = Project::withCount([
            'tasks',
            'completedTasks as completed_tasks_count',
        ])->latest()->get();

        // Get the selected project if there is one
        $selectedProject = $this->selectedProjectId
            ? Project::query()->find($this->selectedProjectId)
            : null;

        // Render the view with the projects and selected project
        return view('livewire.project-manager', compact('projects', 'selectedProject'));
    }
}
