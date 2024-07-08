<?php

namespace App\Livewire;

use App\Models\Todo;
use Illuminate\Http\Client\Request;
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

    public function create()
    {
        $validatedItem = $this->validateOnly('name');

        Todo::create($validatedItem);

        $this->reset('name');

        session()->flash('success', 'Todo has been created.');
    }

    public function render()
    {
        return view('livewire.todo-list', [
            'todos' => Todo::latest()->paginate(5)
        ]);
    }
}
