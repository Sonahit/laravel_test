import React, { Component } from "react";
import PropTypes from "prop-types";

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
            this.props.handleFilterReset(this.props.method, this.props.filteringKey);
        } else {
            this.props.handleFilterValue(this.state.string, "");
        }
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
    filteringKey: PropTypes.string.isRequired,
    handleFilterValue: PropTypes.func.isRequired,
    handleFilterReset: PropTypes.func.isRequired
};
