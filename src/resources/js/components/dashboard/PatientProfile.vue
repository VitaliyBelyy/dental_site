<template>
    <v-container fluid>
        <v-row class="ma-0" justify="center">
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
                                    <v-row v-if="patient.id" class="patient-info__group-row">
                                        <v-col cols="12" sm="3" class="patient-info__group-column">
                                            <h5 class="patient-info__details-heading">ID:</h5>
                                        </v-col>
                                        <v-col cols="12" sm="9" class="patient-info__group-column">
                                            <p class="patient-info__details">{{ patient.id }}</p>
                                        </v-col>
                                    </v-row>
                                    <v-row v-if="patient.first_name && patient.last_name" class="patient-info__group-row">
                                        <v-col cols="12" sm="3" class="patient-info__group-column">
                                            <h5 class="patient-info__details-heading">Name:</h5>
                                        </v-col>
                                        <v-col cols="12" sm="9" class="patient-info__group-column">
                                            <p class="patient-info__details">{{ patient.first_name + ' ' + patient.last_name }}</p>
                                        </v-col>
                                    </v-row>
                                    <v-row v-if="gender" class="patient-info__group-row">
                                        <v-col cols="12" sm="3" class="patient-info__group-column">
                                            <h5 class="patient-info__details-heading">Gender:</h5>
                                        </v-col>
                                        <v-col cols="12" sm="9" class="patient-info__group-column">
                                            <p class="patient-info__details">{{ gender }}</p>
                                        </v-col>
                                    </v-row>
                                    <v-row v-if="birthDate" class="patient-info__group-row">
                                        <v-col cols="12" sm="3" class="patient-info__group-column">
                                            <h5 class="patient-info__details-heading">Birth date:</h5>
                                        </v-col>
                                        <v-col cols="12" sm="9" class="patient-info__group-column">
                                            <p class="patient-info__details">{{ birthDate }}</p>
                                        </v-col>
                                    </v-row>
                                </div>

                                <v-divider class="mt-4 mb-6"></v-divider>

                                <div class="patient-info__group">
                                    <h4 class="patient-info__group-title">Contact details</h4>
                                    <v-row v-if="patient.phone" class="patient-info__group-row">
                                        <v-col cols="12" sm="3" class="patient-info__group-column">
                                            <h5 class="patient-info__details-heading">Phone:</h5>
                                        </v-col>
                                        <v-col cols="12" sm="9" class="patient-info__group-column">
                                            <p class="patient-info__details">{{ patient.phone }}</p>
                                        </v-col>
                                    </v-row>
                                    <v-row v-if="patient.email" class="patient-info__group-row">
                                        <v-col cols="12" sm="3" class="patient-info__group-column">
                                            <h5 class="patient-info__details-heading">Email address:</h5>
                                        </v-col>
                                        <v-col cols="12" sm="9" class="patient-info__group-column">
                                            <p class="patient-info__details">{{ patient.email }}</p>
                                        </v-col>
                                    </v-row>
                                </div>

                                <v-divider class="mt-4 mb-6"></v-divider>

                                <div class="patient-info__group">
                                    <h4 class="patient-info__group-title">Medical info</h4>
                                    <v-row v-if="patient.anamnesis && patient.anamnesis.name" class="patient-info__group-row">
                                        <v-col cols="12" sm="3" class="patient-info__group-column">
                                            <h5 class="patient-info__details-heading">Anamnesis:</h5>
                                        </v-col>
                                        <v-col cols="12" sm="9" class="patient-info__group-column">
                                            <p class="patient-info__details">{{ patient.anamnesis.name }}</p>
                                        </v-col>
                                    </v-row>
                                    <v-row v-if="patient.medical_info" class="patient-info__group-row">
                                        <v-col cols="12" sm="3" class="patient-info__group-column">
                                            <h5 class="patient-info__details-heading">Notes:</h5>
                                        </v-col>
                                        <v-col cols="12" sm="9" class="patient-info__group-column">
                                            <p class="patient-info__details">{{ patient.medical_info }}</p>
                                        </v-col>
                                    </v-row>
                                </div>
                            </v-col>
                        </v-row>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
    import moment from 'moment';

    const MALE = 0;
    const FEMALE = 1;

    export default {
        name: "PatientProfile",

        props: ['id'],

        data() {
            return {
                isLoading: false,
            }
        },

        computed: {
            patient() {
                return this.$store.state.patients.selectedPatient || {};
            },
            avatar() {
                return this.patient.image_path || '/storage/images/no-profile-image.png';
            },
            gender() {
                if (this.patient.gender || this.patient.gender === 0) {
                    return (+this.patient.gender === MALE) ? 'male' : (+this.patient.gender === FEMALE) ? 'female' : null;
                }

                return null;
            },
            birthDate() {
                return this.patient.birth_date ? moment(this.patient.birth_date).format('DD-MM-YYYY') : null;
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
        }
    }
</script>

<style scoped>
    .patient-info__group-title {
        font-weight: 400;
        letter-spacing: normal;
        font-size: 22px;
        text-transform: capitalize;
        margin-bottom: 10px;
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
        padding: 8px;
    }
</style>
