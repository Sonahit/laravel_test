import React, { useState, useEffect } from "react";
import DatePicker from "react-datepicker";
import PropTypes from "prop-types";

import "react-datepicker/dist/react-datepicker.css";

let initEndDate;
const DateFiltering = props => {
    const {filteringKey, method, filter} = props;
    let startValue, endValue;
    if(filter){ 
        startValue = filter.startValue;
        endValue = filter.endValue;
    }
    const [startDate, setStartDate] = useState(startValue || new Date("2017/01/01"));
    const [endDate, setEndDate] = useState(endValue || new Date());
    if(!initEndDate) initEndDate = endDate;
    useEffect(() => {
        if (!startDate) {
            props.handleFilterReset(filteringKey);
        } else {
            props.handleFilterValue(filteringKey, method, startDate, endDate || initEndDate, new Date("2017/01/01"), initEndDate);
        }
    });
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
    filter: PropTypes.any,
    filteringKey: PropTypes.string.isRequired,
    handleFilterValue: PropTypes.func.isRequired,
    handleFilterReset: PropTypes.func.isRequired
};
