"use strict";
import React, { Component } from "react";
import PropTypes from "prop-types";

import "./Table.scss";

import TableBody from "./TableBody";
import TableHead from "./TableHead";
import TableOptions from "../Options/TableOptions";
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
            methodFilterSelect: "date",
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
        this.handleSort = this.handleSort.bind(this);
        this.handleFilterReset = this.handleFilterReset.bind(this);
        this.handleFilterSelect = this.handleFilterSelect.bind(this);
        this.handleFilterValue = this.handleFilterValue.bind(this);
        this.resetAllFilters = this.resetAllFilters.bind(this);
    }

    handleSort(e, method, dataSort) {
        if (!method || !dataSort) return;
        this.setState({ sort: { method, dataSort, asc: changeArrowDirection(e) } });
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
            filters: {}
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
        if (startValue.toString() === initStartValue.toString() && endValue.toString() === initEndValue.toString()) {
            this.props.setFetch(false);
        } else {
            this.props.setFetch(true);
        }
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
                <TableOptions
                    method={this.state.methodFilterSelect}
                    filters={this.state.filters}
                    handleFilterValue={this.handleFilterValue}
                    handleFilterReset={this.handleFilterReset}
                    handleFilterSelect={this.handleFilterSelect}
                    handleImportCSV={this.props.handleImportCSV}
                    stopRenderImport={this.props.stopRenderImport}
                    fetchAllData={this.props.fetchAllData}
                    external={this.props.external}
                    resetAllFilters={this.resetAllFilters}
                    reset={this.state.reset}
                />
                {this.props.table ? (
                    <table className="main-table">
                        <TableHead tHead={tHead} handleSort={this.handleSort} />
                        <TableBody sort={this.state.sort} filters={this.state.filters} table={this.props.table} />
                    </table>
                ) : (
                    <Modal>
                        <div className="loader"></div>
                    </Modal>
                )}
                {this.props.isUpdating && (
                    <Modal relative={true}>
                        <div className="loader" />
                    </Modal>
                )}
            </>
        );
    }
}

Table.propTypes = {
    table: PropTypes.oneOfType([PropTypes.bool, PropTypes.array]),
    handleRefresh: PropTypes.func.isRequired,
    handleImportCSV: PropTypes.func.isRequired,
    stopRenderImport: PropTypes.func.isRequired,
    fetchAllData: PropTypes.func.isRequired,
    setFetch: PropTypes.func.isRequired,
    external: PropTypes.bool,
    error: PropTypes.any,
    isUpdating: PropTypes.bool
};

function changeArrowDirection(e) {
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
    return asc;
}

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
