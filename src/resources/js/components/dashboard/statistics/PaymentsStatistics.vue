<template>
  <v-card class="statistic-card">
    <v-toolbar flat color="white" height="64">
      <payments-filters @filters-changed="(filters) => loadChartData(filters)" />
    </v-toolbar>
    <v-divider></v-divider>
    <v-card-text>
      <div class="statistic-card__chart-wrapper">
        <bar-chart :chart-data="statisticData" :options="statisticOptions" v-if="!isLoading" class="statistic-card__chart" />
        <div class="card-progress" v-else>
          <v-progress-circular :size="50" color="primary" indeterminate></v-progress-circular>
        </div>
      </div>
    </v-card-text>
  </v-card>
</template>

<script>
import PaymentsFilters from "./PaymentsFilters.vue";
import BarChart from "../charts/BarChart.vue";

const COLORS = {
  "Оплата услуг": "#aef849",
  "Стоимость услуг": "#f8a421"
};

export default {
  components: {
    PaymentsFilters,
    BarChart
  },

  data() {
    return {
      isLoading: false,
      statisticData: {
        labels: [],
        datasets: []
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
      }
    };
  },

  methods: {
    loadChartData(filters) {
      this.isLoading = true;
      this.getPayments(filters)
        .then(() => {
          return this.getServicesCost(filters);
        })
        .finally(() => {
          this.isLoading = false;
        });
    },
    getPayments(filters) {
      return new Promise((resolve, reject) => {
        this.$store
          .dispatch("statistics/loadChartData", {
            chartType: "payments",
            params: filters
          })
          .then(() => {
            let chartData = this.$store.state.statistics.chartsData["payments"];

            if (chartData) {
              this.setChartData(chartData, "Оплата услуг");
            }

            resolve();
          });
      });
    },
    getServicesCost(filters) {
      return new Promise((resolve, reject) => {
        this.$store
          .dispatch("statistics/loadChartData", {
            chartType: "services-cost",
            params: filters
          })
          .then(() => {
            let chartData = this.$store.state.statistics.chartsData[
              "services-cost"
            ];

            if (chartData) {
              this.setChartData(chartData, "Стоимость услуг");
            }

            resolve();
          });
      });
    },
    setChartData(data, label) {
      let labelName = label || "Unknown";
      let index = this.statisticData.datasets.findIndex(
        set => set.label === labelName
      );

      if (index >= 0) {
        let set = this.statisticData.datasets[index];
        this.statisticData.datasets = this.statisticData.datasets
          .slice(0, index)
          .concat([
            Object.assign({}, set, {
              data: data ? Object.values(data) : []
            }),
            ...this.statisticData.datasets.slice(index + 1)
          ]);
      } else {
        let color = COLORS[labelName] || "#877D87";
        let dataq = {
          label: labelName,
          backgroundColor: color,
          borderColor: color,
          data: data ? Object.values(data) : [],
          fill: false,
          spanGaps: true
        };
        this.statisticData.datasets = [...this.statisticData.datasets, dataq];
      }
      this.statisticData.labels = Object.keys(data);
    }
  }
};
</script>