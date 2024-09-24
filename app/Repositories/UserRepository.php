<?php

namespace App\Repositories;

use App\Models\UserMysql;
use App\Events\UserCreated;
use App\Facades\UserFirebase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Services\LocalStorageService;
use App\Interfaces\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    protected $LocalStorageService;
    public function __construct(LocalStorageService $LocalStorageService)
    {
        $this->LocalStorageService = $LocalStorageService;
    }

    public function getAllUsers(array $filters)
    {
        $query = UserFirebase::query();
        if (!empty($filters['role'])) {
            $query->where('fonction', $filters['role']);
        }
        return $query->get();
    }

    public function createUser(array $data)
    {
        DB::beginTransaction();
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        $originalFileName = $data['photo']->getClientOriginalName();
        $localPath = $this->LocalStorageService->storeImageLocally('images/users', $originalFileName);
        $data['photo'] = $localPath;
        $userMysql = UserMysql::create($data);
        $firebaseUserId = UserFirebase::create($data);
        $data = UserFirebase::find($firebaseUserId);
        $userMysql->id = $firebaseUserId;
        $userMysql->save();
        DB::commit();
        event(new UserCreated($userMysql, $firebaseUserId));
        return $userMysql;
    }


    public function getUserById(string $id)
    {
        return UserFirebase::find($id);
    }

    public function updateUser(string $id, array $data): ?array
    {
        DB::beginTransaction();
        $userMysql = UserMysql::find($id);
        if ($userMysql) {
            $userMysql->update($data);
        }
        $userFirebase = UserFirebase::find($id);
        if ($userFirebase) {
            UserFirebase::update($id, $data);
        }
        DB::commit();
        return [
            'firebase' => UserFirebase::find($id),
        ];
    }

    public function deleteUser(string $id): bool
    {
        DB::beginTransaction();
        $deletedMysql = UserMysql::destroy($id);
        $deletedFirebase = UserFirebase::delete($id);
        DB::commit();
        return $deletedMysql && $deletedFirebase;
    }
}
