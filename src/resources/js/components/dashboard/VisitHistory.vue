<template>
    <v-container fluid>
        <v-row class="ma-0" justify="center">
            <v-col cols="12" class="py-0">
                <v-card>
                    <v-toolbar flat color="white">
                        <v-spacer></v-spacer>
                        <v-btn color="primary" @click="formDialog = true">
                            <v-icon left>mdi-plus</v-icon>
                            Add new
                        </v-btn>
                    </v-toolbar>
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
                        >
                            <template v-slot:item.action="{ item }">
                                <v-tooltip bottom>
                                    <template v-slot:activator="{ on }">
                                        <v-btn text icon @click="showServicesTable(item.id)">
                                            <v-icon small v-on="on">mdi-eye</v-icon>
                                        </v-btn>
                                    </template>
                                    <span>Show provided services</span>
                                </v-tooltip>
                            </template>
                        </v-data-table>
                    </v-card-text>

                    <visit-history-form 
                        :patient-id="id"
                        :dialog="formDialog"
                        :record="editedRecord"
                        :selected-id="selectedId"
                        @on-create="recordCreated"
                        @on-update="recordUpdated"
                        @on-close="closeForm"
                    ></visit-history-form>

                    <visit-history-services-table 
                        :dialog="servicesDialog"
                        :selected-id="visitId"
                        @on-close="closeServicesTable"
                    ></visit-history-services-table>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
    import VisitHistoryForm from "./VisitHistoryForm";
    import VisitHistoryServicesTable from "./VisitHistoryServicesTable";

    export default {
        name: "VisitHistory",

        components: {
            VisitHistoryForm,
            VisitHistoryServicesTable
        },

        props: ['id'],

        data() {
            return {
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
                        text: 'Date',
                        align: 'left',
                        sortable: true,
                        value: 'date',
                    },
                    {
                        text: 'Service cost',
                        align: 'left',
                        sortable: true,
                        value: 'service_cost',
                    },
                    {
                        text: 'Actions',
                        align: 'left',
                        sortable: false,
                        value: 'action',
                    },
                ],
                formDialog: false,
                selectedId: null,
                editedRecord: {},
                servicesDialog: false,
                visitId: null
            }
        },

        computed: {
            historyItems() {
                return this.$store.state.patients.visitHistory || [];
            },
            totalHistoryItems() {
                return this.$store.state.patients.visitHistoryPagination.total || 0;
            }
        },

        watch: {
            options: {
                handler() {
                    this.loadVisitHistory();
                },
                deep: true,
            },
        },

        methods: {
            loadVisitHistory() {
                let params = {
                    page: this.options.page || 1,
                    limit: this.options.itemsPerPage || 15,
                    sort_by: this.options.sortBy || [],
                    sort_desc: this.options.sortDesc || [],
                };

                this.isLoading = true;
                this.$store.dispatch('patients/loadVisitHistory', { id: this.id, params })
                    .finally(() => {
                        this.isLoading = false;
                    });
            },
            recordCreated() {
                this.loadVisitHistory();
                this.closeForm();
            },
            recordUpdated() {
                this.loadVisitHistory();
                this.closeForm();
            },
            closeForm() {
                this.formDialog = false;
                setTimeout(() => {
                    this.selectedId = null;
                    this.editedRecord = {};
                }, 300);
            },
            showServicesTable(id) {
                this.visitId = id;
                this.servicesDialog = true;
            },
            closeServicesTable() {
                this.servicesDialog = false;
            }
        }
    }
</script>

<style scoped>

</style>
