@extends('layouts.master')

@section('title', $product->name)

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/product.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('js/cart.js') }}"></script>
    <script src="{{ asset('js/product.js') }}"></script>
@endpush

@section('body')
    @php
        $firstImage   = $product->images->first();
        $visibleComments = $product->comments->where('is_showable', true);
    @endphp

    <main>
        <section class="product-detail container"
                 id="productDetail"
                 data-product-id="{{ $product->id }}"
                 data-product-name="{{ $product->name }}"
                 data-product-price="{{ number_format($product->price, 2, '.', '') }}"
                 data-product-image="{{ $firstImage ? $firstImage->name : '' }}"
                 data-product-stock="{{ (int) $product->stock }}">

            <div class="product-detail__wrapper">

                {{-- ===================== گالری ===================== --}}
                <div class="product-detail__gallery">
                    <div class="product-detail__main-image" id="mainImage">
                        @if ($firstImage)
                            {{-- نکته: $firstImage->name قبلاً asset شده است --}}
                            <img class="product-detail__image"
                                 src="{{ $firstImage->name }}"
                                 alt="{{ $product->name }}">
                        @else
                            <div class="product-detail__image product-detail__image--placeholder">
                                No Image
                            </div>
                        @endif
                    </div>

                    @if ($product->images->count() > 1)
                        <div class="product-detail__thumbnails" id="thumbnails">
                            @foreach ($product->images as $index => $image)
                                <div class="product-detail__thumb {{ $index === 0 ? 'active' : '' }}"
                                     data-src="{{ $image->name }}">
                                    <img src="{{ $image->name }}"
                                         alt="{{ $product->name }} #{{ $index + 1 }}">
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- ===================== اطلاعات محصول ===================== --}}
                <div class="product-detail__info">
                    <h1 class="product-detail__name" id="productName">{{ $product->name }}</h1>

                    <div class="product-detail__rating">
                        <span class="stars">★★★★★</span>
                        <span class="reviews">
                            ({{ $visibleComments->count() }} reviews)
                        </span>
                    </div>

                    <div class="product-detail__price" id="productPrice">
                        ${{ number_format($product->price, 2) }}
                    </div>

                    <div class="product-detail__description" id="productDesc">
                        <p>{{ $product->description }}</p>
                    </div>

                    <div class="product-detail__meta">
                        <div class="meta-item">
                            <span class="meta-label">Category:</span>
                            <span class="meta-value">
                                {{ $product->categories->pluck('name')->join(', ') ?: '—' }}
                            </span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">SKU:</span>
                            <span class="meta-value">
                                #{{ str_pad($product->id, 4, '0', STR_PAD_LEFT) }}
                            </span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Stock:</span>
                            <span class="meta-value">
                                {{ $product->stock > 0
                                    ? 'In Stock (' . (int) $product->stock . ')'
                                    : 'Out of Stock' }}
                            </span>
                        </div>
                    </div>

                    <div class="product-detail__actions">
                        <div class="qty-selector">
                            <button class="qty-btn" id="qtyDecrease" type="button">−</button>
                            <span class="qty-value" id="qtyValue">1</span>
                            <button class="qty-btn" id="qtyIncrease" type="button">+</button>
                        </div>

                        <button class="btn btn--primary add-to-cart-btn"
                                id="addToCartBtn"
                                type="button"
                                {{ $product->stock <= 0 ? 'disabled' : '' }}>
                            {{ $product->stock > 0 ? 'Add to Cart' : 'Out of Stock' }}
                        </button>

                        <button class="btn btn--secondary wishlist-btn"
                                id="wishlistBtn"
                                type="button">
                            ♡ Wishlist
                        </button>
                    </div>

                    <div class="product-detail__shipping">
                        <span>🚚 Free shipping on orders over $50</span>
                        <span>↩️ 30-day return policy</span>
                    </div>
                </div>
            </div>

            {{-- ===================== نظرات ===================== --}}
            @if ($visibleComments->isNotEmpty())
                <section class="product-reviews">
                    <h2 class="section-title">💬 Customer Reviews</h2>
                    <div class="reviews-list">
                        @foreach ($visibleComments as $comment)
                            <div class="review-card">
                                <div class="review-card__header">
                                    <strong class="review-card__author">
                                        {{ $comment->author_name ?? 'Anonymous' }}
                                    </strong>
                                    <span class="review-card__date">
                                        {{ $comment->created_at?->format('Y-m-d') }}
                                    </span>
                                </div>
                                <p class="review-card__text">{{ $comment->comment }}</p>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            {{-- ===================== محصولات مرتبط ===================== --}}
            @if (isset($related) && $related->isNotEmpty())
                <section class="related-products">
                    <h2 class="section-title">✨ You May Also Like</h2>
                    <div class="products__grid" id="relatedGrid">
                        @foreach ($related as $item)
                            @php $thumb = $item->images->first(); @endphp
                            <div class="product-card">
                                @if ($thumb)
                                    <img class="product-card__emoji"
                                         src="{{ $thumb->name }}"
                                         alt="{{ $item->name }}">
                                @endif
                                <h3 class="product-card__name">{{ $item->name }}</h3>
                                <div class="product-card__price">
                                    ${{ number_format($item->price, 2) }}
                                </div>
                                <a class="product-card__btn"
                                   href="{{ route('product', $item->id) }}">
                                    View Product
                                </a>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif
        </section>
    </main>
@endsection