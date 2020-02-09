<template>
    <div>
        <v-snackbar
            v-model="snackbar"
            color="success"
            top
        >
            <v-icon dark class="mr-2">mdi-checkbox-marked-circle</v-icon>
            Verification email was successfully resent.
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
                <h3>Verify your email address</h3>
            </v-card-title>

            <v-divider></v-divider>

            <v-card-text>
                <p class="ma-0">
                    Before proceeding, please check your email for a verification link. If you did not receive the email, <a
                    href="#" @click="resendEmailVerification">click here to request another.</a>
                </p>
            </v-card-text>
        </v-card>
    </div>
</template>

<script>
    export default {
        name: "VerificationRequired",

        data() {
            return {
                isLoading: false,
                snackbar: false,
            }
        },

        methods: {
            resendEmailVerification() {
                this.isLoading = true;
                this.snackbar = false;
                this.$store.dispatch('auth/resend')
                    .then(() => {
                        this.snackbar = true;
                    })
                    .finally(() => {
                        this.isLoading = false;
                    });
            }
        }
    }
</script>

<style scoped>

</style>
