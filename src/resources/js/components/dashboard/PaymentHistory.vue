<template>
    <v-container fluid>
        <v-row class="ma-0" justify="center">
            <v-col cols="12" class="py-0">
                <v-card>
                    <v-toolbar flat color="white">
                        <v-spacer></v-spacer>
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

                    <payment-history-form :patient-id="id"
                                          :dialog="dialog"
                                          :record="editedRecord"
                                          :selected-id="selectedId"
                                          @on-create="recordCreated"
                                          @on-update="recordUpdated"
                                          @on-close="closeForm"
                    ></payment-history-form>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
    import PaymentHistoryForm from "./PaymentHistoryForm";

    export default {
        name: "PaymentHistory",

        components: {
            PaymentHistoryForm,
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
                dialog: false,
                selectedId: null,
                editedRecord: {},
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
        },

        methods: {
            loadPaymentHistory() {
                let params = {
                    page: this.options.page || 1,
                    limit: this.options.itemsPerPage || 15,
                    sort_by: this.options.sortBy || [],
                    sort_desc: this.options.sortDesc || [],
                };

                this.isLoading = true;
                this.$store.dispatch('patients/loadPaymentHistory', { id: this.id, params })
                    .finally(() => {
                        this.isLoading = false;
                    });
            },
            recordCreated() {
                this.loadPaymentHistory();
                this.closeForm();
            },
            recordUpdated() {
                this.loadPaymentHistory();
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
