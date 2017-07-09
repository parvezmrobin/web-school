<?php

namespace App\Http\Controllers\Auth;

use App\Admin;
use App\Teacher;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Auth\Events\Registered;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/links';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name' => 'required|max:255',
            'last_name' => 'required:max255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'fathers_name' => $data['fathers_name'],
            'mothers_name' => $data['mothers_name'],
            'address' => $data['address'],
            'sex_id' => $data['sex'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    protected function registered(Request $request, $user)
    {
        $role = $request->input('role');
        if ($role == 1) {
            return $this->registerStudent($request, $user);
        } else if ($role == 2) {
            return $this->registerTeacher($request, $user);
        } else if($role == 3) {
            return $this->registerAdmin($request, $user);
        }
    }

    private function registerTeacher($request, $user)
    {
        $teacher = new Teacher();
        $teacher->designation_id = is_null($request->input('designation_id')) ?
            2 : $request->input('designation_id');
        $teacher->qualification = is_null($request->input('qualification')) ?
            2 : $request->input('qualification');
        $teacher->id = $user->id;
        $teacher->save();
    }

    private function registerAdmin($request, $user)
    {
        $admin = new Admin();
        $admin->id = $user->id;
        $admin->save();
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());
    }
}
