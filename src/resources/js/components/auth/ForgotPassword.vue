<template>
    <div>
        <v-card class="auth-card">
            <v-card-title class="auth-card__title">
                <h3>Восстановления пароля</h3>
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
                </v-form>
            </v-card-text>
            <v-card-actions class="px-4 pb-6 pt-0">
                <v-btn color="primary"
                       text
                       link
                       exact
                       :disabled="isLoading"
                       :to="{ name: 'auth.login' }"
                >Отмена</v-btn>
                <v-spacer></v-spacer>
                <v-btn color="primary"
                       :loading="isLoading"
                       @click.prevent="sendResetLink"
                >Отправить ссылку</v-btn>
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
                !this.$v.email.required && errors.push('Поле \'E-mail\' не должно быть пустым.');
                !this.$v.email.email && errors.push('Некорректный формат электронного адреса.');
                return errors;
            }
        },

        methods: {
            sendResetLink() {
                this.$v.$touch();

                if (!this.$v.$invalid) {
                    const {email} = this;

                    this.isLoading = true;
                    this.$store.dispatch('auth/sendResetLink', {email})
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
