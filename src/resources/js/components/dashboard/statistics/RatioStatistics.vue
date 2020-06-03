<template>
  <v-card class="statistic-card">
    <v-toolbar flat color="white" height="64">
      <v-select
        dense
        outlined
        v-model="type"
        :items="TypeSelectOptions"
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
      <div class="statistic-card__chart-wrapper">
        <doughnut-chart :chart-data="ratioData" :options="ratioOptions" v-if="!isLoading" class="statistic-card__chart" />
        <div class="card-progress" v-else>
          <v-progress-circular :size="50" color="primary" indeterminate></v-progress-circular>
        </div>
      </div>
    </v-card-text>
  </v-card>
</template>

<script>
import DoughnutChart from "../charts/DoughnutChart.vue";

const COLORS = [
  "#0aadd0",
  "#e43e6e",
  "#01c498",
  "#ffbe07",
  "#4e40de",
  "#7f2084"
];

export default {
  components: {
    DoughnutChart
  },

  data() {
    return {
      isLoading: false,
      ratioData: {
        labels: [],
        datasets: []
      },
      ratioOptions: {
        responsive: true,
        maintainAspectRatio: false
      },
      type: null,
      TypeSelectOptions: [
        {
          name: "Возраст",
          value: 0
        },
        {
          name: "Пол",
          value: 1
        }
      ]
    };
  },

  created() {
    this.type = 0;
  },

  watch: {
    type(value) {
      this.loadRatio(value);
    }
  },

  methods: {
    loadRatio(type) {
      this.isLoading = true;
      this.$store
        .dispatch("statistics/loadRatio", {
          params: { type }
        })
        .then(() => {
          let chartData = this.$store.state.statistics.ratio;

          if (chartData) {
            this.setRatio(chartData);
          }
        })
        .finally(() => {
          this.isLoading = false;
        });
    },
    setRatio(data) {
      let labels = [];
      let datasets = [
        {
          backgroundColor: [],
          data: []
        }
      ];

      Object.keys(data).forEach((key, index) => {
        labels.push(key);
        datasets[0].backgroundColor.push(COLORS[index]);
        datasets[0].data.push(data[key]);
      });

      this.ratioData = Object.assign({}, this.ratioData, {
        labels,
        datasets
      });
    }
  }
};
</script>