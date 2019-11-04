import React, { Component } from "react";
import PropTypes from "prop-types";
import { dispatchCustomEvent } from "@helpers/EventHelper.js";

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
            dispatchCustomEvent(`filter_table__reset`);
        } else {
            dispatchCustomEvent(`filter_table__${this.props.method}`, {
                startValue: this.state.startValue,
                endValue: this.state.endValue,
                key: this.props.filteringKey
            });
        }
    }

    componentWillUnmount() {
        dispatchCustomEvent(`filter_table__reset`);
    }

    handleStartValue(e) {
        const input = e.target;
        const value = parseInt(input.value);
        this.setState({ startValue: value });
    }

    handleEndValue(e) {
        const input = e.target;
        if(parseInt(input.value) === 0){
            this.setState({ endValue: parseInt(input.value)});
        }
        this.setState({ endValue: parseInt(input.value || Number.MAX_SAFE_INTEGER) });
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
