<template>
    <v-dialog v-model="dialog" persistent max-width="600px">
        <v-card class="event-card">
            <v-card-title class="py-4 px-6">
                <h3 class="dashboard-card__title">{{ formTitle }}</h3>
                <v-spacer></v-spacer>
                <div class="total-price">
                    <b class="total-price__heading">Total: </b>
                    <span class="total-price__value">{{ total }}</span>
                </div>
            </v-card-title>

            <v-divider></v-divider>

            <v-card-text class="px-4 pb-0 pt-4">
                <v-form>
                    <v-container class="pa-0">
                        <v-row>
                            <v-col cols="12" sm="8">
                                <v-autocomplete
                                    dense
                                    outlined
                                    :no-filter="true"
                                    :search-input.sync="searchQuery"
                                    v-model="form.service"
                                    :loading="isLoadingServices"
                                    :items="services"
                                    item-text="name"
                                    return-object
                                    :error-messages="servicesErrors"
                                    @blur="$v.form.service.$touch()"
                                >
                                    <template v-slot:label>
                                        Service <span class="red--text">*</span>
                                    </template>
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
                            <v-col cols="12" sm="4" class="counter">
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
                            <v-col cols="12" class="py-0">
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
    import { Subject } from 'rxjs';
    import { debounceTime, distinctUntilChanged } from 'rxjs/operators';


    export default {
        name: "ServiceHistoryForm",

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
                service: {
                    required,
                },
                date: {
                    required
                },
            }
        },

        data() {
            return {
                isLoading: false,
                isLoadingServices: false,
                searchQuery: null,
                searchStream: new Subject(),
                menu: false,
                minCount: 1,
                maxCount: 10,
                form: {
                    service: null,
                    count: 1,
                    date: moment().format('YYYY-MM-DD'),
                }
            }
        },

        computed: {
            services() {
                return this.$store.state.patients.services || [];
            },
            formTitle() {
                return this.selectedId === null ? "New Payment Record" : "Edit Payment Record";
            },
            servicesErrors() {
                const errors = [];
                if (!this.$v.form.service.$dirty) return errors;
                !this.$v.form.service.required && errors.push('The service field is required.');
                return errors;
            },
            dateErrors() {
                const errors = [];
                if (!this.$v.form.date.$dirty) return errors;
                !this.$v.form.date.required && errors.push('The date field is required.');
                return errors;
            },
            total() {
                return (this.form.service && this.form.service.price) ? this.form.service.price * this.form.count : 0;
            }
        },

        created() {
            this.loadServices();
            this.searchStream.pipe(
                // wait 1s after each keystroke before considering the term
                debounceTime(500),

                // ignore new term if same as previous term
                distinctUntilChanged(),

            ).subscribe(query => this.loadServices(query));
        },

        watch: {
            record() {
                this.initForm();
            },
            searchQuery(query) {
                if (this.form.service && query === this.form.service.name) {
                    return;
                }

                this.searchStream.next(query);
            }
        },

        methods: {
            loadServices(query = null) {
                let params = {
                    q: query,
                    page: 1,
                    limit: 15
                };

                this.isLoadingServices = true;
                this.$store.dispatch('patients/loadServices', { params })
                    .finally(() => {
                        this.isLoadingServices = false;
                    });
            },
            initForm() {
                this.form = {
                    service: this.record.service || null,
                    count: this.record.count || 1,
                    date: this.record.date || moment().format('YYYY-MM-DD'),
                };
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
            saveForm() {
                this.$v.$touch();

                if (!this.$v.$invalid) {
                    let data = this.prepareData();

                    if (this.selectedId) {
                        this.updateRecord();
                    } else {
                        this.createRecord({ id: this.patientId, data });
                    }
                }
            },
            prepareData() {
                return {
                    'service_id': this.form.service.id,
                    'date': this.form.date,
                    'count': this.form.count,
                    'service_cost': this.total
                }
            },
            createRecord(payload) {
                this.isLoading = true;
                this.$store.dispatch('patients/createServiceHistoryRecord', payload)
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
    .price {
        font-weight: 500;
        color: #949494;
    }
    .total-price {
        font-size: 18px;
    }
</style>
