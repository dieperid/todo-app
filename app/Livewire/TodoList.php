<?php

namespace App\Livewire;

use App\Models\Todo;
use Livewire\Attributes\Rule;
use Livewire\Component;

class TodoList extends Component
{
    /**
     * Name of the Todo
     */
    #[Rule('required|min:3|max:50')]
    public $name;

    public $search;

    public function create()
    {
        $validatedItem = $this->validateOnly('name');

        Todo::create($validatedItem);

        $this->reset('name');

        session()->flash('success', 'Todo has been created');
    }

    public function render()
    {
        return view('livewire.todo-list');
    }
}
