<template>
    <div>
        <h1 class="form-title">Register an account</h1>
        <v-card class="form-card elevation-2">
            <v-card-text class="card-content">
                <v-form>
                    <v-text-field
                        label="Full name"
                        prepend-icon="mdi-account-circle"
                        type="text"
                        v-model="fullName"
                        maxlength="255"
                        :error-messages="fullNameErrors"
                        @blur="$v.fullName.$touch()"
                        @keyup.native.enter="submit"
                    ></v-text-field>

                    <v-text-field
                        label="Email"
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
                </v-form>
            </v-card-text>
            <v-card-actions class="card-actions pt-6">
                <v-btn color="primary"
                       text
                       link
                       exact
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
                fullName: '',
                email: '',
                password: '',
                showPassword: false,
                isLoading: false,
            }
        },

        validations: {
            fullName: {
                required,
            },
            email: {
                required,
                email
            },
            password: {
                required,
                minLength: minLength(8)
            },
        },

        computed: {
            fullNameErrors() {
                const errors = this.$store.state.auth.validationErrors.full_name || [];
                if (!this.$v.fullName.$dirty) return errors;
                !this.$v.fullName.required && errors.push('The full name is required.');
                return errors;
            },
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
            submit () {
                this.$v.$touch();

                if (!this.$v.$invalid) {
                    const {fullName, email, password} = this;

                    this.isLoading = true;
                    this.$store.dispatch('auth/register', {data: {full_name: fullName, email, password}})
                        .then(() => {
                            this.$store.dispatch('auth/clearValidationErrors');
                            this.$router.push({ name: 'auth.email-verification' });
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
