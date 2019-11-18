import React from "react";
import PropTypes from "prop-types";

import DownloadHelper from "@helpers/DownloadHelper";
const downloadHelper = new DownloadHelper();

export default function ExportOptions(props) {
    return (
        <section className="options__download">
            <div className="options__buttons">
                <button className="options__button options__download__pdf" onClick={() => downloadHelper.downloadPDF()}>
                    Download PDF
                </button>
                <button className="options__button options__download__csv" onClick={({ target }) => downloadHelper.downloadCSV(target)}>
                    Download CSV
                </button>
                <button className="options__button options__download__csv" onClick={() => props.fetchAllData()}>
                    Get all meals
                </button>
            </div>
            <div className="options__buttons">
                {!localStorage.getItem("table") && (
                    <button className={`options__button options__download__csv`} onClick={() => props.rememberTable()}>
                        Remember Table
                    </button>
                )}
                {localStorage.getItem("table") && (
                    <button className={`options__button options__download__csv`} onClick={() => props.forgetTable()}>
                        Forget Table
                    </button>
                )}
                <button className={`options__button options__download__csv`} onClick={() => props.refreshTable()}>
                    Refresh Table
                </button>
                <button className={`options__button options__download__csv`} onClick={() => downloadHelper.tableToCsv()}>
                    Convert table to CSV
                </button>
                {props.external && (
                    <button className={`options__button options__download__csv`} onClick={() => props.stopRenderImport()}>
                        Delete import data
                    </button>
                )}
            </div>
        </section>
    );
}

ExportOptions.propTypes = {
    fetchAllData: PropTypes.func.isRequired,
    rememberTable: PropTypes.func.isRequired,
    forgetTable: PropTypes.func.isRequired,
    refreshTable: PropTypes.func.isRequired,
    stopRenderImport: PropTypes.func,
    external: PropTypes.bool
};
