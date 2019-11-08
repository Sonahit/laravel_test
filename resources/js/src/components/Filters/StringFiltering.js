import React, { useState, useEffect } from "react";
import PropTypes from "prop-types";

const StringFiltering = props => {
    const [string, setString] = useState("");
    useEffect(() => {
        if (!string) {
            props.handleFilterReset(props.method, props.filteringKey);
        } else {
            props.handleFilterValue(string, "");
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
                    />
                </div>
            </div>
        </div>
    );
};

export default StringFiltering;

StringFiltering.propTypes = {
    method: PropTypes.string.isRequired,
    filteringKey: PropTypes.string.isRequired,
    handleFilterValue: PropTypes.func.isRequired,
    handleFilterReset: PropTypes.func.isRequired
};
