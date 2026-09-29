@extends('layouts.master')

@section('title')
  Cart
@endsection
@push('scripts')
  <script src={{ asset("js/cart.js") }}></script>
@endpush
@push('styles')
  <link rel="stylesheet" href={{ asset("css/cart.css") }}>
@endpush


@section('body')
  <main>
    <!-- ===== CART SECTION ===== -->
    <section class="cart-section container">
      <h1 class="cart-title">🛒 Your Shopping Cart</h1>

      <div class="cart-wrapper">
        <!-- لیست سبد خرید -->
        <div class="cart-items" id="cartItems">
          <!-- توسط JS پر می‌شود -->
        </div>

        <!-- خلاصه سبد -->
        <aside class="cart-summary" id="cartSummary">
          <h3 class="summary-title">Order Summary</h3>
          <div class="summary-row">
            <span>Subtotal</span>
            <span id="subtotal">$0.00</span>
          </div>
          <div class="summary-row">
            <span>Shipping</span>
            <span id="shipping">$0.00</span>
          </div>
          <div class="summary-row total">
            <span>Total</span>
            <span id="totalPrice">$0.00</span>
          </div>
          <button class="btn btn--primary btn--full" id="checkoutBtn">Proceed to Checkout</button>
          <a href="index.html" class="btn btn--secondary btn--full">Continue Shopping</a>
        </aside>
      </div>

      <!-- سبد خالی -->
      <div class="empty-cart" id="emptyCart" style="display:none;">
        <span class="empty-icon">🛒</span>
        <h2>Your cart is empty</h2>
        <p>Looks like you haven't added any items yet.</p>
        <a href="index.html" class="btn btn--primary">Start Shopping</a>
      </div>
    </section>
  </main>
@endsection