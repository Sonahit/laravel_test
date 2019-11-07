import React, { Component } from "react";
import PropTypes from "prop-types";

import TableHelper from "@helpers/TableHelper";

export default class TableBody extends Component {
    constructor(props) {
        super(props);
        this.state = {
            filteredTable: false
        };
        this.filterTable = this.filterTable.bind(this);
        this.sortTable = this.sortTable.bind(this);
        this.filterByString = this.filterByString.bind(this);
        this.filterByNumber = this.filterByNumber.bind(this);
        this.filterByDate = this.filterByDate.bind(this);
        this.showTable = this.showTable.bind(this);
        this.handleCSV = this.handleCSV.bind(this);
        this.handleTableReset = this.handleTableReset.bind(this);
    }

    handleTableReset() {
        let table = "";
        if (this.state.pages && this.state.pages.data) {
            table = this.state.pages.data;
        } else if (this.state.pages) {
            table = this.state.pages;
        }
        this.setState({ table });
    }

    handleCSV(e) {
        const { detail } = e;
        this.setState({ table: TableHelper.prototype.csvToJson(detail) });
    }

    filterTable(table, filter) {
        const { startValue, endValue, key, method } = filter;
        if (!startValue || !key || !method) return table;

        const [index, subIndex] = this.getIndex(key);
        if (!index) return table;
        if (method === "date") {
            return this.filterByDate(table, startValue, endValue || startValue, index, subIndex);
        } else if (method === "number") {
            return this.filterByNumber(table, startValue, endValue, index, subIndex);
        } else if (method === "string") {
            return this.filterByString(table, startValue, index, subIndex);
        }
    }

    sortTable(table, sortOptions) {
        const { dataSort, asc } = sortOptions;
        if (!dataSort) return table;
        const comparer = asc => (a, b) =>
            ((v1, v2) => (v1 !== "" && v2 !== "" && !isNaN(v1) && !isNaN(v2) ? v1 - v2 : v1.toString().localeCompare(v2)))(asc ? a : b, asc ? b : a);
        const [index, subIndex] = this.getIndex(dataSort);
        return table.sort((trA, trB) => {
            if (subIndex) {
                const vA = trA[index][subIndex];
                const vB = trB[index][subIndex];
                return comparer(!asc)(vA, vB);
            }
            const vA = trA[index];
            const vB = trB[index];
            return comparer(!asc)(vA, vB);
        });
    }

    getIndex(key) {
        switch (key) {
            case "flight_date":
                return ["date", false];
            case "flight_id":
                return ["id", false];
            case "plan_code":
                return ["plan_attributes", "codes"];
            case "plan_qty":
                return ["plan_attributes", "qty"];
            case "plan_price":
                return ["plan_attributes", "price"];
            case "fact_code":
                return ["fact_attributes", "codes"];
            case "fact_qty":
                return ["fact_attributes", "qty"];
            case "fact_price":
                return ["fact_attributes", "price"];
            case "delta":
                return ["delta", false];
            default:
                return [false, false];
        }
    }

    showTable() {
        this.setState({ filteredTable: false });
    }

    filterByNumber(table, startValue, endValue, index, subIndex) {
        const start = parseInt(startValue);
        const end = parseInt(endValue);
        if (end < start || isNaN(start) || isNaN(end)) return table;
        return table.filter(tr => {
            if (subIndex) {
                const trNumber = parseInt(tr[index][subIndex]);
                if (!trNumber) return;
                return trNumber >= start && trNumber <= end;
            }
            const trNumber = index === "delta" ? tr.plan_attributes.price - tr.fact_attributes.price : parseInt(tr[index]);
            return trNumber >= start && trNumber <= end;
        });
    }

    filterByString(table, string, index, subIndex) {
        if (!string.toLocaleLowerCase) return table;
        const s = string.toLocaleLowerCase();
        return table.filter(tr => {
            if (subIndex) {
                const trString = tr[index][subIndex];
                if (!trString) return;
                return trString.toLocaleLowerCase().includes(s);
            }
            const trString = tr[index].toLocaleLowerCase();
            return trString.includes(s);
        });
    }

    filterByDate(table, startDate, endDate, index, subIndex) {
        const start = Date.parse(startDate);
        const end = Date.parse(endDate);
        return table.filter(tr => {
            if (subIndex) {
                const trDate = Date.parse(tr[index][subIndex]);
                if (!trDate) return;
                return trDate >= start && trDate <= end;
            }
            const trDate = Date.parse(tr[index]);
            return trDate >= start && trDate <= end;
        });
    }

    render() {
        const { table, filter, sort } = this.props;
        const tBody = this.filterTable(this.sortTable(table, sort), filter);
        return (
            <tbody className="main-table__tbody">
                {tBody.map((tr, i) => (
                    <TableElement key={i} {...tr} />
                ))}
            </tbody>
        );
    }
}

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
