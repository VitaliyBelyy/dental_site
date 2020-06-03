<template>
    <div>
        <v-card class="auth-card">
            <v-card-title class="auth-card__title">
                <h3>Сбросить пароль</h3>
            </v-card-title>

            <v-divider></v-divider>

            <v-card-text class="px-4 pb-0 pt-6">
                <v-form>
                    <v-text-field
                        label="E-mail"
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

                    <v-text-field
                        label="Новый пароль"
                        name="password"
                        dense
                        outlined
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
            <v-card-actions class="px-4 pb-6 pt-0">
                <v-spacer></v-spacer>
                <v-btn color="primary"
                       :loading="isLoading"
                       @click.prevent="resetPassword"
                >Подтвердить</v-btn>
            </v-card-actions>
        </v-card>
    </div>
</template>

<script>
    import { required, email, minLength } from 'vuelidate/lib/validators';

    export default {
        name: "ResetPassword",

        data() {
            return {
                email: '',
                password: '',
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
                const errors = [];
                if (!this.$v.email.$dirty) return errors;
                !this.$v.email.required && errors.push('Поле \'E-mail\' не должно быть пустым.');
                !this.$v.email.email && errors.push('Некорректный формат электронного адреса.');
                return errors;
            },
            passwordErrors() {
                const errors = [];
                if (!this.$v.password.$dirty) return errors;
                !this.$v.password.required && errors.push('Поле \'Пароль\' не должно быть пустым.');
                !this.$v.password.minLength && errors.push('Минимальная длина пароля 8 символов.');
                return errors;
            },
        },

        methods: {
            resetPassword() {
                this.$v.$touch();

                if (!this.$v.$invalid) {
                    const { token } = this.$route.query;
                    const { email, password } = this;

                    this.isLoading = true;
                    this.$store.dispatch('auth/resetPassword', { token, email, password, password_confirmation: password })
                        .then(() => {
                            this.$router.push({ name: 'auth.login' });
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
