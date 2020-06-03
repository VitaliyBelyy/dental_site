<template>
  <v-container fluid>
    <v-row class="mx-0 mb-8" justify="center">
      <v-col cols="12" md="6" class="py-0 mb-8 mb-md-0">
        <visits-statistics />
      </v-col>
      <v-col cols="12" md="6" class="py-0">
        <ratio-statistics />
      </v-col>
    </v-row>
    <v-row class="ma-0" justify="center">
      <v-col cols="12" class="py-0">
        <payments-statistics />
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
  import VisitsStatistics from "./statistics/VisitsStatistics.vue";
  import PaymentsStatistics from "./statistics/PaymentsStatistics.vue";
  import RatioStatistics from "./statistics/RatioStatistics.vue";

  export default {
    components: {
      VisitsStatistics,
      PaymentsStatistics,
      RatioStatistics
    }
  };
</script>
