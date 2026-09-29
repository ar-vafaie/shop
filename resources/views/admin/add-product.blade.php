@extends('admin.layouts.master')


@section('body')
    
{{-- <body>
<div class="admin-shell">
    <div class="sidebar-backdrop" data-sidebar-close></div>

    <aside class="admin-sidebar" id="adminSidebar" aria-label="Main navigation">
        
        <div class="sidebar-header">
            <a class="brand-mark" href="index.html" aria-label="adminHMD dashboard">
                <span class="brand-icon"><i class="bi bi-grid-1x2-fill" aria-hidden="true"></i></span>
                <span class="brand-copy">
                    <span class="brand-title">adminHMD</span>
                    <span class="brand-subtitle">Admin Template</span>
                </span>
            </a>
        </div>

        <nav class="sidebar-nav">
            <a class="nav-link" href="index.html">
                <span class="nav-icon"><i class="bi bi-speedometer2"></i></span>
                <span class="nav-text">Dashboard</span>
            </a>
            <a class="nav-link active" href="add-user.html" aria-current="page">
                <span class="nav-icon"><i class="bi bi-person-plus"></i></span>
                <span class="nav-text">Add Product</span>
            </a>
        </nav>

        <div class="sidebar-user">
            <img class="avatar-img avatar-md sidebar-user-avatar" src="../assets/images/avatar/avatar.jpg" alt="Admin Hasan">
            <strong>Admin Hasan</strong>
            <small>Active Workspace</small>
        </div>

        <div class="sidebar-footer">
            <span class="status-dot"></span>
            <span class="sidebar-footer-text">System running smoothly</span>
        </div>
    </aside> --}}

    <div class="admin-main">
        <nav class="navbar admin-navbar navbar-expand bg-white">
            <div class="container-fluid px-3 px-lg-4">
                <button class="sidebar-toggle" type="button" data-sidebar-toggle aria-controls="adminSidebar" aria-expanded="true" aria-label="Toggle sidebar">
                    <span></span><span></span><span></span>
                </button>

                <form class="d-none d-md-flex ms-3 flex-grow-1" role="search">
                    <input class="form-control search-input" type="search" placeholder="Search users, orders, reports" aria-label="Search">
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
                            <h1 class="h3 mb-1">Add Product</h1>
                            <p class="text-muted mb-0">Add a new product to your store.</p>
                        </div>
                    </div>
                    <div class="heading-actions">
                        <a class="btn btn-outline-secondary btn-sm" href={{ route('admin') }}>
                            <i class="bi bi-arrow-left"></i> Back
                        </a>
                    </div>
                </div>

                <section class="row g-3">
                    <div class="col-12 col-xl-8">
                        {{-- ✅ فرم اصلی: action و method اضافه شد --}}
                        <form class="panel needs-validation"
                              action="{{ route('admin.products.store') }}"
                              method="POST"
                              enctype="multipart/form-data"
                              novalidate>
                            @csrf

                            <div class="panel-header">
                                <div>
                                    <h2 class="h5 mb-1 section-title">
                                        <i class="bi bi-box-seam"></i>
                                        <span>Product Information</span>
                                    </h2>
                                    <p class="text-muted mb-0">Fill the fields below to add a product.</p>
                                </div>
                            </div>

                            <div class="row g-3">
                                {{-- Name --}}
                                <div class="col-md-6">
                                    <label class="form-label" for="productName">Name</label>
                                    <input class="form-control" id="productName" name="name" type="text" required>
                                    <div class="invalid-feedback">Name is required.</div>
                                </div>

                                {{-- Price --}}
                                <div class="col-md-6">
                                    <label class="form-label" for="productPrice">Price</label>
                                    <input class="form-control" id="productPrice" name="price" type="number" step="0.01" required>
                                    <div class="invalid-feedback">Enter a valid price.</div>
                                </div>

                                {{-- Stock --}}
                                <div class="col-md-6">
                                    <label class="form-label" for="productStock">Stock</label>
                                    <input class="form-control" id="productStock" name="stock" type="number" required>
                                    <div class="invalid-feedback">Stock is required.</div>
                                </div>


                                {{-- ✅ Categories با مودال --}}
                                <div class="col-12">
                                    <label class="form-label">Categories</label>
                                    <button type="button"
                                            class="btn btn-outline-primary d-flex align-items-center gap-2"
                                            id="openCategoryModal">
                                        <i class="bi bi-tags"></i> Select categories
                                    </button>

                                    {{-- نمایش چیپ‌های انتخاب‌شده --}}
                                    <div id="selectedCategories" class="mt-2 d-flex flex-wrap gap-2"></div>

                                    {{-- اینپوت‌های مخفی که با فرم ارسال می‌شوند --}}
                                    <div id="categoryInputs"></div>

                                    @error('categories')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- ✅ فایل با استایل پنل --}}
                                <div class="col-12">
                                  <label class="form-label" for="productImages">Product Images</label>
                                  <div class="form-file-wrapper">
                                      <input type="file"
                                            name="images[]"
                                            id="productImages"
                                            accept="image/*"
                                            multiple>
                                      <span class="form-file-btn">
                                          <i class="bi bi-upload"></i> Choose Files
                                      </span>
                                      <span class="form-file-name" id="fileName">No files selected</span>
                                  </div>

                                  {{-- پیش‌نمایش چند عکس --}}
                                  <div id="imagesPreview" class="images-preview"></div>

                                  @error('images')
                                      <div class="text-danger small mt-1">{{ $message }}</div>
                                  @enderror
                                  @error('images.*')
                                      <div class="text-danger small mt-1">{{ $message }}</div>
                                  @enderror
                              </div>

                                {{-- Description --}}
                                <div class="col-12">
                                    <label class="form-label" for="productDescription">Description</label>
                                    <textarea class="form-control" id="productDescription" name="description" rows="4"></textarea>
                                </div>
                            </div>

                            <div class="d-flex flex-wrap justify-content-end gap-2 mt-4">
                                <a class="btn btn-outline-secondary" href="users.html">Cancel</a>
                                <button class="btn btn-primary" type="submit">
                                    <i class="bi bi-check2-circle"></i> Create Product
                                </button>
                            </div>
                        </form>
                    </div>

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
                    </div>
                </section>
            </div>
        </main>
    </div>
</div>

{{-- ✅ مودال انتخاب کتگوری --}}
<div class="modal fade" id="categoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Select categories</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <input type="text" class="form-control" id="categorySearch" placeholder="Search categories...">
                </div>
                <div id="categoryList" class="category-list"></div>
                <div id="categoryEmpty" class="text-center text-muted py-4 d-none">
                    No categories found
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmCategories">Confirm</button>
            </div>
        </div>
    </div>
</div>
@endsection
