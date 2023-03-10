<?php

namespace App\Http\Controllers\Admin;

use Auth;
use App\Models\City;
use App\Models\User;
use App\Models\State;
use App\Models\Course;
use App\Models\Country;
use App\Models\Student;
use App\Traits\General;
use App\Models\Instructor;
use App\Models\Order_item;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Course_lesson;
use App\Models\Course_lecture;
use App\Traits\ImageSaveTrait;
use App\Tools\Repositories\Crud;
use App\Http\Controllers\Controller;
use App\Mail\InstructorApprovedMail;
use App\Models\Course_lecture_views;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class InstructorController extends Controller
{
    use General, ImageSaveTrait;

    protected $instructorModel, $studentModel;
    public function __construct(Instructor $instructor, Student $student)
    {
        $this->instructorModel = new Crud($instructor);
        $this->studentModel = new Crud($student);
    }

    public function index()
    {
        if (!Auth::user()->can('all_instructor')) {
            abort('403');
        } // end permission checking

        $data['title'] = 'All Instructors';
        $data['instructors'] = $this->instructorModel->getOrderById('DESC', 25);;
        return view('admin.instructor.index', $data);
    }

    public function view($uuid)
    {
        $data['title'] = 'Instructor Profile';
        $data['instructor'] = $this->instructorModel->getRecordByUuid($uuid);
        $userCourseIds = Course::whereUserId($data['instructor']->user->id)->pluck('id')->toArray();
        if (count($userCourseIds) > 0){
            $orderItems = Order_item::whereIn('course_id', $userCourseIds)
                ->whereYear("created_at", now()->year)->whereMonth("created_at", now()->month)
                ->whereHas('order', function ($q) {
                    $q->where('payment_status', 'paid');
                });
            $data['total_earning'] = $orderItems->sum('owner_balance');
        }

        return view('admin.instructor.view', $data);
    }

    public function pending()
    {
        if (!Auth::user()->can('pending_instructor')) {
            abort('403');
        } // end permission checking

        $data['title'] = 'Pending for Review';
        $data['instructors'] = Instructor::pending()->orderBy('id', 'desc')->paginate(25);
        return view('admin.instructor.pending', $data);
    }

    public function approved()
    {
        if (!Auth::user()->can('approved_instructor')) {
            abort('403');
        } // end permission checking

        $data['title'] = 'Approved Instructor';
        $data['instructors'] = Instructor::approved()->orderBy('id', 'desc')->paginate(25);
        return view('admin.instructor.approved', $data);
    }

    public function blocked()
    {
        if (!Auth::user()->can('approved_instructor')) {
            abort('403');
        } // end permission checking

        $data['title'] = 'Blocked Instructor';
        $data['instructors'] = Instructor::blocked()->orderBy('id', 'desc')->paginate(25);
        return view('admin.instructor.blocked', $data);
    }

    public function changeStatus($uuid, $status)
    {
        $instructor = $this->instructorModel->getRecordByUuid($uuid);
        $instructor->status = $status;
        $instructor->save();

        if ($status == 1)
        {
            $user = User::find($instructor->user_id);
            $user->role = 2;
            $user->save();

            $user = User::find($instructor->user_id);

            try {
                Mail::to($user->email)->send(new InstructorApprovedMail($user));
            }catch (\Exception $exception){
                toastrMessage('error', 'Something is wrong. Please contact with '. get_option('app_name') .' support team');
                return redirect()->back();
            }

        }

        $this->showToastrMessage('success', 'Status has been changed');
        return redirect()->back();
    }

    public function create()
    {
        $data['title'] = 'Add Instructor';
        $data['countries'] = Country::orderBy('country_name', 'asc')->get();

        if (old('country_id')) {
            $data['states'] = State::where('country_id', old('country_id'))->orderBy('name', 'asc')->get();
        }

        if (old('state_id')) {
            $data['cities'] = City::where('state_id', old('state_id'))->orderBy('name', 'asc')->get();
        }

        return view('admin.instructor.add', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6'],
            'professional_title' => 'required',
            'phone_number' => 'required',
            'address' => 'required',
            'gender' => 'required',
            'about_me' => 'required',
            'image' => 'mimes:jpeg,png,jpg|file|dimensions:min_width=300,min_height=300,max_width=300,max_height=300|max:1024'
        ]);

        $user = new User();
        $user->name = $request->first_name . ' '. $request->last_name;
        $user->email = $request->email;
        $user->email_verified_at = now();
        $user->password = Hash::make($request->password);
        $user->role = 2;
        $user->image =  $request->image ? $this->saveImage('user', $request->image, null, null) :   null;
        $user->save();

        $student_data = [
            'user_id' => $user->id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'address' => $request->address,
            'phone_number' => $request->phone_number,
            'country_id' => $request->country_id,
            'state_id' => $request->state_id,
            'city_id' => $request->city_id,
            'gender' => $request->gender,
            'about_me' => $request->about_me,
            'postal_code' => $request->postal_code,
        ];

        $this->studentModel->create($student_data);

        if (Instructor::where('slug', Str::slug($user->name))->count() > 0)
        {
            $slug = Str::slug($user->name) . '-'. rand(100000, 999999);
        } else {
            $slug = Str::slug($user->name);
        }

        $instructor_data = [
            'user_id' => $user->id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'address' => $request->address,
            'professional_title' => $request->professional_title,
            'phone_number' => $request->phone_number,
            'slug' => $slug,
            'status' => 1,
            'country_id' => $request->country_id,
            'state_id' => $request->state_id,
            'city_id' => $request->city_id,
            'gender' => $request->gender,
            'about_me' => $request->about_me,
            'postal_code' => $request->postal_code,
            'social_link' => json_encode($request->social_link),
        ];

        $this->instructorModel->create($instructor_data);

        $this->showToastrMessage('success', 'Instructor created successfully');
        return redirect()->route('instructor.index');
    }

    public function edit($uuid)
    {
        $data['title'] = 'Edit Instructor';
        $data['instructor'] = $this->instructorModel->getRecordByUuid($uuid);
        $data['user'] = User::findOrfail($data['instructor']->user_id);

        $data['countries'] = Country::orderBy('country_name', 'asc')->get();

        if (old('country_id'))
        {
            $data['states'] = State::where('country_id', old('country_id'))->orderBy('name', 'asc')->get();
        }

        if (old('state_id'))
        {
            $data['cities'] = City::where('state_id', old('state_id'))->orderBy('name', 'asc')->get();
        }

        return view('admin.instructor.edit', $data);
    }

    public function update(Request $request, $uuid)
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'professional_title' => 'required',
            'phone_number' => 'required',
            'address' => 'required',
            'gender' => 'required',
            'about_me' => 'required',
            'image' => 'mimes:jpeg,png,jpg|file|dimensions:min_width=300,min_height=300,max_width=300,max_height=300|max:1024'
        ]);

        $instructor = $this->instructorModel->getRecordByUuid($uuid);

        $user = User::findOrfail($instructor->user_id);
        if (User::where('id', '!=', $instructor->user_id)->where('email', $request->email)->count() > 0) {
            $this->showToastrMessage('warning', 'Email already exist');
            return redirect()->back();
        }

        $user->name = $request->first_name . ' '. $request->last_name;
        $user->email = $request->email;
        if ($request->password){
            $request->validate([
                'password' => 'required|string|min:6'
            ]);
            $user->password = Hash::make($request->password);
        }
        $user->image =  $request->image ? $this->saveImage('user', $request->image, null, null) :   $user->image;
        $user->save();

        if (Instructor::where('slug', Str::slug($user->name))->count() > 0)
        {
            $slug = Str::slug($user->name) . '-'. rand(100000, 999999);
        } else {
            $slug = Str::slug($user->name);
        }

        $instructor_data = [
            'user_id' => $user->id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'address' => $request->address,
            'professional_title' => $request->professional_title,
            'phone_number' => $request->phone_number,
            'slug' => $slug,
            'country_id' => $request->country_id,
            'state_id' => $request->state_id,
            'city_id' => $request->city_id,
            'gender' => $request->gender,
            'about_me' => $request->about_me,
            'postal_code' => $request->postal_code,
            'social_link' => json_encode($request->social_link),
        ];

        $this->instructorModel->updateByUuid($instructor_data, $uuid);

        $this->showToastrMessage('success', 'Updated Successfully');
        return redirect()->route('instructor.index');
    }

    public function delete($uuid)
    {
        if (!Auth::user()->can('manage_instructor')) {
            abort('403');
        } // end permission checking

        $instructor = $this->instructorModel->getRecordByUuid($uuid);
        $user = User::findOrfail($instructor->user_id);

        if ($instructor && $user){
            //Start:: Course Delete
            $courses = Course::whereUserId($user->id)->get();
            foreach ($courses as $course)
            {
                //start:: Course lesson delete
                $lessons = Course_lesson::where('course_id', $course->id)->get();
                if (count($lessons) > 0)
                {
                    foreach ($lessons as $lesson)
                    {
                        //start:: lecture delete
                        $lectures = Course_lecture::where('lesson_id', $lesson->id)->get();
                        if (count($lectures) > 0)
                        {
                            foreach ($lectures as $lecture)
                            {
                                $lecture = Course_lecture::find($lecture->id);
                                if ($lecture)
                                {
                                    $this->deleteFile($lecture->file_path); // delete file from server

                                    if ($lecture->type == 'vimeo')
                                    {
                                        if ($lecture->url_path)
                                        {
                                            $this->deleteVimeoVideoFile($lecture->url_path);
                                        }
                                    }

                                    Course_lecture_views::where('course_lecture_id', $lecture->id)->get()->map(function ($q) {
                                        $q->delete();
                                    });

                                    Course_lecture::find($lecture->id)->delete(); // delete lecture record
                                }
                            }
                        }
                        //end:: delete lesson record
                        Course_lesson::find($lesson->id)->delete();
                    }
                }
                //end

                $this->deleteFile($course->image);
                $this->deleteVideoFile($course->video);
                $course->delete();
            }
            //End:: Course Delete
        }
        $this->instructorModel->deleteByUuid($uuid);

        $user->role = 3;
        $user->save();

        $this->showToastrMessage('success', 'Instructor Deleted Successfully');
        return redirect()->back();
    }

    public function getStateByCountry($country_id)
    {
        return State::where('country_id', $country_id)->orderBy('name', 'asc')->get()->toJson();
    }

    public function getCityByState($state_id)
    {
        return City::where('state_id', $state_id)->orderBy('name', 'asc')->get()->toJson();
    }

    public function changeInstructorStatus(Request $request)
    {
        $instructor = Instructor::findOrFail($request->id);
        $instructor->status = $request->status;
        $instructor->save();

        return response()->json([
            'data' => 'success',
        ]);
    }
}
