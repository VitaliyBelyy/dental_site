<template>
  <v-card class="statistic-card">
    <v-toolbar flat color="white" height="64">
      <visits-filters @filters-changed="(filters) => loadChartData(filters)" />
    </v-toolbar>
    <v-divider></v-divider>
    <v-card-text>
      <div class="statistic-card__chart-wrapper">
        <line-chart :chart-data="statisticData" :options="statisticOptions" v-if="!isLoading" class="statistic-card__chart" />
        <div class="card-progress" v-else>
          <v-progress-circular :size="50" color="primary" indeterminate></v-progress-circular>
        </div>
      </div>
    </v-card-text>
  </v-card>
</template>

<script>
import VisitsFilters from "./VisitsFilters.vue";
import LineChart from "../charts/LineChart.vue";

const COLOR = "#dc62f8";

export default {
  components: {
    VisitsFilters,
    LineChart
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

      this.$store
        .dispatch("statistics/loadChartData", {
          chartType: "visits",
          params: filters
        })
        .then(() => {
          let chartData = this.$store.state.statistics.chartsData["visits"];

          if (chartData) {
            this.setChartData(chartData, "Количество посещений");
          }
        })
        .finally(() => {
          this.isLoading = false;
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
        let dataq = {
          label: labelName,
          backgroundColor: COLOR,
          borderColor: COLOR,
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