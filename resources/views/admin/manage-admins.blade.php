@extends('admin.layouts.master')

@section('title', 'Admins')

@push('script')
    <script src="{{ asset('js/manage-admins.js') }}"></script>
@endpush
@section('body')

    <main class="dashboard-content">
        <div class="container-fluid px-3 px-lg-4 py-4">
            <div class="page-heading">
                <div class="page-heading-copy">
                    <span class="page-icon"><i class="bi bi-people" aria-hidden="true"></i></span>
                    <div>
                        <p class="eyebrow mb-1">Management</p>
                        <h1 class="h3 mb-1">Admin's</h1>
                        <p class="text-muted mb-0">Review accounts, roles, account status.</p>
                    </div>
                </div>
            </div>

            <section class="panel mt-3">
                <div class="panel-header">
                    <div>
                        <h2 class="h5 mb-1 section-title"><i class="bi bi-table" aria-hidden="true"></i><span>Admin
                                List</span></h2>
                        <p class="text-muted mb-0">Search, review, and manage team member accounts.</p>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <input class="form-control form-control-sm table-search" type="search" placeholder="Search users"
                            data-table-search="usersTable" aria-label="Search users">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#exampleModal">
                            Add admin
                        </button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0" id="usersTable" data-searchable-table>
                        <thead>
                            <tr>
                                <th scope="col">User</th>
                                <th scope="col">Role</th>
                                <th scope="col">Team</th>
                                <th scope="col">Status</th>
                                <th scope="col">Joined</th>
                                <th scope="col" class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody id="content">
                            {{--  --}}

                            {{--  --}}
                        </tbody>
                    </table>
                </div>
                <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mt-3">
                    <nav aria-label="Users pagination">
                        <ul class="pagination pagination-sm mb-0">
                            {{-- compeled with js --}}
                        </ul>
                    </nav>
                </div>
            </section>
        </div>
        {{-- modal --}}
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Add admin</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input class="form-control form-control-sm table-search" type="search" placeholder="Search users"
                            data-table-search="usersTable" aria-label="Search users">
                        <table class="table align-middle mb-0" id="usersTable" data-searchable-table>
                        <thead>
                            <tr>
                                <th scope="col">User</th>
                                <th scope="col">Role</th>
                                <th scope="col">Team</th>
                            </tr>
                        </thead>
                        <tbody id="c">
                            {{--  --}}

                            {{--  --}}
                        </tbody>
                    </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Add</button>
                    </div>
                </div>
            </div>
        </div>

    </main>

@endsection
