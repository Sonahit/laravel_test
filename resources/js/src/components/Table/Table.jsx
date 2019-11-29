import React, { Component } from 'react';
import PropTypes from 'prop-types';

import './Table.scss';

import TableOptions from '@components/TableOptions/TableOptions';
import Modal from '@components/Modal/Modal';

import TableBody from './TableBody';
import TableHead from './TableHead';
import tHead from './headRows';

export default class Table extends Component {
  constructor(props) {
    super(props);
    this.state = {
      sort: {
        method: false,
        dataSort: false
      },
      methodFilterSelect: 'date',
      reset: false,
      filters: {}
      /*
        Example filter
        filters: {
            [key]: {
                method: 'date'
                startValue: '0'
                endValue: '0',
                init: {
                    startValue: '0',
                    endValue: '0'
                }
            }
        }
      */
    };
    this.handleReset = this.handleReset.bind(this);
    this.handleFilterReset = this.handleFilterReset.bind(this);
    this.handleFilterSelect = this.handleFilterSelect.bind(this);
    this.handleFilterValue = this.handleFilterValue.bind(this);
    this.resetAllFilters = this.resetAllFilters.bind(this);
  }

  handleReset(reset) {
    this.setState({ reset });
  }

  handleFilterReset(key) {
    this.setState(prev => ({
      ...prev,
      filters: {
        ...prev.filters,
        [key]: false
      }
    }));
  }

  resetAllFilters() {
    this.setState(prev => ({
      ...prev,
      reset: true,
      filters: {},
      quickFilter: {
        startValue: false,
        init: {
          startValue: false
        }
      }
    }));
  }

  handleFilterSelect(_, method) {
    this.setState({
      reset: false,
      methodFilterSelect: method
    });
  }

  handleFilterValue(key, method, startValue, endValue, initStartValue, initEndValue) {
    this.setState(prev => ({
      ...prev,
      method,
      reset: false,
      filters: {
        ...prev.filters,
        [key]: {
          method: (prev.filters[key] && prev.filters[key].method) || method,
          startValue,
          endValue,
          init: {
            startValue: (prev.filters[key] && prev.filters[key].init.startValue) || startValue,
            endValue: (prev.filters[key] && prev.filters[key].init.endValue) || endValue
          }
        }
      }
    }));
    const { setFetch } = this.props;
    if (
      startValue.toString() === initStartValue.toString() &&
      endValue.toString() === initEndValue.toString()
    ) {
      setFetch(false);
    } else {
      setFetch(true);
    }
  }

  render() {
    const {
      error = false,
      handleRefresh,
      handleImportCSV,
      stopRenderImport,
      fetchAllData,
      isUpdating,
      table,
      external,
      forgetTable,
      rememberTable,
      handleQuickFiltering,
      handleSort,
      sortValue
    } = this.props;
    const { reset, methodFilterSelect, filters, sort } = this.state;
    if (error) {
      return (
        <Modal>
          <button type="button" onClick={handleRefresh}>
            Refresh
          </button>
        </Modal>
      );
    }
    return (
      <>
        <TableOptions
          method={methodFilterSelect}
          filters={filters}
          handleFilterValue={this.handleFilterValue}
          handleFilterReset={this.handleFilterReset}
          handleFilterSelect={this.handleFilterSelect}
          handleImportCSV={handleImportCSV}
          handleQuickFiltering={handleQuickFiltering}
          stopRenderImport={stopRenderImport}
          fetchAllData={fetchAllData}
          external={external}
          error={error}
          resetAllFilters={this.resetAllFilters}
          rememberTable={rememberTable}
          refreshTable={handleRefresh}
          forgetTable={forgetTable}
          reset={reset}
          setReset={this.handleReset}
        />
        {Array.isArray(table) ? (
          <table className="main-table">
            <TableHead tHead={tHead} handleSort={handleSort} sortValue={sortValue} />
            <TableBody sort={sort} filters={filters} table={table} />
          </table>
        ) : (
          <Modal>
            <div className="loader" />
          </Modal>
        )}
        {isUpdating && Array.isArray(table) && (
          <Modal relative>
            <div className="loader" />
          </Modal>
        )}
      </>
    );
  }
}

Table.defaultProps = {
  external: false,
  error: false,
  isUpdating: false
};

Table.propTypes = {
  table: PropTypes.oneOfType([PropTypes.bool, PropTypes.array]).isRequired,
  handleRefresh: PropTypes.func.isRequired,
  handleImportCSV: PropTypes.func.isRequired,
  stopRenderImport: PropTypes.func.isRequired,
  fetchAllData: PropTypes.func.isRequired,
  sortValue: PropTypes.oneOfType([PropTypes.bool, PropTypes.string]).isRequired,
  setFetch: PropTypes.func.isRequired,
  rememberTable: PropTypes.func.isRequired,
  forgetTable: PropTypes.func.isRequired,
  handleQuickFiltering: PropTypes.func.isRequired,
  handleSort: PropTypes.func.isRequired,
  external: PropTypes.bool,
  error: PropTypes.any,
  isUpdating: PropTypes.bool
};
