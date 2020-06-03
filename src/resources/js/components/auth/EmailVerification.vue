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

        <v-card class="auth-card">
            <v-card-title class="auth-card__title">
                <h3>Подтверждение адреса электронной почты</h3>
            </v-card-title>

            <v-divider></v-divider>

            <v-card-text>
                <p class="ma-0">
                    Прежде чем продолжить необходимо подтвердить адрес электронной почты. Проверьте ваш почтовый ящих и выполните указанные действия. Если по какой-то причине письмо не было получено, нажмите <a
                    href="#" @click.prevent="resendEmailVerification">здесь</a> для повторной отправки.
                </p>
            </v-card-text>

            <v-divider></v-divider>

            <v-progress-linear
                indeterminate
                color="primary"
                v-if="inProgress"
            ></v-progress-linear>

            <v-card-actions class="verification-card__actions">
                <v-spacer></v-spacer>
                <v-btn color="grey lighten-2"
                       link
                       light
                       @click="handleLogout"
                >Назад к авторизации</v-btn>
            </v-card-actions>
        </v-card>
    </div>
</template>

<script>
    export default {
        name: "VerificationRequired",

        data() {
            return {
                inProgress: false,
                snackbar: false,
            }
        },

        methods: {
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
            },
            handleLogout() {
                this.$store.dispatch('auth/logout')
                    .then(() => {
                        this.$router.push({ name: 'auth.login' });
                    })
            },
        }
    }
</script>

<style scoped>
    .verification-card__actions {
        padding: 16px;
    }
</style>
