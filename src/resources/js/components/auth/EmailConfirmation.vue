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

        <v-card class="confirmation-card">
            <v-card-text class="text-center py-6" v-if="isLoading">
                <v-progress-circular
                    indeterminate
                    color="primary"
                    size="50"
                    class="mb-5"
                ></v-progress-circular>
                <p class="confirmation-card__message ma-0">Processing the email confirmation request ...</p>
            </v-card-text>

            <v-card-text class="text-center py-6" v-else-if="confirmationError">
                <v-icon
                    size="60"
                    color="red"
                    class="mb-5"
                >mdi-alert-circle</v-icon>
                <p class="confirmation-card__message ma-0">An error occurred. Try to <a href="#" @click.prevent="resendEmailVerification">resend verification email.</a></p>
            </v-card-text>

            <v-card-text class="text-center py-6" v-else>
                <v-icon
                    size="60"
                    color="green"
                    class="mb-5"
                >mdi-checkbox-marked-circle</v-icon>
                <p class="confirmation-card__message ma-0">Your email address was successfully verified.</p>
            </v-card-text>

            <v-divider></v-divider>

            <v-progress-linear
                indeterminate
                color="primary"
                v-if="inProgress"
            ></v-progress-linear>

            <v-card-actions class="confirmation-card__actions">
                <v-spacer></v-spacer>
                <v-btn color="primary"
                       :disabled="isLoading || confirmationError"
                       link
                       :to="{ name: 'dashboard.patients' }"
                >Go to dashboard</v-btn>
            </v-card-actions>
        </v-card>
    </div>
</template>

<script>
    export default {
        name: "EmailConfirmation",

        data() {
            return {
                isLoading: false,
                confirmationError: false,
                inProgress: false,
                snackbar: false,
            }
        },

        mounted () {
            const { queryURL } = this.$route.query;

            this.verify(queryURL);
        },

        methods: {
            verify(url) {
                this.isLoading = true;
                this.$store.dispatch('auth/verify', url)
                    .catch(() => {
                        this.confirmationError = true;
                    })
                    .finally(() => {
                        this.isLoading = false;
                    });
            },
            resendEmailVerification() {
                this.inProgress = true;
                this.snackbar = false;
                this.$store.dispatch('auth/resend')
                    .then(() => {
                        this.snackbar = true;
                    })
                    .finally(() => {
                        this.inProgress = false;
                    });
            }
        }
    }
</script>

<style scoped>
    .confirmation-card__message {
        font-size: 1.125rem;
    }
    .confirmation-card__actions {
        padding: 16px;
    }
</style>
