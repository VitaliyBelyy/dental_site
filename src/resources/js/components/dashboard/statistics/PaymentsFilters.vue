<template>
  <v-row>
    <v-col cols="12" sm="6" md="3">
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
    <v-col cols="12" sm="6" md="3">
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
    <v-col cols="12" sm="6" md="3">
      <v-autocomplete
        dense
        outlined
        :search-input.sync="userSearchQuery"
        v-model="filters.user"
        :loading="isLoadingUsers"
        :items="users"
        item-text="full_name"
        return-object
        hide-details
        placeholder="Врач"
        :menu-props="{ nudgeBottom: 10 }"
        clearable
        :disabled="filters.patient && filters.patient.id"
      >
        <template v-slot:progress>
          <v-progress-circular
            indeterminate
            color="primary"
            size="20"
            width="2"
            class="select-progress"
          ></v-progress-circular>
        </template>
      </v-autocomplete>
    </v-col>
    <v-col cols="12" sm="6" md="3">
      <v-autocomplete
        dense
        outlined
        :search-input.sync="patientSearchQuery"
        v-model="filters.patient"
        :loading="isLoadingPatients"
        :items="patients"
        item-text="full_name"
        return-object
        hide-details
        placeholder="Пациент"
        :menu-props="{ nudgeBottom: 10 }"
        clearable
        :disabled="filters.user && filters.user.id"
      >
        <template v-slot:progress>
          <v-progress-circular
            indeterminate
            color="primary"
            size="20"
            width="2"
            class="select-progress"
          ></v-progress-circular>
        </template>
      </v-autocomplete>
    </v-col>
  </v-row>
</template>

<script>
import moment from "moment";
import { Subject } from "rxjs";
import { debounceTime, distinctUntilChanged } from "rxjs/operators";

export default {
  data() {
    return {
      periodMenu: false,
      userSearchQuery: null,
      patientSearchQuery: null,
      usersSearchStream: new Subject(),
      patientsSearchStream: new Subject(),
      isLoadingUsers: false,
      isLoadingPatients: false,
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
        periodType: null,
        user: null,
        patient: null
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

    this.loadUsers();
    this.loadPatients();

    this.usersSearchStream
      .pipe(debounceTime(500), distinctUntilChanged())
      .subscribe(query => this.loadUsers(query));
    this.patientsSearchStream
      .pipe(debounceTime(500), distinctUntilChanged())
      .subscribe(query => this.loadPatients(query));
  },

  methods: {
    loadUsers(query = null) {
      let params = {
        q: query,
        page: 1,
        limit: 15
      };

      this.isLoadingUsers = true;
      this.$store.dispatch("statistics/loadUsers", { params }).finally(() => {
        this.isLoadingUsers = false;
      });
    },
    loadPatients(query = null) {
      let params = {
        q: query,
        page: 1,
        limit: 15
      };

      this.isLoadingPatients = true;
      this.$store
        .dispatch("statistics/loadPatients", { params })
        .finally(() => {
          this.isLoadingPatients = false;
        });
    },
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
        user_id:
          this.filters.user && this.filters.user.id
            ? this.filters.user.id
            : null,
        patient_id:
          this.filters.patient && this.filters.patient.id
            ? this.filters.patient.id
            : null
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
    users() {
      return this.$store.state.statistics.users;
    },
    patients() {
      return this.$store.state.statistics.patients;
    },
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
    userSearchQuery(query) {
      if (this.filters.user && query === this.filters.user.full_name) {
        return;
      }

      this.usersSearchStream.next(query);
    },
    patientSearchQuery(query) {
      if (this.filters.patient && query === this.filters.patient.full_name) {
        return;
      }

      this.patientsSearchStream.next(query);
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