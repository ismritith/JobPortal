@extends('front.layouts.app')


@section('main')    
    <section class="section-5 bg-2">
    <div class="container py-5">
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class=" rounded-3 p-3 mb-4">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Account Settings</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3">
                @include('front.account.sidebar')
            </div>
            <div class="col-lg-9">

                @include('front.message')

                <div class="card border-0 shadow mb-4 p-3">
                    <div class="card-body card-form">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h3 class="fs-4 mb-1">My Jobs</h3>
                            </div>
                            <div style="margin-top: -10px;">
                                <a href="{{ route('account.createJob') }}" class="btn btn-custom">Post a Job</a>
                            </div>
                            
                        </div>
                        <div class="table-responsive">
                            <table class="table ">
                                <thead class="bg-light">
                                    <tr>
                                        <th scope="col">Title</th>
                                        <th scope="col">Job Created</th>
                                        <th scope="col">Applicants</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="border-0">
                                    @if($jobs->isNotEmpty())
                                        @foreach($jobs as $job)
                                            <tr class="active">
                                                <td>
                                                    <div class="job-name fw-500">{{ $job->title }}</div>
                                                    <div class="info1">{{ $job->jobType->name }} . {{ $job->location }}</div>
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($job->created_at)->format('d M, Y')}}</td>
                                                <td>0 Applications</td>
                                                <td>
                                                    <div class="job-status text-capitalize">active</div>
                                                </td>
                                                <td>
                                                    <div class="action-dots float-end">
                                                        <button href="#" class="btn" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                            <li><a class="dropdown-item" href="job-detail.html"> <i class="fa fa-eye" aria-hidden="true"></i> View</a></li>
                                                            <li><a class="dropdown-item" href="{{ route('account.editJob', $job->id) }}"><i class="fa fa-edit" aria-hidden="true"></i> Edit</a></li>
                                                            <li><a class="dropdown-item" href="#" onclick="deleteJob({{ $job->id }})"><i class="fa fa-trash" aria-hidden="true"></i> delete</a></li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">
                                                <i class="fa fa-briefcase mb-2" style="font-size: 1.5rem;"></i><br>
                                                You haven’t posted any jobs yet.
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                                
                                
                            </table>
                        </div>
                    </div>

                    {{ $jobs->links()}}
                </div> 


                            
            </div>
        </div>
    </div>

</section>
@endsection

@section('customJs')

<script type="text/javascript">
   function deleteJob(jobId) {
        if(confirm('Are you sure you want to delete this job?')) {
            $.ajax({
                url: "{{ route('account.deleteJob') }}",
                type: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    id: jobId
                },
                dataType: 'json',
                success: function (response) {
                    if(response.status === true) {
                        alert("Job deleted successfully!");
                        window.location.href = "{{ route('account.myJobs') }}";
                    } else {
                        alert("Failed to delete the job.");
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", error);
                    alert("An error occurred while deleting the job.");
                }
            });
        }
    }
</script>

@endsection