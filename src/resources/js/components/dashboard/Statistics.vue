<template>
    <v-container fluid>
        <v-row class="ma-0" justify="center">
            <v-col cols="8" class="py-0">
                <v-card>
                    <v-toolbar flat color="white" height="64">
                        <chart-filters @filters-changed="(filters) => loadChartData(filters)"/>
                    </v-toolbar>
                    <v-divider></v-divider>
                    <v-card-text>
                        <line-chart :chart-data="statisticData" :options="statisticOptions" v-if="!statisticLoading"/>
                        <div class="chart-progress" v-else>
                            <v-progress-circular
                                :size="50"
                                color="primary"
                                indeterminate
                            ></v-progress-circular>
                        </div>
                    </v-card-text>
                </v-card>
            </v-col>

            <v-col cols="4" class="py-0">
                <v-card>
                    <v-toolbar flat color="white" height="64">
                        <h3 class="dashboard-card__title">Patients ratio</h3>
                        <v-spacer></v-spacer>
                        <v-select
                            class="ratio-type"
                            dense
                            outlined
                            v-model="ratioType"
                            :items="ratioTypeOptions"
                            hide-details
                            item-text="name"
                            item-value="value"
                            placeholder="Ratio type"
                            :menu-props="{ offsetY: true, nudgeBottom: 10 }"
                        >
                            <template v-slot:item="{ item }">
                                <span class="subtitle-1">{{ item.name }}</span>
                            </template>
                        </v-select>
                    </v-toolbar>
                    <v-divider></v-divider>
                    <v-card-text>
                        <doughnut-chart :chart-data="ratioData" :options="ratioOptions" v-if="!ratioLoading"/>
                        <div class="chart-progress" v-else>
                            <v-progress-circular
                                :size="50"
                                color="primary"
                                indeterminate
                            ></v-progress-circular>
                        </div>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
    import ChartFilters from './ChartFilters.vue';
    import LineChart from './Charts/LineChart.vue';
    import DoughnutChart from './Charts/DoughnutChart.vue';

    const colors = {
      "Visits": '#dc62f8',
      "Payments": '#aef849',
      "Services Cost": '#f8a421',
    };

    export default {
        components: {
            ChartFilters,
            LineChart,
            DoughnutChart
        },

        data() {
            return {
                statisticLoading: false,
                ratioLoading: false,
                statisticData: {
                    labels: [],
                    datasets: [],
                },
                ratioData: {
                    labels: [],
                    datasets: [],
                },
                statisticOptions: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: {
                        duration: 0
                    },
                    hover: {
                        animationDuration: 0
                    },
                    responsiveAnimationDuration: 0
                },
                ratioOptions: {
                    responsive: true,
                    maintainAspectRatio: false,
                },
                ratioType: null,
                ratioTypeOptions: [
                    {
                        name: 'Age',
                        value: 0
                    },
                    {
                        name: 'Gender',
                        value: 1
                    }
                ]
            }
        },

        created() {
            this.ratioType = 0;
        },

        watch: {
            ratioType(value) {
                this.loadRatio(value);
            }
        },

        methods: {
            loadChartData(filters) {
                this.statisticLoading = true;
                Promise.resolve(this.getVisits(filters))
                    .then(() => {
                        return this.getPayments(filters);
                    })
                    .then(() => {
                        return this.getServicesCost(filters);
                    })
                    .finally(() => {
                        this.statisticLoading = false;
                    });
            },
            getVisits(filters) {
                return new Promise((resolve, reject) => {
                    this.$store.dispatch("statistics/loadChartData", {
                        chartType: 'visits', params: filters
                    }).then(() => {
                        let chartData = this.$store.state.statistics.chartsData['visits'];

                        if (chartData) {
                            this.setChartData(chartData, 'Visits');
                        }

                        resolve();
                    });
                });
            },
            getPayments(filters) {
                return new Promise((resolve, reject) => {
                    this.$store.dispatch("statistics/loadChartData", {
                        chartType: 'payments', params: filters
                    }).then(() => {
                        let chartData = this.$store.state.statistics.chartsData['payments'];

                        if (chartData) {
                            this.setChartData(chartData, 'Payments');
                        }

                        resolve();
                    });
                });
            },
            getServicesCost(filters) {
                return new Promise((resolve, reject) => {
                    this.$store.dispatch("statistics/loadChartData", {
                        chartType: 'services-cost', params: filters
                    }).then(() => {
                        let chartData = this.$store.state.statistics.chartsData['services-cost'];

                        if (chartData) {
                            this.setChartData(chartData, 'Services Cost');
                        }

                        resolve();
                    });
                });
            },
            setChartData(data, label) {
                let labelName = label || 'Unknown';
                let index = this.statisticData.datasets.findIndex(set => set.label === labelName);

                if (index >= 0) {
                    let set = this.statisticData.datasets[index];
                    this.statisticData.datasets = this.statisticData.datasets.slice(0, index).concat([Object.assign({}, set, {
                        data: data ? Object.values(data) : [],
                    }), ...this.statisticData.datasets.slice(index + 1)]);
                } else {
                    let color = colors[labelName] || '#877D87';
                    let dataq = {
                        label: labelName,
                        backgroundColor: color,
                        borderColor: color,
                        data: data ? Object.values(data) : [],
                        fill: false,
                        spanGaps: true,
                    };
                    this.statisticData.datasets = [...this.statisticData.datasets, dataq]
                }
                this.statisticData.labels = Object.keys(data);
            },
            loadRatio(type) {
                this.ratioLoading = true;
                this.$store.dispatch("statistics/loadRatio", {
                    params: { type }
                }).then(() => {
                    let chartData = this.$store.state.statistics.ratio;
                    
                    if (chartData) {
                        this.setRatio(chartData);
                    }
                })
                .finally(() => {
                    this.ratioLoading = false;
                });
            },
            setRatio(data) {
                const colors = ['#0aadd0', '#e43e6e', '#01c498', '#ffbe07', '#4e40de', '#7f2084'];
                let labels = [];
                let datasets = [
                    {
                        backgroundColor: [],
                        data: []
                    }
                ];

                Object.keys(data).forEach((key, index) => {
                    labels.push(key);
                    datasets[0].backgroundColor.push(colors[index]);
                    datasets[0].data.push(data[key]);
                });

                this.ratioData = Object.assign({}, this.ratioData, {
                    labels,
                    datasets
                });
            }
        }
    }
</script>

<style scoped>
    .chart-progress {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 400px;
    }
    .ratio-type {
        max-width: 235px;
    }
</style>
