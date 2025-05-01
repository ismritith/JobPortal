@extends('front.layouts.app')

@section('main')
<section class="section-5 bg-2">
    <div class="container py-5">
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class="rounded-3 p-3 mb-4">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.jobs') }}">Jobs</a></li>
                        <li class="breadcrumb-item active">Applications</li>
                    </ol>
                </nav>
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-3">
                @include('admin.sidebar')
            </div>
            
            <div class="col-lg-9">
                @include('front.message')
                
                <div class="card border-0 shadow mb-4">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h3 class="fs-4 mb-0">Applications for: {{ $job->title }}</h3>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" 
                                    id="filterDropdown" data-bs-toggle="dropdown">
                                Filter
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="?status=all">All Applications</a></li>
                                <li><a class="dropdown-item" href="?status=pending">Pending</a></li>
                                <li><a class="dropdown-item" href="?status=approved">Approved</a></li>
                                <li><a class="dropdown-item" href="?status=rejected">Rejected</a></li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        @if($applications->isEmpty())
                            <div class="alert alert-info">No applications found.</div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Applicant</th>
                                            <th>Email</th>
                                            <th>Applied On</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($applications as $application)
                                        <tr>
                                            <td>
                                                <a href="#" class="text-primary view-application" 
                                                   data-id="{{ $application->id }}">
                                                    {{ $application->user->name }}
                                                </a>
                                            </td>
                                            <td>{{ $application->user->email }}</td>
                                            <td>{{ $application->created_at->format('d M Y') }}</td>
                                            <td>
                                                <span class="badge bg-{{ getStatusColor($application->status) }}">
                                                    {{ ucfirst($application->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <button class="btn btn-sm btn-primary view-application" 
                                                            data-id="{{ $application->id }}">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-success approve-application" 
                                                            data-id="{{ $application->id }}">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-danger reject-application" 
                                                            data-id="{{ $application->id }}">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="mt-3">
                                {{ $applications->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Application Detail Modal -->
<div class="modal fade" id="applicationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Application Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="applicationDetailContent">
                <!-- Content will be loaded via AJAX -->
                <div class="text-center py-5">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('customJs')
<script>
$(document).ready(function() {
    // View Application Details
    $('.view-application').click(function() {
        var applicationId = $(this).data('id');
        $('#applicationModal').modal('show');
        
        $.ajax({
            url: '/admin.jobs.applications/' + applicationId,
            type: 'GET',
            success: function(response) {
                $('#applicationDetailContent').html(response);
            },
            error: function() {
                $('#applicationDetailContent').html(
                    '<div class="alert alert-danger">Failed to load application details.</div>'
                );
            }
        });
    });
    
    // Approve Application
    $('.approve-application').click(function() {
        if (confirm('Are you sure you want to approve this application?')) {
            var applicationId = $(this).data('id');
            updateApplicationStatus(applicationId, 'approved');
        }
    });
    
    // Reject Application
    $('.reject-application').click(function() {
        if (confirm('Are you sure you want to reject this application?')) {
            var applicationId = $(this).data('id');
            updateApplicationStatus(applicationId, 'rejected');
        }
    });
    
    function updateApplicationStatus(applicationId, status) {
        $.ajax({
            url: '/admin/applications/' + applicationId + '/status',
            type: 'PUT',
            data: {
                status: status,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                window.location.reload();
            },
            error: function() {
                alert('Failed to update application status');
            }
        });
    }
});
</script>

<style>

</style>
@endsection

@php
function getStatusColor($status) {
    switch($status) {
        case 'approved': return 'success';
        case 'rejected': return 'danger';
        default: return 'warning';
    }
}
@endphp