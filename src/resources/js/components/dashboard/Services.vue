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
                            class="hidden-sm-and-down"
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
                            :items="services"
                            :server-items-length="totalServices"
                            :options.sync="options"
                            :loading="isLoading"
                            :items-per-page="15"
                            :footer-props="footerProps"
                            class="elevation-1"
                        >
                            <template v-slot:item.action="{ item }">
                                <v-tooltip bottom>
                                    <template v-slot:activator="{ on }">
                                        <v-btn text icon @click="editService(item)">
                                            <v-icon small v-on="on">mdi-pencil</v-icon>
                                        </v-btn>
                                    </template>
                                    <span>Edit service</span>
                                </v-tooltip>
                                <v-tooltip bottom>
                                    <template v-slot:activator="{ on }">
                                        <v-btn text icon @click="deleteService(item)">
                                            <v-icon small v-on="on">mdi-delete</v-icon>
                                        </v-btn>
                                    </template>
                                    <span>Delete service</span>
                                </v-tooltip>
                            </template>
                        </v-data-table>
                    </v-card-text>

                    <service-form :dialog="dialog"
                                  :service="editedService"
                                  :selected-id="selectedId"
                                  @on-create="serviceCreated"
                                  @on-update="serviceUpdated"
                                  @on-close="closeForm"
                    ></service-form>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
    import ServiceForm from "./ServiceForm";

    export default {
        name: "Users",

        components: {
            ServiceForm,
        },

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
                        text: 'Price',
                        align: 'left',
                        sortable: true,
                        value: 'price',
                    },
                    {
                        text: 'Actions',
                        align: 'left',
                        sortable: false,
                        value: 'action',
                    },
                ],
                dialog: false,
                selectedId: null,
                editedService: {},
            }
        },

        computed: {
            services() {
                return this.$store.state.services.services || [];
            },
            totalServices() {
                return this.$store.state.services.pagination.total || 0;
            }
        },

        watch: {
            options: {
                handler() {
                    this.loadServices();
                },
                deep: true,
            },
            search() {
                this.options.page = 1;
                this.loadServices();
            }
        },

        methods: {
            loadServices() {
                let params = {
                    q: this.search,
                    page: this.options.page || 1,
                    limit: this.options.itemsPerPage || 15,
                    sort_by: this.options.sortBy || null,
                    sort_desc: this.options.sortDesc || null,
                };

                this.isLoading = true;
                this.$store.dispatch('services/loadServices', { params })
                    .finally(() => {
                        this.isLoading = false;
                    });
            },
            editService(service) {
                this.selectedId = service.id;
                this.editedService = service;
                this.dialog = true;
            },
            deleteService(service) {
                if (confirm('Are you sure you want to delete this service?')) {
                    this.$store.dispatch('services/deleteService', service.id)
                        .then(() => {
                            this.loadServices();
                        });
                }
            },
            serviceCreated() {
                this.loadServices();
                this.closeForm();
            },
            serviceUpdated() {
                this.loadServices();
                this.closeForm();
            },
            closeForm() {
                this.dialog = false;
                setTimeout(() => {
                    this.selectedId = null;
                    this.editedService = {};
                }, 300);
            },
        }
    }
</script>

<style scoped>

</style>
