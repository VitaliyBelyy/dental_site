<template>
    <v-container fluid>
        <v-row class="ma-0" justify="center">
            <v-col cols="12" class="py-0">
                <v-card>
                    <v-toolbar flat color="white">
                        <v-spacer></v-spacer>
                        <v-btn color="primary" link :to="{ name: 'dashboard.create-payment-history-record', params: { id }}">
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
                            :items-per-page="5"
                            :footer-props="footerProps"
                            class="elevation-1"
                        >
                        </v-data-table>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
    export default {
        name: "PaymentHistory",

        props: ['id'],

        data() {
            return {
                isLoading: false,
                options: {},
                footerProps: {
                    'items-per-page-options': [5, 15, 30]
                },
                headers: [
                    {
                        text: 'ID',
                        align: 'left',
                        sortable: false,
                        value: 'id',
                    },
                    {
                        text: 'Amount',
                        align: 'left',
                        sortable: true,
                        value: 'amount',
                    },
                    {
                        text: 'Date',
                        align: 'left',
                        sortable: true,
                        value: 'date',
                    },
                    {
                        text: 'Notes',
                        align: 'left',
                        sortable: false,
                        value: 'notes',
                    },
                ],
            }
        },

        computed: {
            historyItems() {
                return this.$store.state.patients.paymentHistory || [];
            },
            totalHistoryItems() {
                return this.$store.state.patients.paymentHistoryPagination.total || 0;
            }
        },

        watch: {
            options: {
                handler() {
                    this.loadPaymentHistory();
                },
                deep: true,
            },
            search() {
                this.options.page = 1;
                this.loadPaymentHistory();
            }
        },

        methods: {
            loadPaymentHistory() {
                let params = {
                    page: this.options.page || 1,
                    limit: this.options.itemsPerPage || 5,
                    sort_by: this.options.sortBy || [],
                    sort_desc: this.options.sortDesc || [],
                };

                this.isLoading = true;
                this.$store.dispatch('patients/loadPaymentHistory', { id: this.id, params })
                    .finally(() => {
                        this.isLoading = false;
                    });
            },
        }
    }
</script>

<style scoped>

</style>
