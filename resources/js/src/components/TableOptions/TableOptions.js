import React, { useMemo } from "react";
import PropTypes from "prop-types";

import "./TableOptions.scss";

import Filters from "@components/Filters/Filters";
import ExportOptions from "./ExportOptions";

const TableOptions = props => {
    const filtersMemo = useMemo(
        () => (
            <Filters
                method={props.method}
                filters={props.filters}
                handleFilterSelect={props.handleFilterSelect}
                handleFilterReset={props.handleFilterReset}
                handleFilterValue={props.handleFilterValue}
                resetAllFilters={props.resetAllFilters}
                reset={props.reset}
            />
        ),
        [props.method, props.filters, props.handleFilterSelect, props.handleFilterReset, props.handleFilterValue, props.resetAllFilters, props.reset]
    );

    return (
        <section className="options">
            <section className="options__filtering">{filtersMemo}</section>
            <section className="options__files">
                <ExportOptions
                    external={props.external}
                    stopRenderImport={props.stopRenderImport}
                    fetchAllData={props.fetchAllData}
                    rememberTable={props.rememberTable}
                    forgetTable={props.forgetTable}
                    refreshTable={props.refreshTable}
                />
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
    resetAllFilters: PropTypes.func.isRequired,
    fetchAllData: PropTypes.func.isRequired,
    rememberTable: PropTypes.func.isRequired,
    forgetTable: PropTypes.func.isRequired,
    refreshTable: PropTypes.func.isRequired,
    external: PropTypes.bool,
    reset: PropTypes.bool
};
