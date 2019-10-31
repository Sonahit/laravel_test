import React, { Component } from "react";
import DatePicker from "react-datepicker";
import ReactDOM from "react-dom";

import "react-datepicker/dist/react-datepicker.css";

export class DateTable extends Component {
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
        window.dispatchEvent(
            new CustomEvent("filter_table__date", {
                detail: {
                    startDate: this.state.startDate,
                    endDate: this.state.endDate
                }
            })
        );
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
                <label className="options__information">Фильтрация по датам</label>
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

const el = document.getElementsByClassName("options__datepicker")[0];
if (el) {
    ReactDOM.render(<DateTable />, el);
}
