<template>
    <div>
        <v-snackbar
            v-model="snackbar"
            color="success"
            top
        >
            <v-icon dark class="mr-2">mdi-checkbox-marked-circle</v-icon>
            Password recovery email was sent successfully.
            <v-btn
                text
                icon
                dark
                @click="snackbar = false">
                <v-icon size="20">mdi-close</v-icon>
            </v-btn>
        </v-snackbar>

        <v-card class="auth-card">
            <v-card-title class="auth-card__title">
                <h3>Reset password</h3>
            </v-card-title>

            <v-divider></v-divider>

            <v-card-text class="px-4 pb-0 pt-6">
                <v-form>
                    <v-text-field
                        label="Email address"
                        name="email"
                        dense
                        outlined
                        append-icon="email"
                        type="email"
                        v-model="email"
                        maxlength="255"
                        :error-messages="emailErrors"
                        @blur="$v.email.$touch()"
                    ></v-text-field>
                </v-form>
            </v-card-text>
            <v-card-actions class="px-4 pb-6 pt-0">
                <v-spacer></v-spacer>
                <v-btn color="primary"
                       :loading="isLoading"
                       @click.prevent="sendResetLink"
                >Send password reset link</v-btn>
            </v-card-actions>
        </v-card>
    </div>
</template>

<script>
    import { required, email } from 'vuelidate/lib/validators';

    export default {
        name: "ForgotPassword",

        data() {
            return {
                email: '',
                isLoading: false,
                snackbar: false
            }
        },

        validations: {
            email: {
                required,
                email
            }
        },

        computed: {
            emailErrors() {
                const errors = [];
                if (!this.$v.email.$dirty) return errors;
                !this.$v.email.required && errors.push('The e-mail is required.');
                !this.$v.email.email && errors.push('The email must be a valid email address.');
                return errors;
            }
        },

        methods: {
            sendResetLink() {
                this.$v.$touch();

                if (!this.$v.$invalid) {
                    const {email} = this;

                    this.isLoading = true;
                    this.snackbar = false;
                    this.$store.dispatch('auth/sendResetLink', {email})
                        .then(() => {
                            this.snackbar = true;
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

</style>
