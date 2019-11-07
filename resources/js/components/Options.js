import React, { Component } from "react";
import PropTypes from "prop-types";

import "./Options.scss";

import Filtering from "./Filtering.js";
import Database from "../database.js";
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

    render() {
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
                    </section>
                    <section className="options__upload">
                        <button className="options__download__csv" onClick={() => database.importCSV()}>
                            Import CSV
                        </button>
                        <input id="input_csv" type="file" className="hidden" />
                        <button className="options__download__csv" onClick={() => database.reset()}>
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
                <section className="options__get-data">
                    <label>Отобразить количество позиций на одну страницу</label>
                    <input id="input_get-data" type="text" name="page" onChange={this.handleChange} placeholder="Per page" />
                    <button onClick={() => database.getMoreData()}>Отобразить</button>
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
    handleFilterReset: PropTypes.func.isRequired
};
