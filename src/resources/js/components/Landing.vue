<template>
  <div class="landing">
    <header class="default-header">
			<div class="container">
				<div class="header-wrap">
					<div class="header-top d-flex justify-content-between align-items-center">
						<div class="logo">
							<a href="#" v-scroll-to="'#home'">
								<img src="/storage/images/logo.svg" width="36" height="36" alt="App logo">
                <span>Dental CRM</span>
							</a>
						</div>
						<div class="main-menubar d-flex align-items-center">
							<nav :class="{'hide': !menuOpened}">
								<a href="#" v-scroll-to="'#home'">Главная</a>
								<a href="#" v-scroll-to="'#about'">О нас</a>
								<a href="#" v-scroll-to="'#service'">Услуги</a>
								<a href="#" v-scroll-to="'#appoinment'">Оставить заявку</a>
								<a href="#" v-scroll-to="'#gallery'">Галерея</a>
							
								<a :href="isAuthorized ? '/dashboard' : '/auth/login'">Войти</a>
							</nav>
							<div class="menu-bar" @click="menuOpened = !menuOpened"><span :class="['lnr', menuOpened ? 'lnr-cross' : 'lnr-menu']"></span></div>
						</div>
					</div>
				</div>
			</div>
		</header>
		<section class="banner-area relative" id="home">
			<div class="container">
				<div class="row fullscreen align-items-center">
					<div class="banner-content col-lg-6 col-md-12">
						<h1>
							Качественная стоматология в Запорожье
						</h1>
						<p>
							Обследование и лечение на самом современном оборудовании.
						</p>
						<button class="primary-btn mt-20 text-uppercase" v-scroll-to="'#appoinment'">Задать вопрос<span class="lnr lnr-arrow-right"></span></button>
					</div>
				</div>
			</div>
		</section>

		<!-- Start info Area -->
		<section class="info-area section-full" id="about">
			<div class="container">
				<div class="row">
					<div class="col-lg-5 mb-5 mb-lg-0">
						<img src="/storage/images/about.jpg" alt="">
					</div>
					<div class="col-lg-7 info-text">
						<h2 class="text-uppercase">Мы заботимся о ваших зубах</h2>
						<p>Стоматологическая клиника Дельта оказывает полный перечень услуг по профилактике болезней полости рта и лечению зубов, имплантологии и протезированию. У нас работают высококвалифицированные врачи всех направлений стоматологии. Мы ценим здоровье и безопасность каждого нашего пациента. Приходите и сами в этом убедитесь!</p>
					</div>
				</div>
			</div>
		</section>
		<!-- End info Area -->

    <!-- Start fact Area -->
		<section class="facts-area section-full" v-view.once="viewHandler">
			<div class="container">
				<div class="row">
					<template v-for="i in 1">
						<div class="col-md-4 mb-4 mb-md-0 single-fact">
							<h2>
								<count-to :startVal='0' :endVal='8' :duration='3000' :autoplay="false" class="count" ref="count"></count-to>+
							</h2>
							<p class="text-capitalize">Лет безупречной работы</p>
						</div>
						<div class="col-md-4 mb-4 mb-md-0 single-fact">
							<h2>
								<count-to :startVal='0' :endVal='15' :duration='3000' :autoplay="false" class="count" ref="count"></count-to>+
							</h2>
							<p class="text-capitalize">Специалистов</p>
						</div>
						<div class="col-md-4 mb-4 mb-md-0 single-fact">
							<h2>
								<count-to :startVal='0' :endVal='1500' :duration='3000' :autoplay="false" class="count" ref="count"></count-to>+
							</h2>
							<p class="text-capitalize">Счастливых улыбок</p>
						</div>
					</template>
				</div>
			</div>
		</section>
		<!-- End fact Area -->

		<!-- Start services Area -->
		<section class="services-area section-full" id="service">
			<div class="container">
				<div class="row justify-content-center">
					<div class="col-md-8 pb-30 header-text">
						<h1>Услуги нашей клиники</h1>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-4 col-md-6 service">
						<div class="service-icon">
							<i class="flaticon-020-decay"></i>
						</div>
						<div class="service-content">
							<h4>Консультация</h4>
							<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis sem per venenatis turpis.</p>
						</div>
					</div>
					<div class="col-lg-4 col-md-6 service">
						<div class="service-icon">
							<i class="flaticon-011-implants"></i>
						</div>
						<div class="service-content">
							<h4>Протезирование</h4>
							<p>Phasellus vehicula tempus orci vel consequat. Nullam lorem sem, viverra a rutrum sed.</p>
						</div>
					</div>
					<div class="col-lg-4 col-md-6 service">
						<div class="service-icon">
							<i class="flaticon-024-toothbrush"></i>
						</div>
						<div class="service-content">
							<h4>Отбеливание зубов</h4>
							<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis sem per venenatis turpis,</p>
						</div>
					</div>
					<div class="col-lg-4 col-md-6 service">
						<div class="service-icon">
							<i class="flaticon-019-dentist"></i>
						</div>
						<div class="service-content">
							<h4>Лечение кариеса</h4>
							<p>Phasellus vehicula tempus orci vel consequat. Nullam lorem sem, viverra a rutrum sed.</p>
						</div>
					</div>
					<div class="col-lg-4 col-md-6 service">
						<div class="service-icon">
							<i class="flaticon-001-tooth-4"></i>
						</div>
						<div class="service-content">
							<h4>Реставрация</h4>
							<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis sem per venenatis turpis,</p>
						</div>
					</div>
					<div class="col-lg-4 col-md-6 service">
						<div class="service-icon">
							<i class="flaticon-029-braces"></i>
						</div>
						<div class="service-content">
							<h4>Исправление прикуса</h4>
							<p>Nullam lorem sem, viverra a rutru m sed, gravida mattis magna. Suspendisse vitae commodo.</p>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- End services Area  -->

		<!-- Start about Area -->
		<section class="about-area" id="appoinment">
			<div class="container-fluid">
				<div class="row d-flex justify-content-end align-items-stretch">
					<div class="col-lg-6 col-md-12 about-left">
						<img class="img-fluid" src="" alt="">
					</div>
					<div class="col-lg-6 col-md-12 about-right">
						<h2 class="text-uppercase">Свяжитесь с нами</h2>
						<form class="booking-form" id="myForm" @submit.prevent="sendConactLetter">
							<div class="row">
								<div class="col-lg-12 d-flex flex-column">
							 			<input name="name" placeholder="Имя" :class="['form-control', 'mt-20', {'is-invalid': $v.form.name.$error }]" type="text" @blur="$v.form.name.$touch()" v-model.trim="form.name">
								</div>
							 	<div class="col-lg-6 d-flex flex-column">
										<input name="phone" placeholder="Телефон" :class="['form-control', 'mt-20', {'is-invalid': $v.form.phone.$error }]" type="text" v-mask="phoneMask" @blur="$v.form.phone.$touch()" v-model.trim="form.phone">
								</div>
								<div class="col-lg-6 d-flex flex-column">
										<input name="email" placeholder="E-mail" :class="['form-control', 'mt-20', {'is-invalid': $v.form.email.$error }]" type="email" @blur="$v.form.email.$touch()" v-model.trim="form.email">
								</div>
								<div class="col-lg-12 flex-column">
										<textarea name="message" placeholder="Ваш вопрос" :class="['form-control', 'mt-20', {'is-invalid': $v.form.message.$error }]" @blur="$v.form.message.$touch()" v-model.trim="form.message"></textarea>
								</div>

								<div class="col-lg-12 d-flex justify-content-end send-btn">
										<button class="submit-btn primary-btn mt-20 text-uppercase" :disabled="isLoading">Оставить заявку<span class="lnr lnr-arrow-right"></span></button>
								</div>

								<div class="alert-msg"></div>
							</div>
					  </form>
					</div>
				</div>
			</div>
		</section>
		<!-- End about Area -->

		<!-- Start gallery Area -->
		<section class="gallery-area" id="gallery">
			<div class="container-fluid">
				<div class="row justify-content-center">
					<div class="col-md-8 pb-30 header-text">
						<h1>Наша Фотогалерея</h1>
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
		<!-- End gallery Area -->

			<!-- start footer Area -->
			<footer class="footer-area pt-60 pb-60">
				<div class="container">
					<div class="row">					
						<div class="col-12 col-md-4">
							<div class="single-footer-widget mail-chimp">
								<h6 class="mb-20">Время работы</h6>
								<p>
									Понедельник - Пятница: 8:00 - 20:00 <br>
									Суббота: 8:00 - 15:00 <br>
									Воскресенье: Выходной
								</p>
							</div>
						</div>

						<div class="col-12 col-md-4">
							<div class="single-footer-widget mail-chimp">
								<h6 class="mb-20">Контакты</h6>
								<p>
									г. Запорожье, <br>
									ул. Сталеваров, 23
								</p>
								<h3 class="mb-2">012-6532-568-9746</h3>
								<h3>012-6532-568-97468</h3>
							</div>
						</div>

						<div class="col-12 col-md-4">
							<div class="single-footer-widget newsletter">
								<h6 class="mb-20">Запишитесь на прием онлайн</h6>
								<p>Заполните, пожалуйста, форму. Ми свяжемся с Вами и согласуем все детали.</p>
								<div id="mc_embed_signup">
									<div class="row" style="width: 100%">
										<div class="col-12 pt-1">
											<button class="nw-btn primary-btn" v-scroll-to="'#appoinment'">Записаться<span class="lnr lnr-arrow-right"></span></button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="row footer-bottom d-flex justify-content-between">
						<p class="col-12 footer-text m-0">
							Copyright &copy;2020 All rights reserved
						</p>
					</div>
				</div>
			</footer>

			<v-snackbar v-model="snackbar" color="#4CAF50" top>
				<v-icon dark class="mr-2">mdi-checkbox-marked-circle</v-icon>
					Письмо было успешно отправлено.
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