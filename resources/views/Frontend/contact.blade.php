@extends('layouts.frontend')
@section('title') Contact Us | Auto Vehicle Parts @endsection
@section('content')
<main>

    <!-- breadcrumb-area -->
    <section class="breadcrumb-area d-flex align-items-center" style="background-image:url(/frontend/img/bg/footer-bg.png)">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-xl-12 col-lg-12">
                    <div class="breadcrumb-wrap text-left">
                        <div class="breadcrumb-title">
                            <h2>Contact Us</h2>
                            <div class="breadcrumb-wrap">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Contact Us</li>
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

    <!-- contact-area -->
    <section id="contact" class="contact-area after-none contact-bg pt-120 pb-120 p-relative fix">
        <div class="container">
            <div class="row justify-content-center align-items-center">

                <div class="col-lg-4 order-1">
                    <div class="contact-info">
                        <div class="single-cta pb-30 mb-30 wow fadeInUp animated" data-animation="fadeInDown animated" data-delay=".2s">
                            <div class="f-cta-icon">
                                <i class="far fa-map"></i>
                            </div>
                            <h5>Our Location</h5>
                            <p>9620 Hillock Ct Burke, VA 22015, USA</p>
                        </div>
                        <div class="single-cta wow fadeInUp animated" data-animation="fadeInDown animated" data-delay=".2s">
                            <div class="f-cta-icon">
                                <i class="far fa-envelope-open"></i>
                            </div>
                            <h5>Contact &amp; Hotline</h5>
                            <p>
                                <a href="mailto:sales@autovehicleparts.com">sales@autovehicleparts.com</a><br>
                                <a href="tel:+18883709806"><strong>+1-888-370-9806</strong></a>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8 order-2">
                    <div class="contact-bg02 text-center">
                        <div class="section-title center-align">
                            <h2>Call Us for a Free Part Quote</h2>
                            <p style="margin-top:10px; color:#666;">
                                Need a tested engine, transmission, body panel, or electrical module?
                                Call our parts specialists with your vehicle details (Year / Make / Model / VIN)
                                and we'll check live recycler inventory and give you the best price.
                            </p>
                        </div>

                        <div class="slider-btn mt-30">
                            <a href="tel:+18883709806" class="btn ss-btn">
                                <span><i class="fal fa-phone-alt"></i> Call Now: +1-888-370-9806</span>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <!-- contact-area-end -->

    <div class="map fix" style="background: #f5f5f5;">
        <div class="container-fluid p-0">
            <div class="row g-0">
                <div class="col-lg-12">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3109.681062723335!2d-77.27802109999999!3d38.7939453!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89b64df978e83c03%3A0x1e8f94883e861db3!2s9620%20Hillock%20Ct%2C%20Burke%2C%20VA%2022015%2C%20USA!5e0!3m2!1sen!2sin!4v1792950195822!5m2!1sen!2sin"
                        width="100%" height="450" style="border:0; display:block;" allowfullscreen="" loading="lazy"
                        referrerpolicy="strict-origin-when-cross-origin"></iframe>
                </div>
            </div>
        </div>
    </div>
    <!-- map-area-end -->

</main>
@endsection