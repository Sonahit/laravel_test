import React, { useMemo } from 'react';
import PropTypes from 'prop-types';

import './TableOptions.scss';

import Filters from '@components/Filters/Filters';
import ExportOptions from './ExportOptions';

const TableOptions = props => {
  const {
    method,
    filters,
    handleFilterReset,
    handleFilterValue,
    handleFilterSelect,
    resetAllFilters,
    reset
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
      />
    ),
    // eslint-disable-next-line react-hooks/exhaustive-deps
    [method, filters, reset]
  );
  const {
    external,
    stopRenderImport,
    fetchAllData,
    rememberTable,
    forgetTable,
    refreshTable
  } = props;
  return (
    <section className="options">
      <section className="options__filtering">{filtersMemo}</section>
      <section className="options__files">
        <ExportOptions
          external={external}
          stopRenderImport={stopRenderImport}
          fetchAllData={fetchAllData}
          rememberTable={rememberTable}
          forgetTable={forgetTable}
          refreshTable={refreshTable}
        />
      </section>
    </section>
  );
};

export default TableOptions;

TableOptions.defaultProps = {
  external: false,
  reset: false
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
  external: PropTypes.bool,
  reset: PropTypes.bool
};
