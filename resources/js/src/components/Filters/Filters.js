import React, { useState, useMemo } from "react";
import PropTypes from "prop-types";

import "./Filters.scss";

import { DateFiltering, StringFiltering, NumberFiltering } from "./Filtering.js";

const Filters = props => {
    const [filteringKey, setFilteringKey] = useState("flight_date");

    const handleSelect = ({ target }) => {
        const option = Array.from(target.children).find(option => option.selected === true);
        const key = option.getAttribute("value");
        setFilteringKey(key);
        const method = option.getAttribute("method");
        props.handleFilterSelect(key, method);
    };

    const handleResetAllFilters = () => {
        props.resetAllFilters();
    };

    const isNotInitValue = filter => {
        const { init, startValue, endValue } = filter;
        return init.startValue.toString() !== startValue.toString() || init.endValue.toString() !== endValue.toString();
    };

    const hasFilter = key => Object.keys(props.filters).some(k => k === key && props.filters[k] && isNotInitValue(props.filters[k]));

    const getFilter = key => props.filters[Object.keys(props.filters).find(k => k === key)];
    const filter = getFilter(filteringKey);

    const filtersMemo = useMemo(
        () => (
            <FilteringMethod
                method={props.method}
                filteringKey={filteringKey}
                handleFilterReset={props.handleFilterReset}
                handleFilterValue={props.handleFilterValue}
                filter={filter}
                reset={props.reset}
            />
        ),
        // eslint-disable-next-line react-hooks/exhaustive-deps
        [props.method, filteringKey, props.reset]
    );

    return (
        <>
            <div className="filtering">
                <div className="filtering__wrapper">
                    <span style={{ marginRight: 5 }}>Фильтрация по</span>
                    <select onChange={e => handleSelect(e)} className="filtering__select">
                        <option className={`${hasFilter("flight_date") ? "active" : "disabled"}`} defaultValue value="flight_date" method="date">
                            Датам
                        </option>
                        <option className={`${hasFilter("flight_id") ? "active" : "disabled"}`} value="flight_id" method="number">
                            Номеру полёта
                        </option>
                        <option className={`${hasFilter("plan_code") ? "active" : "disabled"}`} value="plan_code" method="string">
                            Коду план
                        </option>
                        <option className={`${hasFilter("plan_qty") ? "active" : "disabled"}`} value="plan_qty" method="number">
                            Количество план
                        </option>
                        <option className={`${hasFilter("plan_price") ? "active" : "disabled"}`} value="plan_price" method="number">
                            Цене план
                        </option>
                        <option className={`${hasFilter("fact_code") ? "active" : "disabled"}`} value="fact_code" method="string">
                            Коду факт
                        </option>
                        <option className={`${hasFilter("fact_qty") ? "active" : "disabled"}`} value="fact_qty" method="number">
                            Количеству факт
                        </option>
                        <option className={`${hasFilter("fact_price") ? "active" : "disabled"}`} value="fact_price" method="number">
                            Цене факт
                        </option>
                        <option className={`${hasFilter("delta") ? "active" : "disabled"}`} value="delta" method="number">
                            Дельте
                        </option>
                    </select>
                </div>
                <button onClick={handleResetAllFilters}>Обнулить все фильтры</button>
            </div>
            {filtersMemo}
        </>
    );
};

export default Filters;

Filters.propTypes = {
    method: PropTypes.oneOfType([PropTypes.string, PropTypes.bool]).isRequired,
    filters: PropTypes.object.isRequired,
    handleFilterSelect: PropTypes.func.isRequired,
    handleFilterValue: PropTypes.func.isRequired,
    handleFilterReset: PropTypes.func.isRequired,
    resetAllFilters: PropTypes.func.isRequired,
    reset: PropTypes.bool
};

const FilteringMethod = ({ filter, reset, method, filteringKey, handleFilterValue, handleFilterReset }) => {
    if (method === "date") {
        return (
            <DateFiltering
                filter={filter}
                method={method}
                filteringKey={filteringKey}
                handleFilterReset={handleFilterReset}
                handleFilterValue={handleFilterValue}
                reset={reset}
            />
        );
    }
    if (method === "string") {
        return (
            <StringFiltering
                filter={filter}
                method={method}
                filteringKey={filteringKey}
                handleFilterReset={handleFilterReset}
                handleFilterValue={handleFilterValue}
                reset={reset}
            />
        );
    }
    if (method === "number") {
        return (
            <NumberFiltering
                filter={filter}
                method={method}
                filteringKey={filteringKey}
                handleFilterReset={handleFilterReset}
                handleFilterValue={handleFilterValue}
                reset={reset}
            />
        );
    }
};
