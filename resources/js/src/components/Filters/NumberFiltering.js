import React, { useState, useEffect } from "react";
import PropTypes from "prop-types";

const NumberFiltering = props => {
    const [startValue, setStartValue] = useState(Number.MIN_SAFE_INTEGER);
    const [endValue, setEndValue] = useState(Number.MAX_SAFE_INTEGER);
    useEffect(() => {
        if (isNaN(startValue)) {
            props.handleFilterReset(props.method, props.filteringKey);
        } else {
            props.handleFilterValue(startValue, endValue);
        }
    });
    return (
        <div className="flex_wrapper">
            <div className="input_wrapper">
                <div className="input_wrapper-container">
                    <input
                        placeholder="От"
                        style={{ margin: "0 3px" }}
                        onChange={({ target }) => setStartValue(isNaN(parseInt(target.value)) ? Number.MIN_SAFE_INTEGER : parseInt(target.value))}
                    />
                </div>
            </div>
            <div className="input_wrapper">
                <div className="input_wrapper-container">
                    <input
                        placeholder="До"
                        style={{ margin: "0 3px" }}
                        onChange={({ target }) => setEndValue(isNaN(parseInt(target.value)) ? Number.MAX_SAFE_INTEGER : parseInt(target.value))}
                    />
                </div>
            </div>
        </div>
    );
};

export default NumberFiltering;

NumberFiltering.propTypes = {
    method: PropTypes.string.isRequired,
    filteringKey: PropTypes.string.isRequired,
    handleFilterValue: PropTypes.func.isRequired,
    handleFilterReset: PropTypes.func.isRequired
};
