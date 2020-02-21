<template>
    <v-container fluid>
        <v-row class="ma-0" justify="center">
            <v-col cols="12" md="8" class="py-0">
                <v-card class="dashboard-card">
                    <v-card-title class="py-4 px-6">
                        <h3 class="dashboard-card__title">New visit</h3>
                    </v-card-title>

                    <v-divider></v-divider>

                    <v-card-text class="py-4 px-6">
                        <v-form>
                            <v-container class="pa-0">
                                <v-row>
                                    <v-col cols="12" sm="9">
                                        <v-autocomplete
                                            dense
                                            outlined
                                            v-model="form.service"
                                            :loading="isLoadingServices"
                                            :items="services"
                                            item-text="name"
                                            return-object
                                            label="Services"
                                            :error-messages="servicesErrors"
                                            @blur="$v.form.service.$touch()"
                                        >
                                            <template v-slot:item='{item}'>
                                                {{ item.name }}
                                                <v-spacer></v-spacer>
                                                <span class="price">{{ item.price }}</span>
                                            </template>
                                            <template v-slot:progress>
                                                <v-progress-circular
                                                    indeterminate
                                                    color="primary"
                                                    size="20"
                                                    width="2"
                                                    class="select-progress"
                                                ></v-progress-circular>
                                            </template>
                                        </v-autocomplete>
                                    </v-col>
                                    <v-col cols="12" sm="3" class="counter">
                                        <v-btn
                                            color="primary"
                                            min-width="40px"
                                            class="counter__minus pa-0"
                                            @click="decrementCounter"
                                            :disabled="form.count <= minCount"
                                        >
                                            <v-icon large>mdi-minus</v-icon>
                                        </v-btn>
                                        <v-text-field
                                            flat
                                            solo
                                            dense
                                            outlined
                                            readonly
                                            hide-details
                                            v-model="form.count"
                                            class="counter__field mx-3"
                                        ></v-text-field>
                                        <v-btn
                                            color="primary"
                                            min-width="40px"
                                            class="counter__minus pa-0"
                                            @click="incrementCounter"
                                            :disabled="form.count >= maxCount"
                                        >
                                            <v-icon large>mdi-plus</v-icon>
                                        </v-btn>
                                    </v-col>
                                    <v-col cols="12">
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
                                        <div class="total_price">
                                            <b class="total_price__heading">Total: </b>
                                            <span class="total_price__value">{{ total }}</span>
                                        </div>
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

    export default {
        name: "CreateServiceHistoryRecord",

        props: ['id'],

        validations: {
            form: {
                service: {
                    required,
                },
            }
        },

        data() {
            return {
                isLoading: false,
                isLoadingServices: false,
                modal: false,
                minCount: 1,
                maxCount: 10,
                form: {
                    service: null,
                    count: 1,
                    date: new Date().toISOString().substr(0, 10),
                }
            }
        },

        computed: {
            services() {
                return this.$store.state.patients.services || [];
            },
            servicesErrors() {
                const errors = [];
                if (!this.$v.form.service.$dirty) return errors;
                !this.$v.form.service.required && errors.push('The service field is required.');
                return errors;
            },
            total() {
                return (this.form.service && this.form.service.price) ? this.form.service.price * this.form.count : 0;
            }
        },

        created() {
            this.loadServices();
        },

        methods: {
            loadServices() {
                this.isLoadingServices = true;
                this.$store.dispatch('patients/loadServices')
                    .finally(() => {
                        this.isLoadingServices = false;
                    });
            },
            incrementCounter() {
                if (this.form.count < this.maxCount) {
                    this.form.count++;
                }
            },
            decrementCounter() {
                if (this.form.count > this.minCount) {
                    this.form.count--;
                }
            },
            submit() {
                this.$v.$touch();

                if (!this.$v.$invalid) {
                    let data = this.prepareData();

                    this.isLoading = true;
                    this.$store.dispatch('patients/createServiceHistoryRecord', { id: this.id, data })
                        .then(() => {
                            this.$router.push({ name: 'dashboard.service-history', params: { id: this.id }});
                        })
                        .finally(() => {
                            this.isLoading = false;
                        });
                }
            },
            prepareData() {
                return {
                    'service_id': this.form.service.id,
                    'date': this.form.date,
                    'count': this.form.count,
                }
            }
        }
    }
</script>

<style scoped>
    .price {
        font-weight: bold;
        color: #949494;
    }
    .total_price {
        font-size: 18px;
    }
    .select-progress {
        position: absolute;
        right: 15px;
        top: 10px;
        background: #fff;
    }
</style>
