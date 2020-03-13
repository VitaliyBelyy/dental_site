<template>
    <v-container fluid>
        <v-row class="ma-0" justify="center">
            <v-col cols="12" class="py-0">
                <v-card>
                    <v-toolbar flat color="white">
                        <v-text-field
                            flat
                            solo
                            prepend-icon="search"
                            placeholder="Type something"
                            v-model="search"
                            hide-details
                            class="hidden-sm-and-down mr-4"
                        ></v-text-field>
                        <v-btn color="primary" @click="dialog = true">
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
                        </v-data-table>
                    </v-card-text>

                    <service-history-form :patient-id="id"
                                          :dialog="dialog"
                                          :record="editedRecord"
                                          :selected-id="selectedId"
                                          @on-create="recordCreated"
                                          @on-update="recordUpdated"
                                          @on-close="closeForm"
                    ></service-history-form>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
    import ServiceHistoryForm from "./ServiceHistoryForm";

    export default {
        name: "ServiceHistory",

        components: {
            ServiceHistoryForm,
        },

        props: ['id'],

        data() {
            return {
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
                        text: 'Service cost',
                        align: 'left',
                        sortable: true,
                        value: 'service_cost',
                    },
                    {
                        text: 'Date',
                        align: 'left',
                        sortable: true,
                        value: 'date',
                    },
                ],
                dialog: false,
                selectedId: null,
                editedRecord: {},
            }
        },

        computed: {
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
            search() {
                this.options.page = 1;
                this.loadServiceHistory();
            }
        },

        methods: {
            loadServiceHistory() {
                let params = {
                    q: this.search,
                    page: this.options.page || 1,
                    limit: this.options.itemsPerPage || 15,
                    sort_by: this.options.sortBy || [],
                    sort_desc: this.options.sortDesc || [],
                };

                this.isLoading = true;
                this.$store.dispatch('patients/loadServiceHistory', { id: this.id, params })
                    .finally(() => {
                        this.isLoading = false;
                    });
            },
            recordCreated() {
                this.loadServiceHistory();
                this.closeForm();
            },
            recordUpdated() {
                this.loadServiceHistory();
                this.closeForm();
            },
            closeForm() {
                this.dialog = false;
                setTimeout(() => {
                    this.selectedId = null;
                    this.editedRecord = {};
                }, 300);
            },
        }
    }
</script>

<style scoped>

</style>
