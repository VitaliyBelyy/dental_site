<template>
    <v-container fluid>
        <v-row class="ma-0" justify="center">
            <v-col cols="12" class="py-0">
                <v-btn fab dark color="primary" class="add-event-button" @click="dialog = true">
                    <v-icon>mdi-plus</v-icon>
                </v-btn>

                <event-form
                    :dialog="dialog"
                    :event="editedEvent"
                    :selected-id="selectedId"
                    @on-create="eventCreated"
                    @on-update="eventUpdated"
                    @on-close="closeForm"
                ></event-form>

                <v-card>
                    <v-toolbar flat color="white" height="64">
                        <v-btn outlined class="mr-4" @click="setToday">
                            Today
                        </v-btn>
                        <v-btn fab text small class="mr-1" @click="prev">
                            <v-icon small>mdi-chevron-left</v-icon>
                        </v-btn>
                        <v-btn fab text small class="mr-4" @click="next">
                            <v-icon small>mdi-chevron-right</v-icon>
                        </v-btn>

                        <v-toolbar-title>{{ title }}</v-toolbar-title>
                        <div class="flex-grow-1"></div>
                        <v-menu offset-y origin="center center" :nudge-bottom="10" transition="scale-transition">
                            <template v-slot:activator="{ on }">
                                <v-btn outlined v-on="on">
                                    <span>{{ typeToLabel[type] }}</span>
                                    <v-icon right>mdi-menu-down</v-icon>
                                </v-btn>
                            </template>
                            <v-list>
                                <v-list-item @click="type = 'day'">
                                    <v-list-item-title>Day</v-list-item-title>
                                </v-list-item>
                                <v-list-item @click="type = 'week'">
                                    <v-list-item-title>Week</v-list-item-title>
                                </v-list-item>
                                <v-list-item @click="type = 'month'">
                                    <v-list-item-title>Month</v-list-item-title>
                                </v-list-item>
                            </v-list>
                        </v-menu>
                    </v-toolbar>

                    <v-divider></v-divider>
                    <v-progress-linear indeterminate color="primary" height="2" v-if="isLoading"></v-progress-linear>

                    <v-card-text>
                        <v-calendar
                            class="calendar"
                            ref="calendar"
                            v-model="focus"
                            color="primary"
                            :events="events"
                            :event-color="(event) => getStatusColor(event.status)"
                            :event-margin-bottom="3"
                            :now="today"
                            :type="type"
                            :weekdays="[1,2,3,4,5,6,0]"
                            @click:event="showEvent"
                            @click:more="viewDay"
                            @click:date="viewDay"
                            @change="updateRange"
                        ></v-calendar>
                        <v-menu
                            v-model="selectedOpen"
                            :close-on-content-click="false"
                            :activator="selectedElement"
                            offset-x
                        >
                            <v-card color="grey lighten-4" min-width="350px" flat>
                                <v-toolbar :color="getStatusColor(selectedEvent.status)" dark>
                                    <v-btn @click="deleteEvent(selectedEvent)" icon>
                                        <v-icon>mdi-delete</v-icon>
                                    </v-btn>
                                    <v-toolbar-title v-html="selectedEvent.name"></v-toolbar-title>
                                    <div class="flex-grow-1"></div>
                                </v-toolbar>
                                <v-card-text>
                                    {{ selectedEvent.details }}
                                </v-card-text>
                                <v-card-actions>
                                    <v-btn text color="secondary" @click="selectedOpen = false">
                                        Close
                                    </v-btn>
                                    <v-spacer></v-spacer>
                                    <v-btn text color="primary" @click.prevent="editEvent(selectedEvent)">
                                        Edit
                                    </v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-menu>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
    import moment from 'moment';
    import EventForm from "./EventForm";

    export default {
        name: "Calendar",

        components: {
            EventForm,
        },

        data () {
            return {
                isLoading: false,
                today: moment().format('YYYY-MM-DD'),
                focus: moment().format('YYYY-MM-DD'),
                type: 'month',
                typeToLabel: {
                    month: 'Month',
                    week: 'Week',
                    day: 'Day',
                },
                start: null,
                end: null,
                selectedId: null,
                selectedEvent: {},
                editedEvent: {},
                selectedElement: null,
                selectedOpen: false,
                dialog: false,
            }
        },

        created () {
            this.loadEvents();
        },

        computed: {
            events () {
                if (this.$store.state.events.events && this.$store.state.events.events.length) {
                    return this.$store.state.events.events.map(event => {
                        return {
                            'id': event.id,
                            'name': (event.patient && event.patient.full_name) ? event.patient.full_name : null,
                            'start': event.start,
                            'end': event.end,
                            'status': event.status,
                            'details': event.details || null,
                            'patient': event.patient || null,
                            'user': event.user || null
                        };
                    });
                }

                return [];
            },
            title () {
                const { start, end } = this;
                if (!start || !end) {
                    return '';
                }

                const startMonth = moment(start.date).format('MMMM');
                const endMonth = moment(end.date).format('MMMM');
                const suffixMonth = startMonth === endMonth ? '' : endMonth;
                const startYear = start.year;
                const endYear = end.year;
                const suffixYear = startYear === endYear ? '' : endYear;
                const startDay = moment(start.date).format('Do');
                const endDay = moment(end.date).format('Do');
                switch (this.type) {
                    case 'month':
                        return `${startMonth} ${startYear}`;
                    case 'week':
                        return suffixYear ? `${startMonth} ${startDay} ${startYear} - ${suffixMonth} ${endDay} ${suffixYear}` : `${startMonth} ${startDay} - ${suffixMonth} ${endDay} ${startYear}`;
                    case 'day':
                        return `${startMonth} ${startDay} ${startYear}`;
                }

                return '';
            }
        },

        methods: {
            loadEvents () {
                this.isLoading = true;
                this.$store.dispatch('events/loadEvents')
                    .finally(() => {
                        this.isLoading = false;
                    });
            },
            viewDay ({ date }) {
                this.focus = date;
                this.type = 'day';
            },
            setToday () {
                this.focus = this.today;
            },
            prev () {
                this.$refs.calendar.prev();
            },
            next () {
                this.$refs.calendar.next();
            },
            updateRange ({ start, end }) {
                this.start = start;
                this.end = end;
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
            editEvent (event) {
                this.$store.dispatch('events/setPatients', [event.patient]);
                this.selectedId = event.id;
                this.editedEvent = event;
                this.dialog = true;
            },
            deleteEvent (event) {
                this.$store.dispatch('events/deleteEvent', event.id)
                    .then(() => {
                        this.selectedOpen = false;
                        this.loadEvents();
                    });
            },
            showEvent ({ nativeEvent, event }) {
                const open = () => {
                    this.selectedEvent = event;
                    this.selectedElement = nativeEvent.target;
                    setTimeout(() => this.selectedOpen = true, 10)
                };

                if (this.selectedOpen) {
                    this.selectedOpen = false;
                    setTimeout(open, 10);
                } else {
                    open();
                }

                nativeEvent.stopPropagation();
            },
            eventCreated () {
                this.loadEvents();
                this.closeForm();
            },
            eventUpdated (data) {
                this.selectedEvent = Object.assign({}, this.selectedEvent, data);
                this.loadEvents();
                this.closeForm();
            },
            closeForm () {
                this.dialog = false;
                setTimeout(() => {
                    this.selectedId = null;
                    this.editedEvent = {};
                }, 300);
            },
        },
    }
</script>

<style scoped>
    .add-event-button {
        position: fixed;
        bottom: 45px;
        right: 25px;
        z-index: 99;
    }
    .calendar {
        min-height: 650px;
    }
</style>
