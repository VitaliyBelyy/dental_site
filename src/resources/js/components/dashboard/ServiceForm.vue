<template>
    <v-dialog v-model="dialog" persistent max-width="500px">
        <v-card class="event-card">
            <v-card-title class="py-4 px-6">
                <h3 class="dashboard-card__title">{{ formTitle }}</h3>
            </v-card-title>

            <v-divider></v-divider>

            <v-card-text class="px-4 pb-0 pt-4">
                <v-form>
                    <v-container class="pa-0">
                        <v-row>
                            <v-col cols="12">
                                <v-text-field
                                    dense
                                    outlined
                                    v-model="form.name"
                                    type="text"
                                    maxlength="255"
                                    :error-messages="nameErrors"
                                    @blur="$v.form.name.$touch()"
                                >
                                    <template v-slot:label>
                                        Название <span class="red--text">*</span>
                                    </template>
                                </v-text-field>
                            </v-col>
                            <v-col cols="12" class="py-0">
                                <v-text-field
                                    dense
                                    outlined
                                    v-model="form.price"
                                    type="text"
                                    maxlength="9"
                                    :error-messages="priceErrors"
                                    @blur="$v.form.price.$touch()"
                                >
                                    <template v-slot:label>
                                        Стоимость <span class="red--text">*</span>
                                    </template>
                                </v-text-field>
                            </v-col>
                            <v-col cols="12" class="pt-0">
                                <small><span class="red--text">*</span> Обязательные поля</small>
                            </v-col>
                        </v-row>
                    </v-container>
                </v-form>
            </v-card-text>

            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="primary"
                       text
                       :disabled="isLoading"
                       @click="closeForm"
                >Закрыть</v-btn>
                <v-btn color="primary"
                       text
                       :loading="isLoading"
                       @click="saveForm"
                >Сохранить</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
    import { required } from 'vuelidate/lib/validators';

    const isNumeric = (n) => {
        return !isNaN(parseFloat(n)) && isFinite(n);
    };

    export default {
        name: "ServiceForm",

        props: {
            dialog: {
                type: Boolean,
                default: false
            },
            service: {
                type: Object
            },
            selectedId: {
                type: Number
            }
        },

        validations: {
            form: {
                name: {
                    required
                },
                price: {
                    required,
                    isNumeric
                }
            }
        },

        data() {
            return {
                isLoading: false,
                menu: false,
                form: {
                    name: null,
                    price: null,
                }
            }
        },

        computed: {
            formTitle() {
                return this.selectedId === null ? "Новая услуга" : "Редактировать услугу";
            },
            nameErrors() {
                const errors = [];
                if (!this.$v.form.name.$dirty) return errors;
                !this.$v.form.name.required && errors.push('Поле \'Название\' не должно быть пустым.');
                return errors;
            },
            priceErrors() {
                const errors = [];
                if (!this.$v.form.price.$dirty) return errors;
                !this.$v.form.price.required && errors.push('Поле \'Стоимость\' не должно быть пустым.');
                !this.$v.form.price.isNumeric && errors.push('Некорректный формат.');
                return errors;
            },
        },

        watch: {
            service() {
                this.initForm();
            },
        },

        methods: {
            initForm() {
                this.form = {
                    name: this.service.name || null,
                    price: this.service.price || null,
                };
            },
            saveForm() {
                this.$v.$touch();

                if (!this.$v.$invalid) {
                    if (this.selectedId) {
                        this.updateService({ id: this.selectedId, data: this.form });
                    } else {
                        this.createService({ data: this.form });
                    }
                }
            },
            createService(payload) {
                this.isLoading = true;
                this.$store.dispatch('services/createService', payload)
                    .then(() => {
                        this.serviceCreated();
                    })
                    .finally(() => {
                        this.isLoading = false;
                    });
            },
            updateService(payload) {
                this.isLoading = true;
                this.$store.dispatch('services/updateService', payload)
                    .then(() => {
                        this.serviceUpdated();
                    })
                    .finally(() => {
                        this.isLoading = false;
                    });
            },
            serviceCreated() {
                this.$v.$reset();
                this.$emit("on-create");
            },
            serviceUpdated() {
                this.$v.$reset();
                this.$emit("on-update");
            },
            closeForm() {
                this.$v.$reset();
                this.$emit("on-close");
            },
        }
    }
</script>

<style scoped>

</style>
