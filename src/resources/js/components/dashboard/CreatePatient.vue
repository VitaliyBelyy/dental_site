<template>
    <v-container fluid>
        <v-row class="ma-0" justify="center">
            <v-col cols="12" md="8" class="py-0">
                <patient-form title="New patient"
                              :is-loading="dataProcessing"
                              @on-submit="createPatient"
                ></patient-form>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
    import PatientForm from "./PatientForm";

    export default {
        name: "CreatePatient",

        components: {
            PatientForm,
        },

        data() {
            return {
                dataProcessing: false,
            }
        },

        methods: {
            createPatient(data) {
                this.isLoading = true;
                this.$store.dispatch('patients/createPatient', { data })
                    .then(() => {
                        this.$router.push({ name: 'dashboard.patients' });
                    })
                    .finally(() => {
                        this.isLoading = false;
                    });
            },
        }
    }
</script>

<style scoped>

</style>
