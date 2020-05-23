<template>
    <v-container fluid>
        <v-row class="mx-0 mb-8" justify="center">
            <v-col cols="12" md="8" class="py-0">
                <v-card class="dashboard-card">
                    <v-card-title class="py-4 px-6">
                        <h3 class="dashboard-card__title">Patient profile</h3>
                        <v-spacer></v-spacer>
                        <v-btn color="primary" link :to="{ name: 'dashboard.edit-patient', params: { id }}">
                            <v-icon left>mdi-pencil</v-icon>
                            Edit profile
                        </v-btn>
                    </v-card-title>

                    <v-divider></v-divider>

                    <v-card-text class="patient-info pt-4 pb-6 px-6">
                        <v-row>
                            <v-col cols="12" sm="4">
                                <div class="patient-info__avatar">
                                    <v-img
                                        :src="avatar"
                                        width="300"
                                        class="elevation-2"
                                    ></v-img>
                                </div>
                            </v-col>
                            <v-col cols="12" sm="8">
                                <div class="patient-info__group">
                                    <h4 class="patient-info__group-title">Personal info</h4>
                                    <v-row class="patient-info__group-row">
                                        <v-col cols="12" sm="3" class="patient-info__group-column">
                                            <h5 class="patient-info__details-heading">ID:</h5>
                                        </v-col>
                                        <v-col cols="12" sm="9" class="patient-info__group-column">
                                            <p class="patient-info__details">{{ patient.id ? patient.id : '-' }}</p>
                                        </v-col>
                                    </v-row>
                                    <v-row class="patient-info__group-row">
                                        <v-col cols="12" sm="3" class="patient-info__group-column">
                                            <h5 class="patient-info__details-heading">Full Name:</h5>
                                        </v-col>
                                        <v-col cols="12" sm="9" class="patient-info__group-column">
                                            <p class="patient-info__details">{{ patient.full_name ? patient.full_name : '-' }}</p>
                                        </v-col>
                                    </v-row>
                                    <v-row class="patient-info__group-row">
                                        <v-col cols="12" sm="3" class="patient-info__group-column">
                                            <h5 class="patient-info__details-heading">Gender:</h5>
                                        </v-col>
                                        <v-col cols="12" sm="9" class="patient-info__group-column">
                                            <p class="patient-info__details">{{ patientGender ? patientGender : '-' }}</p>
                                        </v-col>
                                    </v-row>
                                    <v-row class="patient-info__group-row">
                                        <v-col cols="12" sm="3" class="patient-info__group-column">
                                            <h5 class="patient-info__details-heading">Birth date:</h5>
                                        </v-col>
                                        <v-col cols="12" sm="9" class="patient-info__group-column">
                                            <p class="patient-info__details">{{ patient.birth_date ? patient.birth_date : '-' }}</p>
                                        </v-col>
                                    </v-row>
                                </div>

                                <v-divider class="mt-4 mb-6"></v-divider>

                                <div class="patient-info__group">
                                    <h4 class="patient-info__group-title">Contact details</h4>
                                    <v-row class="patient-info__group-row">
                                        <v-col cols="12" sm="3" class="patient-info__group-column">
                                            <h5 class="patient-info__details-heading">Phone:</h5>
                                        </v-col>
                                        <v-col cols="12" sm="9" class="patient-info__group-column">
                                            <p class="patient-info__details">{{ patient.phone ? patient.phone : '-' }}</p>
                                        </v-col>
                                    </v-row>
                                    <v-row class="patient-info__group-row">
                                        <v-col cols="12" sm="3" class="patient-info__group-column">
                                            <h5 class="patient-info__details-heading">Email address:</h5>
                                        </v-col>
                                        <v-col cols="12" sm="9" class="patient-info__group-column">
                                            <p class="patient-info__details">{{ patient.email ? patient.email : '-' }}</p>
                                        </v-col>
                                    </v-row>
                                </div>

                                <v-divider class="mt-4 mb-6"></v-divider>

                                <div class="patient-info__group">
                                    <h4 class="patient-info__group-title">Medical info</h4>
                                    <v-row class="patient-info__group-row">
                                        <v-col cols="12" sm="3" class="patient-info__group-column">
                                            <h5 class="patient-info__details-heading">Anamnesis:</h5>
                                        </v-col>
                                        <v-col cols="12" sm="9" class="patient-info__group-column">
                                            <p class="patient-info__details">{{ patient.anamnesis && patient.anamnesis.name ? patient.anamnesis.name : '-' }}</p>
                                        </v-col>
                                    </v-row>
                                    <v-row class="patient-info__group-row">
                                        <v-col cols="12" sm="3" class="patient-info__group-column">
                                            <h5 class="patient-info__details-heading">Notes:</h5>
                                        </v-col>
                                        <v-col cols="12" sm="9" class="patient-info__group-column">
                                            <p class="patient-info__details">{{ patient.medical_info ? patient.medical_info : '-' }}</p>
                                        </v-col>
                                    </v-row>
                                </div>
                            </v-col>
                        </v-row>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
        
        <v-row class="mx-0 mb-8" justify="center">
            <v-col cols="12" md="8" class="py-0">
                <v-card class="dashboard-card">
                    <v-card-title class="py-4 px-6">
                        <h3 class="dashboard-card__title">Teeth map</h3>
                    </v-card-title>
                    <v-divider></v-divider>
                    <v-card-text class="pt-4 pb-6 px-6">
                        <teeth-map :value="selectedTooth" @change="(value) => selectedTooth = value"/>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>

        <v-row class="mx-0" justify="center">
            <v-col cols="12" md="8" class="py-0">
                <v-card class="dashboard-card">
                    <v-card-title class="py-4 px-6">
                        <h3 class="dashboard-card__title">Services history</h3>
                    </v-card-title>
                    <v-divider></v-divider>
                    <v-card-text class="pa-0">
                        <v-data-table
                            :headers="headers"
                            :items="historyItems"
                            :server-items-length="totalHistoryItems"
                            :options.sync="options"
                            :loading="isLoading"
                            :items-per-page="15"
                            :footer-props="footerProps"
                            class="elevation-1"
                        ></v-data-table>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
    import TeethMap from './TeethMap';

    const MALE = 0;
    const FEMALE = 1;

    export default {
        name: "PatientProfile",

        props: ['id'],

        components: {
            TeethMap
        },

        data() {
            return {
                selectedTooth: null,
                search: null,
                isLoading: false,
                options: {},
                footerProps: {
                    'items-per-page-options': [15, 30, 45]
                },
                headers: [
                    {
                        text: 'ID',
                        align: 'left',
                        sortable: false,
                        value: 'id',
                    },
                    {
                        text: 'Name',
                        align: 'left',
                        sortable: true,
                        value: 'name',
                    },
                    {
                        text: 'Count',
                        align: 'left',
                        sortable: true,
                        value: 'service_count',
                    },
                    {
                        text: 'Total cost',
                        align: 'left',
                        sortable: true,
                        value: 'total_cost',
                    },
                    {
                        text: 'Date',
                        align: 'left',
                        sortable: true,
                        value: 'date',
                    },
                ],
            }
        },

        computed: {
            patient() {
                return this.$store.state.patients.selectedPatient || {};
            },
            avatar() {
                return this.patient.image_path || '/storage/images/no-profile-image.png';
            },
            patientGender() {
                if (this.patient.gender || this.patient.gender === 0) {
                    return (+this.patient.gender === MALE) ? 'male' : (+this.patient.gender === FEMALE) ? 'female' : null;
                }

                return null;
            },
            historyItems() {
                return this.$store.state.patients.serviceHistory || [];
            },
            totalHistoryItems() {
                return this.$store.state.patients.serviceHistoryPagination.total || 0;
            }
        },

        watch: {
            options: {
                handler() {
                    this.loadServiceHistory();
                },
                deep: true,
            },
            selectedTooth() {
                this.options.page = 1;
                this.loadServiceHistory();
            }
        },

        created() {
            this.loadPatient();
        },

        methods: {
            loadPatient() {
                this.isLoading = true;
                this.$store.dispatch('patients/loadPatient', this.id)
                    .finally(() => {
                        this.isLoading = false;
                    });
            },
            loadServiceHistory() {
                let params = {
                    tooth: this.selectedTooth,
                    page: this.options.page || 1,
                    limit: this.options.itemsPerPage || 15,
                    sort_by: this.options.sortBy || null,
                    sort_desc: this.options.sortDesc || null,
                };

                this.isLoading = true;
                this.$store.dispatch('patients/loadServiceHistory', { id: this.id, params })
                    .finally(() => {
                        this.isLoading = false;
                    });
            },
        }
    }
</script>

<style scoped>
    .patient-info__group-title {
        font-weight: 400;
        letter-spacing: normal;
        font-size: 26px;
        text-transform: capitalize;
        margin-bottom: 20px;
    }
    .patient-info__details-heading {
        font-size: 14px;
    }
    .patient-info__details {
        font-size: 14px;
        margin-bottom: 0;
    }
    .patient-info__group-row {
        margin-left: -8px;
        margin-right: -8px;
    }
    .patient-info__group-column {
        padding: 6px;
    }
</style>
