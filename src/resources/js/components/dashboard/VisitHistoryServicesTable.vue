<template>
    <v-dialog
        v-model="dialog"
        scrollable
        persistent
        max-width="600px"
    >
        <v-card max-height="600px" class="dashboard-card">
            <v-card-title class="py-4">
                <h3 class="dashboard-card__title">Provided services</h3>
                <v-spacer></v-spacer>
                <v-btn icon @click="closeServicesTable">
                    <v-icon>mdi-close</v-icon>
                </v-btn>
            </v-card-title>

            <v-divider></v-divider>

            <v-card-text class="py-6">
                <v-data-table
                    disable-sort
                    hide-default-footer
                    :headers="headers"
                    :items="services"
                    :loading="isLoading"
                    class="dashboard-card__table--modal"
                ></v-data-table>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>

<script>
    export default {
        name: "VisitHistoryServicesTable",

        props: {
            selectedId: {
                type: Number
            },
            dialog: {
                type: Boolean,
                default: false
            },
        },

        data() {
            return {
                isLoading: false,
                headers: [
                    {
                        text: 'ID',
                        align: 'left',
                        value: 'id',
                    },
                    {
                        text: 'Name',
                        align: 'left',
                        value: 'name',
                    },
                    {
                        text: 'Count',
                        align: 'left',
                        value: 'service_count',
                    },
                    {
                        text: 'Price',
                        align: 'left',
                        value: 'total_cost',
                    },
                ],
            }
        },

        computed: {
            services() {
                if (this.$store.state.patients.visitHistoryServices && this.$store.state.patients.visitHistoryServices.length) {
                    return this.$store.state.patients.visitHistoryServices.map(service => {
                        return {
                            'id': service.id,
                            'name': service.name,
                            'service_count': service.pivot.service_count,
                            'total_cost': service.pivot.total_cost,
                        };
                    });
                }

                return [];
            }
        },

        watch: {
            selectedId(newId) {
                this.loadServices(newId);
            }
        },

        methods: {
            loadServices(id) {
                this.isLoading = true;
                this.$store.dispatch('patients/loadVisitHistoryServices', { id })
                    .finally(() => {
                        this.isLoading = false;
                    });
            },
            closeServicesTable() {
                this.$emit("on-close");
            }
        }
    }
</script>

<style scoped>

</style>
