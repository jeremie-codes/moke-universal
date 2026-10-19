   <footer class="footer bg-dark">
       <div class="footer-middle">
           <div class="container">
               <div class="row">
                <div class="col-lg-4 col-sm-6">
                       <div class="widget">
                           <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" width="100" class="footer-logo"> <br>
                           <p>MokeUniversal - la plateforme de commerce en ligne par excellence.</p>
                       </div>
                       <!-- End .widget -->

                       <div class="social-icons">
                            <a href="{{ $settings->facebook ?? '#' }}" class="social-icon social-facebook icon-facebook" target="_blank"  title="Facebook"></a>
                            <a href="{{ $settings->twitter ?? '#' }}" class="social-icon social-twitter icon-twitter" target="_blank" title="Twitter"></a>
                            <a href="{{ $settings->instagram ?? '#' }}" class="social-icon social-instagram icon-instagram" target="_blank" title="Instagram"></a>
                        </div>
                        <!-- End .social-icons -->
                   </div>
                   <!-- End .col-lg-4 -->


                   <div class="col-lg-4 col-sm-6">
                       <div class="widget">
                           <h4 class="widget-title">Contact Info</h4>
                           <ul class="contact-info">
                               <li>
                                   <span class="contact-info-label">Address:</span> {{ $settings->address ?? "Adress non définie" }}
                               </li>
                               <li>
                                   <span class="contact-info-label">Phone:</span><a href="tel:{{ $settings->phone ?? '' }}">{{ $settings->phone ?? '(Numéro non défini)' }}</a>
                               </li>
                               <li>
                                    <span class="contact-info-label">Email:</span>
                                    <a href="mailto:{{ $settings->email ?? '' }}">
                                        {{ $settings->email ?? 'Email non défini' }}
                                    </a>
                               </li>
                           </ul>
                       </div>
                       <!-- End .widget -->
                   </div>
                   <!-- End .col-lg-4 -->


                   <div class="col-lg-4 col-sm-6">
                       <div class="widget widget-newsletter">
                           <h4 class="widget-title">Inscrivez-vous à la newsletter</h4>
                           <p>Recevez toutes les dernières informations sur les événements, les ventes et les offres. Inscrivez-vous à la newsletter :</p>
                           <form action="{{ route('newsletter.store') }}" method="POST" class="mb-0">
                                @csrf
                               <input type="email" name="email" class="form-control m-b-3  @error('email') is-invalid @enderror" placeholder="Adresse e-mail" required>
                               @error('email')
                                   <div class="invalid-feedback">{{ $message }}</div>
                               @enderror
                               <input type="submit" class="btn btn-primary shadow-none" value="S'inscrire">
                           </form>
                       </div>
                       <!-- End .widget -->
                   </div>
                   <!-- End .col-lg-4 -->
               </div>
               <!-- End .row -->
           </div>
           <!-- End .container -->
       </div>
       <!-- End .footer-middle -->

       <div class="container">
           <div class="footer-bottom">
               <div class="container d-sm-flex align-items-center">
                   <div class="footer-left">
                       <span class="footer-copyright">© Porto eCommerce. 2021. All Rights Reserved</span>
                   </div>

                   <div class="footer-right ml-auto mt-1 mt-sm-0">
                       <div class="payment-icons">
                           <span class="payment-icon visa" style="background-image: url({{ asset('assets/images/payments/payment-visa.svg') }})"></span>
                           <span class="payment-icon mpesa" style="background-image: url({{ asset('assets/images/payments/mpesa.png') }})"></span>
                           <span class="payment-icon airtel" style="background-image: url({{ asset('assets/images/payments/airtel-w.png') }})"></span>
                           <span class="payment-icon orange" style="background-image: url({{ asset('assets/images/payments/orange-r.png') }})"></span>
                       </div>
                   </div>
               </div>
           </div>
           <!-- End .footer-bottom -->
       </div>
       <!-- End .container -->
   </footer>
   <!-- End .footer -->
