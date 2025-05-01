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

        // Search using keywords, sodheko matrai
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
        return view('front.jobDetail', ['job' => $job, 'category' => $categories]);
    }

    public function applyJob(Request $request)
    {
        $jobId = $request->job_id;
        $userId = Auth::id();

        // Validate the incoming request
        $request->validate([
            'email' => 'required|email',
            'phone' => 'required|string',
            'resume' => 'required|file|mimes:pdf,doc,docx|max:2048', // Adjust max size as needed
        ]);

        // Check if the job exists
        $job = Job::find($jobId);
        if (!$job) {
            return response()->json([
                'status' => false,
                'message' => 'Job does not exist.'
            ]);
        }

        // Check if the user is trying to apply to their own job
        if ($job->user_id == $userId) {
            return response()->json([
                'status' => false,
                'message' => 'You cannot apply to your own job.'
            ]);
        }

        // Check if the user has already applied
        $existingApplication = JobApplication::where('job_id', $jobId)
            ->where('user_id', $userId)
            ->exists();

        if ($existingApplication) {
            return response()->json([
                'status' => false,
                'message' => 'You have already applied to this job.'
            ]);
        }

        // Handle file upload
        $resumePath = $request->file('resume')->store('resumes', 'public');

        // Create a new application
        $application = JobApplication::create([
            'job_id' => $jobId,
            'user_id' => $userId,
            'username' => Auth::user()->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'employer_id' => $job->user_id,
            'resume' => $resumePath,
            'job_title' => $job->title,
            'company_name' => $job->company_name,
            'location' => $job->location,
            'salary' => $job->salary,
            'status' => 'pending',
            'applied_date' => now()
        ]);
        $application->save();

        return response()->json([
            'status' => true,
            'message' => 'You have successfully applied to the job.'
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $application = JobApplication::find($id);

        if (!$application) {
            return response()->json([
                'status' => false,
                'message' => 'Application not found.',
            ]);
        }

        // Update the status
        $application->status = $request->status;
        $application->save();

        return response()->json([
            'status' => true,
            'message' => 'Application status updated successfully.',
        ]);
    }
}
