import React from "react";
import PropTypes from "prop-types";

import filterTable from "./filterTable.js";
import sortTable from "./sortTable.js";

const TableBody = props => {
    const { table, filter, sort } = props;
    const tBody = filterTable(sortTable(table, sort), filter);
    return (
        <tbody className="main-table__tbody">
            {tBody.map((tr, i) => (
                <TableElement key={i} {...tr} />
            ))}
        </tbody>
    );
};

export default TableBody;

TableBody.propTypes = {
    table: PropTypes.oneOfType([PropTypes.bool, PropTypes.array]).isRequired,
    filter: PropTypes.object.isRequired,
    sort: PropTypes.object.isRequired
};

const TableElement = props => {
    const { id, date, type, plan_attributes, fact_attributes } = props;
    const nom_class = props.class;
    const delta = (plan_attributes.price - fact_attributes.price).toFixed(2);
    return (
        <tr>
            <td className="main-table__td">{id}</td>
            <td className="main-table__td">{date}</td>
            <td className="main-table__td">{nom_class}</td>
            <td className="main-table__td">{type}</td>
            <td className="main-table__td">{plan_attributes.codes.join(", ") || "NO DATA"}</td>
            <td className="main-table__td">{fact_attributes.codes.join(", ")}</td>
            <td className="main-table__td">{plan_attributes.qty}</td>
            <td className="main-table__td">{fact_attributes.qty}</td>
            <td className="main-table__td">{plan_attributes.price.toFixed(2)}</td>
            <td className="main-table__td">{fact_attributes.price.toFixed(2)}</td>
            <td className="main-table__td">{delta}</td>
        </tr>
    );
};

TableElement.propTypes = {
    id: PropTypes.oneOfType([PropTypes.string, PropTypes.number]),
    date: PropTypes.string.isRequired,
    class: PropTypes.string.isRequired,
    type: PropTypes.string.isRequired,
    plan_attributes: PropTypes.shape({
        codes: PropTypes.array,
        qty: PropTypes.oneOfType([PropTypes.string, PropTypes.number]),
        price: PropTypes.oneOfType([PropTypes.string, PropTypes.number])
    }),
    fact_attributes: PropTypes.shape({
        codes: PropTypes.array,
        qty: PropTypes.oneOfType([PropTypes.string, PropTypes.number]),
        price: PropTypes.oneOfType([PropTypes.string, PropTypes.number])
    })
};
