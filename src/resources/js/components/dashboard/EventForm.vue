<template>
    <v-dialog v-model="dialog" persistent max-width="500px">
        <v-card class="dashboard-card">
            <v-card-title class="px-4 py-3">
                <h3 class="dashboard-card__title">{{ formTitle }}</h3>
                <v-spacer></v-spacer>
                <v-select
                    solo
                    dark
                    dense
                    hide-details
                    :background-color="getStatusColor(form.status)"
                    v-model="form.status"
                    :items="statusList"
                    class="dashboard-card__status-select"
                >
                    <template v-slot:item="{ item }">
                        <span :style="'color: ' + getStatusColor(item)">{{ item }}</span>
                    </template>
                </v-select>
            </v-card-title>
            <v-divider></v-divider>
            <v-card-text class="px-4 pb-0 pt-4">
                <v-form>
                    <v-container class="pa-0">
                        <v-row>
                            <v-col cols="12" class="pb-1">
                                <v-autocomplete
                                    dense
                                    outlined
                                    :search-input.sync="searchQuery"
                                    v-model="form.patient"
                                    :loading="isLoadingPatients"
                                    :items="patients"
                                    item-text="full_name"
                                    return-object
                                    :error-messages="patientErrors"
                                    @blur="$v.form.patient.$touch()"
                                >
                                    <template v-slot:label>
                                        Пациент <span class="red--text">*</span>
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
                            <v-col cols="12" class="pt-0 pb-1">
                                <v-menu
                                    v-model="dateMenu"
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
                                                Дата визита
                                                <span class="red--text">*</span>
                                            </template>
                                        </v-text-field>
                                    </template>
                                    <v-date-picker
                                        v-model="form.date"
                                        @input="dateMenu = false"
                                        :min="new Date().toISOString().substr(0, 10)"
                                    ></v-date-picker>
                                </v-menu>
                            </v-col>
                            <v-col cols="12" sm="6" class="pt-0 pb-1">
                                <v-menu
                                    ref="startTimeMenuRef"
                                    v-model="startTimeMenu"
                                    :close-on-content-click="false"
                                    :return-value.sync="form.startTime"
                                    transition="scale-transition"
                                    offset-y
                                    max-width="280px"
                                    min-width="280px"
                                >
                                    <template v-slot:activator="{ on }">
                                        <v-text-field
                                            v-on="on"
                                            v-model="form.startTime"
                                            append-icon="access_time"
                                            dense
                                            outlined
                                            readonly
                                            :error-messages="startTimeErrors"
                                            @blur="$v.form.startTime.$touch()"
                                        >
                                            <template v-slot:label>
                                                Начало
                                                <span class="red--text">*</span>
                                            </template>
                                        </v-text-field>
                                    </template>
                                    <v-time-picker
                                        v-if="startTimeMenu"
                                        v-model="form.startTime"
                                        full-width
                                        format="24hr"
                                        @click:minute="$refs.startTimeMenuRef.save(form.startTime)"
                                    ></v-time-picker>
                                </v-menu>
                            </v-col>
                            <v-col cols="12" sm="6" class="pt-0 pb-1">
                                <v-menu
                                    ref="endTimeMenuRef"
                                    v-model="endTimeMenu"
                                    :close-on-content-click="false"
                                    :return-value.sync="form.endTime"
                                    transition="scale-transition"
                                    offset-y
                                    max-width="280px"
                                    min-width="280px"
                                >
                                    <template v-slot:activator="{ on }">
                                        <v-text-field
                                            v-on="on"
                                            v-model="form.endTime"
                                            append-icon="access_time"
                                            dense
                                            outlined
                                            readonly
                                            :error-messages="endTimeErrors"
                                            @blur="$v.form.endTime.$touch()"
                                        >
                                            <template v-slot:label>
                                                Окончание
                                                <span class="red--text">*</span>
                                            </template>
                                        </v-text-field>
                                    </template>
                                    <v-time-picker
                                        v-if="endTimeMenu"
                                        v-model="form.endTime"
                                        full-width
                                        format="24hr"
                                        @click:minute="$refs.endTimeMenuRef.save(form.endTime)"
                                    ></v-time-picker>
                                </v-menu>
                            </v-col>
                            <v-col cols="12" class="pt-0">
                                <v-textarea
                                    label="Примечания"
                                    v-model="form.details"
                                    outlined
                                    hide-details
                                    autocomplete="none"
                                    maxlength="500"
                                    rows="4"
                                ></v-textarea>
                            </v-col>
                            <v-col cols="12">
                                <small>
                                    <span class="red--text">*</span> Обязательные поля
                                </small>
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
    import { required } from "vuelidate/lib/validators";
    import { Subject } from 'rxjs';
    import { debounceTime, distinctUntilChanged } from 'rxjs/operators';

    export default {
        name: "EventForm",

        props: {
            dialog: {
                type: Boolean,
                default: false
            },
            event: {
                type: Object
            },
            selectedId: {
                type: Number
            }
        },

        validations: {
            form: {
                patient: {
                    required
                },
                date: {
                    required
                },
                startTime: {
                    required
                },
                endTime: {
                    required
                }
            }
        },

        data() {
            return {
                isLoading: false,
                searchQuery: null,
                searchStream: new Subject(),
                isLoadingPatients: false,
                statusList: [
                    "Запланировано",
                    "Подтверждено",
                    "Выполнено",
                    "Пропущено",
                    "Перенесено"
                ],
                dialog: false,
                dateMenu: false,
                startTimeMenu: false,
                endTimeMenu: false,
                form: {
                    patient: null,
                    status: "Запланировано",
                    date: moment().format("YYYY-MM-DD"),
                    startTime: null,
                    endTime: null,
                    details: null
                }
            };
        },

        computed: {
            patients() {
                return this.$store.state.events.patients;
            },
            formTitle() {
                return this.selectedId === null ? "Новая запись" : "Редактировать запись";
            },
            patientErrors() {
                const errors = [];
                if (!this.$v.form.patient.$dirty) return errors;
                !this.$v.form.patient.required &&
                errors.push('Поле \'Пациент\' не должно быть пустым.');
                return errors;
            },
            dateErrors() {
                const errors = [];
                if (!this.$v.form.date.$dirty) return errors;
                !this.$v.form.date.required &&
                errors.push('Поле \'Дата визита\' не должно быть пустым.');
                return errors;
            },
            startTimeErrors() {
                const errors = [];
                if (!this.$v.form.startTime.$dirty) return errors;
                !this.$v.form.startTime.required &&
                errors.push('Поле \'Начало\' не должно быть пустым.');
                return errors;
            },
            endTimeErrors() {
                const errors = [];
                if (!this.$v.form.endTime.$dirty) return errors;
                !this.$v.form.endTime.required &&
                errors.push('Поле \'Окончание\' не должно быть пустым.');
                return errors;
            }
        },

        created() {
            this.loadPatients();
            this.searchStream.pipe(
                // wait 1s after each keystroke before considering the term
                debounceTime(500),

                // ignore new term if same as previous term
                distinctUntilChanged(),

            ).subscribe(query => this.loadPatients(query));
        },

        watch: {
            event() {
                this.initForm();
            },
            searchQuery(query) {
                if (this.form.patient && query === this.form.patient.full_name) {
                    return;
                }

                this.searchStream.next(query);
            }
        },

        methods: {
            loadPatients(query = null) {
                let params = {
                    q: query,
                    page: 1,
                    limit: 15
                };

                this.isLoadingPatients = true;
                this.$store.dispatch("events/loadPatients", { params })
                    .finally(() => {
                        this.isLoadingPatients = false;
                    });
            },
            getStatusColor(status) {
                switch (status) {
                    case "Запланировано":
                        return "#2196F3";
                    case "Подтверждено":
                        return "#673AB7";
                    case "Выполнено":
                        return "#8BC34A";
                    case "Пропущено":
                        return "#FF5252";
                    case "Перенесено":
                        return "#FFC107";
                }

                return "#2196F3";
            },
            initForm() {
                this.form = {
                    patient: this.event.patient || null,
                    status: this.event.status || "Запланировано",
                    date: this.event.start
                        ? moment(this.event.start).format("YYYY-MM-DD")
                        : moment().format("YYYY-MM-DD"),
                    startTime: this.event.start
                        ? moment(this.event.start).format("HH:mm")
                        : null,
                    endTime: this.event.start
                        ? moment(this.event.end).format("HH:mm")
                        : null,
                    details: this.event.details || null
                };
            },
            saveForm() {
                this.$v.$touch();

                if (!this.$v.$invalid) {
                    let data = this.prepareData();

                    if (this.selectedId) {
                        this.updateEvent({ id: this.selectedId, data });
                    } else {
                        this.createEvent({ data });
                    }
                }
            },
            prepareData() {
                return {
                    patient_id: this.form.patient.id,
                    start: moment(this.form.date + " " + this.form.startTime).format(
                        "YYYY-MM-DD HH:mm:ss"
                    ),
                    end: moment(this.form.date + " " + this.form.endTime).format(
                        "YYYY-MM-DD HH:mm:ss"
                    ),
                    status: this.form.status,
                    details: this.form.details
                };
            },
            createEvent(payload) {
                this.isLoading = true;
                this.$store.dispatch('events/createEvent', payload)
                    .then(() => {
                        this.eventCreated();
                    })
                    .finally(() => {
                        this.isLoading = false;
                    });
            },
            updateEvent(payload) {
                this.isLoading = true;
                this.$store.dispatch('events/updateEvent', payload)
                    .then(() => {
                        let event = {
                            name: this.form.patient.full_name,
                            start: moment(this.form.date + ' ' + this.form.startTime).format('YYYY-MM-DD HH:mm:ss'),
                            end: moment(this.form.date + ' ' + this.form.endTime).format('YYYY-MM-DD HH:mm:ss'),
                            status: this.form.status,
                            details: this.form.details,
                            patient: this.form.patient,
                        };
                        this.eventUpdated(event);
                    })
                    .finally(() => {
                        this.isLoading = false;
                    });
            },
            eventCreated() {
                this.$v.$reset();
                this.$emit("on-create");
            },
            eventUpdated(event) {
                this.$v.$reset();
                this.$emit("on-update", event);
            },
            closeForm() {
                this.$v.$reset();
                this.$emit("on-close");
            },
        },
    };
</script>

<style scoped>
    .dashboard-card__status-select {
        max-width: 185px;
    }

    @media (max-width: 600px) {
        .dashboard-card__status-select {
            min-width: 100%;
            margin-top: 10px;
        }
    }
</style>
