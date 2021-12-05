<?php

namespace App\Http\Livewire\Users;

use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class UserIndex extends Component
{
    public $search = '';
    public $username, $firstName, $lastName, $email, $password, $userId;
    public $editMode = false;

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
        session()->flash('message', 'User successfully created');
    }

    public function updateUser()
    {
        $validated = $this->validate([
            'username'  => 'required',
            'firstName' => 'required',
            'lastName'  => 'required',
            'email'     => 'required|email'
        ]);

        $user = User::find($this->userId);
        $user->update([
            'username'   => $validated['username'],
            'first_name' => $validated['firstName'],
            'last_name'  => $validated['lastName'],
            'email'      => $validated['email'],
        ]);

        $this->reset();
        $this->dispatchBrowserEvent('closeModal');
        session()->flash('message', 'User successfully updated');
    }

    public function showEditModal(User $user)
    {
        $this->reset();
        $this->editMode = true;
        $this->loadUser($user);
        $this->dispatchBrowserEvent('showModal');
    }

    public function loadUser(User $user)
    {
        $this->userId    = $user->id;
        $this->username  = $user->username;
        $this->firstName = $user->first_name;
        $this->lastName  = $user->last_name;
        $this->email     = $user->email;
    }

    public function closeModal()
    {
        $this->reset();
        $this->dispatchBrowserEvent('closeModal');
    }

    public function deleteUser(User $user)
    {
        $user->delete();
        $this->reset();

        session()->flash('message', 'User successfully deleted');
    }
}
