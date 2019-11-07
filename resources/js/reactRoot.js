import React, { Component } from "react";
import ReactDOM from "react-dom";

import Table from "./components/Table/Table.js";

import ApiHelper from "@helpers/ApiHelper";
const apiHelper = new ApiHelper("localhost:8000");

export default class App extends Component {
    constructor(props) {
        super(props);
        this.state = {
            fetch_table: false,
            error: false,
            isUpdating: false,
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
    }

    componentDidMount() {
        const url = new URL(location.href);
        const page = url.searchParams.get("page") || 1;
        const paginate = url.searchParams.get("paginate") || 40;
        sessionStorage.setItem("paginate", paginate);
        sessionStorage.setItem("page", page);
        this.fetchTable(page, paginate).then(({ pages, table }) => {
            this.setState({
                pages: pages,
                fetch_table: table
            });
        });
        window.addEventListener("scroll", this.handleScroll);
    }

    componentWillUnmount() {
        window.removeEventListener("scroll", this.handleScroll);
    }

    componentDidUpdate() {
        apiHelper.isFetching = false;
    }

    handleImportCSV(body) {
        this.setState({ external: { table: body, render: true } });
    }

    stopRenderImport() {
        this.setState({ external: { table: false, render: false } });
    }

    fetchAllData() {
        this.setState({ external: { render: true } });
        this.fetchTable(1, -1).then(({ table }) => this.setState({ external: { table, render: true } }));
    }

    handleScroll() {
        const scroll = window.scrollY;
        const height = window.innerHeight;
        const doUpdate = (scroll, height) => scroll > height * 0.9;
        if (!this.state.external.render && !apiHelper.isFetching && doUpdate(scroll, height)) {
            apiHelper.isFetching = true;
            const nextPage = parseInt(sessionStorage.getItem("page")) + 1;
            sessionStorage.setItem("page", nextPage);
            this.setState({ isUpdating: true });
            this.fetchTable(nextPage, sessionStorage.getItem("paginate")).then(({ pages, table }) => {
                this.setState(prevState => ({
                    pages,
                    // fetch_table: prevState.fetch_table.concat(table),
                    fetch_table: this.group(prevState.fetch_table.concat(table)),
                    isUpdating: false
                }));
            });
        }
    }

    group(table) {
        const groupBy = (xs, key) => {
            return xs.reduce((rv, x) => {
                (rv[x[key]] = rv[x[key]] || []).push(x);
                return rv;
            }, {});
        };
        const groupIdTable = groupBy(table, "id");
        const groupIdDateTable = Object.keys(groupIdTable).reduce((acc, id) => {
            acc[id] = groupBy(groupIdTable[id], "date");
            return acc;
        }, {});
        //Grouping by data according to table headers
        return Object.keys(groupIdDateTable)
            .map(id => {
                return Object.keys(groupIdDateTable[id]).map(date => {
                    const values = groupIdDateTable[id][date];
                    const accum = values.reduce(
                        (accum, v) => {
                            if (!accum.id) accum.id = v.id;
                            if (!accum.date) accum.date = v.date;
                            if (!accum.class) accum.class = v.class;
                            if (!accum.type) accum.type = v.type;
                            accum.plan_attributes = v.plan_attributes;
                            accum.fact_attributes.qty += v.fact_attributes.qty;
                            accum.fact_attributes.price += v.fact_attributes.price;
                            v.fact_attributes.codes.forEach(code => {
                                if (!accum.fact_attributes.codes.includes(code)) {
                                    accum.fact_attributes.codes.push(code);
                                }
                            });
                            return accum;
                        },
                        {
                            id: 0,
                            date: 0,
                            class: "",
                            type: "",
                            fact_attributes: {
                                qty: 0,
                                codes: [],
                                price: 0
                            },
                            plan_attributes: {
                                qty: 0,
                                codes: [],
                                price: 0
                            }
                        }
                    );
                    return accum;
                });
            })
            .flat();
    }

    fetchTable(page, pagination) {
        return apiHelper
            .get(`${apiHelper.url}/billed_meals`, [
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
                let table = "";
                if (response.html) {
                    table = pages.data;
                } else {
                    table = pages;
                }
                return {
                    pages,
                    table
                };
            })
            .catch(e => {
                this.setState({ error: e.message });
            });
    }

    handleRefresh() {
        this.setState({ error: false });
        this.fetchData(sessionStorage.getItem("page"), sessionStorage.getItem("paginate")).then(({ pages, table }) =>
            this.setState({ pages, fetch_table: table })
        );
    }

    render() {
        const { fetch_table, isUpdating, error, external } = this.state;
        const table = external.render ? external.table : fetch_table;
        return (
            <main>
                <Table
                    handleRefresh={this.handleRefresh}
                    table={table}
                    error={error}
                    isUpdating={isUpdating}
                    handleImportCSV={this.handleImportCSV}
                    stopRenderImport={this.stopRenderImport}
                    fetchAllData={this.fetchAllData}
                />
            </main>
        );
    }
}

const el = document.querySelector("#root");
if (el) {
    ReactDOM.render(<App />, el);
}
