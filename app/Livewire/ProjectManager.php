<?php

namespace App\Livewire;

use App\Models\Project;
use Livewire\Component;

class ProjectManager extends Component
{

    // View state
    public bool $viewingTasks = false;

    // Modal visibility
    public bool $showAddModal  = false;
    public bool $showEditModal = false;
    public bool $showTaskModal = false;

    // Add form fields
    public string $name        = '';
    public string $description = '';

    // Edit form fields
    public ?int   $editingProjectId = null;
    public string $editName         = '';
    public string $editDescription  = '';

    // Selected project for task modal
    public ?int $selectedProjectId = null;

    // Alert messages
    public ?string $errorMessage   = null;
    public ?string $successMessage = null;

    public function openTaskView(int $projectId): void
    {
        $this->selectedProjectId = $projectId;
        $this->viewingTasks = true;
        $this->clearMessages();
    }

    /**
     * Back to projects List
     */
    public function backToProjects(): void
    {
        $this->viewingTasks = false;
        $this->selectedProjectId = null;
    }



    // Open Add modal
    public function openAddModal(): void
    {
        $this->reset(['name', 'description']);
        $this->resetErrorBag();
        $this->showAddModal = true;
    }

    public function closeAddModal(): void
    {
        $this->showAddModal = false;
        $this->reset(['name', 'description']);
    }

    // Create project
    public function createProject(): void
    {
        $this->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ], [
            'name.required' => 'Project name is required.',
        ]);

        // Duplicate name check (case-insensitive)
        $exists = Project::whereRaw('LOWER(name) = ?', [strtolower(trim($this->name))])->exists();
        if ($exists) {
            $this->addError('name', 'A project with this name already exists.');
            return;
        }

        Project::create([
            'name'        => trim($this->name),
            'description' => trim($this->description),
        ]);

        $this->closeAddModal();
        $this->successMessage = 'Project created successfully!';
    }

    // Open Edit modal
    public function openEditModal(int $projectId): void
    {
        $project = Project::findOrFail($projectId);

        $this->editingProjectId = $projectId;
        $this->editName         = $project->name;
        $this->editDescription  = $project->description ?? '';

        $this->resetErrorBag();
        $this->showEditModal = true;
    }

    public function closeEditModal(): void
    {
        $this->showEditModal = false;
        $this->reset(['editingProjectId', 'editName', 'editDescription']);
    }

    // Update Project
    public function updateProject(): void
    {
        $this->validate([
            'editName'        => 'required|string|max:255',
            'editDescription' => 'nullable|string|max:1000',
        ], [
            'editName.required' => 'Project name is required.',
        ]);

        // Duplicate check — exclude self
        $exists = Project::whereRaw('LOWER(name) = ?', [strtolower(trim($this->editName))])
            ->where('id', '!=', $this->editingProjectId)
            ->exists();

        if ($exists) {
            $this->addError('editName', 'A project with this name already exists.');
            return;
        }

        $project = Project::findOrFail($this->editingProjectId);
        $project->update([
            'name'        => trim($this->editName),
            'description' => trim($this->editDescription),
        ]);

        $this->closeEditModal();
        $this->successMessage = 'Project updated successfully!';
    }

    // Delete Project
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
            $this->showTaskModal     = false;
        }

        $project->tasks()->delete();
        $project->delete();

        $this->errorMessage   = null;
        $this->successMessage = 'Project deleted successfully!';
    }

    // Open Task Modal
    public function openTaskModal(int $projectId): void
    {
        $this->selectedProjectId = $projectId;
        $this->showTaskModal     = true;
    }

    public function closeTaskModal(): void
    {
        $this->showTaskModal     = false;
        $this->selectedProjectId = null;
    }

    // Clear messages
    public function clearMessages(): void
    {
        $this->errorMessage   = null;
        $this->successMessage = null;
    }

    // Render
    public function render()
    {
        $projects = Project::withCount([
            'tasks',
            'completedTasks as completed_tasks_count',
        ])->latest()->get();

        $selectedProject = $this->selectedProjectId
            ? Project::find($this->selectedProjectId)
            : null;

        return view('livewire.project-manager', compact('projects', 'selectedProject'));
    }
}
