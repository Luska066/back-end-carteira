<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;

class UserController extends Controller {

    public function __construct() {
		$this->authorizeResource(User::class, 'user');
	}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, ) {

        $users = User::query();

        if(!empty($request->search)) {
			$users->where('name', 'like', '%' . $request->search . '%');
		}

        $users = $users->paginate(10);

        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('users.create', []);
    }

    public function profile() {
        $users = request()->user();
        return view('users.profile', compact('users'));
    }
    public function configuracaoPlataforma() {

        return view('users.configuracaoPlataforma', );
    }

    public function changePassword() {
        return view('users.changePassword', []);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, ) {

        $request->validate(["name" => "required", "email" => "required|unique:users,email", "password" => "required"]);

        try {

            $user = new User();
            $user->name = $request->name;
		$user->email = $request->email;
		$user->email_verified_at = $request->email_verified_at;
		$user->password = $request->password;
		$user->remember_token = $request->remember_token;
            $user->save();

            return redirect()->route('users.index', [])->with('success', __('User created successfully.'));
        } catch (\Throwable $e) {
            return redirect()->route('users.create', [])->withInput($request->input())->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\User $user
     *
     * @return \Illuminate\Http\Response
     */
    public function show(User $user,) {

        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\User $user
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user,) {

        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user,) {

        $request->validate(["name" => "required", "email" => "required|unique:users,email,$user->id", "password" => "required"]);

        try {
            $user->name = $request->name;
		$user->email = $request->email;
		$user->email_verified_at = $request->email_verified_at;
		$user->password = $request->password;
		$user->remember_token = $request->remember_token;
            $user->save();

            return redirect()->route('users.index', [])->with('success', __('User edited successfully.'));
        } catch (\Throwable $e) {
            return redirect()->route('users.edit', compact('user'))->withInput($request->input())->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\User $user
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user,) {

        try {
            $user->delete();

            return redirect()->route('users.index', [])->with('success', __('User deleted successfully'));
        } catch (\Throwable $e) {
            return redirect()->route('users.index', [])->with('error', 'Cannot delete User: ' . $e->getMessage());
        }
    }


}
