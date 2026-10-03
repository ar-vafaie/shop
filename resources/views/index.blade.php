@extends('layouts.master')

@section('title')
  Home
@endsection

@section('body')
  <main>
    <!-- ===== SLIDER ===== -->
    <section class="slider" id="slider">
      <div class="slider__container">
        <div class="slider__slide active">
          <div class="slider__content">
            <h2 class="slider__title">Latest Tech Gear</h2>
            <p class="slider__desc">Best prices & top quality</p>
            <a href="#" class="btn btn--primary">Shop Now</a>
          </div>
        </div>
        <div class="slider__slide">
          <div class="slider__content">
            <h2 class="slider__title">Amazing Deals</h2>
            <p class="slider__desc">Up to 50% off your first order</p>
            <a href="#" class="btn btn--primary">Claim Offer</a>
          </div>
        </div>
        <div class="slider__slide">
          <div class="slider__content">
            <h2 class="slider__title">Free Shipping</h2>
            <p class="slider__desc">On orders over $100</p>
            <a href="#" class="btn btn--primary">Learn More</a>
          </div>
        </div>
      </div>
      <button class="slider__btn slider__btn--prev" id="prevBtn">‹</button>
      <button class="slider__btn slider__btn--next" id="nextBtn">›</button>
      <div class="slider__dots" id="dots"></div>
    </section>

    <!-- ===== PRODUCTS with CATEGORIES ===== -->
    <section class="products container">
      <div class="products__header">
        <h2 class="section-title">🔥 Featured Products</h2>
        <!-- دسته‌بندی -->
        <div class="category-filters" id="categoryFilters">
          <button class="cat-btn active" data-cat="bestseller">Best Seller</button>
          <button class="cat-btn" data-cat="newest">Newest</button>
          
        </div>
      </div>

      <div class="products__grid" id="productGrid">
        <!-- product cards injected by JS -->
      </div>

      <!-- پیام عدم وجود نتیجه -->
      <p class="no-result" id="noResult" style="display:none;">No products found.</p>
    </section>
  </main>

  @endsection