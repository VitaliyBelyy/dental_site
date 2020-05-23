<template>
    <div>
        <h1 class="form-title">Login</h1>
        <v-card class="form-card elevation-2">
            <v-card-text class="card-content">
                <v-form>
                    <v-text-field
                        label="Email"
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
                        label="Password"
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
                            label="Remember me"
                            color="primary"
                            hide-details
                            v-model="remember"
                            class="mt-2"
                        ></v-checkbox>
                        <router-link :to="{ name: 'auth.forgot-password' }">Forgot Password?</router-link>
                    </div>
                </v-form>
            </v-card-text>
            <v-card-actions class="card-actions">
                <v-btn color="primary"
                       class="form-submit"
                       :loading="isLoading"
                       @click.prevent="submit">Login</v-btn>
                <div><router-link :to="{ name: 'home' }" class="back-link"><span class="lnr lnr-arrow-left back-link__icon"></span> Back to the site</router-link></div>
            </v-card-actions>
        </v-card>
    </div>
</template>

<script>
    import { required, email, minLength } from 'vuelidate/lib/validators';

    export default {
        name: "Login",

        data() {
            return {
                email: '',
                password: '',
                remember: false,
                showPassword: false,
                isLoading: false,
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
                const errors = this.$store.state.auth.validationErrors.email || [];
                if (!this.$v.email.$dirty) return errors;
                !this.$v.email.required && errors.push('The e-mail is required.');
                !this.$v.email.email && errors.push('The email must be a valid email address.');
                return errors;
            },
            passwordErrors() {
                const errors = this.$store.state.auth.validationErrors.password || [];
                if (!this.$v.password.$dirty) return errors;
                !this.$v.password.required && errors.push('The password is required.');
                !this.$v.password.minLength && errors.push('Password must be at least 8 characters long');
                return errors;
            },
        },

        methods: {
            submit() {
                this.$v.$touch();

                if (!this.$v.$invalid) {
                    const {email, password, remember} = this;

                    this.isLoading = true;
                    this.$store.dispatch('auth/login', {data: {email, password}, remember})
                        .then(() => {
                            this.$store.dispatch('auth/clearValidationErrors');

                            if (this.$route.params.nextUrl != null) {
                                this.$router.push(this.$route.params.nextUrl);
                            } else {
                                this.$router.push({ name: 'dashboard.patients' });
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
