<template>
  <div class="landing">
    <header class="default-header">
			<div class="container">
				<div class="header-wrap">
					<div class="header-top d-flex justify-content-between align-items-center">
						<div class="logo">
							<a href="#" v-scroll-to="'#home'"><img src="/storage/images/logo.png" alt=""></a>
						</div>
						<div class="main-menubar d-flex align-items-center">
							<nav :class="{'hide': !menuOpened}">
								<a href="#" v-scroll-to="'#home'">Home</a>
								<a href="#" v-scroll-to="'#service'">Services</a>
								<a href="#" v-scroll-to="'#appoinment'">Appoinment</a>
								<a href="#" v-scroll-to="'#gallery'">Gallery</a>
								
								<a href="/dashboard" v-if="isAuthorized">Dashboard</a>
								<a href="/auth/login" v-else>Login</a>
							</nav>
							<div class="menu-bar" @click="menuOpened = !menuOpened"><span :class="['lnr', menuOpened ? 'lnr-cross' : 'lnr-menu']"></span></div>
						</div>
					</div>
				</div>
			</div>
		</header>
		<section class="banner-area relative" id="home">
			<div class="container">
				<div class="row fullscreen align-items-center justify-content-center">
					<div class="banner-content col-lg-6 col-md-12">
						<h1 class="text-uppercase">
							We are the team <br>
							of excellence
						</h1>
						<p>
							Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore  et dolore magna aliqua.
						</p>
						<button class="primary-btn2 mt-20 text-uppercase ">Get Started<span class="lnr lnr-arrow-right"></span></button>
					</div>
					<div class="col-lg-6 d-flex align-self-end img-right">
						<img class="img-fluid" src="/storage/images/header-img.png" alt="">
					</div>
				</div>
			</div>
		</section>

    <!-- Start feature Area -->
			<section class="feature-area section-gap" id="service">
				<div class="container">
					<div class="row">
						<div class="col-lg-6">
							<div class="single-feature d-flex flex-row pb-30">
								<div class="icon">
									<span class="lnr lnr-rocket"></span>
								</div>
								<div class="desc">
									<h4 class="text-uppercase">24/7 emergency</h4>
									<p>
										inappropriate behavior is often laughed off as “boys will be boys,” <br> women face higher conduct women face higher conduct.
									</p>
								</div>
							</div>
							<div class="single-feature d-flex flex-row pb-30">
								<div class="icon">
									<span class="lnr lnr-chart-bars"></span>
								</div>
								<div class="desc">
									<h4 class="text-uppercase">X-Ray Service</h4>
									<p>
										inappropriate behavior is often laughed off as “boys will be boys,” <br> women face higher conduct women face higher conduct.
									</p>
								</div>
							</div>
							<div class="single-feature d-flex flex-row">
								<div class="icon">
									<span class="lnr lnr-bug"></span>
								</div>
								<div class="desc">
									<h4 class="text-uppercase">Intensive Care</h4>
									<p>
										inappropriate behavior is often laughed off as “boys will be boys,” <br> women face higher conduct women face higher conduct.
									</p>
								</div>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="single-feature d-flex flex-row pb-30">
								<div class="icon">
									<span class="lnr lnr-heart-pulse"></span>
								</div>
								<div class="desc">
									<h4 class="text-uppercase">24/7 emergency</h4>
									<p>
										inappropriate behavior is often laughed off as “boys will be boys,” <br> women face higher conduct women face higher conduct.
									</p>
								</div>
							</div>
							<div class="single-feature d-flex flex-row pb-30">
								<div class="icon">
									<span class="lnr lnr-paw"></span>
								</div>
								<div class="desc">
									<h4 class="text-uppercase">X-Ray Service</h4>
									<p>
										inappropriate behavior is often laughed off as “boys will be boys,” <br> women face higher conduct women face higher conduct.
									</p>
								</div>
							</div>
							<div class="single-feature d-flex flex-row">
								<div class="icon">
									<span class="lnr lnr-users"></span>
								</div>
								<div class="desc">
									<h4 class="text-uppercase">Intensive Care</h4>
									<p>
										inappropriate behavior is often laughed off as “boys will be boys,” <br> women face higher conduct women face higher conduct.
									</p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
			<!-- End feature Area -->


			<!-- Start about Area -->
		<section class="about-area" id="appoinment">
			<div class="container-fluid">
				<div class="row d-flex justify-content-end align-items-center">
					<div class="col-lg-6 col-md-12 about-left no-padding">
						<img class="img-fluid" src="/storage/images/about-img.jpg" alt="">
					</div>
					<div class="col-lg-6 col-md-12 about-right no-padding">
						<h1>Book an <br> Appoinment</h1>
						<form class="booking-form" id="myForm" @submit.prevent="sendConactLetter">
							<div class="row">
								<div class="col-lg-12 d-flex flex-column">
							 			<input name="name" placeholder="Patient name" :class="['form-control', 'mt-20', {'is-invalid': $v.form.name.$error }]" type="text" @blur="$v.form.name.$touch()" v-model.trim="form.name">
								</div>
							 	<div class="col-lg-6 d-flex flex-column">
										<input name="phone" placeholder="Phone" :class="['form-control', 'mt-20', {'is-invalid': $v.form.phone.$error }]" type="text" v-mask="phoneMask" @blur="$v.form.phone.$touch()" v-model.trim="form.phone">
								</div>
								<div class="col-lg-6 d-flex flex-column">
										<input name="email" placeholder="Email" :class="['form-control', 'mt-20', {'is-invalid': $v.form.email.$error }]" type="email" @blur="$v.form.email.$touch()" v-model.trim="form.email">
								</div>
								<div class="col-lg-12 flex-column">
										<textarea name="message" placeholder="Messege" :class="['form-control', 'mt-20', {'is-invalid': $v.form.message.$error }]" @blur="$v.form.message.$touch()" v-model.trim="form.message"></textarea>
								</div>

								<div class="col-lg-12 d-flex justify-content-end send-btn">
										<button class="submit-btn primary-btn mt-20 text-uppercase" :disabled="isLoading">confirm booking<span class="lnr lnr-arrow-right"></span></button>
								</div>

								<div class="alert-msg"></div>
							</div>
					  </form>
					</div>
				</div>
			</div>
		</section>
    
			<section class="gallery-area" id="gallery">
				<div class="container-fluid">
					<div class="row justify-content-center">
						<div class="col-md-8 pb-30 header-text">
							<h1>Our Recent Blogs</h1>
							<p>
								Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut <br> labore  et dolore magna aliqua.
							</p>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-3 col-sm-6 p-0">
							<img src="/storage/images/1.jpg" alt="">
						</div>
						<div class="col-lg-3 col-sm-6 p-0">
							<img src="/storage/images/2.jpg" alt="">
						</div>
						<div class="col-lg-3 col-sm-6 p-0">
							<img src="/storage/images/3.jpg" alt="">
						</div>
						<div class="col-lg-3 col-sm-6 p-0">
							<img src="/storage/images/4.jpg" alt="">
						</div>
					</div>
				</div>
			</section>
		

			<section class="facts-area pt-100 pb-100" v-view.once="viewHandler">
				<div class="container">
					<div class="row">
						<template v-for="i in 1">
							<div class="col-lg-3 col-md-6 single-fact">
								<h2>
									<count-to :startVal='0' :endVal='1300' :duration='3000' :autoplay="false" class="count" ref="count"></count-to>
								</h2>
								<p class="text-uppercase">Clients Served</p>
							</div>
							<div class="col-lg-3 col-md-6 single-fact">
								<h2>
									<count-to :startVal='0' :endVal='1300' :duration='3000' :autoplay="false" class="count" ref="count"></count-to>
								</h2>
								<p class="text-uppercase">X-rays Done</p>
							</div>
							<div class="col-lg-3 col-md-6 single-fact">
								<h2>
									<count-to :startVal='0' :endVal='1300' :duration='3000' :autoplay="false" class="count" ref="count"></count-to>
								</h2>
								<p class="text-uppercase">Worldwide stuff</p>
							</div>
							<div class="col-lg-3 col-md-6 single-fact">
								<h2>
									<count-to :startVal='0' :endVal='1300' :duration='3000' :autoplay="false" class="count" ref="count"></count-to>
								</h2>
								<p class="text-uppercase">Lives Saved</p>
							</div>
						</template>
					</div>
				</div>
			</section>
			<!-- end fact Area -->

			<!-- start footer Area -->
			<footer class="footer-area">
				<div class="container">
					<div class="row">
						<div class="col-lg-2  col-md-6">
							<div class="single-footer-widget">
								<h6>Top Products</h6>
								<ul class="footer-nav">
									<li><a href="#">Managed Website</a></li>
									<li><a href="#">Manage Reputation</a></li>
									<li><a href="#">Power Tools</a></li>
									<li><a href="#">Marketing Service</a></li>
								</ul>
							</div>
						</div>
						<div class="col-lg-4  col-md-6">
							<div class="single-footer-widget mail-chimp">
								<h6 class="mb-20">Contact Us</h6>
								<p>
									56/8, bir uttam qazi nuruzzaman road, west panthapath, kalabagan, Dhanmondi, Dhaka - 1205
								</p>
								<h3>012-6532-568-9746</h3>
								<h3>012-6532-568-97468</h3>
							</div>
						</div>
						<div class="col-lg-6  col-md-12">
							<div class="single-footer-widget newsletter">
								<h6 class="mb-20">Newsletter</h6>
								<p>You can trust us. we only send promo offers, not a single spam.</p>
								<div id="mc_embed_signup">
									<form target="_blank" novalidate="true" action="https://spondonit.us12.list-manage.com/subscribe/post?u=1462626880ade1ac87bd9c93a&amp;id=92a4423d01" method="get" class="form-inline">

										<div class="form-group row" style="width: 100%">
											<div class="col-lg-8 col-md-12">
												<input name="EMAIL" placeholder="Enter Email" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Enter Email '" required="" type="email">
												<div style="position: absolute; left: -5000px;">
													<input name="b_36c4fd991d266f23781ded980_aefe40901a" tabindex="-1" value="" type="text">
												</div>
											</div>

											<div class="col-lg-4 col-md-12">
												<button class="nw-btn primary-btn">Subscribe<span class="lnr lnr-arrow-right"></span></button>
											</div>
										</div>
										<div class="info"></div>
									</form>
								</div>
							</div>
						</div>
					</div>

					<div class="row footer-bottom d-flex justify-content-between">
						<p class="col-lg-8 col-sm-12 footer-text m-0">
							<!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
							Copyright &copy;2020 All rights reserved | This template is made by <a href="https://colorlib.com" target="_blank">Colorlib</a>
							<!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
						</p>
					</div>
				</div>
			</footer>

			<v-snackbar v-model="snackbar" color="#4CAF50" top>
				<v-icon dark class="mr-2">mdi-checkbox-marked-circle</v-icon>
					Letter was sent successfully.
				<v-btn text icon dark @click="snackbar = false">
					<v-icon size="20">mdi-close</v-icon>
				</v-btn>
    	</v-snackbar>
  </div>
