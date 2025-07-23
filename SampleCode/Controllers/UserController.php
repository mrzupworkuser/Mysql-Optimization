<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\Address;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response(
            UserResource::collection(
                User::latest()->get()
            )
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        $hasError = false;
        DB::beginTransaction();
        try {
            /** @var User */
            $user = User::create($request->validated());
            foreach ($request->get('address', []) as $address) {
                Address::on($user->db_connection)->create($address + ['user_id' => $user->id]);
            }

            DB::commit();
        } catch (\Throwable $exception) {
            DB::rollBack();
            $hasError = true;
        }

        if ($hasError) {
            return response("Error to create user", Response::HTTP_BAD_GATEWAY);
        }

        return response(new UserResource($user), Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return response(new UserResource($user));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, User $user)
    {
        $hasError = false;
        DB::beginTransaction();
        try {
            $user->update($request->validated());
            foreach ($request->get('address', []) as $address) {
                Address::on($user->db_connection)->create($address + ['user_id' => $user->id]);
            }
            DB::commit();
        } catch (\Throwable $exception) {
            DB::rollBack();
            $hasError = true;
        }

        if ($hasError) {
            return response("Error to update user", Response::HTTP_BAD_GATEWAY);
        }

        return response(new UserResource($user));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response('User deleted successfully!');
    }
}
