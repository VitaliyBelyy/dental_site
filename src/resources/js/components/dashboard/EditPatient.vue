<template>
    <v-container fluid>
        <v-row class="ma-0" justify="center">
            <v-col cols="12" md="8" class="py-0">
                <patient-form title="Edit patient"
                              :patient="patient"
                              :is-loading="dataProcessing"
                              @on-submit="updatePatient"
                ></patient-form>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
    import PatientForm from "./PatientForm";

    export default {
        name: "UpdatePatient",

        props: ['id'],

        components: {
            PatientForm,
        },

        data() {
            return {
                isLoading: false,
                dataProcessing: false,
            }
        },

        computed: {
            patient() {
                return this.$store.state.patients.selectedPatient || {};
            },
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
            updatePatient(data) {
                this.dataProcessing = true;
                this.$store.dispatch('patients/updatePatient', { id: this.id, data })
                    .then(() => {
                        this.$router.push({ name: 'dashboard.patient-profile', params: { id: this.id }});
                    })
                    .finally(() => {
                        this.dataProcessing = false;
                    });
            },
        }
    }
</script>

<style scoped>

</style>
