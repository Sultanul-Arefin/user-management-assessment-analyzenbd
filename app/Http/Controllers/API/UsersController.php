<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Http\Services\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function __construct(
        public UserRepositoryInterface $userRepo
    )
    {
    }

    public function index()
    {
        return UserResource::collection(
            $this->userRepo->allWithSearch(
                ['*'],
                ['created_by_user']
            )
        );
    }

    public function store(CreateUserRequest $request)
    {
        try{
            $avatar = $this->get_avatar($request);
            $request->request->add([
                'created_by' => auth()->user()->id,
                'avatar' => $avatar // changing the image key to 'avatar' for mass assignment
            ]);
            $user = $this->userRepo->create(
                $request->only([
                    'name',
                    'email',
                    'password',
                    'avatar',
                    'created_by'
                ])
            );
        } catch(Exception $e){
            throw new CustomException('Oops! something wrong, please try again', 422);
        }
        return apiResponse(
            data: $user,
            message: 'User Created Successfully',
            statusCode: 201
        );
    }

    private function get_avatar($request)
    {
        $avatar = null;
        if($request->has('user_avatar'))
        {
            $file = $request->file('user_avatar');

            if ($file) {
                $desired_path = "uploads/user/avatar/";
                $image_name = rand(10000, 50000).'_'.time().'.'.$file->extension();
                $file->storeAs($desired_path, $image_name, 'public');
                $avatar = $desired_path.$image_name;
            }
        }
        return $avatar;
    }

    public function show(User $user)
    {
        $user->avatar = $user->avatar ? env('APP_URL') . '/' . $user->avatar : null;
        return apiResponse(
            data: $user
        );
    }

    public function update(User $user, UpdateUserRequest $request)
    {
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password
        ]);
        $user->avatar = $user->avatar ? env('APP_URL') . '/' . $user->avatar : null;

        return apiResponse(
            data: $user,
            message: "User Successfully Updated"
        );
    }

    public function destroy(User $user, Request $request)
    {
        $user->delete();

        return apiResponse(
            data: [],
            message: "User Successfully Deleted"
        );
    }

    public function soft_deleted_users()
    {
        return UserResource::collection(
            $this->userRepo->getTrashedUser(
                ['*'],
                ['created_by_user']
            )
        );
    }

    public function restore_soft_deleted_user($user_id)
    {
        $user = $this->userRepo->findByIdTrashedUser(
            $user_id,
        );
        $user->restore();
        return apiResponse(
            data: $user,
            message: "User Successfully Restored"
        );
    }

    public function permanent_delete_soft_deleted_user($user_id)
    {
        $user = $this->userRepo->findByIdTrashedUser(
            $user_id,
        );
        $user->forceDelete();
        return apiResponse(
            data: [],
            message: "User Permanently Deleted"
        );
    }
}
