<div class="application-details">
    <div class="row mb-4">
        <div class="col-md-4">
            <h6>Applicant Information</h6>
            <p><strong>Name:</strong> {{ $application->user->name }}</p>
            <p><strong>Email:</strong> {{ $application->user->email }}</p>
            <p><strong>Phone:</strong> {{ $application->user->phone ?? 'N/A' }}</p>
        </div>
        <div class="col-md-4">
            <h6>Application Details</h6>
            <p><strong>Applied On:</strong> {{ $application->created_at->format('M d, Y') }}</p>
            <p><strong>Status:</strong> 
                <span class="badge bg-{{ getStatusColor($application->status) }}">
                    {{ ucfirst($application->status) }}
                </span>
            </p>
        </div>
        <div class="col-md-4">
            <h6>Job Information</h6>
            <p><strong>Position:</strong> {{ $application->job->title }}</p>
            <p><strong>Company:</strong> {{ $application->job->company_name }}</p>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h6>Cover Letter</h6>
                </div>
                <div class="card-body">
                    {!! nl2br(e($application->cover_letter)) !!}
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6>Resume</h6>
                </div>
                <div class="card-body">
                    @if($application->resume)
                        <a href="{{ asset('storage/resumes/'.$application->resume) }}" 
                           target="_blank" class="btn btn-primary">
                            <i class="fas fa-download"></i> Download Resume
                        </a>
                    @else
                        <p class="text-muted">No resume uploaded</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>