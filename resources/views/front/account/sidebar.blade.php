<div class="card border-0 shadow mb-4 p-3">
    <div class="s-body text-center mt-3">

        @if (Auth::user()->image != '')
            <img src="{{ asset('profile_pic/thumb/' . Auth::user()->image) }}" alt="avatar"
                class="rounded-circle img-fluid d-block mx-auto" style="width: 100px;">
        @else
            <img src="{{ asset('assets/images/avatar.png') }}" alt="avatar"
                class="rounded-circle img-fluid d-block mx-auto" style="width: 100px;">
        @endif

        <h5 class="mt-3 pb-0"> {{ Auth::user()->name }}</h5>
        <p class="text-muted mb-1 fs-6">{{ Auth::user()->designation }}</p>
        <div class="d-flex justify-content-center mb-2">
            <button data-bs-toggle="modal" data-bs-target="#exampleModal" type="submit" class="btn btn-custom">Change
                Profile Picture</button>
        </div>
    </div>
</div>
<div class="card account-nav border-0 shadow mb-4 mb-lg-0">
    <div class="card-body p-0">
        <ul class="list-group list-group-flush ">
            <li class="list-group-item d-flex justify-content-between p-3">
                <a href="{{ route('account.profile') }}">Account Settings</a>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center p-3"
                @if (Auth::check() && Auth::user()->role !== 'admin') style="display:none;" @endif>
                <a href="{{ route('account.createJob') }}">Post a Job</a>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center p-3"
                @if (Auth::check() && Auth::user()->role !== 'admin') style="display:none;" @endif>
                <a href="{{ route('account.myJobs') }}">My Jobs</a>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                <a href="{{ route('account.logout') }}">Jobs Applied</a>
            </li>
            {{-- Only show for employers (or admins) --}}
            @if (Auth::check() && in_array(Auth::user()->role, ['admin', 'superadmin']))
                <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                    <a href="{{ route('admin.applications') }}">Applications</a>
                </li>
            @endif
            <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                <a href="{{ route('account.logout') }}">Logout</a>
            </li>
        </ul>
    </div>
</div>
