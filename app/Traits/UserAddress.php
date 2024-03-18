<?php

namespace App\Traits;

use App\Http\Resources\UserAddressResource;
use App\Models\User;
use App\Models\UserAddress as UserAddressModel;
use Illuminate\Http\Request;

trait UserAddress
{
    public function user_address(User $user)
    {
        return new UserAddressResource($user);
    }

    public function store_user_address(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'address' => 'required|min:5|max:255'
        ]);
        $user_address = UserAddressModel::create([
            'user_id' => $request->user_id,
            'address' => $request->address
        ]);
        return apiResponse(
            data: $user_address,
            message: 'Address Successfully Inserted',
            statusCode: 201
        );
    }

    public function update_user_address(UserAddressModel $user_address, Request $request)
    {
        $request->validate([
            'address' => 'required|min:5|max:255'
        ]);
        $user_address->update([
            'address' => $request->address
        ]);
        return apiResponse(
            data: $user_address,
        );
    }
    
    public function destroy_user_address(UserAddressModel $user_address)
    {
        $user_address->delete();
        return apiResponse(
            data: [],
            message: 'Address Successfully Deleted'
        );
    }
}
