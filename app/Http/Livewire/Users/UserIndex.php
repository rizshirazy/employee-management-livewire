<?php

namespace App\Http\Livewire\Users;

use App\Models\User;
use Livewire\Component;

class UserIndex extends Component
{
    public $search = '';

    protected $queryString  = [
        'search' => ['except' => '']
    ];

    public function render()
    {
        return view('livewire.users.user-index', [
            'users' => User::where('username', 'like', "%{$this->search}%")->get()
        ])
            ->layout('layouts.main');
    }
}
