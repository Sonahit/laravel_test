import React, { Component } from "react";
import PropTypes from "prop-types";

import "./Table.scss";

import TableBody from "./TableBody";
import TableHead from "./TableHead";
import Options from "../Options.js";
import Modal from "../Modal/Modal.js";
import tHead from "./headRows.js";

export default class Table extends Component {
    constructor(props) {
        super(props);
        this.state = {
            sort: {
                method: false,
                dataSort: false
            },
            filter: {
                method: false,
                key: false,
                startValue: false,
                endValue: false
            }
        };
        this.handleSort = this.handleSort.bind(this);
        this.handleFilterReset = this.handleFilterReset.bind(this);
        this.handleFilterSelect = this.handleFilterSelect.bind(this);
        this.handleFilterValue = this.handleFilterValue.bind(this);
    }

    handleSort(e, method, dataSort) {
        if (!method || !dataSort) return;
        const node = e.target;
        const parent = node.parentNode;
        let asc = false;
        if (parent.classList.contains("main-table__th--sortable")) {
            if (node.classList.contains("asc")) {
                if (node.classList.contains("active")) {
                    asc = false;
                    toDesc(node);
                } else {
                    asc = true;
                    toAsc(node);
                }
            } else if (node.classList.contains("desc") && parent.classList.contains("main-table__th--sortable")) {
                if (node.classList.contains("active")) {
                    asc = true;
                    toAsc(node);
                } else {
                    asc = false;
                    toDesc(node);
                }
            }
            active(node);
        } else if (node.classList.contains("main-table__th--sortable")) {
            const child = node.children[0];
            if (child.classList.contains("desc")) {
                if (child.classList.contains("active")) {
                    asc = true;
                    toAsc(child);
                } else {
                    asc = false;
                    toDesc(child);
                }
            } else if (child.classList.contains("asc")) {
                if (child.classList.contains("active")) {
                    asc = false;
                    toDesc(child);
                } else {
                    asc = true;
                    toAsc(child);
                }
            }
            active(child);
        }
        this.setState({ sort: { method, dataSort, asc } });
    }

    handleFilterReset(method, key) {
        this.setState({
            filter: {
                method,
                key,
                startValue: false,
                endValue: false
            }
        });
    }

    handleFilterSelect(key, method) {
        this.setState(prev => ({
            filter: {
                key,
                method,
                startValue: prev.filter.startValue,
                endValue: prev.filter.endValue
            }
        }));
    }

    handleFilterValue(startValue, endValue) {
        this.setState(prev => ({
            filter: {
                key: prev.filter.key,
                method: prev.filter.method,
                startValue,
                endValue
            }
        }));
    }

    render() {
        if (this.props.error) {
            return (
                <Modal>
                    <button onClick={this.props.handleRefresh}>Refresh</button>
                </Modal>
            );
        }
        return (
            <>
                <Options
                    method={this.state.filter.method}
                    filtering={this.state.filter.key}
                    handleFilterValue={this.handleFilterValue}
                    handleFilterReset={this.handleFilterReset}
                    handleFilterSelect={this.handleFilterSelect}
                    handleImportCSV={this.props.handleImportCSV}
                    stopRenderImport={this.props.stopRenderImport}
                    fetchAllData={this.props.fetchAllData}
                />
                {this.props.table ? (
                    <table className="main-table">
                        <TableHead tHead={tHead} handleSort={this.handleSort} />
                        <TableBody sort={this.state.sort} filter={this.state.filter} table={this.props.table} />
                    </table>
                ) : (
                    <Modal>
                        <div className="loader"></div>
                    </Modal>
                )}
                {this.props.isUpdating ? (
                    <Modal>
                        <div className="loader" />
                    </Modal>
                ) : (
                    <div className="hidden" />
                )}
            </>
        );
    }
}

Table.propTypes = {
    table: PropTypes.oneOfType([PropTypes.bool, PropTypes.array]),
    error: PropTypes.any,
    isUpdating: PropTypes.bool,
    handleRefresh: PropTypes.func.isRequired,
    handleImportCSV: PropTypes.func.isRequired,
    stopRenderImport: PropTypes.func.isRequired,
    fetchAllData: PropTypes.func.isRequired
};

function toAsc(node) {
    node.classList.remove("desc");
    node.classList.add("asc");
}

function toDesc(node) {
    node.classList.remove("asc");
    node.classList.add("desc");
}

function active(node) {
    if (!node.classList.contains("active")) {
        const prevActive = document.getElementsByClassName("active")[0];
        if (prevActive) {
            prevActive.classList.remove("active");
        }
        node.classList.add("active");
    }
}
