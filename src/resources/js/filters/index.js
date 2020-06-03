import Vue from 'vue';

import {date, datetime} from "@/js/filters/date";

export default {
  init() {
    Vue.filter('date', date);
    Vue.filter('datetime', datetime);
  }
}
