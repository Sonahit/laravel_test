import React, { Component } from "react";
import DatePicker from "react-datepicker";
import PropTypes from "prop-types";

import "react-datepicker/dist/react-datepicker.css";
import { dispatchCustomEvent } from "@helpers/EventHelper";

export default class DateFiltering extends Component {
    constructor(props) {
        super(props);
        this.state = {
            startDate: new Date("2017/01/01"),
            endDate: ""
        };
        this.setStartDate = this.setStartDate.bind(this);
        this.setEndDate = this.setEndDate.bind(this);
    }

    componentDidUpdate() {
        if (!this.state.startDate) {
            dispatchCustomEvent(`filter_table__reset`);
        } else {
            dispatchCustomEvent(`filter_table_${this.props.method}`, {
                startDate: this.state.startDate,
                endDate: this.state.endDate,
                key: this.props.filteringKey
            });
        }
    }

    componentWillUnmount() {
        dispatchCustomEvent(`filter_table__reset`);
    }

    setStartDate(startDate) {
        this.setState({
            startDate
        });
    }

    setEndDate(endDate) {
        this.setState({
            endDate
        });
    }

    render() {
        const dateFormat = "yyyy-MM-dd";
        return (
            <div className="options__datepicker__render">
                <DatePicker
                    dateFormat={dateFormat}
                    selected={this.state.startDate}
                    onChange={this.setStartDate}
                    selectsStart
                    endDate={this.state.endDate}
                    placeholderText="С"
                />
                <DatePicker
                    dateFormat={dateFormat}
                    selected={this.state.endDate}
                    onChange={this.setEndDate}
                    selectsEnd
                    endDate={this.state.endDate}
                    minDate={this.state.startDate}
                    placeholderText="До"
                />
            </div>
        );
    }
}

DateFiltering.propTypes = {
    method: PropTypes.string.isRequired,
    filteringKey: PropTypes.string.isRequired
};
