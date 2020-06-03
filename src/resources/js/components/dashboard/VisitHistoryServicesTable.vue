<template>
    <v-dialog
        v-model="dialog"
        scrollable
        persistent
        max-width="600px"
    >
        <v-card max-height="600px" class="dashboard-card">
            <v-card-title class="py-4">
                <h3 class="dashboard-card__title">Перечень услуг</h3>
                <v-spacer></v-spacer>
                <v-btn icon @click="closeServicesTable">
                    <v-icon>mdi-close</v-icon>
                </v-btn>
            </v-card-title>

            <v-divider></v-divider>

            <v-card-text class="py-6">
                <v-data-table
                    v-if="!isLoading"
                    disable-sort
                    hide-default-footer
                    :headers="headers"
                    :items="services"
                    class="dashboard-card__table--modal"
                    no-data-text="Нет записей"
                ></v-data-table>
                <div class="card-progress" v-else>
                    <v-progress-circular
                        :size="50"
                        color="primary"
                        indeterminate
                    ></v-progress-circular>
                </div>
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
                        text: 'Название',
                        align: 'left',
                        value: 'name',
                    },
                    {
                        text: 'Количество',
                        align: 'left',
                        value: 'service_count',
                    },
                    {
                        text: 'Стоимость',
                        align: 'left',
                        value: 'total_cost',
                    },
                    {
                        text: 'ID Зуба',
                        align: 'left',
                        value: 'tooth_id',
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
                            'tooth_id': service.pivot.tooth_id,
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

<style lang="scss" scoped>
  .card-progress {
    min-height: 100px;
  }
</style>