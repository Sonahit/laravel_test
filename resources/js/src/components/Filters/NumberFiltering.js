"use strict";
import React, { useState, useEffect } from "react";
import PropTypes from "prop-types";

const NumberFiltering = props => {
    const { filteringKey, method, startValue, endValue, reset } = props;
    const [startNumber, setStartNumber] = useState(Number.MIN_SAFE_INTEGER);
    const [endNumber, setEndNumber] = useState(Number.MAX_SAFE_INTEGER);
    const [filter, setFilter] = useState(filteringKey);
    if (filter !== filteringKey) {
        setFilter(filteringKey);
        setStartNumber(isNaN(parseInt(startValue)) ? Number.MIN_SAFE_INTEGER : startValue);
        setEndNumber(isNaN(parseInt(endValue)) ? Number.MAX_SAFE_INTEGER : endValue);
    }
    if (reset) {
        if (startNumber !== Number.MIN_SAFE_INTEGER) {
            setStartNumber(Number.MIN_SAFE_INTEGER);
        }
        if (endNumber !== Number.MAX_SAFE_INTEGER) {
            setEndNumber(Number.MAX_SAFE_INTEGER);
        }
    }
    useEffect(() => {
        if (startNumber === "-" || endNumber === "-") return;

        if (isNaN(startNumber)) {
            props.handleFilterReset(filteringKey);
        } else if (startNumber >= endNumber) {
            const end = endNumber >= startNumber ? endNumber : Number.MAX_SAFE_INTEGER;
            props.handleFilterValue(filteringKey, method, startNumber, end, Number.MIN_SAFE_INTEGER, Number.MAX_SAFE_INTEGER);
        } else if (endNumber <= startNumber) {
            const start = startNumber <= endNumber ? startNumber : Number.MIN_SAFE_INTEGER;
            props.handleFilterValue(filteringKey, method, start, endNumber, Number.MIN_SAFE_INTEGER, Number.MAX_SAFE_INTEGER);
        } else {
            props.handleFilterValue(filteringKey, method, startNumber, endNumber, Number.MIN_SAFE_INTEGER, Number.MAX_SAFE_INTEGER);
        }
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [filteringKey, startNumber, endNumber]);

    const handleStartValue = ({ target }) => {
        if (target.value === "-") {
            setStartNumber(target.value);
        } else {
            const v = parseInt(target.value);
            setStartNumber(!isNaN(v) ? v : Number.MIN_SAFE_INTEGER);
        }
    };

    const handleEndValue = ({ target }) => {
        if (target.value === "-") {
            setEndNumber(target.value);
        } else {
            const v = parseInt(target.value);
            setEndNumber(!isNaN(v) ? v : Number.MAX_SAFE_INTEGER);
        }
    };
    return (
        <div className="flex_wrapper">
            <div className="input_wrapper">
                <div className="input_wrapper-container">
                    <input
                        placeholder="От"
                        style={{ margin: "0 3px" }}
                        onChange={handleStartValue}
                        value={startNumber === Number.MIN_SAFE_INTEGER ? "" : startNumber}
                    />
                </div>
            </div>
            <div className="input_wrapper">
                <div className="input_wrapper-container">
                    <input
                        placeholder="До"
                        style={{ margin: "0 3px" }}
                        onChange={handleEndValue}
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
    startValue: PropTypes.any,
    endValue: PropTypes.any,
    filteringKey: PropTypes.string.isRequired,
    handleFilterValue: PropTypes.func.isRequired,
    handleFilterReset: PropTypes.func.isRequired,
    reset: PropTypes.bool
};
