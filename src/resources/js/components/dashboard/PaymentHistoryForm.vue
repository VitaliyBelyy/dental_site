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
                            <v-col cols="12" sm="6">
                                <v-text-field
                                    dense
                                    outlined
                                    v-model="form.amount"
                                    type="text"
                                    maxlength="10"
                                    :error-messages="amountErrors"
                                    @blur="$v.form.amount.$touch()"
                                >
                                    <template v-slot:label>
                                        Amount <span class="red--text">*</span>
                                    </template>
                                </v-text-field>
                            </v-col>
                            <v-col cols="12" sm="6">
                                <v-menu
                                    v-model="menu"
                                    :close-on-content-click="false"
                                    transition="scale-transition"
                                    offset-y
                                    min-width="280px"
                                >
                                    <template v-slot:activator="{ on }">
                                        <v-text-field
                                            v-on="on"
                                            v-model="form.date"
                                            append-icon="event"
                                            dense
                                            outlined
                                            readonly
                                            :error-messages="dateErrors"
                                            @blur="$v.form.date.$touch()"
                                        >
                                            <template v-slot:label>
                                                Date <span class="red--text">*</span>
                                            </template>
                                        </v-text-field>
                                    </template>
                                    <v-date-picker
                                        v-model="form.date"
                                        @input="menu = false"
                                    ></v-date-picker>
                                </v-menu>
                            </v-col>
                            <v-col cols="12" class="pt-0">
                                <v-textarea
                                    label="Notes"
                                    v-model="form.notes"
                                    outlined
                                    hide-details
                                    autocomplete="none"
                                    maxlength="1000"
                                    rows="4"
                                ></v-textarea>
                            </v-col>
                            <v-col cols="12">
                                <small><span class="red--text">*</span> indicates required field</small>
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
                >Close</v-btn>
                <v-btn color="primary"
                       text
                       :loading="isLoading"
                       @click="saveForm"
                >Save</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
    import moment from 'moment';
    import { required } from 'vuelidate/lib/validators';

    const isNumeric = (n) => {
        return !isNaN(parseFloat(n)) && isFinite(n);
    };

    export default {
        name: "PaymentHistoryForm",

        props: {
            patientId: {
                type: Number,
                required: true
            },
            dialog: {
                type: Boolean,
                default: false
            },
            record: {
                type: Object
            },
            selectedId: {
                type: Number
            }
        },

        validations: {
            form: {
                amount: {
                    required,
                    isNumeric
                },
                date: {
                    required
                },
            }
        },

        data() {
            return {
                isLoading: false,
                menu: false,
                form: {
                    amount: null,
                    date: moment().format('YYYY-MM-DD'),
                    notes: null,
                }
            }
        },

        computed: {
            formTitle() {
                return this.selectedId === null ? "New Payment Record" : "Edit Payment Record";
            },
            amountErrors() {
                const errors = [];
                if (!this.$v.form.amount.$dirty) return errors;
                !this.$v.form.amount.required && errors.push('The amount field is required.');
                !this.$v.form.amount.isNumeric && errors.push('Wrong format of the amount field.');
                return errors;
            },
            dateErrors() {
                const errors = [];
                if (!this.$v.form.date.$dirty) return errors;
                !this.$v.form.date.required && errors.push('The date field is required.');
                return errors;
            },
        },

        watch: {
            record() {
                this.initForm();
            },
        },

        methods: {
            initForm() {
                this.form = {
                    amount: this.record.amount || null,
                    date: this.record.date || moment().format('YYYY-MM-DD'),
                    notes: this.record.notes || null,
                };
            },
            saveForm() {
                this.$v.$touch();

                if (!this.$v.$invalid) {
                    if (this.selectedId) {
                        this.updateRecord();
                    } else {
                        this.createRecord({ id: this.patientId, data: this.form });
                    }
                }
            },
            createRecord(payload) {
                this.isLoading = true;
                this.$store.dispatch('patients/createPaymentHistoryRecord', payload)
                    .then(() => {
                        this.recordCreated();
                    })
                    .finally(() => {
                        this.isLoading = false;
                    });
            },
            updateRecord(payload) {

            },
            recordCreated() {
                this.$v.$reset();
                this.$emit("on-create");
            },
            recordUpdated() {
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
