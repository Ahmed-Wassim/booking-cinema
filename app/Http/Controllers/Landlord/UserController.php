<?php

namespace App\Http\Controllers\Landlord;

use App\Domain\Landlord\DTO\UserDTO;
use App\Domain\Landlord\Services\Interfaces\IUserService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Landlord\StoreUserRequest;
use App\Http\Requests\Landlord\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(
        protected IUserService $userService
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = $this->userService->listAllUsers();

        return view('landlord.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('landlord.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $this->userService->storeUser((array) UserDTO::fromRequest($request->validated()));

        return redirect()->route('landlord.users.index')->with('success', 'User created successfully');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);

        return view('landlord.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, string $id)
    {
        $this->userService->updateUser((array) UserDTO::fromRequest($request->validated()), (int) $id);

        return redirect()->route('landlord.users.index')->with('success', 'User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->userService->deleteUser((int) $id);

        return redirect()->route('landlord.users.index')->with('success', 'User deleted successfully');
    }
}
