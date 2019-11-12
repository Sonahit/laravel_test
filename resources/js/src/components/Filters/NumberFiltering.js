"use strict";
import React, { useState, useEffect } from "react";
import PropTypes from "prop-types";

const NumberFiltering = props => {
    const { filteringKey, method, filter, reset } = props;
    let startValue, endValue;
    if (filter) {
        startValue = filter.startValue;
        endValue = filter.endValue;
    }
    const [startNumber, setStartNumber] = useState(!isNaN(startValue) ? startValue : Number.MIN_SAFE_INTEGER);
    const [endNumber, setEndNumber] = useState(!isNaN(endValue) ? endValue : Number.MAX_SAFE_INTEGER);
    if (reset && startNumber !== Number.MIN_SAFE_INTEGER) {
        setStartNumber(Number.MIN_SAFE_INTEGER);
    }
    if (reset && endNumber !== Number.MAX_SAFE_INTEGER) {
        setEndNumber(Number.MAX_SAFE_INTEGER);
    }
    useEffect(() => {
        if (isNaN(startNumber)) {
            props.handleFilterReset(filteringKey);
        } else {
            props.handleFilterValue(filteringKey, method, startNumber, endNumber, Number.MIN_SAFE_INTEGER, Number.MAX_SAFE_INTEGER);
        }
    });
    return (
        <div className="flex_wrapper">
            <div className="input_wrapper">
                <div className="input_wrapper-container">
                    <input
                        placeholder="От"
                        style={{ margin: "0 3px" }}
                        onChange={({ target }) => setStartNumber(isNaN(parseInt(target.value)) ? Number.MIN_SAFE_INTEGER : parseInt(target.value))}
                        value={startNumber === Number.MIN_SAFE_INTEGER ? "" : startNumber}
                    />
                </div>
            </div>
            <div className="input_wrapper">
                <div className="input_wrapper-container">
                    <input
                        placeholder="До"
                        style={{ margin: "0 3px" }}
                        onChange={({ target }) => setEndNumber(isNaN(parseInt(target.value)) ? Number.MAX_SAFE_INTEGER : parseInt(target.value))}
                        value={endNumber === Number.MAX_SAFE_INTEGER ? "" : endNumber}
                    />
                </div>
            </div>
        </div>
    );
};

export default NumberFiltering;

NumberFiltering.propTypes = {
    method: PropTypes.string.isRequired,
    filter: PropTypes.oneOfType([PropTypes.object, PropTypes.bool]),
    filteringKey: PropTypes.string.isRequired,
    handleFilterValue: PropTypes.func.isRequired,
    handleFilterReset: PropTypes.func.isRequired,
    reset: PropTypes.bool
};
