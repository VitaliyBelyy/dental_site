<template>
    <v-dialog
        v-model="dialog"
        scrollable
        persistent
        max-width="600px"
    >
        <v-card max-height="600px" class="dashboard-card">
            <v-card-title class="py-4 px-6">
                <h3 class="dashboard-card__title">{{ formTitle }}</h3>
                <v-spacer></v-spacer>
                <p class="total-price mb-0">
                    <b class="total-price__heading">Итоговая стоимость: </b>
                    <span class="total-price__value">{{ total }}</span>
                </p>
            </v-card-title>

            <v-divider></v-divider>

            <v-card-text class="px-4 pb-0 pt-4">
                <v-form>
                    <v-container class="pa-0">
                        <v-row class="mb-4">
                            <v-col cols="12">
                                <teeth-map :value="form.selectedTooth" @change="(value) => form.selectedTooth = value"/>
                            </v-col>
                        </v-row>

                        <v-row>
                            <v-col cols="12">
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
                                                Дата <span class="red--text">*</span>
                                            </template>
                                        </v-text-field>
                                    </template>
                                    <v-date-picker
                                        v-model="form.date"
                                        @input="menu = false"
                                    ></v-date-picker>
                                </v-menu>
                            </v-col>
                            <v-col cols="12" sm="5" class="pt-0">
                                <v-autocomplete
                                    dense
                                    outlined
                                    no-filter
                                    item-text="name"
                                    return-object
                                    label="Услуга"
                                    :search-input.sync="searchQuery"
                                    v-model="form.service"
                                    :loading="isLoadingServices"
                                    :items="services"
                                    :error-messages="serviceErrors"
                                >
                                    <template v-slot:item='{item}'>
                                        {{ item.name }}
                                        <v-spacer></v-spacer>
                                        <span class="item-price">{{ item.price }}</span>
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
                            <v-col cols="12" sm="4" class="counter pt-0">
                                <v-btn
                                    text
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
                                    text
                                    color="primary"
                                    min-width="40px"
                                    class="counter__minus pa-0"
                                    @click="incrementCounter"
                                    :disabled="form.count >= maxCount"
                                >
                                    <v-icon large>mdi-plus</v-icon>
                                </v-btn>
                            </v-col>
                            <v-col cols="12" sm="3" class="pt-0">
                                <v-btn
                                    color="primary"
                                    elevation="1"
                                    width="100%"
                                    height="40px"
                                    @click="addSelectedService"
                                >Добавить</v-btn>
                            </v-col>
                            <v-col cols="12" class="pt-0">
                                <v-data-table
                                    disable-sort
                                    hide-default-footer
                                    :headers="headers"
                                    :items="form.selectedServices"
                                    class="dashboard-card__table--modal"
                                    no-data-text="Нет записей"
                                >
                                    <template v-if="selectedServicesErrors.length" v-slot:no-data>
                                        <div class="error--text" role="alert">
                                            {{ selectedServicesErrors[0] }}
                                        </div>
                                    </template>
                                </v-data-table>
                            </v-col>
                            <v-col cols="12">
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
    import moment from 'moment';
    import { required } from 'vuelidate/lib/validators';
    import { Subject } from 'rxjs';
    import { debounceTime, distinctUntilChanged } from 'rxjs/operators';
    import TeethMap from './TeethMap';


    export default {
        name: "VisitHistoryForm",

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

        components: {
            TeethMap
        },

        validations: {
            form: {
                selectedServices: {
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
                serviceValid: true,
                form: {
                    selectedTooth: null,
                    date: moment().format('YYYY-MM-DD'),
                    selectedServices: [],
                    service: null,
                    count: 1,
                },
                headers: [
                    {
                        text: 'ID',
                        align: 'left',
                        value: 'id',
                    },
                    {
                        text: 'Название',
                        align: 'left',
                        value: 'name',
                    },
                    {
                        text: 'Количество',
                        align: 'left',
                        value: 'service_count',
                    },
                    {
                        text: 'Стоимость',
                        align: 'left',
                        value: 'total_cost',
                    },
                    {
                        text: 'ID Зуба',
                        align: 'left',
                        value: 'tooth_id',
                    },
                ],
            }
        },

        computed: {
            services() {
                return this.$store.state.patients.services || [];
            },
            formTitle() {
                return this.selectedId === null ? "Новая запись" : "Редактировать запись";
            },
            serviceErrors() {
                const errors = [];
                if (this.serviceValid) return errors;
                !this.serviceValid && errors.push('Поле \'Услуга\' не должно быть пустым.');
                return errors;
            },
            selectedServicesErrors() {
                const errors = [];
                if (!this.$v.form.selectedServices.$dirty) return errors;
                !this.$v.form.selectedServices.required && errors.push('Необходимо выбрать хотя бы одну услугу.');
                return errors;
            },
            dateErrors() {
                const errors = [];
                if (!this.$v.form.date.$dirty) return errors;
                !this.$v.form.date.required && errors.push('Поле \'Дата\' не должно быть пустым.');
                return errors;
            },
            total() {
                return this.form.selectedServices.reduce((sum, item) => {
                    return sum + item.total_cost;
                }, 0);
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
                    date: this.record.date || moment().format('YYYY-MM-DD'),
                    selectedServices: this.record.services || [],
                    service: null,
                    count: 1,
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
                    'date': this.form.date,
                    'services': this.form.selectedServices,
                }
            },
            createRecord(payload) {
                this.isLoading = true;
                this.$store.dispatch('patients/createVisitHistoryRecord', payload)
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
            addSelectedService() {
                if (!this.form.service) {
                    this.serviceValid = false;
                    return;
                }

                let index = this.form.selectedServices.findIndex(service => {
                    return service.id === this.form.service.id && service.tooth_id === this.form.selectedTooth;
                });

                if (index >= 0) {
                    let selected = Object.assign({}, this.form.selectedServices[index], {
                        'service_count': this.form.selectedServices[index]['service_count'] + this.form.count,
                        'total_cost': this.form.selectedServices[index]['total_cost'] + this.form.service.price * this.form.count
                    });
                    this.form.selectedServices = this.form.selectedServices.slice(0, index).concat([selected, ...this.form.selectedServices.slice(index + 1)]);
                } else {
                    let selected = {
                        'id': this.form.service.id,
                        'name': this.form.service.name,
                        'tooth_id': this.form.selectedTooth,
                        'service_count': this.form.count,
                        'total_cost': this.form.service.price * this.form.count,
                    };
                    this.form.selectedServices = [...this.form.selectedServices, selected];
                }

                this.form.service = null;
                this.form.count = 1;
                this.serviceValid = true;
                this.$v.form.selectedServices.$reset();
            }
        }
    }
</script>

<style scoped>
    .item-price {
        font-weight: 500;
        color: #949494;
        padding-left: 15px;
    }
    .total-price {
        font-size: 18px;
    }
    .teeth-map {
        font-size: 10px;
    }
</style>
