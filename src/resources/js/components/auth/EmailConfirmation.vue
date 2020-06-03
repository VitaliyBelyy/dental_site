<template>
    <div>
        <v-snackbar
            v-model="snackbar"
            color="success"
            top
        >
            <v-icon dark class="mr-2">mdi-checkbox-marked-circle</v-icon>
            Письмо для подтверждения адреса электронной почты было успешно отправлено.
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
                <p class="confirmation-card__message ma-0">Обработка запроса ...</p>
            </v-card-text>

            <v-card-text class="text-center py-6" v-else-if="confirmationError">
                <v-icon
                    size="60"
                    color="red"
                    class="mb-5"
                >mdi-alert-circle</v-icon>
                <p class="confirmation-card__message ma-0">Возникла ошибка. Попробуйте <a href="#" @click.prevent="resendEmailVerification">отправить письмо повторно.</a></p>
            </v-card-text>

            <v-card-text class="text-center py-6" v-else>
                <v-icon
                    size="60"
                    color="green"
                    class="mb-5"
                >mdi-checkbox-marked-circle</v-icon>
                <p class="confirmation-card__message ma-0">Адрес электронной почты был успешно подтвержден.</p>
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
                >Перейти к административной части</v-btn>
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
