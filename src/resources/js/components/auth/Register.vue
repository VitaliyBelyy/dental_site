<template>
    <div>
        <h1 class="form-title">Register an account</h1>
        <v-card class="form-card elevation-2">
            <v-card-text class="card-content">
                <v-form>
                    <v-text-field
                        label="Full name"
                        name="fullname"
                        prepend-icon="mdi-account-circle"
                        type="text"
                        v-model="fullname"
                        maxlength="255"
                        :error-messages="fullnameErrors"
                        @blur="$v.fullname.$touch()"
                    ></v-text-field>

                    <v-text-field
                        label="Email"
                        name="email"
                        prepend-icon="email"
                        type="email"
                        v-model="email"
                        maxlength="255"
                        :error-messages="emailErrors"
                        @blur="$v.email.$touch()"
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
                    ></v-text-field>
                </v-form>
            </v-card-text>
            <v-card-actions class="card-actions pt-2">
                <v-btn color="primary"
                       text
                       :disabled="isLoading"
                       :to="{ name: 'auth.login' }"
                >Cancel</v-btn>
                <v-spacer></v-spacer>
                <v-btn color="primary"
                       :loading="isLoading"
                       @click.prevent="submit">Register</v-btn>
            </v-card-actions>
        </v-card>
    </div>
</template>

<script>
    import { required, email, minLength } from 'vuelidate/lib/validators';

    export default {
        name: "Register",

        data() {
            return {
                fullname: '',
                email: '',
                password: '',
                showPassword: false,
                isLoading: false,
            }
        },

        validations: {
            fullname: {
                required,
            },
            email: {
                required,
                email
            },
            password: {
                required,
                minLength: minLength(5)
            },
        },

        computed: {
            fullnameErrors() {
                const errors = [];
                if (!this.$v.fullname.$dirty) return errors;
                !this.$v.fullname.required && errors.push('The full name is required.');
                return errors;
            },
            emailErrors() {
                const errors = [];
                if (!this.$v.email.$dirty) return errors;
                !this.$v.email.required && errors.push('The e-mail is required.');
                !this.$v.email.email && errors.push('The email must be a valid email address.');
                return errors;
            },
            passwordErrors() {
                const errors = [];
                if (!this.$v.password.$dirty) return errors;
                !this.$v.password.required && errors.push('The password is required.');
                !this.$v.password.minLength && errors.push('Password must be at least 5 characters long');
                return errors;
            },
        },
        methods: {
            submit () {
                this.$v.$touch();

                if (!this.$v.$invalid) {
                    const {fullname, email, password} = this;

                    this.isLoading = true;
                    this.$store.dispatch('auth/register', {data: {fullname, email, password}})
                        .then(() => {
                            this.$router.push({ name: 'auth.email-verification' })
                        })
                        .finally(() => {
                            this.isLoading = false;
                        });
                }
            },
        },
    }
</script>

<style scoped>
    .form-title {
        color: #fff;
        text-transform: uppercase;
        margin-bottom: 5px;
    }
    .form-card {
        padding: 30px 40px 40px;
    }
    .card-content {
        padding: 0;
    }
    .card-actions {
        padding: 0;
    }
</style>
