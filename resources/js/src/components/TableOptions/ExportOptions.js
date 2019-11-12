import React from "react";
import PropTypes from "prop-types";

import DownloadHelper from "@helpers/DownloadHelper";
const downloadHelper = new DownloadHelper();

export default function ExportOptions(props) {
    return (
        <section className="options__download">
            <button className="options__download__pdf" onClick={() => downloadHelper.downloadPDF()}>
                Download PDF
            </button>
            <button className="options__download__xml" onClick={() => downloadHelper.downloadXML()}>
                Download XML
            </button>
            <button className="options__download__csv" onClick={() => downloadHelper.downloadCSV()}>
                Download CSV
            </button>
            <button className="options__download__csv" onClick={() => props.fetchAllData()}>
                Get all meals
            </button>
            <button className={`options__download__csv ${!props.external ? "hidden" : ""}`} onClick={() => props.stopRenderImport()}>
                Delete import data
            </button>
        </section>
    );
}

ExportOptions.propTypes = {
    fetchAllData: PropTypes.func.isRequired,
    stopRenderImport: PropTypes.func,
    external: PropTypes.bool
};
