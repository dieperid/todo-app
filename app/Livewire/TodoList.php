<?php

namespace App\Livewire;

use App\Models\Todo;
use Exception;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class TodoList extends Component
{
    use WithPagination;

    /**
     * Name of the Todo
     */
    #[Rule('required|min:3|max:50')]
    public $name;

    public $search;

    public $editingTodoId;

    #[Rule('required|min:3|max:50')]
    public $editingTodoName;

    /**
     * Function to create a Todo
     */
    public function create()
    {
        $validatedItem = $this->validateOnly('name');

        Todo::create($validatedItem);

        $this->reset('name');

        session()->flash('success', 'Todo has been created.');

        // Reset pagination to the first page
        $this->resetPage();
    }

    /**
     * Function to delete a Todo
     * @param int $todoId - ID of the Tod
     */
    public function delete($todoId)
    {
        try {
            Todo::findOrFail($todoId)->delete();
        } catch (Exception $e) {
            session()->flash('error', 'Failed to delete Todo');
        }
    }

    /**
     * Function to toggle the checkbox status of a Todo
     * @param int $todoId - ID of the Todo
     */
    public function toggle($todoId)
    {
        $todo = Todo::find($todoId);
        $todo->completed = !$todo->completed;
        $todo->save();
    }

    /**
     * Function to edit a Todo
     * @param int $todoId - ID of the Todo
     */
    public function edit($todoId)
    {
        $this->editingTodoId = $todoId;
        $this->editingTodoName = Todo::find($todoId)->name;
    }

    /**
     * Function to cancel an edit of a Todo
     */
    public function cancelEdit()
    {
        $this->reset('editingTodoId', 'editingTodoName');
    }

    /**
     * Function to update a Todo
     */
    public function update()
    {
        $this->validateOnly('editingTodoName');
        Todo::find($this->editingTodoId)->update(
            [
                'name' => $this->editingTodoName
            ]
        );

        $this->cancelEdit();
    }

    /**
     * Function to render the view of the component
     */
    public function render()
    {
        return view('livewire.todo-list', [
            'todos' => Todo::latest()->where('name', 'like', "%{$this->search}%")->paginate(5)
        ]);
    }
}
