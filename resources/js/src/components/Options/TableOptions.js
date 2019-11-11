import React, { useMemo } from "react";
import PropTypes from "prop-types";

import "./TableOptions.scss";

import Filters from "../Filters/Filters.js";
import ExportOptions from "./ExportOptions.js";

const TableOptions = props => {
    const filtersMemo = useMemo(
        () => (
            <Filters
                method={props.method}
                filters={props.filters}
                handleFilterSelect={props.handleFilterSelect}
                handleFilterReset={props.handleFilterReset}
                handleFilterValue={props.handleFilterValue}
            />
        ),
        [props.method, props.filters, props.handleFilterReset, props.handleFilterValue, props.handleFilterSelect]
    );

    return (
        <section className="options">
            <section className="options__filtering">
                {filtersMemo}
            </section>
            <section className="options__files">
                <ExportOptions external={props.external} stopRenderImport={props.stopRenderImport} fetchAllData={props.fetchAllData} />
            </section>
        </section>
    );
};

export default TableOptions;

TableOptions.propTypes = {
    method: PropTypes.string.isRequired,
    filters: PropTypes.object.isRequired,
    handleFilterSelect: PropTypes.func.isRequired,
    handleFilterValue: PropTypes.func.isRequired,
    handleFilterReset: PropTypes.func.isRequired,
    handleImportCSV: PropTypes.func.isRequired,
    stopRenderImport: PropTypes.func.isRequired,
    fetchAllData: PropTypes.func.isRequired,
    external: PropTypes.bool
};
