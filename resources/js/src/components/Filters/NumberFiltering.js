"use strict";
import React, { useState, useEffect } from "react";
import PropTypes from "prop-types";

const NumberFiltering = props => {
    const { filteringKey, method, startValue, endValue, reset } = props;
    //TODO: fix filter //TOO MANY RERENDERS
    const [startNumber, setStartNumber] = useState(!isNaN(startValue) ? startValue : Number.MIN_SAFE_INTEGER);
    const [endNumber, setEndNumber] = useState(!isNaN(endValue) ? endValue : Number.MAX_SAFE_INTEGER);
    if (reset && startNumber !== Number.MIN_SAFE_INTEGER) {
        setStartNumber(Number.MIN_SAFE_INTEGER);
    }
    if (reset && endNumber !== Number.MAX_SAFE_INTEGER) {
        setEndNumber(Number.MAX_SAFE_INTEGER);
    }
    if(!reset){
        if(startNumber !== startValue && !isNaN(startValue) && startNumber !== '-' && startNumber !== "") setStartNumber(startValue);
        if(endNumber !== endValue && !isNaN(endValue) && endNumber !== '-' && endNumber !== "") setEndNumber(endValue);
    }
    
    useEffect(() => {
        if (isNaN(startNumber) && startNumber !== '-') {
            props.handleFilterReset(filteringKey);
        } else {
            const start = !isNaN(startNumber) && startNumber !== '-' && startNumber !== ""? startNumber : Number.MIN_SAFE_INTEGER;
            const end = !isNaN(endNumber) && endNumber !== '-' && endNumber !== "" ? endNumber : Number.MAX_SAFE_INTEGER;
            props.handleFilterValue(filteringKey, method, start, end, Number.MIN_SAFE_INTEGER, Number.MAX_SAFE_INTEGER);
        } 
    })
    const handleStartValue = ({target}) => {
        setStartNumber(isNaN(target.value) && target.value !== '-'? Number.MIN_SAFE_INTEGER : target.value)
        if (isNaN(target.value) && target.value !== '-') {
            props.handleFilterReset(filteringKey);
        } else {
            const start = target.value === '-' ? 0 : parseInt(target.value);
            const end = isNaN(endNumber) && endNumber !== '-' ? endNumber : Number.MAX_SAFE_INTEGER;
            props.handleFilterValue(filteringKey, method, start, end, Number.MIN_SAFE_INTEGER, Number.MAX_SAFE_INTEGER);
        }
    }

    const handleEndValue = ({target}) => {
        setEndNumber(isNaN(target.value) && target.value !== '-' ? Number.MAX_SAFE_INTEGER : target.value)
        if (isNaN(target.value) && target.value !== '-') {
            props.handleFilterReset(filteringKey);
        } else {
            const start = isNaN(startNumber) && startNumber !== '-' ? startNumber : Number.MIN_SAFE_INTEGER;
            const end = target.value === '-' ? 0 : parseInt(target.value);
            props.handleFilterValue(filteringKey, method, start, end, Number.MIN_SAFE_INTEGER, Number.MAX_SAFE_INTEGER);
        }
    }
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
