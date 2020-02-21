<template>
    <v-container fluid>
        <v-row class="ma-0" justify="center">
            <v-col cols="12" md="8" class="py-0">
                <v-card class="dashboard-card">
                    <v-card-title class="py-4 px-6">
                        <h3 class="dashboard-card__title">New payment</h3>
                    </v-card-title>

                    <v-divider></v-divider>

                    <v-card-text class="py-4 px-6">
                        <v-form>
                            <v-container class="pa-0">
                                <v-row>
                                    <v-col cols="12" sm="6">
                                        <v-text-field
                                            dense
                                            outlined
                                            v-model="form.amount"
                                            label="Amount"
                                            type="text"
                                            maxlength="11"
                                            :error-messages="amountErrors"
                                            @blur="$v.form.amount.$touch()"
                                        ></v-text-field>
                                    </v-col>
                                    <v-col cols="12" sm="6">
                                        <v-dialog
                                            ref="dialog"
                                            v-model="modal"
                                            :return-value.sync="form.date"
                                            persistent
                                            width="290px"
                                        >
                                            <template v-slot:activator="{ on }">
                                                <v-text-field
                                                    dense
                                                    outlined
                                                    v-model="form.date"
                                                    label="Date"
                                                    type="text"
                                                    append-icon="mdi-calendar"
                                                    readonly
                                                    v-on="on"
                                                ></v-text-field>
                                            </template>
                                            <v-date-picker v-model="form.date" scrollable>
                                                <v-spacer></v-spacer>
                                                <v-btn text color="primary" @click="modal = false">Cancel</v-btn>
                                                <v-btn text color="primary" @click="$refs.dialog.save(form.date)">OK</v-btn>
                                            </v-date-picker>
                                        </v-dialog>
                                    </v-col>
                                    <v-col cols="12" class="pt-0">
                                        <v-textarea
                                            label="Notes"
                                            v-model="form.notes"
                                            outlined
                                            hide-details
                                            autocomplete="none"
                                            maxlength="1000"
                                        ></v-textarea>
                                    </v-col>
                                </v-row>
                            </v-container>
                        </v-form>
                    </v-card-text>

                    <v-card-actions class="pt-0 pb-4 px-6">
                        <v-spacer></v-spacer>
                        <v-btn color="primary"
                               :loading="isLoading"
                               @click.prevent="submit"
                        >Save</v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
    import { required } from 'vuelidate/lib/validators';

    const isNumeric = (n) => {
        return !isNaN(parseFloat(n)) && isFinite(n);
    };

    export default {
        name: "CreatePaymentHistoryRecord",

        props: ['id'],

        validations: {
            form: {
                amount: {
                    required,
                    isNumeric
                },
            }
        },

        data() {
            return {
                isLoading: false,
                modal: false,
                form: {
                    amount: null,
                    date: new Date().toISOString().substr(0, 10),
                    notes: null,
                }
            }
        },

        computed: {
            amountErrors() {
                const errors = [];
                if (!this.$v.form.amount.$dirty) return errors;
                !this.$v.form.amount.required && errors.push('The amount field is required.');
                !this.$v.form.amount.isNumeric && errors.push('Wrong format of the amount field.');
                return errors;
            },
        },

        methods: {
            submit() {
                this.$v.$touch();

                if (!this.$v.$invalid) {
                    this.isLoading = true;
                    this.$store.dispatch('patients/createPaymentHistoryRecord', { id: this.id, data: this.form })
                        .then(() => {
                            this.$router.push({ name: 'dashboard.payment-history', params: { id: this.id }});
                        })
                        .finally(() => {
                            this.isLoading = false;
                        });
                }
            }
        }
    }
</script>

<style scoped>

</style>
