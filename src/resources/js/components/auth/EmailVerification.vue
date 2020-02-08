<template>
    <v-card class="verification-card">
        <v-card-title class="verification-card__title">
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
</template>

<script>
    export default {
        name: "VerificationRequired",

        data() {
            return {
                isLoading: false,
            }
        },

        methods: {
            resendEmailVerification() {
                this.isLoading = true;
                this.$store.dispatch('auth/resend')
                    .then(() => {

                    })
                    .finally(() => {
                        this.isLoading = false;
                    });
            }
        }
    }
</script>

<style scoped>
    .verification-card__title {
        font-size: 1rem;
        text-transform: capitalize;
        line-height: 1;
        background: #f5f5f5;
    }
</style>
