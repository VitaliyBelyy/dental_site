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
                    </v-toolbar>
                    <v-divider></v-divider>
                    <v-card-text class="pa-0">
                        <v-data-table
                            :headers="headers"
                            :items="patients"
                            :server-items-length="totalPatients"
                            :options.sync="options"
                            :loading="isLoading"
                            :items-per-page="5"
                            :footer-props="footerProps"
                            :multi-sort="true"
                            class="elevation-1"
                        >
                            <template v-slot:item.action="{ item }">
                                <v-menu left offset-y origin="center center" :nudge-bottom="10" transition="scale-transition">
                                    <template v-slot:activator="{ on }">
                                        <v-btn text icon v-on="on">
                                            <v-icon>mdi-dots-vertical</v-icon>
                                        </v-btn>
                                    </template>

                                    <v-list class="pa-0">
                                        <v-list-item
                                            v-for="(item, index) in patientActions"
                                            :to="!item.href ? { name: item.name } : null"
                                            :href="item.href"
                                            @click="() => item.click(item.id)"
                                            ripple="ripple"
                                            :disabled="item.disabled"
                                            :key="index"
                                        >
                                            <v-list-item-content>
                                                <v-list-item-title>{{ item.title }}</v-list-item-title>
                                            </v-list-item-content>
                                            <v-list-item-icon v-if="item.icon">
                                                <v-icon>{{ item.icon }}</v-icon>
                                            </v-list-item-icon>
                                        </v-list-item>
                                    </v-list>
                                </v-menu>
                            </template>
                        </v-data-table>
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
        name: "Patients",

        data() {
            return {
                search: null,
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
                        text: 'Name',
                        align: 'left',
                        sortable: true,
                        value: 'name',
                    },
                    {
                        text: 'Phone',
                        align: 'left',
                        sortable: true,
                        value: 'phone',
                    },
                    {
                        text: 'Email',
                        align: 'left',
                        sortable: true,
                        value: 'email',
                    },
                    {
                        text: 'Gender',
                        align: 'left',
                        sortable: false,
                        value: 'gender',
                    },
                    {
                        text: 'Birth date',
                        align: 'left',
                        sortable: false,
                        value: 'birth_date',
                    },
                    {
                        text: 'Anamnesis',
                        align: 'left',
                        sortable: false,
                        value: 'anamnesis',
                    },
                    {
                        text: 'Actions',
                        align: 'left',
                        sortable: false,
                        value: 'action',
                    },
                ],
                patientActions: [
                    {
                        icon: "mdi-exit-to-app",
                        href: "#",
                        title: 'Test',
                    },
                ]
            }
        },

        computed: {
            patients() {
                if (this.$store.state.patients.patients && this.$store.state.patients.patients.length) {
                    return this.$store.state.patients.patients.map(patient => {
                       return {
                           'id': patient.id,
                           'name': patient.first_name + ' ' + patient.last_name,
                           'phone': patient.phone || null,
                           'email': patient.email || null,
                           'gender': (+patient.gender === MALE) ? 'male' : (+patient.gender === FEMALE) ? 'female' : null,
                           'birth_date': patient.birth_date ? moment(patient.birth_date).format('DD-MM-YYYY') : null,
                           'anamnesis': (patient.anamnesis && patient.anamnesis.name) ? patient.anamnesis.name : null,
                       };
                    });
                }

                return [];
            },
            totalPatients() {
                return this.$store.state.patients.pagination.total || 0;
            }
        },

        watch: {
            options: {
                handler() {
                    this.loadPatients();
                },
                deep: true,
            },
            search() {
                this.options.page = 1;
                this.loadPatients();
            }
        },

        methods: {
            loadPatients() {
                let params = {
                    q: this.search,
                    page: this.options.page || 1,
                    limit: this.options.itemsPerPage || 5,
                    sort_by: this.options.sortBy || [],
                    sort_desc: this.options.sortDesc || [],
                };

                this.isLoading = true;
                this.$store.dispatch('patients/loadPatients', { params })
                    .finally(() => {
                        this.isLoading = false;
                    });
            },
        }
    }
</script>

<style scoped>

</style>
