<template>
  <v-row>
    <v-col cols="12" sm="6">
      <v-menu
        v-model="periodMenu"
        :close-on-content-click="false"
        transition="scale-transition"
        offset-y
        min-width="280px"
        :nudge-bottom="10"
      >
        <template v-slot:activator="{ on }">
          <v-text-field
            v-on="on"
            v-model="periodText"
            append-icon="event"
            dense
            outlined
            readonly
            hide-details
            placeholder="Период"
          ></v-text-field>
        </template>
        <v-date-picker range scrollable v-model="datePeriod">
          <v-spacer></v-spacer>
          <v-btn text color="primary" @click="periodMenu = false">Cancel</v-btn>
          <v-btn text color="primary" @click="savePeriod">OK</v-btn>
        </v-date-picker>
      </v-menu>
    </v-col>
    <v-col cols="12" sm="6">
      <v-select
        dense
        outlined
        v-model="filters.periodType"
        :items="periodTypeOptions"
        hide-details
        item-text="name"
        item-value="value"
        placeholder="Тип периода"
        :menu-props="{ offsetY: true, nudgeBottom: 10 }"
      >
        <template v-slot:item="{ item }">
          <span class="subtitle-1">{{ item.name }}</span>
        </template>
      </v-select>
    </v-col>
  </v-row>
</template>

<script>
import moment from "moment";

export default {
  data() {
    return {
      periodMenu: false,
      periodTypeOptions: [
        {
          name: "День",
          value: 0
        },
        {
          name: "Неделя",
          value: 1
        },
        {
          name: "Месяц",
          value: 2
        },
      ],
      datePeriod: [],
      isPeriodSaved: false,
      filters: {
        period: [],
        periodType: null
      }
    };
  },

  created() {
    this.datePeriod = this.filters.period = [
      moment()
        .startOf("month")
        .format("YYYY-MM-DD"),
      moment()
        .endOf("month")
        .format("YYYY-MM-DD")
    ];
    this.filters.periodType = 0;
  },

  methods: {
    getFilters() {
      let date = this.filters.period;

      if (!this.filters.period[0] || !this.filters.period[1]) {
        date = [
          moment()
            .startOf("month")
            .format("YYYY-MM-DD"),
          moment()
            .endOf("month")
            .format("YYYY-MM-DD")
        ];
      }

      return {
        start_date: date[0],
        end_date: date[1],
        period: this.filters.periodType >= 0
          ? this.filters.periodType
          : 0,
      };
    },
    savePeriod() {
      if (this.datePeriod.length < 2) {
        this.periodMenu = false;
        return;
      }

      if (moment(this.datePeriod[0]).isAfter(this.datePeriod[1])) {
        this.filters.period = this.datePeriod.reverse();
      } else {
        this.filters.period = this.datePeriod;
      }

      this.isPeriodSaved = true;
      this.periodMenu = false;
    }
  },

  computed: {
    periodText() {
      return this.filters.period.join(" ~ ");
    }
  },

  watch: {
    periodMenu(val) {
      if (!val) {
        if (!this.isPeriodSaved) {
          this.datePeriod = this.filters.period;
        } else {
          this.isPeriodSaved = false;
        }
      }
    },
    filters: {
      handler() {
        this.$emit("filters-changed", this.getFilters());
      },
      deep: true
    }
  }
};
</script>