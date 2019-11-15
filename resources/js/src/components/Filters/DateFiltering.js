"use strict";
import React, { useState, useEffect } from "react";
import DatePicker from "react-datepicker";
import PropTypes from "prop-types";

import "react-datepicker/dist/react-datepicker.css";

let initEndDate;
const DateFiltering = props => {
    const { filteringKey, method, startValue, endValue, reset } = props;
    const [startDate, setStartDate] = useState(new Date("2017/01/01"));
    const [endDate, setEndDate] = useState(new Date(new Date().setHours(0, 0, 0)));
    const [filter, setFilter] = useState(filteringKey);
    if (filter !== filteringKey) {
        setFilter(filteringKey);
        setStartDate(startValue);
        setEndDate(endValue);
    }
    if (!initEndDate) initEndDate = endDate;
    if (reset) {
        if (startDate.toString() !== new Date("2017/01/01").toString()) {
            setStartDate(new Date("2017/01/01"));
        }
        if (endDate.toString() !== new Date(new Date().setHours(0, 0, 0)).toString()) {
            setEndDate(new Date(new Date().setHours(0, 0, 0)));
        }
    }
    useEffect(() => {
        if (!startDate) {
            props.handleFilterReset(filteringKey);
        } else {
            props.handleFilterValue(filteringKey, method, startDate, endDate || initEndDate, new Date("2017/01/01"), initEndDate);
        }
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [filteringKey, startDate, endDate]);
    const dateFormat = "yyyy-MM-dd";
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

DateFiltering.propTypes = {
    method: PropTypes.string.isRequired,
    startValue: PropTypes.any,
    endValue: PropTypes.any,
    filteringKey: PropTypes.string.isRequired,
    handleFilterValue: PropTypes.func.isRequired,
    handleFilterReset: PropTypes.func.isRequired,
    reset: PropTypes.bool
};
