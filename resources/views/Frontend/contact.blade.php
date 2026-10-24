@extends('layouts.frontend')
@section('title') Contact Us &amp; Free Part Quote | Auto Vehicle Parts @endsection
@section('content')
<main>
            
            <!-- breadcrumb-area -->
            <section class="breadcrumb-area d-flex align-items-center" style="background-image:url(/frontend/img/bg/footer-bg.png)">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-xl-12 col-lg-12">
                            <div class="breadcrumb-wrap text-left">
                                <div class="breadcrumb-title">
                                    <h2>Contact &amp; Part Quote</h2>    
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
                                     <div class="single-cta pb-30 mb-30 wow fadeInUp animated" data-animation="fadeInDown animated" data-delay=".2s">
                                        <div class="f-cta-icon">
                                            <i class="far fa-clock"></i>
                                        </div>
                                        <h5>Specialist Hours</h5>
                                        <p>Monday to Friday: 8:00 AM - 7:00 PM EST <br> 
                                            Saturday: 9:00 AM - 4:00 PM EST</p>
                                    </div>
                                     <div class="single-cta wow fadeInUp animated" data-animation="fadeInDown animated" data-delay=".2s">
                                        <div class="f-cta-icon">
                                            <i class="far fa-envelope-open"></i>
                                        </div>
                                        <h5>Contact &amp; Hotline</h5>
                                        <p><a href="mailto:sales@autovehicleparts.com">sales@autovehicleparts.com</a><br>
                                            <a href="tel:+18883709806"><strong>+1-888-370-9806</strong></a></p>
                                    </div>
                                </div>							
                        </div>
                        <div class="col-lg-8 order-2">
                            <div class="contact-bg02">
                                <div class="section-title center-align">
                                    <h2>
                                       Request a Free Part Quote &amp; Fitment Check
                                    </h2>
                                    <p style="margin-top:10px; color:#666;">Need a tested engine, transmission, body panel, or electrical module? Send us your vehicle details and our parts specialists will verify live recycler inventory and call or email you with the best price quote.</p>
                                </div>     
                                
                                @if (session('success'))
                                    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert" style="background:#d4edda; color:#155724; border-color:#c3e6cb; padding:15px; border-radius:6px;">
                                        <strong>Success!</strong> {{ session('success') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="float:right; border:0; background:none; font-size:18px; cursor:pointer;">&times;</button>
                                    </div>
                                @endif

                                <form action="{{ route('contact.submit') }}" method="post" class="contact-form mt-30">
                                    @csrf
                                    <div class="row">
                                    <div class="col-lg-6">
                                        <div class="contact-field p-relative c-name mb-20">                                    
                                            <input type="text" id="firstn" name="firstn" placeholder="Your Full Name *" required>
                                        </div>                               
                                    </div>

                                    <div class="col-lg-6">                               
                                        <div class="contact-field p-relative c-subject mb-20">                                   
                                            <input type="email" id="email" name="email" placeholder="Your Email Address *" required>
                                        </div>
                                    </div>		
                                    <div class="col-lg-6">                               
                                        <div class="contact-field p-relative c-subject mb-20">                                   
                                            <input type="tel" id="phone" name="phone" placeholder="Phone Number (for instant quote) *" required>
                                        </div>
                                    </div>	
                                    <div class="col-lg-6">                               
                                        <div class="contact-field p-relative c-subject mb-20">                                   
                                            <input type="text" id="vehicle" name="vehicle" placeholder="Year / Make / Model / VIN (e.g. 2018 Ford F150)" required>
                                        </div>
                                    </div>	
                                    <div class="col-lg-12">                               
                                        <div class="contact-field p-relative c-subject mb-20">                                   
                                            <input type="text" id="subject" name="subject" placeholder="Part Needed (e.g. Complete Engine, Transmission, Headlight...)" required>
                                        </div>
                                    </div>	
                                    <div class="col-lg-12">
                                        <div class="contact-field p-relative c-message mb-30">                                  
                                            <textarea name="message" id="message" cols="30" rows="5" placeholder="Additional details: transmission type, engine size, delivery zip code, or specific questions..."></textarea>
                                        </div>
                                        <div class="slider-btn">                                          
                                            <button class="btn ss-btn" type="submit"><span>Submit Part Request <i class="fal fa-arrow-right"></i></span></button>				
                                        </div>                             
                                    </div>
                                    </div>
                            </form>                            
                            </div>    
                        
						</div>
					</div>
                    
                </div>
               
            </section>
            <!-- contact-area-end -->

            <div class="map fix" style="background: #f5f5f5;">
                <div class="container-flud">
                    <div class="row">
                        <div class="col-lg-12">
                       <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3106.6457488!2d-77.2721!3d38.7962!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89b64c8e1f7e8f3f%3A0x1f2e3d4c5b6a7890!2s9620+Hillock+Ct%2C+Burke%2C+VA+22015!5e0!3m2!1sen!2sus!4v1618567685329!5m2!1sen!2sus" width="100%" height="450" style="border:0; display:block;" allowfullscreen="" loading="lazy"></iframe>
                        </div>
                    </div>
                </div>
            </div>
		     <!-- map-area-end -->
             
        </main>
@endsection