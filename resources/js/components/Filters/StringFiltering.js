import React, { Component } from "react";
import PropTypes from "prop-types";
import { dispatchCustomEvent } from "@helpers/EventHelper.js";

export default class StringFiltering extends Component {
    constructor(props) {
        super(props);
        this.state = {
            value: false
        };
        this.handleChange = this.handleChange.bind(this);
    }

    componentDidUpdate() {
        if (!this.state.value) {
            dispatchCustomEvent(`filter_table__reset`);
        } else {
            dispatchCustomEvent(`filter_table__${this.props.method}`, {
                string: this.state.value,
                key: this.props.filteringKey
            });
        }
    }

    componentWillUnmount() {
        dispatchCustomEvent(`filter_table__reset`);
    }

    handleChange(e) {
        const input = e.target;
        this.setState({ value: input.value });
    }

    render() {
        return (
            <div className="input_container">
                <input style={{ width: "100%", margin: "0 3px" }} placeholder="Введите текст" onChange={this.handleChange} />
            </div>
        );
    }
}

StringFiltering.propTypes = {
    method: PropTypes.string.isRequired,
    filteringKey: PropTypes.string.isRequired
};
