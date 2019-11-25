import React, { Component } from 'react';
import PropTypes from 'prop-types';

import './Table.scss';

import TableOptions from '@components/TableOptions/TableOptions';
import Modal from '@components/Modal/Modal';

import TableBody from './TableBody';
import TableHead from './TableHead';
import tHead from './headRows';

function toAsc(node) {
  node.classList.remove('desc');
  node.classList.add('asc');
}

function toDesc(node) {
  node.classList.remove('asc');
  node.classList.add('desc');
}

function active(node) {
  const prevActive = Array.from(document.getElementsByClassName('active'));
  if (prevActive) {
    prevActive.forEach(prev => prev.classList.remove('active'));
  }
  node.classList.toggle('active');
}

function changeArrowDirection(e) {
  const node = e.target;
  const parent = node.parentNode;
  let asc = false;
  if (parent.classList.contains('main-table__th--sortable')) {
    if (node.classList.contains('asc')) {
      if (node.classList.contains('active')) {
        asc = false;
        toDesc(node);
      } else {
        asc = true;
        toAsc(node);
      }
    } else if (
      node.classList.contains('desc') &&
      parent.classList.contains('main-table__th--sortable')
    ) {
      if (node.classList.contains('active')) {
        asc = true;
        toAsc(node);
      } else {
        asc = false;
        toDesc(node);
      }
    }
    active(node);
  } else if (node.classList.contains('main-table__th--sortable')) {
    const child = node.children[0];
    if (child.classList.contains('desc')) {
      if (child.classList.contains('active')) {
        asc = true;
        toAsc(child);
      } else {
        asc = false;
        toDesc(child);
      }
    } else if (child.classList.contains('asc')) {
      if (child.classList.contains('active')) {
        asc = false;
        toDesc(child);
      } else {
        asc = true;
        toAsc(child);
      }
    }
    active(child);
  }
  return asc;
}

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
      filters: {},
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
      quickFilter: {
        startValue: false,
        init: {
          startValue: false
        }
      }
      /*
        startValue: false
      */
    };
    this.handleReset = this.handleReset.bind(this);
    this.handleSort = this.handleSort.bind(this);
    this.handleFilterReset = this.handleFilterReset.bind(this);
    this.handleFilterSelect = this.handleFilterSelect.bind(this);
    this.handleFilterValue = this.handleFilterValue.bind(this);
    this.handleQuickFiltering = this.handleQuickFiltering.bind(this);
    this.resetAllFilters = this.resetAllFilters.bind(this);
  }

  handleReset(reset) {
    this.setState({ reset });
  }

  handleSort(e, method, dataSort) {
    if (!method || !dataSort) return;
    this.setState({
      sort: { method, dataSort, asc: changeArrowDirection(e) }
    });
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

  handleQuickFiltering(startValue) {
    this.setState(prev => ({ quickFilter: { ...prev.quickFilter, startValue } }));
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
      rememberTable
    } = this.props;
    const { reset, methodFilterSelect, filters, sort, quickFilter } = this.state;
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
          quickFilter={quickFilter}
          handleFilterValue={this.handleFilterValue}
          handleFilterReset={this.handleFilterReset}
          handleFilterSelect={this.handleFilterSelect}
          handleImportCSV={handleImportCSV}
          handleQuickFiltering={this.handleQuickFiltering}
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
        {Array.isArray(table) && table.length > 0 ? (
          <table className="main-table">
            <TableHead tHead={tHead} handleSort={this.handleSort} />
            <TableBody sort={sort} filters={filters} quickFilter={quickFilter} table={table} />
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
  setFetch: PropTypes.func.isRequired,
  rememberTable: PropTypes.func.isRequired,
  forgetTable: PropTypes.func.isRequired,
  external: PropTypes.bool,
  error: PropTypes.any,
  isUpdating: PropTypes.bool
};
