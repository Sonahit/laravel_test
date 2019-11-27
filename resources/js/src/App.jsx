/* eslint-disable import/no-unresolved */
import React, { Component } from 'react';
import { BrowserRouter as Router, Route } from 'react-router-dom';
import ReactDOM from 'react-dom';
import PropTypes from 'prop-types';

import './App.scss';
import Nav from '@components/Nav/Nav';
import ApiHelper from '@helpers/ApiHelper';
import TableHelper from '@helpers/TableHelper';
import ErrorHandler from '@handlers/ErrorHandler';
import routes from './routes';

const apiHelper = new ApiHelper();
// eslint-disable-next-line no-undef
const rootPath = new URL(public_path).pathname;
export default class App extends Component {
  constructor(props) {
    super(props);
    const { initTable } = this.props;
    this.state = {
      fetch_table: false || initTable,
      error: false,
      isUpdating: false,
      shouldUpdate: true,
      isFiltering: false,
      external: {
        table: false,
        render: false
      },
      typingTimeout: false
      /*
        startValue: false
      */
    };
    this.fetchTable = this.fetchTable.bind(this);
    this.fetchAllData = this.fetchAllData.bind(this);
    this.handleImportCSV = this.handleImportCSV.bind(this);
    this.stopRenderImport = this.stopRenderImport.bind(this);
    this.handleRefresh = this.handleRefresh.bind(this);
    this.handleScroll = this.handleScroll.bind(this);
    this.setFetch = this.setFetch.bind(this);
    this.rememberTable = this.rememberTable.bind(this);
    this.forgetTable = this.forgetTable.bind(this);
    this.handleQuickFiltering = this.handleQuickFiltering.bind(this);
  }

  componentDidMount() {
    const url = new URL(location.href);
    const page = parseInt(url.searchParams.get('page')) || 1;
    const paginate = parseInt(url.searchParams.get('paginate')) || 40;
    sessionStorage.setItem('paginate', paginate.toString());
    sessionStorage.setItem('page', page.toString());
    const table = JSON.parse(localStorage.getItem('table'));
    const searchParam =
      localStorage.getItem('searchParam') || sessionStorage.getItem('searchParam') || '';
    if (table) {
      this.setState({ fetch_table: table });
    } else {
      this.fetchTable(page, paginate, searchParam)
        .then(({ table }) => {
          this.setState({
            fetch_table: table
          });
        })
        .catch(e => {
          this.setState({ error: e.message });
        });
    }
    window.addEventListener('scroll', this.handleScroll);
  }

  componentDidUpdate() {
    apiHelper.setFetch(false);
  }

  componentWillUnmount() {
    window.removeEventListener('scroll', this.handleScroll);
  }

  setFetch(fetch) {
    apiHelper.setFetch(fetch);
  }

  handleImportCSV(body) {
    this.setState({ external: { table: body, render: true } });
  }

  stopRenderImport() {
    this.setState({ external: { table: false, render: false } });
    const url = new URL(location.href);
    const { fetch_table, isUpdating, error } = this.state;
    if (fetch_table || isUpdating || error) return;
    const page = parseInt(sessionStorage.getItem('page') || url.searchParams.get('page')) || 1;
    const paginate =
      parseInt(sessionStorage.getItem('paginate') || url.searchParams.get('paginate')) || 40;
    this.fetchTable(page, paginate * page);
  }

  rememberTable() {
    const { external, fetch_table } = this.state;
    localStorage.setItem('table', JSON.stringify(external.table || fetch_table));
    localStorage.setItem('page', sessionStorage.getItem('page'));
    localStorage.setItem('paginate', sessionStorage.getItem('paginate'));
    localStorage.setItem('searchParam', sessionStorage.getItem('searchParam'));
    this.forceUpdate();
  }

  forgetTable() {
    localStorage.removeItem('paginate');
    localStorage.removeItem('table');
    localStorage.removeItem('page');
    localStorage.removeItem('searchParam');
    this.forceUpdate();
  }

  fetchAllData() {
    const paginate = -1;
    sessionStorage.setItem('paginate', paginate.toString());
    this.setState({ fetch_table: false, isUpdating: false });
    this.fetchTable(1, paginate)
      .then(({ table }) => this.setState({ fetch_table: table }))
      .catch(e => {
        this.setState({ error: e.message });
      });
  }

  handleScroll() {
    const scroll = window.scrollY;
    const height = (document.body.clientHeight - window.innerHeight) * 0.8;
    const doUpdate = (scroll, height) => scroll > height;
    const { external, error, isFiltering, shouldUpdate } = this.state;
    // If application neither filtering
    // nor filtering
    // nor has error
    // nor api is already fetching data
    // nor fetched the last chunk of data
    if (
      shouldUpdate &&
      !error &&
      !external.render &&
      !isFiltering &&
      !apiHelper.isFetching &&
      doUpdate(scroll, height)
    ) {
      apiHelper.isFetching = true;
      const nextPage =
        parseInt(localStorage.getItem('page') || sessionStorage.getItem('page'), 10) + 1;
      sessionStorage.setItem('page', nextPage.toString());
      if (localStorage.getItem('page')) localStorage.setItem('page', nextPage);
      this.setState({ isUpdating: true });
      const paginate = parseInt(
        localStorage.getItem('paginate') || sessionStorage.getItem('paginate')
      );
      const searchParam =
        localStorage.getItem('searchParam') || sessionStorage.getItem('searchParam');
      this.fetchTable(nextPage, paginate, searchParam)
        .then(data => {
          this.setState(prevState => {
            // If no data from api
            if (!data || !data.table || data.table.length <= 0) {
              return {
                error: false,
                fetch_table: prevState.fetch_table,
                isUpdating: false,
                shouldUpdate: false
              };
            }
            const { table } = data;
            // const fetch_table = this.group(prevState.fetch_table, table);
            const fetch_table = prevState.fetch_table.concat(table);
            if (localStorage.getItem('table')) {
              localStorage.setItem('table', JSON.stringify(fetch_table));
              localStorage.setItem('page', nextPage.toString());
            }
            return {
              fetch_table,
              isUpdating: false,
              error: false
            };
          });
        })
        .catch(e => {
          this.setState({ error: e.message, isUpdating: false });
        });
    }
  }

