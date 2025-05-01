<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JobApplication;
use App\models\Job;
use App\models\Category;
use App\models\JobType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class JobController extends Controller
{
    public function index()
    {
        $jobs = Job::orderBy('created_at', 'DESC')->with('user', 'applications')->paginate(10);
        return view('admin.jobs.list', [
            'jobs' => $jobs
        ]);
    }

    public function edit($id)
    {
        $job = job::findOrFail($id);

        $categories = Category::orderBy('name', 'ASC')->get();
        $jobTypes = JobType::orderBy('name', 'ASC')->get();


        return view('admin.jobs.edit', [
            'job' => $job,
            'categories' => $categories,
            'jobTypes' => $jobTypes,
        ]);
    }

    public function update(Request $request, $id)

    {
        $rules = [
            'title' => 'required|min:5|max:200',
            'category' => 'required|exists:categories,id',
            'jobType' => 'required|exists:job_types,id',
            'vacancy' => 'required|integer',
            'location' => 'required|max:50',
            'description' => 'required',
            'experience' => 'required|in:1,2,3,4,5,6,7,8,9,10,10_plus',
            'company_name' => 'required|min:3|max:75',

        ];

        $validator = Validator::make($request->all(), $rules);


        if ($validator->passes()) {

            $job = Job::find($id);
            $job->title = $request->title;
            $job->category_id = $request->category;
            $job->job_type_id = $request->jobType;
            $job->vacancy = $request->vacancy;
            $job->salary = $request->salary;
            $job->location = $request->location;
            $job->description = $request->description;
            $job->benefits = $request->benefits;
            $job->responsibility = $request->responsibility;
            $job->qualifications = $request->qualifications;
            $job->keywords = $request->keywords;
            $job->experience = $request->experience;
            $job->company_name = $request->company_name;
            $job->company_location = $request->company_location;
            $job->company_website = $request->company_website;

            $job->status = $request->status;
            $job->isFeatured = (!empty($request->isFeatured)) ? $request->isFeatured : 0;

            // Dump the updated job data before saving
            //dd($job->toArray());

            $job->save();

            session()->flash('success', 'Job updated successfully.');
            session()->flash('updated_job_title', $job->title);

            return redirect()->route('admin.jobs');
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function destroy(Request $request)
    {
        $id = $request->id;

        $job = Job::find($id);

        if ($job == null) {
            session()->flash('error', 'Either job deleted or not found');
            return response()->json([
                'status' => false
            ]);
        }

        $job->delete();
        session()->flash('success', 'Job deleted successfully.');
        return response()->json([
            'status' => true
        ]);
    }

    // public function applications($id)
    // {
    //     if (Auth::user()->role !== 'admin' && Auth::user()->role !== 'superadmin') {
    //         abort(403, 'Unauthorized action.');
    //     }
    //     $job = Job::with(['applications.user', 'applications' => function($query) {
    //         $query->orderBy('created_at', 'DESC');
    //     }])->findOrFail($id);

    //     return view('admin.jobs.applications', compact('job'));
    // }

    public function applications()
    {
        $user = Auth::user();

        // Base query
        $query = JobApplication::query();

        // If not an admin/superadmin, restrict to only your own applications:
        if (! in_array($user->role, ['admin', 'superadmin'])) {
            $query->where('employer_id', $user->id);
        }

        // Select only the columns you care about, newest first
        $applications = $query
            ->orderBy('created_at', 'DESC')
            ->get([
                'id',
                'job_id',
                'username',
                'email',
                'phone',
                'job_title',
                'email',
                'resume',
                'user_id',
                'employer_id',
                'status',
                'applied_date',
            ]);

        // Append the base path to the resume file name if necessary
        foreach ($applications as $app) {
            $app->resume = asset('storage/resumes/' . $app->resume); // Adjust the path as needed
        }

        return view('admin.jobs.applications', compact('applications'));
    }

    public function viewApplication($id)
    {
        $application = JobApplication::with(['user', 'job'])
            ->findOrFail($id);

        return view('admin.applications.show', compact('application'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected,pending'
        ]);

        $application = JobApplication::findOrFail($id);
        $application->status = $request->status;
        $application->save();

        return response()->json([
            'status' => true,
            'message' => 'Status updated successfully'
        ]);
    }

    /**
     * Download applicant's resume
     */
    public function downloadResume($id)
    {
        $application = JobApplication::findOrFail($id);

        if ($application->resume) {
            $path = storage_path('app/public/resumes/' . $application->resume);

            if (file_exists($path)) {
                return response()->download($path);
            }
        }

        return redirect()->back()->with('error', 'Resume not found');
    }
}
