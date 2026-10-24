@extends('layouts.frontend')
@section('title') Refund Policy | Auto Vehicle Parts @endsection
@section('content')
<main>

    <!-- breadcrumb-area -->
    <section class="breadcrumb-area d-flex align-items-center" style="background-image:url(/frontend/img/bg/footer-bg.png)">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-xl-12 col-lg-12">
                    <div class="breadcrumb-wrap text-left">
                        <div class="breadcrumb-title">
                            <h2>Refund Policy</h2>
                            <div class="breadcrumb-wrap">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Refund Policy</li>
                                    </ol>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- breadcrumb-area-end -->

    <!-- Refund Policy Content -->
    <section class="team-area-content">
        <div class="container">
            <div class="lower-content">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="s-about-content wow fadeInRight" data-animation="fadeInRight" data-delay=".2s">

                            <h3>Refund Policy</h3>
                            <p><strong>Last Updated:</strong> October 24, 2026</p>

                            <p>If you receive a damaged or incorrect item, please notify us immediately. <strong>DO NOT SEND THE PART BACK</strong> without contacting us first. The return of the part is the customer&rsquo;s responsibility and must be completed as follows:</p>

                            <h4>1. Return Period and Process</h4>
                            <ul>
                                <li>Items can be returned within 15 days (about 2 weeks) of delivery, based on the tracking number. It may take up to 25 days (about 3 and a half weeks) for a part to reach us once returned.</li>
                                <li>After we receive the part at our fulfillment center, allow up to seven business days for the refund to appear in your account.</li>
                                <li>Single items from a set cannot be returned individually.</li>
                                <li>Refunds will be applied to the original method of payment. If the original credit card is unavailable, store credit will be issued, which can be used for future purchases with Auto Vehicle Parts.</li>
                                <li>Items that are damaged upon return may be denied a refund or exchange.</li>
                            </ul>

                            <h4>2. Return Request and Shipping</h4>
                            <ul>
                                <li>When your return request is accepted, we will send an email to your registered email address with the subject line &ldquo;Return Assistance from Auto Vehicle Parts.&rdquo;</li>
                                <li>Ensure you ship the return by the date specified in this email to avoid delays or issues with the refund process.</li>
                                <li>Depending on the seller&rsquo;s policy, you will either receive a return shipping label from the seller or be asked to purchase one directly from a carrier.</li>
                                <li>The seller pays for return shipping if they offer a free returns policy or if the item was damaged or faulty. The customer pays for return shipping if returning the item due to a change of mind, provided the seller&rsquo;s return policy states that buyers are responsible for return shipping.</li>
                                <li>Use a carrier that provides tracking and upload the tracking details to the return request.</li>
                            </ul>

                            <h4>3. Return Address and Refunds</h4>
                            <ul>
                                <li>The latest date to ship the return will be shown in an email sent to the buyer, as well as within the return request. If the buyer does not ship the return by the specified date, we may close the return request.</li>
                                <li>The return address will be provided at the conclusion of the contract and upon receipt of the RMA. Items returned to Auto Vehicle Parts will be redirected to the correct address specified in the RMA instructions at your expense.</li>
                                <li>A refund will be issued as soon as we receive the goods. <strong>NO OPERATING COSTS</strong> are covered for any parts sold by Auto Vehicle Parts.</li>
                                <li>If the part is found to be defective, we will first attempt to replace it. If a replacement cannot be sent, a full refund of the total sale will be issued. Goods must reach us before the warranty expires to be eligible for a refund. Return shipping charges are non-refundable.</li>
                            </ul>

                            <h4>4. Restocking Fees and Non-Returnable Items</h4>
                            <p>For returns due to reasons other than those specified above, including an incorrect part ordered by the customer or a part that is no longer needed, a 30% restocking fee and the original shipping cost may be deducted from the refund.</p>
                            <p>Please consult with a mechanical specialist before ordering to ensure the correct part is selected. We strive to send the correct parts.</p>
                            <p>Items returned after the warranty period has expired are non-refundable.</p>

                            <h4>5. Contact Us</h4>
                            <p>If you have questions about a return, exchange, or refund, please contact our team through <a href="{{ url('/') }}">Auto Vehicle Parts</a> before sending any part back.</p>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Refund Policy Content -->

</main>
@endsection