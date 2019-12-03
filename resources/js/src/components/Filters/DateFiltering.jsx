import React, { useState, useEffect } from 'react';
import DatePicker from 'react-datepicker';
import PropTypes from 'prop-types';

import './DateFiltering.css';

let initEndDate;
const DateFiltering = props => {
  const { filteringKey, method, startValue, endValue, reset, setReset } = props;
  const [startDate, setStartDate] = useState(new Date('2017/01/01'));
  const [endDate, setEndDate] = useState(new Date('2017/01/31'));
  const [filter, setFilter] = useState(filteringKey);
  if (filter !== filteringKey) {
    setFilter(filteringKey);
    setStartDate(startValue);
    setEndDate(endValue);
  }
  if (!initEndDate) initEndDate = endDate;
  if (reset) {
    if (startDate.toString() !== new Date('2017/01/01').toString()) {
      setStartDate(new Date('2017/01/01'));
    }
    if (endDate.toString() !== new Date(new Date().setHours(23, 59, 59)).toString()) {
      setEndDate(new Date(new Date().setHours(23, 59, 59)));
    }
  }
  useEffect(() => {
    if (reset) {
      setReset(false);
    }
    if (!startDate) {
      props.handleFilterReset(filteringKey);
    } else {
      props.handleFilterValue(
        filteringKey,
        method,
        new Date(new Date(startDate).setHours(23, 59, 59)),
        new Date(new Date(endDate).setHours(23, 59, 59)) || initEndDate,
        new Date('2017/01/01'),
        initEndDate
      );
    }
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [filteringKey, startDate, endDate]);
  const dateFormat = 'yyyy-MM-dd';
  return (
    <div className="options__datepicker__render">
      <DatePicker
        dateFormat={dateFormat}
        selected={startDate}
        onChange={date => setStartDate(date)}
        selectsStart
        endDate={endDate}
        placeholderText="С"
      />
      <DatePicker
        dateFormat={dateFormat}
        selected={endDate}
        onChange={date => setEndDate(date)}
        selectsEnd
        endDate={endDate}
        minDate={startDate}
        placeholderText="До"
      />
    </div>
  );
};

export default DateFiltering;

DateFiltering.defaultProps = {
  startValue: '',
  endValue: '',
  reset: false
};

DateFiltering.propTypes = {
  method: PropTypes.string.isRequired,
  startValue: PropTypes.any,
  endValue: PropTypes.any,
  filteringKey: PropTypes.string.isRequired,
  handleFilterValue: PropTypes.func.isRequired,
  handleFilterReset: PropTypes.func.isRequired,
  setReset: PropTypes.func.isRequired,
  reset: PropTypes.bool
};