  group(prevTable, table) {
    if (!prevTable || !table) return prevTable;
    const concatLast = (minus, prevTable, table) =>
      prevTable.slice(prevTable.length - minus, prevTable.length).concat(table);
    const groupBy = (xs, key) => {
      return xs.reduce((rv, x) => {
        (rv[x[key]] = rv[x[key]] || []).push(x);
        return rv;
      }, {});
    };
    const startLast = 6;
    const groupIdTable = groupBy(concatLast(startLast, prevTable, table), 'id');
    const groupIdDateTable = Object.keys(groupIdTable).reduce((acc, id) => {
      acc[id] = groupBy(groupIdTable[id], 'date');
      return acc;
    }, {});
    // Grouping by data according to table headers
    const groupedTable = Object.keys(groupIdDateTable)
      .map(id => {
        return Object.keys(groupIdDateTable[id]).map(date => {
          const values = groupIdDateTable[id][date];
          const accum = TableHelper.prototype.sumAndGroup(values);
          return accum;
        });
      })
      .flat();
    return prevTable.slice(0, prevTable.length - startLast).concat(groupedTable);
  }

  fetchTable(page, pagination, searchParam) {
    return apiHelper
      .get(`/billed_meals`, [
        {
          key: 'paginate',
          value: pagination
        },
        {
          key: 'page',
          value: page
        },
        {
          key: 'searchParam',
          value: searchParam || ''
        }
      ])
      .then(response => {
        const { pages } = response;
        if (!Array.isArray(pages)) {
          return { table: pages.data };
        }
        return { table: pages || [] };
      })
      .catch(e => {
        this.setState({ error: e.message });
      });
  }

  handleQuickFiltering(value) {
    const { typingTimeout } = this.state;
    if (typingTimeout) clearTimeout(typingTimeout);
    const page = parseInt(localStorage.getItem('page') || sessionStorage.getItem('page'));
    const paginate = parseInt(
      localStorage.getItem('paginate') || sessionStorage.getItem('paginate')
    );
    let searchParam = sessionStorage.getItem('searchParam') || '';
    if (value === searchParam) return;
    searchParam = value;
    sessionStorage.setItem('searchParam', searchParam || '');
    const timeoutId = setTimeout(() => {
      this.setState({ fetch_table: false });
      this.setFetch(true);
      this.fetchTable(1, page * paginate, searchParam)
        .then(({ table }) => {
          this.setState({ fetch_table: table, isUpdating: false, shouldUpdate: true });
          this.setFetch(false);
        })
        .catch(e => {
          this.setState({ error: e.message });
        });
    }, 1000);
    this.setState({ typingTimeout: timeoutId });
  }

  handleRefresh() {
    const { external } = this.state;
    this.setState({ error: false, fetch_table: false });
    const page = parseInt(localStorage.getItem('page') || sessionStorage.getItem('page'));
    const searchParam = parseInt(
      localStorage.getItem('searchParam') || sessionStorage.getItem('searchParam')
    );
    const paginate = parseInt(
      localStorage.getItem('paginate') || sessionStorage.getItem('paginate')
    );
    if (!external.table) {
      this.setFetch(true);
      this.fetchTable(1, paginate * page, searchParam)
        .then(({ table }) => {
          this.setState({ fetch_table: table });
          this.setFetch(false);
        })
        .catch(e => {
          this.setState({ error: e.message });
        });
    }
  }

  render() {
    const { fetch_table, isUpdating, error, external } = this.state;
    const table = external.render ? external.table : fetch_table;
    return (
      <Router basename={rootPath}>
        <React.StrictMode>
          <Nav links={routes.map(route => ({ link: route.path, text: route.text }))} />
          <main>
            {routes.map(route => (
              <Route key={`${route.path}`} path={route.path} exact={route.exact}>
                <route.component
                  handleRefresh={this.handleRefresh}
                  table={table}
                  external={external.render}
                  error={error}
                  isUpdating={isUpdating}
                  handleImportCSV={this.handleImportCSV}
                  stopRenderImport={this.stopRenderImport}
                  fetchAllData={this.fetchAllData}
                  setFetch={this.setFetch}
                  rememberTable={this.rememberTable}
                  forgetTable={this.forgetTable}
                  handleQuickFiltering={this.handleQuickFiltering}
                />
              </Route>
            ))}
          </main>
        </React.StrictMode>
      </Router>
    );
  }
}

App.defaultProps = {
  initTable: []
};

App.propTypes = {
  initTable: PropTypes.array
};

const el = document.querySelector('#root');
if (el) {
  ReactDOM.render(
    <ErrorHandler>
      <App />
    </ErrorHandler>,
    el
  );
}
