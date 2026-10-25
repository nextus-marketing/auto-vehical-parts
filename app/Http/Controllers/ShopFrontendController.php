<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\AuthorizeNetService;
use Illuminate\Support\Str;

class ShopFrontendController extends Controller
{
    /**
     * Display Shop Catalog with dynamic database products and category filter
     */
    public function shop(Request $request)
    {
        $categorySlug = $request->query('category');
        $search = $request->query('q');

        $categories = Category::where('status', 'ACTIVE')->orderBy('index', 'asc')->get();

        $query = Product::with('category')->where('status', 'ACTIVE');

        if ($categorySlug && $categorySlug !== 'all') {
            $query->whereHas('category', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('part_number', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $products = $query->latest('id')->paginate(12);
        $totalProductsCount = Product::where('status', 'ACTIVE')->count();

        return view('Frontend.shop', compact('products', 'categories', 'categorySlug', 'totalProductsCount', 'search'));
    }

    /**
     * Display Product Details Page
     */
    public function shopDetails($slug = null)
    {
        if ($slug) {
            $product = Product::with('category')->where('slug', $slug)->first();
        } else {
            $product = Product::with('category')->where('status', 'ACTIVE')->first();
        }

        if (!$product) {
            return redirect()->route('shop')->with('error', 'Product not found.');
        }

        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', 'ACTIVE')
            ->take(4)
            ->get();

        return view('Frontend.shop-details', compact('product', 'relatedProducts'));
    }

    /**
     * Get or initialize session cart data
     */
    private function getCartData()
    {
        $cart = session()->get('cart', []);
        $subtotal = 0.00;

        foreach ($cart as $item) {
            $subtotal += ($item['price'] * $item['quantity']);
        }

        $appliedCoupon = session()->get('applied_coupon', null);
        $discount = 0.00;

        if ($appliedCoupon) {
            $coupon = Coupon::where('code', $appliedCoupon['code'])->first();
            if ($coupon && $coupon->isValidForAmount($subtotal)) {
                $discount = $coupon->calculateDiscount($subtotal);
                // update session with fresh calculation
                $appliedCoupon['discount'] = $discount;
                session()->put('applied_coupon', $appliedCoupon);
            } else {
                session()->forget('applied_coupon');
                $appliedCoupon = null;
            }
        }

        $shipping = 0.00; // Free freight standard
        $tax = round(($subtotal - $discount) * 0.07, 2); // 7% US avg sales tax
        $total = max(0, round($subtotal - $discount + $shipping + $tax, 2));

        return [
            'items' => $cart,
            'count' => array_sum(array_column($cart, 'quantity')),
            'subtotal' => round($subtotal, 2),
            'discount' => round($discount, 2),
            'shipping' => round($shipping, 2),
            'tax' => round($tax, 2),
            'total' => round($total, 2),
            'coupon' => $appliedCoupon,
        ];
    }

    /**
     * Get current cart summary (AJAX)
     */
    public function getCart()
    {
        return response()->json([
            'status' => 'success',
            'cart' => $this->getCartData()
        ]);
    }

    /**
     * View Cart Page
     */
    public function cartView()
    {
        $cartData = $this->getCartData();
        return view('Frontend.cart', compact('cartData'));
    }

    /**
     * Add Item to Cart
     */
    public function addToCart(Request $request)
    {
        $productId = $request->input('product_id');
        $qty = max(1, intval($request->input('quantity', 1)));

        $product = Product::findOrFail($productId);

        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $qty;
        } else {
            $cart[$productId] = [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'part_number' => $product->part_number ?: 'OEM-STANDARD',
                'price' => floatval($product->price),
                'photo' => $product->photo_url,
                'warranty' => $product->warranty ?: '1-Year Comprehensive Replacement Guarantee',
                'quantity' => $qty,
            ];
        }

        session()->put('cart', $cart);

        return response()->json([
            'status' => 'success',
            'message' => 'Added ' . $product->name . ' to your cart!',
            'cart' => $this->getCartData()
        ]);
    }

    /**
     * Update Item Quantity in Cart
     */
    public function updateCart(Request $request)
    {
        $productId = $request->input('product_id');
        $qty = intval($request->input('quantity', 1));

        $cart = session()->get('cart', []);

        if ($qty <= 0) {
            unset($cart[$productId]);
        } elseif (isset($cart[$productId])) {
            $cart[$productId]['quantity'] = $qty;
        }

        session()->put('cart', $cart);

        return response()->json([
            'status' => 'success',
            'message' => 'Cart updated successfully',
            'cart' => $this->getCartData()
        ]);
    }

    /**
     * Remove Item from Cart
     */
    public function removeFromCart(Request $request)
    {
        $productId = $request->input('product_id');
        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->put('cart', $cart);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Item removed from cart',
            'cart' => $this->getCartData()
        ]);
    }

