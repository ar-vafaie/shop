@extends('admin.layouts.master')


@section('body')
    <div class="admin-main">
        <nav class="navbar admin-navbar navbar-expand bg-white">
            <div class="container-fluid px-3 px-lg-4">
                <button class="sidebar-toggle" type="button" data-sidebar-toggle aria-controls="adminSidebar"
                    aria-expanded="true" aria-label="Toggle sidebar">
                    <span></span><span></span><span></span>
                </button>

                <form class="d-none d-md-flex ms-3 flex-grow-1" role="search">
                    <input class="form-control search-input" type="search" placeholder="Search users, orders, reports"
                        aria-label="Search">
                </form>

                <div class="navbar-actions ms-auto">
                    <button class="icon-button theme-toggle" type="button" data-theme-toggle>
                        <i class="bi bi-moon-stars" data-theme-icon aria-hidden="true"></i>
                    </button>
                </div>
            </div>
        </nav>

        @if(session('success'))
            <div class="alert alert-success">{{ session("success") }}</div>
        @enderror

        <main class="dashboard-content">
            <div class="container-fluid px-3 px-lg-4 py-4">
                <div class="page-heading">
                    <div class="page-heading-copy">
                        <span class="page-icon"><i class="bi bi-box-seam"></i></span>
                        <div>
                            <p class="eyebrow mb-1">Management</p>
                            <h1 class="h3 mb-1">Add Category</h1>
                            <p class="text-muted mb-0">Add a new category to your store.</p>
                        </div>
                    </div>
                    <div class="heading-actions">
                        <a class="btn btn-outline-secondary btn-sm" href="{{ route('admin') }}">
                            <i class="bi bi-arrow-left"></i> Back
                        </a>
                    </div>
                </div>

                <section class="row g-3">
                    <div class="col-12 col-xl-8">
                        {{-- ✅ فرم اصلی: action و method اضافه شد --}}
                        <form class="panel needs-validation" action="{{ route('admin.category.store') }}" method="POST"
                            enctype="multipart/form-data" novalidate>
                            @csrf

                            <div class="panel-header">
                                <div>
                                    <h2 class="h5 mb-1 section-title">
                                        <i class="bi bi-box-seam"></i>
                                        <span>category</span>
                                    </h2>
                                    <p class="text-muted mb-0">Fill the fields below to add a category.</p>
                                </div>
                            </div>

                            <div class="row g-3">
                                {{-- Name --}}
                                <div class="col-md-6">
                                    <label class="form-label" for="productName">Name</label>
                                    <input class="form-control" id="productName" name="name" type="text" required>
                                    <div class="invalid-feedback">Name is required.</div>
                                </div>

                                {{-- ✅ فایل با استایل پنل --}}
                                <div class="col-12">
                                    <label class="form-label" for="productImage">category Image</label>
                                    <div class="form-file-wrapper">
                                        <input type="file" name="image" id="productImage" accept="image/*">
                                        <span class="form-file-btn">
                                            <i class="bi bi-upload"></i> Choose File
                                        </span>
                                        <span class="form-file-name" id="fileName">No file selected</span>
                                        <img id="filePreview" class="form-file-preview" alt="">
                                    </div>
                                    @error('image')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="d-flex flex-wrap justify-content-end gap-2 mt-4">
                                    <a class="btn btn-outline-secondary" href="users.html">Cancel</a>
                                    <button class="btn btn-primary" type="submit">
                                        <i class="bi bi-check2-circle"></i> Add Category
                                    </button>
                                </div>
                            </div> {{-- بستن row g-3 --}}
                        </form>
                    </div> {{-- بستن col-12 col-xl-8 --}}

                    <div class="col-12 col-xl-4">
                        <div class="panel h-100">
                            <h2 class="h5 mb-3 section-title">
                                <i class="bi bi-list-check"></i>
                                <span>Publishing Checklist</span>
                            </h2>
                            <div class="activity-list">
                                <div class="activity-item">
                                    <span class="activity-dot bg-success"></span>
                                    <div>
                                        <p class="mb-1 fw-semibold">Add name & price</p>
                                        <p class="text-muted small mb-0">Required for listing.</p>
                                    </div>
                                </div>
                                <div class="activity-item">
                                    <span class="activity-dot bg-primary"></span>
                                    <div>
                                        <p class="mb-1 fw-semibold">Assign categories</p>
                                        <p class="text-muted small mb-0">Helps users find your product.</p>
                                    </div>
                                </div>
                                <div class="activity-item">
                                    <span class="activity-dot bg-warning"></span>
                                    <div>
                                        <p class="mb-1 fw-semibold">Upload an image</p>
                                        <p class="text-muted small mb-0">Products with images sell faster.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> {{-- بستن col-12 col-xl-4 --}}
                </section>
            </div>
        </main>
    </div>
@endsection
