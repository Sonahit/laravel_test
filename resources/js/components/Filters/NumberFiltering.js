import React, { Component } from "react";
import PropTypes from "prop-types";

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
            this.props.handleFilterReset(this.props.method, this.props.filteringKey);
        } else {
            this.props.handleFilterValue(this.state.startValue, this.state.endValue);
        }
    }

    handleStartValue(e) {
        const input = e.target;
        const value = parseInt(input.value);
        this.setState({ startValue: value });
    }

    handleEndValue(e) {
        const input = e.target;
        if (parseInt(input.value) === 0) {
            this.setState({ endValue: parseInt(input.value) });
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
    filteringKey: PropTypes.string.isRequired,
    handleFilterValue: PropTypes.func.isRequired,
    handleFilterReset: PropTypes.func.isRequired
};
