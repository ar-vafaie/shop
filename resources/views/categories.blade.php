@extends('layouts.master')

@section('title')
  Categories
@endsection
@push('scripts')
  <script src={{ asset("js/category.js") }}></script>

@endpush
@push('styles')
  <link rel="stylesheet" href={{ asset("css/category.css") }}>
@endpush

@section('body')
  <main>
    <!-- ===== CATEGORY HEADER ===== -->
    <section class="category-header container">
      <h1 class="category-title">📂 Browse Categories</h1>
      <p class="category-subtitle">Find exactly what you need by exploring our categories</p>
    </section>

    <!-- ===== CATEGORY GRID ===== -->
    <section class="category-grid-section container">
      <div class="category-grid" id="categoryGrid">
        <!-- توسط JS تولید می‌شود -->

        {{-- <span class="category-card__emoji">${cat.emoji}</span> --}}
        {{-- <div class="category-card category-card__name">test</div> --}}
        {{-- <div class="category-card__count">${count} products</div> --}}
      </div>
    </section>

    <!-- ===== PRODUCTS BY CATEGORY ===== -->
    <section class="category-products container" id="categoryProducts">
      <div class="category-products__header">
        <h2 class="section-title" id="selectedCategoryTitle">All Products</h2>
        <div class="category-products__filters">
          <input type="text" class="search-input" id="categorySearch" placeholder="Search in category...">
        </div>
      </div>
      <div class="products__grid" id="categoryProductGrid">
        <!-- توسط JS تولید می‌شود -->
      </div>
      <p class="no-result" id="noCategoryResult" style="display:none;">No products found in this category.</p>
    </section>
  </main>

@endsection