import React, { Component } from "react";
import ReactDOM from "react-dom";

import Nav from "./components/Nav/Nav.js";
import Table from "./components/Table/Table.js";

import ApiHelper from "./helpers/ApiHelper";

export default class App extends Component {
    constructor(props) {
        super(props);
        this.state = {
            fetch_table: false,
            error: false
        };
        this.fetchTable = this.fetchTable.bind(this);
        this.handleRefresh = this.handleRefresh.bind(this);
    }

    componentDidMount() {
        const url = new URL(location.href);
        const page = url.searchParams.get("page") || sessionStorage.getItem("page") || 1;
        const paginate = url.searchParams.get("paginate") || sessionStorage.getItem("paginate") || 20;
        sessionStorage.setItem("paginate", paginate);
        sessionStorage.setItem("page", page);
        if (window.history) {
            history.pushState(null, "", `?page=${page}&paginate=${paginate}`);
        }
        this.fetchTable();
    }

    fetchTable() {
        this.setState({ fetch_table: false });
        const pagination = sessionStorage.getItem("paginate");
        const page = sessionStorage.getItem("page");
        ApiHelper.get(`${ApiHelper.url}/billed_meals`, [
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
                this.setState({
                    pages: pages,
                    fetch_table: table
                });
            })
            .catch(e => {
                this.setState({ error: e.message });
            });
    }

    handleRefresh() {
        this.setState({ error: false });
        this.fetchData();
    }

    render() {
        return (
            <>
                <Nav />
                <main>
                    <Table handleRefresh={this.handleRefresh} table={this.state.fetch_table} error={this.state.error} />
                </main>
            </>
        );
    }
}

const el = document.querySelector("#root");
if (el) {
    ReactDOM.render(<App />, el);
}

//TODO: Autoscroll
