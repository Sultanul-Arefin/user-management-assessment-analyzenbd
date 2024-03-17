<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::query()
                ->where('created_by', auth()->user()->id)
                ->with(['created_by_user'])
                ->paginate();

        return UserResource::collection(
            $user
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateUserRequest $request)
    {
        try{
            $avatar = null;
            if($request->has('avatar'))
            {
                $file = $request->file('avatar');

                if ($file) {
                    $desired_path = "uploads/user/avatar/";
                    $image_name = rand(10000, 50000).'_'.time().'.'.$file->extension();
                    $file->storeAs($desired_path, $image_name, 'public');
                    $avatar = $desired_path.$image_name;
                }
            }
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
                'avatar' => $avatar,
                'created_by' => auth()->user()->id
            ]);
        } catch(Exception $e){
            throw new CustomException('Oops! something wrong, please try again', 422);
        }
        return apiResponse(
            data: $user,
            message: 'User Created Successfully',
            statusCode: 201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->avatar = $user->avatar ? env('APP_URL') . '/' . $user->avatar : null;
        return apiResponse(
            data: $user
        );
    }

    /**
     * Update the specified resource in storage.
     */
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

    /**
     * Remove the specified resource from storage.
     */
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
        $soft_deleted_users = User::onlyTrashed()->paginate();
        return UserResource::collection(
            $soft_deleted_users
        );
    }

    public function restore_soft_deleted_user($user_id)
    {
        $user = User::onlyTrashed()->findOrFail($user_id);
        $user->restore();
        return apiResponse(
            data: $user,
            message: "User Successfully Restored"
        );
    }

    public function permanent_delete_soft_deleted_user($user_id)
    {
        $user = User::onlyTrashed()->findOrFail($user_id);
        $user->forceDelete();
        return apiResponse(
            data: [],
            message: "User Permanently Deleted"
        );
    }
}
