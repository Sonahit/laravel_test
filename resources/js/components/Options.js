import React, { Component } from "react";
import PropTypes from "prop-types";

import "./Options.scss";

import Filtering from "./Filtering.js";
import Database from "../database.js";
import TableHelper from "../helpers/TableHelper";
const database = new Database();

export default class Options extends Component {
    constructor(props) {
        super(props);
        this.handleChange = this.handleChange.bind(this);
    }

    handleChange(e) {
        const input = e.currentTarget;
        const paginate = parseInt(input.value);
        if (paginate) sessionStorage.setItem("paginate", paginate);
    }

    shouldComponentUpdate(prevProps) {
        if (prevProps.method !== this.props.method) return true;
        if (prevProps.filtering !== this.props.filtering) return true;
        return false;
    }

    handleSelectCSV(ev) {
        const select = ev.target.files[0];
        if (window.FileReader && select) {
            const reader = new FileReader();
            if (!select.type.match(/\w+\/csv/gi)) return;
            reader.onloadend = e => {
                if (e.target.readyState == FileReader.DONE) {
                    const result = e.target.result;
                    // eslint-disable-next-line no-unused-vars
                    const [_, body] = TableHelper.prototype.csvAsTable(result);
                    const tBody = body.map(row => ({
                        id: parseInt(row[0]),
                        date: row[1],
                        class: row[2],
                        type: row[3],
                        plan_attributes: {
                            codes: row[4].split(","),
                            qty: parseInt(row[6]),
                            price: parseInt(row[8])
                        },
                        fact_attributes: {
                            codes: row[5].split(","),
                            qty: parseInt(row[7]),
                            price: parseInt(row[9])
                        }
                    }));
                    this.props.handleImportCSV(tBody);
                }
            };
            const blob = select.slice(0, select.size - 1);
            reader.readAsText(blob, "utf-8");
            ev.currentTarget.value = "";
        }
    }
    importCSV() {
        const input_csv = document.getElementById("input_csv");
        input_csv.addEventListener("change", e => this.handleSelectCSV.call(this, e));
        input_csv.click();
    }

    render() {
        //TODO: Separate export and import
        return (
            <section className="options">
                <section className="options__files">
                    <section className="options__download">
                        <button className="options__download__pdf" onClick={() => database.downloadPDF()}>
                            Download PDF
                        </button>
                        <button className="options__download__xml" onClick={() => database.downloadXML()}>
                            Download XML
                        </button>
                        <button className="options__download__csv" onClick={() => database.downloadCSV()}>
                            Download CSV
                        </button>
                        <button className="options__download__csv" onClick={() => this.props.fetchAllData()}>
                            Get all meals
                        </button>
                    </section>
                    <section className="options__upload">
                        <button className="options__download__csv" onClick={e => this.importCSV(e)}>
                            Import CSV
                        </button>
                        <input id="input_csv" type="file" className="hidden" />
                        <button className="options__download__csv" onClick={() => this.props.stopRenderImport()}>
                            Reset
                        </button>
                        <a href="/api/v1/pdf" className="options__download__button">
                            Convert into PDF
                        </a>
                    </section>
                </section>
                <section className="options__filtering">
                    <Filtering
                        method={this.props.method}
                        filtering={this.props.filtering}
                        handleFilterSelect={this.props.handleFilterSelect}
                        handleFilterReset={this.props.handleFilterReset}
                        handleFilterValue={this.props.handleFilterValue}
                    />
                </section>
            </section>
        );
    }
}

Options.propTypes = {
    method: PropTypes.oneOfType([PropTypes.string, PropTypes.bool]).isRequired,
    filtering: PropTypes.oneOfType([PropTypes.string, PropTypes.bool]).isRequired,
    handleFilterSelect: PropTypes.func.isRequired,
    handleFilterValue: PropTypes.func.isRequired,
    handleFilterReset: PropTypes.func.isRequired,
    handleImportCSV: PropTypes.func.isRequired,
    stopRenderImport: PropTypes.func.isRequired,
    fetchAllData: PropTypes.func.isRequired
};