</template>

<script>
	import { required, email, minLength } from 'vuelidate/lib/validators';
	import { mask } from 'vue-the-mask';
	import CountTo from 'vue-count-to';

	export default {
		components: {
			CountTo
		},

		directives: {
      mask,
    },

		validations: {
			form: {
				name: {
					required,
				},
				phone: {
					required,
				},
				email: {
					required,
					email
				},
				message: {
					required
				},
			}
		},
				
    data() {
			return {
				snackbar: false,
				isLoading: false,
				menuOpened: false,
				phoneMask: '+38 (0##) ###-##-##',
				form: {
					name: '',
					phone: '',
					email: '',
					message: ''
				}
			}
		},

		computed: {
			isAuthorized() {
				return this.$store.getters['auth/isAuthorized'];
			}
		},

		methods: {
			viewHandler(event) {
				if (event.type === 'enter') {
					this.$refs.count.forEach(element => {
						element.start();
					});
				}
			},
			sendConactLetter() {
				this.$v.$touch();

        if (!this.$v.$invalid) {
					this.snackbar = false;
					this.isLoading = true;
          this.$store.dispatch('app/sendContactLetter', this.form)
            .then(() => {
							this.resetForm();
              this.snackbar = true;
						})
						.finally(() => {
              this.isLoading = false;
            });
        }
			},
			resetForm() {
				this.form = {
					name: '',
					phone: '',
					email: '',
					message: ''
				};
				this.$v.$reset();
			}
		}
  }
</script>

<style scoped lang="scss">
	@import '../../sass/bootstrap';
	@import '../../sass/landing';
</style>