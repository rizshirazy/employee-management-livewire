<?php

namespace App\Http\Livewire\Users;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class UserIndex extends Component
{
    public $search = '';
    public $username, $firstName, $lastName, $email, $password;

    protected $queryString  = [
        'search' => ['except' => '']
    ];

    protected $rules = [
        'username'  => 'required',
        'firstName' => 'required',
        'lastName'  => 'required',
        'password'  => 'required',
        'email'     => 'required|email'
    ];

    public function render()
    {
        return view('livewire.users.user-index', [
            'users' => User::where('username', 'like', "%{$this->search}%")->get()
        ])
            ->layout('layouts.main');
    }

    public function storeUser()
    {
        $this->validate();

        User::create([
            'username'   => $this->username,
            'first_name' => $this->firstName,
            'last_name'  => $this->lastName,
            'email'      => $this->email,
            'password'   => Hash::make($this->password),
        ]);

        $this->reset();
        $this->dispatchBrowserEvent('closeModal');
    }
}
