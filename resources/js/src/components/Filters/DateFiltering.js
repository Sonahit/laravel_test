import React, { useState, useEffect } from "react";
import DatePicker from "react-datepicker";
import PropTypes from "prop-types";

import "react-datepicker/dist/react-datepicker.css";

const DateFiltering = props => {
    const [startDate, setStartDate] = useState(new Date("2017/01/01"));
    const [endDate, setEndDate] = useState(new Date());

    useEffect(() => {
        if (!startDate) {
            props.handleFilterReset(props.method, props.filteringKey);
        } else {
            props.handleFilterValue(startDate, endDate);
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
    filteringKey: PropTypes.string.isRequired,
    handleFilterValue: PropTypes.func.isRequired,
    handleFilterReset: PropTypes.func.isRequired
};
