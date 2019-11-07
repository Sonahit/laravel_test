import React, { Component } from "react";

import PropTypes from "prop-types";

export default class TableHead extends Component {
    render() {
        const thead = this.props.tHead;
        return (
            <thead className="main-table__thead">
                {thead.map((tr, i) => (
                    <tr key={`${tr.length}__${i}`}>
                        {tr.map((th, j) => (
                            <Th key={`${i}_${j}`} handleSort={this.props.handleSort} {...th} />
                        ))}
                    </tr>
                ))}
            </thead>
        );
    }
}

TableHead.propTypes = {
    handleSort: PropTypes.func.isRequired,
    tHead: PropTypes.array.isRequired
};

const Th = props => {
    const { sortable, type, dataSort, text, colSpan, rowSpan, handleSort } = props;
    return (
        <th
            className={sortable ? "main-table__th--sortable" : "main-table__th"}
            type={type || ""}
            data-sort={dataSort || ""}
            rowSpan={rowSpan || 1}
            colSpan={colSpan || 1}
            onClick={sortable ? e => handleSort(e, type, dataSort) : e => handleSort(e, false, false)}
        >
            <span className={sortable ? "asc" : ""}>{text}</span>
        </th>
    );
};

Th.propTypes = {
    sortable: PropTypes.bool.isRequired,
    handleSort: PropTypes.func,
    type: PropTypes.string,
    dataSort: PropTypes.string,
    text: PropTypes.string,
    colSpan: PropTypes.oneOfType([PropTypes.string, PropTypes.number]),
    rowSpan: PropTypes.oneOfType([PropTypes.string, PropTypes.number])
};
