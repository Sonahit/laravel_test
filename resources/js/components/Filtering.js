import React, { Component } from "react";
import { DateFiltering, StringFiltering, NumberFiltering } from "./Filters/index.js";

import ReactDOM from "react-dom";

export default class Filtering extends Component {
    constructor(props) {
        super(props);
        this.state = {
            filtering: "flight_date",
            method: "date"
        };
        this.handleSelect = this.handleSelect.bind(this);
    }

    componentDidMount() {
        document.querySelector(".filtering__select").addEventListener("change", this.handleSelect);
    }

    componentWillUnmount() {
        document.querySelector(".filtering__select").removeEventListener("change", this.handleSelect);
    }

    handleSelect({ target }) {
        const option = Array.from(target.children).find(option => option.selected === true);
        this.setState({
            filtering: option.getAttribute("value"),
            method: option.getAttribute("method")
        });
    }

    render() {
        return (
            <>
                <div style={{ margin: 5 }}>
                    <span style={{ marginRight: 5 }}>Фильтрация по</span>
                    <select className="filtering__select">
                        <option defaultValue value="flight_date" method="date">
                            Датам
                        </option>
                        <option value="flight_id" method="number">
                            Номеру полёта
                        </option>
                        <option value="plan_code" method="string">
                            Коду план
                        </option>
                        <option value="plan_qty" method="number">
                            Количество план
                        </option>
                        <option value="plan_price" method="number">
                            Цене план
                        </option>
                        <option value="fact_code" method="string">
                            Коду факт
                        </option>
                        <option value="fact_qty" method="number">
                            Количеству факт
                        </option>
                        <option value="fact_price" method="number">
                            Цене факт
                        </option>
                        <option value="delta" method="number">
                            Дельте
                        </option>
                    </select>
                </div>
                <FilteringMethod method={this.state.method} filteringKey={this.state.filtering} />
            </>
        );
    }
}

const FilteringMethod = ({ method, filteringKey }) => {
    if (method === "date") {
        return <DateFiltering method={method} filteringKey={filteringKey} />;
    } else if (method === "string") {
        return <StringFiltering method={method} filteringKey={filteringKey} />;
    } else if (method === "number") {
        return <NumberFiltering method={method} filteringKey={filteringKey} />;
    }
};

const el = document.getElementsByClassName("options__filtering")[0];
if (el) {
    ReactDOM.render(<Filtering />, el);
}
