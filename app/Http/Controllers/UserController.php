<?php

namespace Departur\Http\Controllers;

use Departur\Http\Requests\UserDestroyRequest;
use Departur\Http\Requests\UserStoreRequest;
use Departur\Http\Requests\UserUpdateRequest;
use Departur\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Departur\Http\Requests\UserStoreRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(UserStoreRequest $request)
    {
        User::create($request->all() + [
                'password' => bcrypt($request->input('password'))
            ]);

        return redirect('/users');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Departur\Http\Requests\UserUpdateRequest $request
     * @param \Departur\User $user
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        $user->fill($request->all());

        if ($request->has('password')) {
            $user->password = bcrypt($request->input('password'));
        }

        $user->save();

        return redirect('/users');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Departur\User $user
     * @param \Departur\Http\Requests\UserDestroyRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user, UserDestroyRequest $request)
    {
        $user->delete();
        return redirect('/users');
    }
}
