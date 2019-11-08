import React, { useMemo } from "react";
import PropTypes from "prop-types";

import "./TableOptions.scss";

import Filtering from "../Filters/Filters.js";
import ExportOptions from "./ExportOptions.js";

const TableOptions = props => {
    const Filters = useMemo(
        () => (
            <Filtering
                method={props.method}
                filtering={props.filtering}
                handleFilterSelect={props.handleFilterSelect}
                handleFilterReset={props.handleFilterReset}
                handleFilterValue={props.handleFilterValue}
            />
        ),
        [props.method, props.filtering, props.handleFilterReset, props.handleFilterValue, props.handleFilterSelect]
    );

    return (
        <section className="options">
            <section className="options__filtering">{Filters}</section>
            <section className="options__files">
                <ExportOptions external={props.external} stopRenderImport={props.stopRenderImport} fetchAllData={props.fetchAllData} />
            </section>
        </section>
    );
};

export default TableOptions;

TableOptions.propTypes = {
    method: PropTypes.oneOfType([PropTypes.string, PropTypes.bool]).isRequired,
    filtering: PropTypes.oneOfType([PropTypes.string, PropTypes.bool]).isRequired,
    handleFilterSelect: PropTypes.func.isRequired,
    handleFilterValue: PropTypes.func.isRequired,
    handleFilterReset: PropTypes.func.isRequired,
    handleImportCSV: PropTypes.func.isRequired,
    stopRenderImport: PropTypes.func.isRequired,
    fetchAllData: PropTypes.func.isRequired,
    external: PropTypes.bool
};