    /**
     * Apply Promo Coupon Code
     */
    public function applyCoupon(Request $request)
    {
        $code = strtoupper(trim($request->input('code')));

        if (empty($code)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Please enter a valid coupon code.'
            ], 422);
        }

        $cartData = $this->getCartData();
        $subtotal = $cartData['subtotal'];

        if ($subtotal <= 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Your cart is empty. Add parts to your cart before applying a coupon.'
            ], 422);
        }

        $coupon = Coupon::where('code', $code)->first();

        if (!$coupon) {
            return response()->json([
                'status' => 'error',
                'message' => "Coupon code '{$code}' was not found. Please check spelling."
            ], 404);
        }

        if ($coupon->status !== 'ACTIVE') {
            return response()->json([
                'status' => 'error',
                'message' => "This coupon is currently inactive or has ended."
            ], 422);
        }

        if ($coupon->expires_at && now()->gt($coupon->expires_at->endOfDay())) {
            return response()->json([
                'status' => 'error',
                'message' => "This promotional coupon code expired on " . $coupon->expires_at->format('M d, Y') . "."
            ], 422);
        }

        if ($coupon->min_order_amount && $subtotal < $coupon->min_order_amount) {
            return response()->json([
                'status' => 'error',
                'message' => "This coupon requires a minimum subtotal of $" . number_format($coupon->min_order_amount, 2) . " USD."
            ], 422);
        }

        $discount = $coupon->calculateDiscount($subtotal);

        session()->put('applied_coupon', [
            'code' => $coupon->code,
            'title' => $coupon->title ?: $coupon->code,
            'discount' => $discount,
            'type' => $coupon->discount_type,
            'value' => $coupon->discount_value,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => "Coupon '{$coupon->code}' applied successfully! Saved $" . number_format($discount, 2) . " USD.",
            'cart' => $this->getCartData()
        ]);
    }

    /**
     * Remove Applied Coupon
     */
    public function removeCoupon()
    {
        session()->forget('applied_coupon');
        return response()->json([
            'status' => 'success',
            'message' => 'Coupon code removed.',
            'cart' => $this->getCartData()
        ]);
    }

    /**
     * View Checkout Page
     */
    public function checkout()
    {
        $cartData = $this->getCartData();

        // If cart is empty, redirect to shop
        if (empty($cartData['items'])) {
            return redirect()->route('shop')->with('info', 'Your cart is empty. Please select an auto part to proceed to checkout.');
        }

        return view('Frontend.checkout', compact('cartData'));
    }

    /**
     * Process Checkout & Payment via Authorize.Net
     */
     public function processCheckout(Request $request)
     {
         $validationRules = [
             'customer_name' => 'required|string|max:255',
             'customer_email' => 'required|email|max:255',
             'customer_phone' => 'required|string|max:50',
             'shipping_address' => 'required|string|max:255',
             'city' => 'required|string|max:100',
             'state' => 'required|string|max:100',
             'zip_code' => 'required|string|max:20',
             'payment_method' => 'required|in:CREDIT_CARD,PAYPAL,CASH_ON_DELIVERY',
         ];

         if ($request->payment_method === 'CREDIT_CARD') {
             $validationRules['card_number'] = 'required|string|min:12|max:24';
             $validationRules['card_expiry'] = 'required|string|min:4|max:7';
             $validationRules['card_cvv'] = 'required|string|min:3|max:4';
         }

         $request->validate($validationRules);

         $cartData = $this->getCartData();

         if (empty($cartData['items'])) {
             return redirect()->route('shop')->with('error', 'Your cart is empty.');
         }

         // Generate Order Number
         $orderNumber = 'AVP-' . date('Y') . '-' . strtoupper(Str::random(6));

         $paymentStatus = 'SUCCESS';
         $orderStatus = 'CONFIRMED';
         $failureReason = null;
         $transactionId = 'TXN-' . strtoupper(Str::random(12));

         if ($request->payment_method === 'CREDIT_CARD') {
             $authNet = app(AuthorizeNetService::class);
             $chargeResult = $authNet->chargeCreditCard([
                 'amount' => $cartData['total'],
                 'card_number' => $request->card_number,
                 'card_expiry' => $request->card_expiry,
                 'card_code' => $request->card_cvv,
                 'customer_name' => $request->customer_name,
                 'customer_email' => $request->customer_email,
                 'customer_phone' => $request->customer_phone,
                 'shipping_address' => $request->shipping_address,
                 'city' => $request->city,
                 'state' => $request->state,
                 'zip_code' => $request->zip_code,
                 'order_number' => $orderNumber,
                 'description' => 'Auto Vehicle Parts - Order #' . $orderNumber,
             ]);

             if ($chargeResult['success']) {
                 $paymentStatus = 'SUCCESS';
                 $orderStatus = 'CONFIRMED';
                 $transactionId = $chargeResult['transaction_id'] ?: ('ANET_' . Str::random(12));
                 $failureReason = null;
             } else {
                 $paymentStatus = 'FAILED';
                 $orderStatus = 'FAILED';
                 $transactionId = $chargeResult['transaction_id'] ?: ('ANET_DECLINED_' . Str::random(8));
                 $failureReason = $chargeResult['error'] ?? 'Your card was declined by the payment gateway.';
             }
         } elseif ($request->payment_method === 'PAYPAL') {
             $paymentStatus = 'PENDING';
             $orderStatus = 'PROCESSING';
             $transactionId = 'PP_' . strtoupper(Str::random(12));
         } elseif ($request->payment_method === 'CASH_ON_DELIVERY') {
             $paymentStatus = 'PENDING';
             $orderStatus = 'CONFIRMED';
             $transactionId = 'COD_' . strtoupper(Str::random(10));
         }

         $order = Order::create([
             'order_number' => $orderNumber,
             'user_id' => auth()->check() ? auth()->id() : null,
             'customer_name' => $request->customer_name,
             'customer_email' => $request->customer_email,
             'customer_phone' => $request->customer_phone,
             'shipping_address' => $request->shipping_address,
             'city' => $request->city,
             'state' => $request->state,
             'zip_code' => $request->zip_code,
             'country' => 'USA',
             'vin_number' => $request->vin_number,
             'vehicle_details' => $request->vehicle_details ?: ($request->vehicle_year . ' ' . $request->vehicle_make . ' ' . $request->vehicle_model),
             'subtotal' => $cartData['subtotal'],
             'coupon_code' => $cartData['coupon']['code'] ?? null,
             'discount_amount' => $cartData['discount'],
             'shipping_amount' => $cartData['shipping'],
             'tax_amount' => $cartData['tax'],
             'total_amount' => $cartData['total'],
             'currency' => 'USD',
             'payment_method' => $request->payment_method,
             'payment_status' => $paymentStatus,
             'order_status' => $orderStatus,
             'transaction_id' => $transactionId,
             'failure_reason' => $failureReason,
             'notes' => $request->order_notes,
         ]);

         // Create Order Items
         foreach ($cartData['items'] as $item) {
             OrderItem::create([
                 'order_id' => $order->id,
                 'product_id' => $item['id'],
                 'product_name' => $item['name'],
                 'part_number' => $item['part_number'] ?? null,
                 'price' => $item['price'],
                 'quantity' => $item['quantity'],
                 'total' => round($item['price'] * $item['quantity'], 2),
             ]);
         }

         if ($paymentStatus === 'SUCCESS') {
             if (!empty($cartData['coupon']['code'])) {
                 Coupon::where('code', $cartData['coupon']['code'])->increment('used_count');
             }
             // Clear cart session only on success
             session()->forget('cart');
             session()->forget('applied_coupon');

             return redirect()->route('order.success', ['order_number' => $order->order_number]);
         } else {
             // Keep cart intact so customer can retry easily
             return redirect()->route('order.failed', ['order_number' => $order->order_number])
                 ->with('error', $failureReason);
         }
     }

    /**
     * Order Success Confirmation Page
     */
    public function orderSuccess($orderNumber)
    {
        $order = Order::with('items')->where('order_number', $orderNumber)->firstOrFail();
        return view('Frontend.order-success', compact('order'));
    }

    /**
     * Order Failed Page
     */
    public function orderFailed($orderNumber)
    {
        $order = Order::with('items')->where('order_number', $orderNumber)->firstOrFail();
        return view('Frontend.order-failed', compact('order'));
    }

    /**
     * Download or Preview Order Invoice PDF
     */
    public function downloadInvoice($orderNumber)
    {
        $order = Order::with('items')->where('order_number', $orderNumber)->firstOrFail();

        $logoPath = public_path('frontend/logo/logo.png');
        $logoBase64 = file_exists($logoPath) ? base64_encode(file_get_contents($logoPath)) : '';

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('Invoices.invoice-pdf', compact('order', 'logoBase64'));
        $pdf->setPaper('A4', 'portrait');

        if (request()->query('preview')) {
            return $pdf->stream('Invoice-' . $order->order_number . '.pdf');
        }

        return $pdf->download('Invoice-' . $order->order_number . '.pdf');
    }
}
