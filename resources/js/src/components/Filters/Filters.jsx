import React, { useState, useMemo } from 'react';
import PropTypes from 'prop-types';

import './Filters.scss';

import { DateFiltering, StringFiltering, NumberFiltering } from './Filtering';

const Filters = props => {
  const [filteringKey, setFilteringKey] = useState('flight_date');
  const options = [
    {
      value: 'flight_date',
      defaultValue: true,
      method: 'date',
      text: 'Датам'
    },
    {
      value: 'flight_id',
      defaultValue: false,
      method: 'number',
      text: 'Номеру полёта'
    },
    {
      value: 'plan_code',
      defaultValue: false,
      method: 'string',
      text: 'Коду план'
    },
    {
      value: 'fact_code',
      defaultValue: false,
      method: 'string',
      text: 'Коду факт'
    },
    {
      value: 'plan_qty',
      defaultValue: false,
      method: 'number',
      text: 'Количеству план'
    },
    {
      value: 'fact_qty',
      defaultValue: false,
      method: 'number',
      text: 'Количеству факт'
    },
    {
      value: 'plan_price',
      defaultValue: false,
      method: 'number',
      text: 'Цене план'
    },
    {
      value: 'fact_price',
      defaultValue: false,
      method: 'number',
      text: 'Цене факт'
    },
    {
      value: 'delta',
      defaultValue: false,
      method: 'number',
      text: 'Дельте'
    }
  ];
  const handleSelect = ({ target }) => {
    const option = Array.from(target.children).find(option => option.selected === true);
    const key = option.getAttribute('value');
    setFilteringKey(key);
    const method = option.getAttribute('method');
    props.handleFilterSelect(key, method);
  };

  const handleResetAllFilters = () => {
    props.resetAllFilters();
  };

  const isNotInitValue = filter => {
    const { init, startValue, endValue } = filter;
    return init.startValue !== startValue || init.endValue !== endValue;
  };

  const hasFilter = key =>
    Object.keys(props.filters).some(
      k => k === key && props.filters[k] && isNotInitValue(props.filters[k])
    );

  const getFilter = key => props.filters[Object.keys(props.filters).find(k => k === key)];
  const filter = getFilter(filteringKey);
  const { startValue = '', endValue = '' } = filter || {};
  const { method, reset } = props;
  const filtersMemo = useMemo(
    () => (
      <FilteringMethod
        method={props.method}
        filteringKey={filteringKey}
        handleFilterReset={props.handleFilterReset}
        handleFilterValue={props.handleFilterValue}
        startValue={startValue}
        endValue={endValue}
        reset={props.reset}
      />
    ),
    // eslint-disable-next-line react-hooks/exhaustive-deps
    [method, filteringKey, reset, startValue, endValue]
  );

  return (
    <>
      <div className="filtering">
        <div className="filtering__wrapper">
          <span style={{ marginRight: 5 }}>Фильтрация по</span>
          <select
            role="filters"
            onChange={e => handleSelect(e)}
            className="filtering__select"
            selected={options[0].value}
          >
            {options.map(option => (
              <option
                key={`${option.value}_${option.method}`}
                className={`${hasFilter(option.value) ? 'active' : 'disabled'}`}
                value={option.value}
                method={option.method}
              >
                {option.text}
              </option>
            ))}
          </select>
        </div>
        <button type="button" className="filtering__button" onClick={handleResetAllFilters}>
          Обнулить все фильтры
        </button>
      </div>
      {filtersMemo}
    </>
  );
};

export default Filters;

Filters.defaultProps = {
  reset: false
};

Filters.propTypes = {
  method: PropTypes.oneOfType([PropTypes.string, PropTypes.bool]).isRequired,
  filters: PropTypes.object.isRequired,
  handleFilterSelect: PropTypes.func.isRequired,
  handleFilterValue: PropTypes.func.isRequired,
  handleFilterReset: PropTypes.func.isRequired,
  resetAllFilters: PropTypes.func.isRequired,
  reset: PropTypes.bool
};

const FilteringMethod = ({
  startValue,
  endValue,
  reset,
  method,
  filteringKey,
  handleFilterValue,
  handleFilterReset
}) => {
  if (method === 'date') {
    return (
      <DateFiltering
        startValue={startValue}
        endValue={endValue}
        method={method}
        filteringKey={filteringKey}
        handleFilterReset={handleFilterReset}
        handleFilterValue={handleFilterValue}
        reset={reset}
      />
    );
  }
  if (method === 'string') {
    return (
      <StringFiltering
        startValue={startValue}
        endValue={endValue}
        method={method}
        filteringKey={filteringKey}
        handleFilterReset={handleFilterReset}
        handleFilterValue={handleFilterValue}
        reset={reset}
      />
    );
  }
  if (method === 'number') {
    return (
      <NumberFiltering
        startValue={startValue}
        endValue={endValue}
        method={method}
        filteringKey={filteringKey}
        handleFilterReset={handleFilterReset}
        handleFilterValue={handleFilterValue}
        reset={reset}
      />
    );
  }
  return <></>;
};

FilteringMethod.propTypes = {
  startValue: PropTypes.any.isRequired,
  endValue: PropTypes.any.isRequired,
  reset: PropTypes.bool.isRequired,
  method: PropTypes.string.isRequired,
  filteringKey: PropTypes.string.isRequired,
  handleFilterValue: PropTypes.func.isRequired,
  handleFilterReset: PropTypes.func.isRequired
};
