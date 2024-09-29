<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Exceptions\UserException;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Interfaces\UserServiceInterface;

class UserController extends Controller
{
    protected $userService;
    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
        // $this->authorizeResource(User::class, 'user');
    }

    public function index(Request $request)
    {
        return $this->userService->getAllUsers($request);
    }

    public function store(StoreUserRequest $request)
    {
        $fonction = $request->input('fonction');
        if (Gate::allows('create', [User::class, $fonction]));
        $data = $request->validated();
        $user = $this->userService->createUser($data);
        throw UserException::userCreated($user);
    }

    public function show(string $id)
    {
        $user = $this->userService->getUserById($id);
        return response()->json($user);
    }

    public function update(UpdateUserRequest $request, string $id)
    {
        $data = $request->validated();
        $updatedUser = $this->userService->updateUser($id, $data);
        throw UserException::userUpdated($updatedUser);
    }

    public function destroy(string $id)
    {
        $users = $this->userService->deleteUser($id);
        throw UserException::userDeleted($users);
    }
}