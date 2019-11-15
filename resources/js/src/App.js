import React, { Component } from "react";
import { BrowserRouter as Router, Route } from "react-router-dom";
import ReactDOM from "react-dom";
import PropTypes from "prop-types";

import "./App.scss";

import Nav from "@components/Nav/Nav.js";
import routes from "./routes.js";

import ApiHelper from "@helpers/ApiHelper";
import TableHelper from "@helpers/TableHelper.js";
import ErrorHandler from "@handlers/ErrorHandler.js";

const apiHelper = new ApiHelper();

export default class App extends Component {
    constructor(props) {
        super(props);
        this.state = {
            fetch_table: false || this.props.initTable,
            error: false,
            isUpdating: false,
            shouldUpdate: true,
            isFiltering: false,
            external: {
                table: false,
                render: false
            }
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
    }

    componentDidMount() {
        const url = new URL(location.href);
        const page = url.searchParams.get("page") || 1;
        const paginate = url.searchParams.get("paginate") || 40;
        sessionStorage.setItem("paginate", paginate);
        sessionStorage.setItem("page", page);
        const table = JSON.parse(localStorage.getItem("table"));
        if (table) {
            this.setState({ fetch_table: table });
        } else {
            this.fetchTable(page, paginate).then(({ table }) => {
                this.setState({
                    fetch_table: table
                });
            });
        }
        window.addEventListener("scroll", this.handleScroll);
    }

    componentWillUnmount() {
        window.removeEventListener("scroll", this.handleScroll);
    }

    componentDidUpdate() {
        apiHelper.setFetch(false);
    }

    handleImportCSV(body) {
        this.setState({ external: { table: body, render: true } });
        return true;
    }

    stopRenderImport() {
        this.setState({ external: { table: false, render: false } });
    }

    rememberTable() {
        localStorage.setItem("table", JSON.stringify(this.state.external.table || this.state.fetch_table));
        localStorage.setItem("page", sessionStorage.getItem("page"));
        localStorage.setItem("paginate", sessionStorage.getItem("paginate"));
        this.forceUpdate();
    }

    forgetTable() {
        localStorage.removeItem("paginate");
        localStorage.removeItem("table");
        localStorage.removeItem("page");
        this.forceUpdate();
    }

    fetchAllData() {
        const paginate = -1;
        sessionStorage.setItem("paginate", paginate);
        this.setState({ fetch_table: false });
        this.fetchTable(1, paginate).then(({ table }) => this.setState({ fetch_table: table }));
    }

    setFetch(fetch) {
        apiHelper.setFetch(fetch);
    }

    handleScroll() {
        const scroll = window.scrollY;
        const height = window.innerHeight;
        const doUpdate = (scroll, height) => scroll > height * 0.9;
        const { external, error, isFiltering, shouldUpdate } = this.state;
        //If application neither filtering
        // nor filtering
        // nor has error
        // nor api is already fetching data
        // nor fetched the last chunk of data
        if (shouldUpdate && !error && !external.render && !isFiltering && !apiHelper.isFetching && doUpdate(scroll, height)) {
            apiHelper.isFetching = true;
            const nextPage = parseInt(sessionStorage.getItem("page")) + 1;
            sessionStorage.setItem("page", nextPage);
            this.setState({ isUpdating: true });
            this.fetchTable(nextPage, sessionStorage.getItem("paginate")).then(data => {
                this.setState(prevState => {
                    //If no data from api
                    if (!data && !data.table) {
                        return {
                            fetch_table: prevState.fetch_table,
                            isUpdating: false,
                            noUpdate: true
                        };
                    }
                    const { table } = data;
                    const fetch_table = this.group(prevState.fetch_table, table);
                    if (localStorage.getItem("table")) {
                        localStorage.setItem("table", JSON.stringify(fetch_table));
                        localStorage.setItem("page", nextPage);
                    }
                    return {
                        fetch_table,
                        isUpdating: false
                    };
                });
            });
        }
    }

    group(prevTable, table) {
        const concatLast = (minus, prevTable, table) => prevTable.slice(prevTable.length - minus, prevTable.length).concat(table);
        const groupBy = (xs, key) => {
            return xs.reduce((rv, x) => {
                (rv[x[key]] = rv[x[key]] || []).push(x);
                return rv;
            }, {});
        };
        const startLast = 6;
        const groupIdTable = groupBy(concatLast(startLast, prevTable, table), "id");
        const groupIdDateTable = Object.keys(groupIdTable).reduce((acc, id) => {
            acc[id] = groupBy(groupIdTable[id], "date");
            return acc;
        }, {});
        //Grouping by data according to table headers
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

    fetchTable(page, pagination) {
        return apiHelper
            .get(`/billed_meals`, [
                {
                    key: "paginate",
                    value: pagination
                },
                {
                    key: "page",
                    value: page
                }
            ])
            .then(response => {
                const pages = response.pages;
                if (!Array.isArray(pages)) {
                    return { table: pages.data };
                }
                return { table: pages || [] };
            })
            .catch(e => {
                this.setState({ error: e.message });
            });
    }

    handleRefresh() {
        this.setState({ error: false, fetch_table: false });
        const page = localStorage.getItem("page") || sessionStorage.getItem("page");
        const paginate = localStorage.getItem("paginate") || sessionStorage.getItem("paginate");
        this.fetchTable(1, paginate * page).then(({ table }) => this.setState({ fetch_table: table }));
    }

    render() {
        const { fetch_table, isUpdating, error, external } = this.state;
        const table = external.render ? external.table : fetch_table;
        return (
            <Router>
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
                                />
                            </Route>
                        ))}
                    </main>
                </React.StrictMode>
            </Router>
        );
    }
}

App.propTypes = {
    initTable: PropTypes.any
};

const el = document.querySelector("#root");
if (el) {
    ReactDOM.render(
        <ErrorHandler>
            <App />
        </ErrorHandler>,
        el
    );
}
