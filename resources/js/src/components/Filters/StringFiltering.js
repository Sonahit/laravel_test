import React, { useState, useEffect } from "react";
import PropTypes from "prop-types";

const StringFiltering = props => {
    const {filteringKey, method, filter} = props;
    let startValue;
    if(filter) startValue = filter.startValue
    const [string, setString] = useState(startValue || "");
    useEffect(() => {
        if (!string) {
            props.handleFilterReset(filteringKey);
        } else {
            props.handleFilterValue(filteringKey, method, string, "", "", "");
        }
    });
    return (
        <div className="input_container">
            <div className="input_wrapper">
                <div className="input_wrapper-container">
                    <input
                        style={{ width: "100%", margin: "0 3px" }}
                        placeholder="Введите текст"
                        onChange={({ target }) => setString(target.value)}
                        value={string !== "" ? string : ""}
                    />
                </div>
            </div>
        </div>
    );
};

export default StringFiltering;

StringFiltering.propTypes = {
    method: PropTypes.string.isRequired,
    filter: PropTypes.object,
    filteringKey: PropTypes.string.isRequired,
    handleFilterValue: PropTypes.func.isRequired,
    handleFilterReset: PropTypes.func.isRequired
};
