import React, { Component } from "react";
import PropTypes from "prop-types";
import { dispatchCustomEvent } from "@helpers/EventHelper.js";
import TableHelper from "../../helpers/TableHelper";

export default class NumberFiltering extends Component {
    constructor(props) {
        super(props);
        this.state = {
            startValue: Number.MIN_SAFE_INTEGER,
            endValue: Number.MAX_SAFE_INTEGER
        };
        this.handleStartValue = this.handleStartValue.bind(this);
        this.handleEndValue = this.handleEndValue.bind(this);
    }

    componentDidUpdate() {
        if (!this.state.startValue) {
            TableHelper.prototype.showTable();
        } else {
            dispatchCustomEvent(`filter_table__${this.props.method}`, {
                startValue: this.state.startValue,
                endValue: this.state.endValue || Number.MAX_SAFE_INTEGER,
                key: this.props.filteringKey
            });
        }
    }

    componentWillUnmount() {
        TableHelper.prototype.showTable();
    }

    handleStartValue(e) {
        const input = e.target;
        this.setState({ startValue: input.value });
    }

    handleEndValue(e) {
        const input = e.target;
        this.setState({ endValue: input.value });
    }

    render() {
        return (
            <div className="flex_wrapper">
                <input placeholder="От" style={{ margin: "0 3px" }} onChange={this.handleStartValue} />
                <input placeholder="До" style={{ margin: "0 3px" }} onChange={this.handleEndValue} />
            </div>
        );
    }
}

NumberFiltering.propTypes = {
    method: PropTypes.string.isRequired,
    filteringKey: PropTypes.string.isRequired
};
