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
                    @include('front.account.sidebar')
                </div>

                <div class="col-lg-9">
                    @include('front.message')

                    <div class="card border-0 shadow mb-4">
                        <div class="card-header bg-white">
                            <h3 class="fs-4 mb-0">Job Applications</h3>
                        </div>
                        <div class="card-body">
                            @if ($applications->isEmpty())
                                <div class="alert alert-info">No applications found.</div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>ID</th>
                                                <th>Applicants</th>
                                                <th>Job Title</th>
                                                <th>Contact</th>
                                                <th>Resume</th>
                                                <th>Status</th>
                                                <th>Applied Date</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($applications as $app)
                                                <tr>
                                                    <td>{{ $app->id }}</td>
                                                    <td>{{$app->username}}</td>
                                                    <td>{{ $app->job_title }}</td>
                                                    <td>{{ $app->email }} <br>{{ $app->phone }}</td>
                                                    <td>
                                                        <a href="{{ $app->resume }}" download class="" style="color: blue; text-decoration: underline;">
                                                            Download</a>
                                                    </td>
                                                    <td>{{ ucfirst($app->status) }}</td>
                                                    <td>{{ $app->applied_date }}</td>
                                                    <td>
                                                        <div class="action-dots">
                                                            <button href="#" class="btn" data-bs-toggle="dropdown"
                                                                aria-expanded="false">
                                                                <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                                            </button>
                                                            <ul class="dropdown-menu dropdown-menu-end">
                                                                <li>
                                                                    <button class="dropdown-item approve-application"
                                                                        data-id="{{ $app->id }}"
                                                                        {{ $app->status !== 'pending' ? 'disabled' : '' }}
                                                                        style="background-color: {{ $app->status === 'approved' ? 'rgba(76, 175, 80, 0.5)' : 'transparent' }};">
                                                                        <i class="fa fa-check" aria-hidden="true"></i>
                                                                        Accept
                                                                    </button>
                                                                </li>
                                                                <li>
                                                                    <button class="dropdown-item reject-application"
                                                                        data-id="{{ $app->id }}"
                                                                        {{ $app->status !== 'pending' ? 'disabled' : '' }}
                                                                        style="background-color: {{ $app->status === 'rejected' ? 'rgba(244, 67, 54, 0.5)' : 'transparent' }};">
                                                                        <i class="fa fa-times" aria-hidden="true"></i>
                                                                        Reject
                                                                    </button>
                                                                </li>
                                                            </ul>

                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
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
                    url: '{{ route('admin.applications.show', ['id' => '__applicationId__']) }}'
                        .replace('__applicationId__', applicationId),
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
                    url: '{{ route('admin.applications.updateStatus', ['id' => '__applicationId__']) }}'
                        .replace('__applicationId__', applicationId),
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
@endsection

@php
    function getStatusColor($status)
    {
        switch ($status) {
            case 'approved':
                return 'success';
            case 'rejected':
                return 'danger';
            default:
                return 'warning';
        }
    }
@endphp
