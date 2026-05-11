<?php

namespace App\Livewire;

use App\Models\Task;
use App\Models\Project;
use Livewire\Component;

class TaskManager extends Component
{
    /** @var Project */
    public Project $project;

    // New task form
    public string $newTaskTitle = '';

    // Edit state
    public ?int $editingTaskId = null;
    public string $editTaskTitle = '';

    // Messages
    public ?string $errorMessage = null;
    public ?string $successMessage = null;

    protected $listeners = ['projectSelected' => 'refreshTasks'];

    public function mount(Project $project): void
    {
        $this->project = $project;
    }

    protected function rules(): array
    {
        return [
            'newTaskTitle'  => 'required|string|max:255',
            'editTaskTitle' => 'required|string|max:255',
        ];
    }

    protected function messages(): array
    {
        return [
            'newTaskTitle.required'  => 'Task title cannot be empty.',
            'editTaskTitle.required' => 'Task title cannot be empty.',
        ];
    }

    public function createTask(): void
    {
        $this->validateOnly('newTaskTitle');

        $this->project->tasks()->create([
            'title'        => trim($this->newTaskTitle),
            'is_completed' => false,
        ]);

        $this->newTaskTitle   = '';
        $this->successMessage = 'Task added!';
        $this->errorMessage   = null;
    }

    public function toggleTask(int $taskId): void
    {
        /** @var Task|null $task */
        $task = $this->project->tasks()->find($taskId);

        if ($task) {
            $task->update(['is_completed' => !$task->is_completed]);
        }
    }

    public function startEditingTask(int $taskId): void
    {
        /** @var Task|null $task */
        $task = $this->project->tasks()->find($taskId);

        if ($task) {
            $this->editingTaskId  = $taskId;
            $this->editTaskTitle  = $task->title;
            $this->errorMessage   = null;
            $this->successMessage = null;
        }
    }

    public function updateTask(): void
    {
        $this->validateOnly('editTaskTitle');

        /** @var Task|null $task */
        $task = $this->project->tasks()->find($this->editingTaskId);

        if ($task) {
            $task->update(['title' => trim($this->editTaskTitle)]);
        }

        $this->cancelEditingTask();
        $this->successMessage = 'Task updated!';
    }

    public function cancelEditingTask(): void
    {
        $this->reset(['editingTaskId', 'editTaskTitle']);
    }

    public function deleteTask(int $taskId): void
    {
        // Store the task in a variable
        $task = $this->project->tasks()->find($taskId);

        if ($task) {
            // Then call delete on the instance
            $task->delete();
        }

        $this->successMessage = 'Task deleted!';
        $this->errorMessage   = null;
    }
    public function clearMessages(): void
    {
        $this->errorMessage   = null;
        $this->successMessage = null;
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function render()
    {
        $tasks = $this->project->tasks()
            ->orderBy('is_completed', 'asc')
            ->orderBy('created_at', 'asc')
            ->get();

        return view('livewire.task-manager', compact('tasks'));
    }
}
