<template>
    <div>
        <v-snackbar
            v-model="snackbar"
            color="error"
            multi-line
            top
        >
            <v-list-item two-line dark>
                <v-list-item-content>
                    <v-list-item-title>Ошибка авторизации!</v-list-item-title>
                    <v-list-item-subtitle>Введен неверный логин или пароль.</v-list-item-subtitle>
                </v-list-item-content>
            </v-list-item>
            <v-btn text icon dark @click="snackbar = false">
                <v-icon size="20">mdi-close</v-icon>
            </v-btn>
        </v-snackbar>

        <h1 class="form-title">Авторизация</h1>
        <v-card class="form-card elevation-2">
            <v-card-text class="card-content">
                <v-form>
                    <v-text-field
                        label="E-mail"
                        name="email"
                        prepend-icon="email"
                        type="email"
                        v-model="email"
                        maxlength="255"
                        :error-messages="emailErrors"
                        @blur="$v.email.$touch()"
                        @keyup.native.enter="submit"
                    ></v-text-field>

                    <v-text-field
                        label="Пароль"
                        name="password"
                        prepend-icon="lock"
                        :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
                        @click:append="showPassword = !showPassword"
                        :type="showPassword ? 'text' : 'password'"
                        v-model="password"
                        maxlength="20"
                        :error-messages="passwordErrors"
                        @blur="$v.password.$touch()"
                        @keyup.native.enter="submit"
                    ></v-text-field>

                    <div class="remember">
                        <v-checkbox
                            label="Запомнить меня"
                            color="primary"
                            hide-details
                            v-model="remember"
                            class="mt-2"
                        ></v-checkbox>
                        <router-link :to="{ name: 'auth.forgot-password' }">Забыли пароль?</router-link>
                    </div>
                </v-form>
            </v-card-text>
            <v-card-actions class="card-actions">
                <v-btn
                    color="primary"
                    class="form-submit"
                    :loading="isLoading"
                    @click.prevent="submit">Войти</v-btn>
                <div><router-link :to="{ name: 'home' }" class="back-link"><span class="lnr lnr-arrow-left back-link__icon"></span> На главную страницу</router-link></div>
            </v-card-actions>
        </v-card>
    </div>
</template>

<script>
    import { required, email, minLength } from 'vuelidate/lib/validators';
    import validation from '../../mixins/validation';

    export default {
        name: "Login",
        
        mixins: [validation],

        data() {
            return {
                email: '',
                password: '',
                remember: false,
                showPassword: false,
                isLoading: false,
                snackbar: false
            }
        },

        validations: {
            email: {
                required,
                email
            },
            password: {
                required,
                minLength: minLength(8)
            }
        },

        computed: {
            emailErrors() {
                const errors = this.getError('email') || [];
                if (!this.$v.email.$dirty) return errors;
                !this.$v.email.required && errors.push('Поле \'E-mail\' не должно быть пустым.');
                !this.$v.email.email && errors.push('Некорректный формат электронного адреса.');
                return errors;
            },
            passwordErrors() {
                const errors = this.getError('password') || [];
                if (!this.$v.password.$dirty) return errors;
                !this.$v.password.required && errors.push('Поле \'Пароль\' не должно быть пустым.');
                !this.$v.password.minLength && errors.push('Минимальная длина пароля 8 символов.');
                return errors;
            },
        },

        methods: {
            submit() {
                this.$v.$touch();

                if (!this.$v.$invalid) {
                    const {email, password, remember} = this;

                    this.isLoading = true;
                    this.snackbar = false;
                    this.clearErrors();
                    this.$store.dispatch('auth/login', {data: {email, password}, remember})
                        .then(() => {
                            this.$store.dispatch('auth/clearValidationErrors');

                            if (this.$route.params.nextUrl != null) {
                                this.$router.push(this.$route.params.nextUrl);
                            } else {
                                this.$router.push({ name: 'dashboard.patients' });
                            }
                        })
                        .catch((error) => {
                            if (error.status === 401) {
                                this.snackbar = true;
                            }

                            if (error.data) {
                                if (error.data.meta && error.data.meta.errors) {
                                    this.handleErrors(error.data.meta.errors);
                                } else if (error.data.message) {
                                    this.handleErrors({password: [error.message]});
                                }
                            }
                        })
                        .finally(() => {
                            this.isLoading = false;
                        });
                }
            },
        },
    }
</script>

<style lang="scss" scoped>
    .form-title {
        color: #fff;
        text-transform: uppercase;
        margin-bottom: 5px;
    }
    .form-card {
        padding: 30px 40px;
    }
    .card-content {
        padding: 0;
    }
    .card-actions {
        padding: 0;
        flex-wrap: wrap;
        justify-content: center;
    }
    .form-submit {
        width: 100%;
        margin: 20px 0 25px;
    }
    .remember {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        font-size: 1rem;
    }
    .back-link {
        position: relative;
        padding-left: 30px;

        &:hover .back-link__icon {
            left: 0;
        }
    }
    .back-link__icon {
        position: absolute;
        top: 1px;
        left: 5px;
        transition: all 0.3s ease 0s;
        font-weight: 700;
    }
</style>
