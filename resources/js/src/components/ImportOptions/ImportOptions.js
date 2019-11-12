import React from "react";
import PropTypes from "prop-types";
import { withRouter } from "react-router-dom";

import TableHelper from "@helpers/TableHelper";

import "./ImportOptions.scss";

function ImportOptions(props) {
    return (
        <section className="import">
            <div className="import__form">
                <fieldset>
                    <legend>Import CSV to Table</legend>
                    <section className="import__options">
                        <div className="import__options__choose">
                            <button className="import__options__csv" onClick={() => importCSV(props.handleImportCSV)}>
                                Choose File
                            </button>
                            <input id="input_csv" type="file" className="hidden" />
                            <span className="import__data">Choose file</span>
                        </div>
                        <div className="import__reset">
                            <button className="import__options__csv" onClick={() => props.history.push("/")}>
                                Import
                            </button>
                            <button
                                className="import__options__delete"
                                onClick={() => {
                                    props.stopRenderImport();
                                    clearImport();
                                }}
                            >
                                Delete Import CSV
                            </button>
                        </div>
                    </section>
                </fieldset>
            </div>
        </section>
    );
}

export default withRouter(ImportOptions);

ImportOptions.propTypes = {
    handleImportCSV: PropTypes.func.isRequired,
    stopRenderImport: PropTypes.func.isRequired,
    history: PropTypes.object
};

const importCSV = handleImport => {
    const input_csv = document.getElementById("input_csv");
    input_csv.addEventListener("change", e => handleSelectCSV.call(this, e, handleImport));
    input_csv.click();
};

const clearImport = () => {
    document.querySelector(".import__data").innerText = "Choose file";
    document.querySelector(".import__reset").style.width = "inherit";
};

const handleSelectCSV = (ev, handleImport) => {
    const select = new File([ev.target.files[0]], ev.target.files[0].name, { type: ev.target.files[0].type });
    if (window.FileReader && select) {
        const reader = new FileReader();
        reader.file_info = select;
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
                const file = e.currentTarget.file_info;
                if (handleImport(tBody)) {
                    const docInfo = document.querySelector(".import__data");
                    const text = `Файл ${file.name}, размером ${Math.round(file.size / 1024)} кбайт. Количество строк ${tBody.length}`;
                    docInfo.innerText = text;
                    document.querySelector(".import__reset").style.width = "100%";
                }
            }
        };
        const blob = select.slice(0, select.size - 1);
        reader.readAsText(blob, "utf-8");
        ev.currentTarget.value = "";
    }
};