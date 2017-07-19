<?php

namespace App\Http\Controllers\Auth;

use App\Admin;
use App\Teacher;
use App\User;
use App\Mark;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Auth\Events\Registered;
use DB;

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
    protected $redirectTo = '/home';

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

    private function registerStudent(Request $request, $user)
    {
        try {
            $year = $request->input('year');
            $class = $request->input('class');
            $section = $request->input('section');
            $csy = DB::table('class_section_year')
                ->where('class_id', $class)
                ->where('section_id', $section)
                ->where('year_id', $year)
                ->first();

            // Existence Check
            $existingRolls = DB::table('student_roll')
                ->where('class_section_year_id', $csy->id)
                ->get();
            $notIn = [];
            foreach ($existingRolls as $existingRoll) {
                $notIn[] = ('"' . $existingRoll->roll . '"');
            }
            $notIn = implode(',', $notIn);

            $this->validate($request, [
                'roll' => ('not_in:' . $notIn)
            ]);

            // Continue to chain insertion
            DB::table('students')
                ->insert([
                    'guardian_occupation' => 'abcd',
                    'guardian_occupation_detail' => 'abcd',
                    'id' => $user->id
                ]);

            $roll = $request->input('roll');
            $stdRollId = DB::table('student_roll')
                ->insertGetId([
                    'student_id' => $user->id,
                    'class_section_year_id' => $csy->id,
                    'roll' => $roll
                ]);

            $subTeachers = DB::table('subject_teacher')
                ->where('class_section_year_id', $csy->id)
                ->get();

            $subTeaStudents = [];
            foreach ($subTeachers as $subTeacher) {
                $subTeaStudents[] = DB::table('subject_teacher_student')
                    ->insertGetId([
                        'subject_teacher_id' => $subTeacher->id,
                        'student_roll_id' => $stdRollId,
                        'is_compulsory' => 1
                    ]);
            }

            $classSectionYearTerms = DB::table('class_section_year_term')
                ->where('class_section_year_id', $csy->id)
                ->get();
            $subTeaStdPortions = DB::table('student_roll')
                ->join('subject_teacher_student', 'student_roll.id', 'student_roll_id')
                ->join(
                    'subject_teacher',
                    'subject_teacher.id',
                    'subject_teacher_student.subject_teacher_id'
                )->join(
                    'subject_teacher_portion',
                    'subject_teacher.id',
                    'subject_teacher_portion.subject_teacher_id'
                )->select(
                    'subject_teacher_portion.id as subject_teacher_portion_id',
                    'subject_teacher_student.id as subject_teacher_student_id'
                )->where('student_roll.id', $stdRollId)->get();

            foreach ($classSectionYearTerms as $classSectionYearTerm) {
                foreach ($subTeaStdPortions as $subTeaStdPortion) {
                    $mark = new Mark;
                    $mark->subject_teacher_portion_id =
                        $subTeaStdPortion->subject_teacher_portion_id;
                    $mark->subject_teacher_student_id =
                        $subTeaStdPortion->subject_teacher_student_id;
                    $mark->class_section_year_term_id =
                        $classSectionYearTerm->id;
                    $mark->mark = -2;
                    $mark->editor_id = $user->id;
                    $mark->save();
                }
            }
        } catch (\Exception $e) {
            $user->delete();
            throw $e;
        }
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());
    }
}
