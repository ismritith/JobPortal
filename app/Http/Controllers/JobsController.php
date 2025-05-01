<?php

namespace App\Http\Controllers;

use App\Mail\JobNotificationEmail;
use App\Models\Category;
use App\Models\JobType;
use App\Models\Job;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\User;


class JobsController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::where('status', 1)->get();
        $jobTypes = JobType::where('status', 1)->get();

        $jobs = Job::where('status', 1);

        // Search using keywords
        if (!empty($request->keyword)) {
            $jobs = $jobs->where(function ($query) use ($request) {
                $query->orwhere('title', 'like', '%' . $request->keyword . '%');
                $query->orwhere('keywords', 'like', '%' . $request->keyword . '%');
            });
        }


        // Search using location
        if (!empty($request->location)) {
            $jobs = $jobs->where('location', $request->location);
        }

        // Search using category
        if (!empty($request->category)) {
            $jobs = $jobs->where('category_id', $request->category);
        }

        $jobTypeArray = [];
        // Search using jobType
        if (!empty($request->jobType)) {
            $jobTypeArray = explode(',', $request->jobType);

            $jobs = $jobs->whereIn('job_type_id', $jobTypeArray);
        }

        // Search using experience
        if (!empty($request->experience)) {
            $jobs = $jobs->where('experience', $request->experience);
        }

        $jobs = $jobs->with(['jobType', 'category']);

        if ($request->sort == 0) {
            $jobs = $jobs->orderBy('created_at', 'ASC');
        } else {
            $jobs = $jobs->orderBy('created_at', 'DESC');
        }

        $jobs = $jobs->paginate(9);

        return view('front.jobs', [
            'categories' => $categories,
            'jobTypes' => $jobTypes,
            'jobs' => $jobs,
            'jobTypeArray' => $jobTypeArray


        ]);
    }

    // This method will show job detaill page
    public function detail($id)
    {
        $categories = Category::all();
        $job = Job::where([
            'id' => $id,
            'status' => 1
        ])->with(['jobType', 'category'])->first();
        if ($job == null) {
            abort(404);
        }
        return view('front.jobDetail', ['job' => $job, 'category'=>$categories]);
    }

    public function applyJob(Request $request)
    {
        $id = $request->id;
        $job = Job::where('id', $id)->first();

        if (!$job) {
            return response()->json([
                'status' => false,
                'message' => 'Job does not exist.'
            ]);
        }

        if ($job->user_id == Auth::id()) {
            return response()->json([
                'status' => false,
                'message' => 'You cannot apply on your own job.'
            ]);
        }

        $alreadyApplied = JobApplication::where([
            'user_id' => Auth::id(),
            'job_id' => $id
        ])->exists();

        if ($alreadyApplied) {
            return response()->json([
                'status' => false,
                'message' => 'You have already applied on this job.'
            ]);
        }

        $application = new JobApplication();
        $application->job_id = $id;
        $application->user_id = Auth::id();
        $application->employer_id = $job->user_id;
        $application->applied_date = now();
        $application->save();

        // Send Notification Email to Employer
        $employer = User::where('id', $job->user_id)->first();
        $mailData = [
            'employer' => $employer,
            'user' => Auth::user(),
            'job' => $job,
        ];
        // Mail::to($employer->email)->send(new JobNotificationEmail($mailData));

        return response()->json([
            'status' => true,
            'message' => 'You have successfully applied.'
        ]);
    }
}
