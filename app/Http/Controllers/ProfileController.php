<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProfileRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function store(StoreProfileRequest $request)
    {
        $user_id = Auth::user()->id;
        $validatedData = $request->validated();
        $validatedData['user_id'] = $user_id;
        $profile = Profile::create($validatedData);
        return response()->json([
            'message' => 'profile created successfully',
            'profile' => $profile
        ], 201);
    }
    public function show($id)
    {
        $user_id = Auth::user()->id;
        $profile = Profile::where('user_id', $id)->firstOrFail();
        if ($profile->user_id != $user_id)
            return response()->json(
                [
                    'message' => 'Unauthorized'
                ],
                403
            );
        return response()->json(['profile' => $profile], 200);
    }

    public function update(UpdateProfileRequest $request, $id)
    {
        $user_id = Auth::user()->id;
        $profile = Profile::where('user_id', $id)->firstOrFail();
        if ($profile->user_id != $user_id)
            return response()->json(
                [
                    'message' => 'Unauthorized'
                ],
                403
            );
        $profile->update($request->validated());
        return response()->json($profile, 200);
    }
}
