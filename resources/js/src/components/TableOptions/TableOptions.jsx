import React, { useMemo } from 'react';
import PropTypes from 'prop-types';

import './TableOptions.scss';

import Filters from '@components/Filters/Filters';
import Buttons from './Buttons';

const TableOptions = props => {
  const {
    method,
    filters,
    handleFilterReset,
    handleFilterValue,
    handleFilterSelect,
    resetAllFilters,
    reset,
    setReset,
    quickFilter,
    handleQuickFiltering
  } = props;
  const filtersMemo = useMemo(
    () => (
      <Filters
        method={method}
        filters={filters}
        handleFilterSelect={handleFilterSelect}
        handleFilterReset={handleFilterReset}
        handleFilterValue={handleFilterValue}
        resetAllFilters={resetAllFilters}
        reset={reset}
        setReset={setReset}
        quickFilter={quickFilter}
        handleQuickFiltering={handleQuickFiltering}
      />
    ),
    // eslint-disable-next-line react-hooks/exhaustive-deps
    [method, filters, reset, quickFilter.startValue]
  );
  const {
    external,
    stopRenderImport,
    fetchAllData,
    rememberTable,
    forgetTable,
    refreshTable,
    error
  } = props;
  return (
    <section className="options">
      <section className="options__filtering">{filtersMemo}</section>
      <section className="options__files">
        <Buttons
          external={external}
          stopRenderImport={stopRenderImport}
          fetchAllData={fetchAllData}
          rememberTable={rememberTable}
          forgetTable={forgetTable}
          refreshTable={refreshTable}
          error={error}
        />
      </section>
    </section>
  );
};

export default TableOptions;

TableOptions.defaultProps = {
  external: false,
  reset: false,
  error: false
};

TableOptions.propTypes = {
  method: PropTypes.string.isRequired,
  filters: PropTypes.object.isRequired,
  handleFilterSelect: PropTypes.func.isRequired,
  handleFilterValue: PropTypes.func.isRequired,
  handleFilterReset: PropTypes.func.isRequired,
  stopRenderImport: PropTypes.func.isRequired,
  resetAllFilters: PropTypes.func.isRequired,
  fetchAllData: PropTypes.func.isRequired,
  rememberTable: PropTypes.func.isRequired,
  forgetTable: PropTypes.func.isRequired,
  refreshTable: PropTypes.func.isRequired,
  quickFilter: PropTypes.shape({
    startValue: PropTypes.any
  }).isRequired,
  handleQuickFiltering: PropTypes.func.isRequired,
  setReset: PropTypes.func.isRequired,
  external: PropTypes.bool,
  reset: PropTypes.bool,
  error: PropTypes.oneOfType([PropTypes.bool, PropTypes.string])
};
