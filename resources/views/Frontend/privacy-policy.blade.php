@extends('layouts.frontend')
@section('title') Privacy Policy | Auto Vehicle Parts @endsection
@section('content')
<main>

    <!-- breadcrumb-area -->
    <section class="breadcrumb-area d-flex align-items-center" style="background-image:url(/frontend/img/bg/footer-bg.png)">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-xl-12 col-lg-12">
                    <div class="breadcrumb-wrap text-left">
                        <div class="breadcrumb-title">
                            <h2>Privacy Policy</h2>
                            <div class="breadcrumb-wrap">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Privacy Policy</li>
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

    <!-- Privacy Policy Content -->
    <section class="team-area-content">
        <div class="container">
            <div class="lower-content">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="s-about-content wow fadeInRight" data-animation="fadeInRight" data-delay=".2s">

                            <h3>Privacy Policy</h3>
                            <p><strong>Last Updated:</strong> October 24, 2026</p>

                            <p>Auto Vehicle Parts recognizes the importance of maintaining your privacy. We value our relationships with our customers and the trust they place in us by providing us with information.</p>
                            <p>Therefore, we need to explain how we collect, use and protect customer data. This Privacy Policy (&ldquo;Privacy Policy&rdquo;) applies to <a href="{{ url('/') }}">Auto Vehicle Parts</a> (&ldquo;Site&rdquo;, &ldquo;Website&rdquo;, &ldquo;we&rdquo; or &ldquo;us&rdquo;). It tells you how we collect and use personal information so that you can make an informed decision about using the Site.</p>
                            <p>Please read this statement before using our website or providing us with any personal data. We reserve the right to change the provisions of this Privacy Policy at any time.</p>
                            <p>Auto Vehicle Parts will notify you of these changes by posting the revised Privacy Policy on <a href="{{ url('/') }}">Auto Vehicle Parts</a> and stating the last update date in the Privacy Policy. Your use of the website after the revised statement is posted constitutes your acceptance of any such changes.</p>
                            <p>Therefore, we recommend that you familiarize yourself with our Policy while visiting the website to receive information about any changes or updates that have been made and to understand how any personal information you provide will be used.</p>

                            <h4>Information We Collect</h4>
                            <p>When you use our website, contact us, request information, or communicate with our team, we may collect information that you voluntarily provide to us. This information may include your name, email address, telephone number, vehicle information, and other information you choose to provide.</p>

                            <h4>How We Use Your Information</h4>
                            <p>We may use the information we collect to respond to inquiries, provide requested products or services, communicate with you, process requests, improve our website and services, and provide customer support.</p>

                            <h4>Protection of Your Information</h4>
                            <p>We take reasonable measures to protect the personal information provided through our website. However, no method of transmission over the Internet or method of electronic storage is completely secure, and we cannot guarantee absolute security.</p>

                            <h4>Third-Party Services</h4>
                            <p>Our website may use third-party service providers to support website functionality, analytics, communications, advertising, or other business operations. These third parties may have access to information only as necessary to perform services on our behalf and are expected to protect such information appropriately.</p>

                            <h4>Cookies and Tracking Technologies</h4>
                            <p>Our website may use cookies and similar technologies to improve website functionality, understand how visitors use the website, and enhance the overall user experience. You may be able to control cookies through your browser settings.</p>

                            <h4>Changes to This Privacy Policy</h4>
                            <p>We may update this Privacy Policy from time to time. Any changes will be posted on this page with an updated &ldquo;Last Updated&rdquo; date. We encourage you to review this Privacy Policy periodically for any updates.</p>

                            <h4>Contact Us</h4>
                            <p>If you have questions regarding this Privacy Policy or how your information is handled, please contact us through the contact information provided on <a href="{{ url('/') }}">Auto Vehicle Parts</a>.</p>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Privacy Policy Content -->

</main>
@endsection