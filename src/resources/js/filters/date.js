import moment from 'moment';

export function date(value) {
  if (!value) {
    return '';
  }

  return moment.utc(value).format('DD-MM-YYYY');
}

export function datetime(value) {
  if (!value) {
    return '';
  }

  return moment.utc(value).format('DD-MM-YYYY HH:mm');
}