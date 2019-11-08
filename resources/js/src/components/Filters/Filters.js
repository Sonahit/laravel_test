import React from "react";
import PropTypes from "prop-types";

import { DateFiltering, StringFiltering, NumberFiltering } from "./Filtering.js";

const Filters = props => {
    const handleSelect = ({ target }) => {
        const option = Array.from(target.children).find(option => option.selected === true);
        props.handleFilterSelect(option.getAttribute("value"), option.getAttribute("method"));
    };
    return (
        <>
            <div style={{ margin: 5 }}>
                <span style={{ marginRight: 5 }}>Фильтрация по</span>
                <select onChange={e => handleSelect(e)} className="filtering__select">
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
            <FilteringMethod
                method={props.method || "date"}
                filteringKey={props.filtering || "flight_date"}
                handleFilterReset={props.handleFilterReset}
                handleFilterValue={props.handleFilterValue}
            />
        </>
    );
};

export default Filters;

Filters.propTypes = {
    method: PropTypes.oneOfType([PropTypes.string, PropTypes.bool]).isRequired,
    filtering: PropTypes.oneOfType([PropTypes.string, PropTypes.bool]).isRequired,
    handleFilterSelect: PropTypes.func.isRequired,
    handleFilterValue: PropTypes.func.isRequired,
    handleFilterReset: PropTypes.func.isRequired
};

const FilteringMethod = ({ method, filteringKey, handleFilterValue, handleFilterReset }) => {
    if (method === "date") {
        return (
            <DateFiltering method={method} filteringKey={filteringKey} handleFilterReset={handleFilterReset} handleFilterValue={handleFilterValue} />
        );
    }
    if (method === "string") {
        return (
            <StringFiltering
                method={method}
                filteringKey={filteringKey}
                handleFilterReset={handleFilterReset}
                handleFilterValue={handleFilterValue}
            />
        );
    }
    if (method === "number") {
        return (
            <NumberFiltering
                method={method}
                filteringKey={filteringKey}
                handleFilterReset={handleFilterReset}
                handleFilterValue={handleFilterValue}
            />
        );
    }
};
