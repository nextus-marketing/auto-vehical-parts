<!doctype html>
<html class="no-js" lang="en">
<head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>@yield('title') </title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="shortcut icon" type="image/x-icon" href="frontend/img/favicon.ico">
        <!-- Place favicon.ico in the root directory -->

		<!-- CSS here -->
        <link rel="stylesheet" href="frontend/css/bootstrap.min.css">
        <link rel="stylesheet" href="frontend/css/animate.min.css">
        <link rel="stylesheet" href="frontend/css/magnific-popup.css">
        <link rel="stylesheet" href="frontend/fontawesome/css/all.min.css">
        <link rel="stylesheet" href="frontend/css/dripicons.css">
        <link rel="stylesheet" href="frontend/css/slick.css">
        <link rel="stylesheet" href="frontend/css/meanmenu.css">
        <link rel="stylesheet" href="frontend/css/default.css">
        <link rel="stylesheet" href="frontend/css/style.css">
        <link rel="stylesheet" href="frontend/css/responsive.css">
    </head>
    <body>
        <!-- header -->
        <header class="header-area header-three">  
           <div class="header-top second-header d-none d-md-block">
                <div class="container">
                    <div class="row align-items-center">      
                        <div class="col-lg-6 col-md-6 d-none d-lg-block">
                             <div class="header-cta">
                               <ul>                                   
                                    <li>
                                        <i class="icon fal fa-envelope"></i>
                                        <span>info@example.com</span>
                                    </li>
                                    <li>
                                        <i class="icon fal fa-phone"></i>
                                        <span>+91-7052-101-786</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 d-none d-lg-block text-right">
                           <div class="header-social">
                                <span>
                                    <a href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                                    <a href="#" title="LinkedIn"><i class="fab fa-instagram"></i></a>               
                                    <a href="#" title="Twitter"><i class="fab fa-twitter"></i></a>
                                    <a href="#" title="Twitter"><i class="fab fa-youtube"></i></a>
                                   </span>                    
                                   <!--  /social media icon redux -->                               
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 d-none d-lg-block">
                             <a href="#0" class="top-btn">Call Now <i class="fal fa-angle-right"></i></a>
                              
                        </div>
                         
                        
                    </div>
                </div>
            </div>		
			  <div id="header-sticky" class="menu-area">
                <div class="container">
                    <div class="second-menu">
                        <div class="row align-items-center">
                            <div class="col-xl-2 col-lg-2">
                                <div class="logo">
                                    <a href="/"><img src="frontend/img/logo/logo.png" alt="logo"></a>
                                </div>
                            </div>
                           <div class="col-xl-7 col-lg-7">
                              
                                <div class="main-menu text-center text-xl-right">
                                    <nav id="mobile-menu">
                                          <ul>
                                            <li>
                                                <a href="/">Home</a>
                                            </li>
                                            <li><a href="/about">About Us</a></li>
                                            
                                            <li class="has-sub"><a href="#">Find By Brands</a>
												<ul>
                                                    <li><a href="#0">Gallery</a></li>
                                                    <li><a href="#0">Pricing</a></li>
                                                    <li><a href="#0">Faq</a></li>
                                                    <li><a href="#0">Team</a></li>
                                                    <li><a href="#0">Team Details</a></li>
                                                    <li><a href="#0">Shop</a></li>
													<li><a href="#0">Shop Details</a>
                                                  </ul>
											</li>

                                            <li class="has-sub"><a href="#">Shop Now</a>
												<ul>
                                                    <li><a href="#0">Gallery</a></li>
                                                    <li><a href="#0">Pricing</a></li>
                                                    <li><a href="#0">Faq</a></li>
                                                    <li><a href="#0">Team</a></li>
                                                    <li><a href="#0">Team Details</a></li>
                                                    <li><a href="#0">Shop</a></li>
													<li><a href="#0">Shop Details</a>
                                                  </ul>
											</li>
                                            
                                              
                                            <li><a href="/contact">Contact Us</a></li>                                               
                                        </ul>
                                    </nav>
                                </div>
                            </div>   
                             <div class="col-xl-3 col-lg-3 d-none d-lg-block">
                               <div class="right-menu">
                                    <ul>
                                        <li> 
                                            <div class="menu-search text-right">
                                                <a href="#" class="menu-tigger"><i class="fas fa-search"></i></a>
                                             </div>
                                        </li>
                                        <li>
                                            <div class="icon"><img src="frontend/img/bg/top-m-icon.png" alt="top-m-icon.png"></div>
                                            <div class="text">
                                                <span>Call Now !</span>
                                                <strong>+91 705 210-1786</strong>
                                            </div>
                                        </li>
                                    </ul>
                                 </div>
                            </div>
                            
                                <div class="col-12">
                                    <div class="mobile-menu"></div>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- header-end -->
        <!-- main-area -->
        @yield('content')
        <!-- main-area-end -->

        <!-- footer -->
        <footer class="footer-bg footer-p pt-90" style="background-color: #0c2957; background-image: url(frontend/img/bg/footer-bg.png);">
            <div class="footer-top pb-70">
                <div class="container">
                    <div class="row justify-content-between">
                        
                        <div class="col-xl-4 col-lg-4 col-sm-6">
                            <div class="footer-widget mb-30">
                                <div class="f-widget-title">
                                    <h2>About Company</h2>
                                </div>
                                <div class="f-contact">
                                     <p>Suspendisse non sem ante. Cras pretium gravida leo a convallis. Nam malesuada interdum metus, sit amet dictum ante congue eu. Maecenas ut maximus enim.</p>
                                    
                                    </div>
                                 <div class="footer-social mt-10">                                    
                                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                                    <a href="#"><i class="fab fa-instagram"></i></a>
                                    <a href="#"><i class="fab fa-twitter"></i></a>
                                </div>    
                            </div>
                        </div>
						<div class="col-xl-2 col-lg-2 col-sm-6">
                            <div class="footer-widget mb-30">
                                <div class="f-widget-title">
                                    <h2>Our Links</h2>
                                </div>
                                <div class="footer-link">
                                    <ul>                                        
                                        <li><a href="/">Home</a></li>
                                        <li><a href="#0"> About</a></li>
                                        <li><a href="#0"> Services </a></li>
                                        <li><a href="#0"> Contact Us</a></li>
                                        <li><a href="#0">Blog </a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-sm-6">
                            <div class="footer-widget mb-30">
                                <div class="f-widget-title">
                                    <h2>Latest Post</h2>
                                </div>
                                <div class="recent-blog-footer">
                                    <ul>
                                        <li>
                                            <div class="thum"> <img src="frontend/img/blog/s-blogimg-01.png" alt="img"></div>
                                            <div class="text"> <a href="#">Nothing impossble to need hard work</a>
                                            <span>7 March, 2020</span></div>
                                        </li>
                                        
                                        <li>
                                            <div class="thum"> <img src="frontend/img/blog/s-blogimg-02.png" alt="img"></div>
                                            <div class="text"> <a href="#">Nothing impossble to need hard work</a>
                                            <span>7 March, 2020</span></div>
                                        </li>
                                    
                                    </ul>
                                   
                                     
                                </div>
                            </div>
                        </div>  
                        <div class="col-xl-3 col-lg-3 col-sm-6">
                            <div class="footer-widget mb-30">
                                <div class="f-widget-title">
                                  <h2>Subscribe Now !</h2>
                                </div>
                                <div class="footer-link">
                                <p>Subscribe our newsletter to get our latest update & News</p>
                                 <div class="subricbe p-relative" data-animation="fadeInDown" data-delay=".4s" >
                                            <form action="https://htmldemo.zcubethemes.com/autozox/news-mail.php" method="post" class="contact-form ">
                                             <input type="text" id="email2" name="email2"  class="header-input" placeholder="Your Email..." required>
                                            <button class="btn header-btn"><i class="fas fa-location-arrow"></i></button>
                                            </form>
                                        </div>
                                </div>
                                
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
            <div class="copyright-wrap">
                <div class="container">
                    <div class="row align-items-center">
                         <div class="col-lg-4">          
                            
                                <img src="frontend/img/logo/f_logo.png" alt="img">
                        </div>
                        <div class="col-lg-4">
                          <div class="copy-text">
                                 Copyright &copy; Autozox 2022 . All rights reserved.       
                            </div>
                        </div>
                       
                       <div class="col-lg-4 text-right text-xl-right">
                           <ul>
                               <li><a href="#">Privacy</a></li>
                               <li><a href="#">Term &amp; Conditions</a></li>
                               <li><a href="#">Legal</a></li>
                           </ul>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- footer-end -->

		<!-- JS here -->
        <script src="/frontend/js/vendor/modernizr-3.5.0.min.js"></script>
        <script src="/frontend/js/vendor/jquery-3.6.0.min.js"></script>
        <script src="/frontend/js/popper.min.js"></script>
        <script src="/frontend/js/bootstrap.min.js"></script>
        <script src="/frontend/js/slick.min.js"></script>
        <script src="/frontend/js/ajax-form.js"></script>
        <script src="/frontend/js/paroller.js"></script>
        <script src="/frontend/js/wow.min.js"></script>
        <script src="/frontend/js/js_isotope.pkgd.min.js"></script>
        <script src="/frontend/js/imagesloaded.min.js"></script>
        <script src="/frontend/js/parallax.min.js"></script>
        <script src="/frontend/js/jquery.waypoints.min.js"></script>
        <script src="/frontend/js/jquery.counterup.min.js"></script>
        <script src="/frontend/js/jquery.scrollUp.min.js"></script>
        <script src="/frontend/js/jquery.meanmenu.min.js"></script>
        <script src="/frontend/js/parallax-scroll.js"></script>
        <script src="/frontend/js/jquery.magnific-popup.min.js"></script>
        <script src="/frontend/js/element-in-view.js"></script>
        <script src="/frontend/js/main.js"></script>
    </body>
</html>