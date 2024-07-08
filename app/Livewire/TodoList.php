<?php

namespace App\Livewire;

use App\Models\Todo;
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

    /**
     * Function to create a Todo
     */
    public function create()
    {
        $validatedItem = $this->validateOnly('name');

        Todo::create($validatedItem);

        $this->reset('name');

        session()->flash('success', 'Todo has been created.');
    }

    /**
     * Function to delete a Todo
     */
    public function delete($todoId)
    {
        Todo::find($todoId)->delete();
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
