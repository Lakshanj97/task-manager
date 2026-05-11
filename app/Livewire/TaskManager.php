<?php

namespace App\Livewire;

use App\Models\Task;
use App\Models\Project;
use Livewire\Component;
use Livewire\WithPagination;

class TaskManager extends Component
{
    use WithPagination;

    /** @var Project */

    // Selected project
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

    // Create a new task for the selected project
    public function createTask(): void
    {
        // Validate the new task title
        $this->validateOnly('newTaskTitle');

        // Create the new task associated with the current project ( Use the relationship to create the task in Project Model)
        $this->project->tasks()->create([
            'title'        => trim($this->newTaskTitle),
            'is_completed' => false,
        ]);

        $this->newTaskTitle   = '';
        $this->successMessage = 'Task added!';
        $this->errorMessage   = null;
        $this->resetPage();
    }

    // Toggle the completion status of a task
   public function toggleTask(int $taskId): void
    {
        $task = $this->project->tasks()->find($taskId);

        if ($task) {
            // Explicitly toggle the boolean value
            $task->is_completed = !$task->is_completed;
            $task->save();

            $this->successMessage = $task->is_completed ? 'Task marked as completed!' : 'Task marked as pending!';
        }
    }

    // Start editing a task
    public function startEditingTask(int $taskId): void
    {
        // Find the task to be edited
        /** @var Task|null $task */
        $task = $this->project->tasks()->find($taskId);

        // If the task exists, set the editing properties
        if ($task) {
            $this->editingTaskId  = $taskId;
            $this->editTaskTitle  = $task->title;
            $this->errorMessage   = null;
            $this->successMessage = null;
        }
    }

    // Update a task
    public function updateTask(): void
    {
        $this->validateOnly('editTaskTitle');

        /** @var Task|null $task */
        $task = $this->project->tasks()->find($this->editingTaskId);

        if ($task) {
            $task->update(['title' => trim($this->editTaskTitle)]);
        }

        // Cancel editing state
        $this->cancelEditingTask();

        // Set the success message
        $this->successMessage = 'Task updated!';
    }

    // Cancel editing a task
    public function cancelEditingTask(): void
    {
        $this->reset(['editingTaskId', 'editTaskTitle']);
    }

    // Delete a task
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


    // Clear messages
    public function clearMessages(): void
    {
        $this->errorMessage   = null;
        $this->successMessage = null;
    }

    // Render the tasks for the selected project
    public function render()
    {
        // Paginate පාවිච්චි කර 10 බැගින් පෙන්වීම
        $tasks = $this->project->tasks()
            ->orderBy('is_completed', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.task-manager', compact('tasks'));
    }
}
