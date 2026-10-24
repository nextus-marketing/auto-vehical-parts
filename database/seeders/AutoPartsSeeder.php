<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;

class AutoPartsSeeder extends Seeder
{
    public function run()
    {
        // 1. Categories
        $categoriesData = [
            [
                'name' => 'Used Engines & Powertrain',
                'slug' => 'used-engines',
                'index' => '1',
                'status' => 'ACTIVE',
                'home_featured' => 'ACTIVE',
                'description' => 'Tested OEM long blocks and complete engine assemblies with compression test verified and warranty guarantee.',
            ],
            [
                'name' => 'Automatic & Manual Transmissions',
                'slug' => 'transmissions',
                'index' => '2',
                'status' => 'ACTIVE',
                'home_featured' => 'ACTIVE',
                'description' => 'Shift-tested automatic and manual transmissions, transfer cases, and differential gears.',
            ],
            [
                'name' => 'Cylinder Heads & Valvetrain',
                'slug' => 'cylinder-heads',
                'index' => '3',
                'status' => 'ACTIVE',
                'home_featured' => 'ACTIVE',
                'description' => 'Machined and pressure-tested cylinder heads, camshafts, and intake manifold assemblies.',
            ],
            [
                'name' => 'Brake Systems & ABS Units',
                'slug' => 'brakes-abs',
                'index' => '4',
                'status' => 'ACTIVE',
                'home_featured' => 'ACTIVE',
                'description' => 'Tested ABS control modules, master cylinders, brake boosters, and performance calipers.',
            ],
            [
                'name' => 'Suspension, Shocks & Steering',
                'slug' => 'suspension-steering',
                'index' => '5',
                'status' => 'ACTIVE',
                'home_featured' => 'ACTIVE',
                'description' => 'Heavy-duty strut assemblies, control arms, steering racks, and steering knuckles.',
            ],
            [
                'name' => 'Headlights, Tail Lights & Body Panels',
                'slug' => 'lights-body-parts',
                'index' => '6',
                'status' => 'ACTIVE',
                'home_featured' => 'ACTIVE',
                'description' => 'OEM projector headlights, LED tail lamps, fenders, hoods, mirrors, and bumper covers.',
            ],
            [
                'name' => 'Electrical, Alternators & Starters',
                'slug' => 'electrical-starters',
                'index' => '7',
                'status' => 'ACTIVE',
                'home_featured' => 'ACTIVE',
                'description' => 'Bench-tested high output alternators, heavy-duty starters, fuse boxes, and ECM/ECU modules.',
            ],
            [
                'name' => 'Turbochargers & Superchargers',
                'slug' => 'turbos-superchargers',
                'index' => '8',
                'status' => 'ACTIVE',
                'home_featured' => 'ACTIVE',
                'description' => 'Balanced twin-scroll OEM turbochargers, intercoolers, and supercharger snout assemblies.',
            ],
        ];

        $categories = [];
        foreach ($categoriesData as $cat) {
            $categories[$cat['slug']] = Category::updateOrCreate(['slug' => $cat['slug']], $cat);
        }

        // 2. Products
        $productsData = [
            [
                'category_id' => $categories['used-engines']->id,
                'name' => 'Tested OEM Complete V6 / V8 Engine Assembly (Inspected Long Block)',
                'slug' => 'tested-oem-engine-assembly',
                'index' => '1',
                'price' => 1150.00,
                'compare_price' => 1650.00,
                'part_number' => 'ENG-OEM-54200-V6',
                'warranty' => '1-Year Comprehensive Replacement Guarantee',
                'mileage' => '54,200 Original Miles',
                'fitment' => '100% Guaranteed Exact Fit by VIN Verification (Ford, Chevy, Dodge, Toyota, Honda)',
                'stock_status' => 'IN_STOCK',
                'rating' => 5.00,
                'reviews_count' => 12,
                'home_featured' => 'ACTIVE',
                'status' => 'ACTIVE',
                'photo' => '/frontend/img/shop/details/large1.jpg',
                'other_photo' => [
                    '/frontend/img/shop/details/large1.jpg',
                    '/frontend/img/shop/details/large2.jpg',
                    '/frontend/img/shop/details/large3.jpg'
                ],
                'short_description' => 'Authentic factory OEM recycled engine assembly sourced from low-mileage donor vehicle. Compression tested (175-180 PSI), leak tested, and backed by a 1-year replacement warranty.',
                'description' => '<h4>Thorough Multi-Point Quality Assurance</h4>
<p>Before being cataloged, this OEM engine was subjected to strict multi-point technical testing by certified ASE automotive technicians. The engine underwent cold cranking compression tests showing uniform cylinder PSI with zero variance beyond factory tolerances. A bore-scope camera inspection was conducted to verify that cylinder walls show clean cross-hatching without scoring or carbon fouling.</p>
<h4>What is Included in This Long Block Assembly:</h4>
<ul>
  <li>Engine Block & Rotating Crankshaft Assembly</li>
  <li>Cylinder Heads with Camshafts and Valves</li>
  <li>Intake Manifold & Timing Cover with Intact Chain/Belt</li>
  <li>OEM Oil Pan and Valve Covers (Sealed)</li>
  <li>Crankshaft & Camshaft Position Sensors</li>
</ul>
<p><em>Note: Wearable items such as spark plugs, oil filters, water pump, external alternator, and wiring harness should be inspected and transferred from your existing vehicle or replaced new during installation.</em></p>',
            ],
            [
                'category_id' => $categories['transmissions']->id,
                'name' => 'Automatic Transmission Assembly (Shift Tested & Dyno Inspected)',
                'slug' => 'automatic-transmission-assembly',
                'index' => '2',
                'price' => 850.00,
                'compare_price' => 1200.00,
                'part_number' => 'TRN-6SPD-AUTO-88',
                'warranty' => '1-Year Comprehensive Replacement Guarantee',
                'mileage' => '48,100 Verified Miles',
                'fitment' => 'Guaranteed VIN match for 6-Speed & 8-Speed automatic applications',
                'stock_status' => 'IN_STOCK',
                'rating' => 4.90,
                'reviews_count' => 8,
                'home_featured' => 'ACTIVE',
                'status' => 'ACTIVE',
                'photo' => '/frontend/img/shop/shop2.jpg',
                'other_photo' => ['/frontend/img/shop/shop2.jpg'],
                'short_description' => 'Complete shift-tested OEM automatic gearbox with torque converter included. Fluid tested clean with zero slippage or clutch material.',
                'description' => '<h4>Complete Dyno & Hydraulic Pressure Tested Transmission</h4>
<p>Every automatic transmission in our inventory goes through dynamic bench testing to confirm proper engagement of forward and reverse clutches, smooth valve body shifting, and lockup solenoid operation. Includes inspected torque converter.</p>',
            ],
            [
                'category_id' => $categories['cylinder-heads']->id,
                'name' => 'OEM Cylinder Head Assembly (Resurfaced & Pressure Tested)',
                'slug' => 'oem-cylinder-head-assembly',
                'index' => '3',
                'price' => 420.00,
                'compare_price' => 600.00,
                'part_number' => 'CYL-HD-ALUM-01',
                'warranty' => '6-Month Comprehensive Warranty',
                'mileage' => 'Low Mileage Verified',
                'fitment' => 'Direct replacement for OEM factory cylinder head casting',
                'stock_status' => 'IN_STOCK',
                'rating' => 4.80,
                'reviews_count' => 5,
                'home_featured' => 'ACTIVE',
                'status' => 'ACTIVE',
                'photo' => '/frontend/img/shop/shop3.jpg',
                'other_photo' => ['/frontend/img/shop/shop3.jpg'],
                'short_description' => 'Precision magnaflux crack-inspected and flat-milled cylinder head. Assembled with OEM valves, springs, and camshaft.',
                'description' => '<p>Pressure tested to 40 PSI in a hot water tank to ensure zero micro-cracks. Deck surface precision milled to factory RA smoothness for perfect head gasket sealing.</p>',
            ],
            [
                'category_id' => $categories['brakes-abs']->id,
                'name' => 'ABS Pump & Hydraulic Control Module (Tested & Programmed)',
                'slug' => 'abs-pump-hydraulic-control-module',
                'index' => '4',
                'price' => 295.00,
                'compare_price' => 450.00,
                'part_number' => 'ABS-MOD-5882',
                'warranty' => '90-Day Replacement Guarantee',
                'mileage' => '39,000 Miles',
                'fitment' => 'Exact part number and VIN match guaranteed',
                'stock_status' => 'IN_STOCK',
                'rating' => 4.95,
                'reviews_count' => 9,
                'home_featured' => 'ACTIVE',
                'status' => 'ACTIVE',
                'photo' => '/frontend/img/shop/shop4.jpg',
                'other_photo' => ['/frontend/img/shop/shop4.jpg'],
                'short_description' => 'Fully scanned electronic brake control module (EBCM) with zero fault codes. High-pressure pump motor tested.',
                'description' => '<p>Eliminate dashboard ABS and Traction Control warning lights with this pre-tested OEM ABS pump and electronic module assembly.</p>',
            ],
            [
                'category_id' => $categories['suspension-steering']->id,
                'name' => 'Heavy Duty Front Strut Assembly & Coil Spring Pair',
                'slug' => 'front-strut-assembly-coil-spring',
                'index' => '5',
                'price' => 195.00,
                'compare_price' => 280.00,
                'part_number' => 'STRUT-PAIR-HD-90',
                'warranty' => '1-Year Warranty',
                'mileage' => '32,000 Miles',
                'fitment' => 'Direct bolt-on replacement for left & right front suspension',
                'stock_status' => 'IN_STOCK',
                'rating' => 4.75,
                'reviews_count' => 6,
                'home_featured' => 'ACTIVE',
                'status' => 'ACTIVE',
                'photo' => '/frontend/img/shop/shop5.jpg',
                'other_photo' => ['/frontend/img/shop/shop5.jpg'],
                'short_description' => 'Tested gas pressurized struts with undamaged boots, fresh top bearing mounts, and factory spec coil springs.',
                'description' => '<p>Restores OEM ride quality, vehicle balance, and cornering stability without requiring dangerous spring compression tools.</p>',
            ],
            [
                'category_id' => $categories['lights-body-parts']->id,
                'name' => 'OEM LED Projector Headlight Assembly (Passenger / Driver)',
                'slug' => 'oem-led-projector-headlight-assembly',
                'index' => '6',
                'price' => 345.00,
                'compare_price' => 520.00,
                'part_number' => 'LGT-LED-PROJ-01',
                'warranty' => '90-Day Warranty',
                'mileage' => 'Salvaged from clean title vehicle',
                'fitment' => '100% factory original tabs and clear lens with no broken clips',
                'stock_status' => 'IN_STOCK',
                'rating' => 5.00,
                'reviews_count' => 14,
                'home_featured' => 'ACTIVE',
                'status' => 'ACTIVE',
                'photo' => '/frontend/img/shop/shop6.jpg',
                'other_photo' => ['/frontend/img/shop/shop6.jpg'],
                'short_description' => 'Crystal clear lens with zero haze or yellowing. All mounting brackets and ballast modules intact and fully functioning.',
                'description' => '<p>OEM factory headlamp providing superior illumination and beam cutoff compared to cheap aftermarket copies.</p>',
            ],
            [
                'category_id' => $categories['electrical-starters']->id,
                'name' => 'High Output OEM Alternator (150A - 220A Bench Tested)',
                'slug' => 'high-output-oem-alternator',
                'index' => '7',
                'price' => 145.00,
                'compare_price' => 220.00,
                'part_number' => 'ALT-OEM-160A',
                'warranty' => '1-Year Comprehensive Warranty',
                'mileage' => '41,000 Miles',
                'fitment' => 'Original factory fitment with matching pulley & regulator connector',
                'stock_status' => 'IN_STOCK',
                'rating' => 4.85,
                'reviews_count' => 7,
                'home_featured' => 'ACTIVE',
                'status' => 'ACTIVE',
                'photo' => '/frontend/img/shop/shop1.jpg',
                'other_photo' => ['/frontend/img/shop/shop1.jpg'],
                'short_description' => 'Tested under high electrical load. Steady 14.4V charging output with smooth rotor bearings and noise-free operation.',
                'description' => '<p>Genuine OEM alternator tested on our specialized test bench to ensure optimal charging voltage under full cabin load.</p>',
            ],
            [
                'category_id' => $categories['turbos-superchargers']->id,
                'name' => 'OEM Twin-Scroll Turbocharger Assembly (Shaft Play Inspected)',
                'slug' => 'oem-twin-scroll-turbocharger-assembly',
                'index' => '8',
                'price' => 580.00,
                'compare_price' => 890.00,
                'part_number' => 'TRB-TWIN-SCROLL-20',
                'warranty' => '6-Month Comprehensive Warranty',
                'mileage' => '36,500 Miles',
                'fitment' => 'Factory turbocharger manifold and wastegate actuator included',
                'stock_status' => 'IN_STOCK',
                'rating' => 4.90,
                'reviews_count' => 11,
                'home_featured' => 'ACTIVE',
                'status' => 'ACTIVE',
                'photo' => '/frontend/img/shop/shop2.jpg',
                'other_photo' => ['/frontend/img/shop/shop2.jpg'],
                'short_description' => 'Zero axial shaft play, pristine turbine and compressor wheels, functioning wastegate actuator, and pressure-checked oil ports.',
                'description' => '<p>Factory original turbocharger delivering peak boost pressure and rapid spool-up response without smoking or seal leaks.</p>',
            ],
        ];

        foreach ($productsData as $prod) {
            Product::updateOrCreate(['slug' => $prod['slug']], $prod);
        }

        // 3. Coupons
        $couponsData = [
            [
                'code' => 'WELCOME10',
                'title' => 'New Customer 10% Discount',
                'discount_type' => 'PERCENTAGE',
                'discount_value' => 10.00,
                'min_order_amount' => 100.00,
                'status' => 'ACTIVE',
                'description' => 'Get 10% off on your used auto part order.',
            ],
            [
                'code' => 'PARTS50',
                'title' => '$50 Instant Savings',
                'discount_type' => 'FIXED',
                'discount_value' => 50.00,
                'min_order_amount' => 400.00,
                'status' => 'ACTIVE',
                'description' => 'Take $50 off any transmission, engine, or order exceeding $400 USD.',
            ],
            [
                'code' => 'OEM100',
                'title' => '$100 Premium Order Discount',
                'discount_type' => 'FIXED',
                'discount_value' => 100.00,
                'min_order_amount' => 800.00,
                'status' => 'ACTIVE',
                'description' => 'Special discount for complete engine and drivetrain assemblies.',
            ],
            [
                'code' => 'SUMMER15',
                'title' => 'Summer Special 15% Off',
                'discount_type' => 'PERCENTAGE',
                'discount_value' => 15.00,
                'min_order_amount' => 250.00,
                'status' => 'ACTIVE',
                'description' => 'Exclusive summer promotional coupon code for auto repair shops and car owners.',
            ],
        ];

        foreach ($couponsData as $cpn) {
            Coupon::updateOrCreate(['code' => $cpn['code']], $cpn);
        }

        // 4. Sample Successful & Failed Orders
        $firstProduct = Product::first();

        // Successful Order
        $successOrder = Order::updateOrCreate(
            ['order_number' => 'AVP-2026-1001'],
            [
                'customer_name' => 'Michael Anderson',
                'customer_email' => 'm.anderson@example.com',
                'customer_phone' => '+1 (555) 234-5678',
                'shipping_address' => '742 Evergreen Terrace',
                'city' => 'Dallas',
                'state' => 'TX',
                'zip_code' => '75001',
                'country' => 'USA',
                'vin_number' => '1FA6P8CF9H5123456',
                'vehicle_details' => '2019 Ford F-150 3.5L V6 EcoBoost',
                'subtotal' => 1150.00,
                'coupon_code' => 'PARTS50',
                'discount_amount' => 50.00,
                'shipping_amount' => 0.00,
                'tax_amount' => 88.00,
                'total_amount' => 1188.00,
                'currency' => 'USD',
                'payment_method' => 'CREDIT_CARD',
                'payment_status' => 'SUCCESS',
                'order_status' => 'CONFIRMED',
                'transaction_id' => 'ch_3N8s9f2eZvKYlo2C09zKSuccess',
                'notes' => 'Customer confirmed delivery with liftgate required for engine drop-off.',
            ]
        );

        OrderItem::updateOrCreate(
            ['order_id' => $successOrder->id, 'product_name' => $firstProduct->name],
            [
                'product_id' => $firstProduct->id,
                'part_number' => $firstProduct->part_number,
                'price' => 1150.00,
                'quantity' => 1,
                'total' => 1150.00,
            ]
        );

        // Failed Order
        $failedOrder = Order::updateOrCreate(
            ['order_number' => 'AVP-2026-1002'],
            [
                'customer_name' => 'David Miller',
                'customer_email' => 'david.m@example.com',
                'customer_phone' => '+1 (555) 876-5432',
                'shipping_address' => '1044 Sunset Blvd',
                'city' => 'Los Angeles',
                'state' => 'CA',
                'zip_code' => '90028',
                'country' => 'USA',
                'vin_number' => '2T1BR32E83C123456',
                'vehicle_details' => '2018 Toyota Camry 2.5L 4-Cyl',
                'subtotal' => 850.00,
                'coupon_code' => 'WELCOME10',
                'discount_amount' => 85.00,
                'shipping_amount' => 0.00,
                'tax_amount' => 61.20,
                'total_amount' => 826.20,
                'currency' => 'USD',
                'payment_method' => 'CREDIT_CARD',
                'payment_status' => 'FAILED',
                'order_status' => 'FAILED',
                'transaction_id' => 'ch_3N8s9f2eZvKYlo2C09zKFailed',
                'failure_reason' => 'Card declined: Insufficient funds in cardholder account during gateway charge attempt.',
                'notes' => 'Transaction failed at payment gateway. Automated notification sent to customer to update payment method.',
            ]
        );

        OrderItem::updateOrCreate(
            ['order_id' => $failedOrder->id, 'product_name' => 'Automatic Transmission Assembly'],
            [
                'product_id' => null,
                'part_number' => 'TRN-6SPD-AUTO-88',
                'price' => 850.00,
                'quantity' => 1,
                'total' => 850.00,
            ]
        );
    }
}
