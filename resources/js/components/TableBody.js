import React, { Component } from "react";
import ReactDOM from "react-dom";
import PropTypes from "prop-types";

import ApiHelper from "../helpers/ApiHelper";

export default class TableBody extends Component {
    constructor(props) {
        super(props);
        this.state = {
            body: false
        };
    }

    componentDidMount() {
        const pagination = sessionStorage.getItem("pagination") || 20;
        const page = sessionStorage.getItem("page") || 1;
        //TODO: Resolve links problem
        ApiHelper.get(`${ApiHelper.url}/billed_meals`, [
            {
                key: "pagination",
                value: pagination
            },
            {
                key: "page",
                value: page
            }
        ]).then(data => this.setState({ body: data }));
    }

    render() {
        if (!this.state.body) {
            //TODO: make modal with loading circle
            return (
                <tr>
                    <td className="main-table__td">LOADING</td>
                    <td className="main-table__td">LOADING</td>
                    <td className="main-table__td">LOADING</td>
                    <td className="main-table__td">LOADING</td>
                    <td className="main-table__td">LOADING</td>
                    <td className="main-table__td">LOADING</td>
                    <td className="main-table__td">LOADING</td>
                    <td className="main-table__td">LOADING</td>
                    <td className="main-table__td">LOADING</td>
                    <td className="main-table__td">LOADING</td>
                    <td className="main-table__td">LOADING</td>
                </tr>
            );
        }
        const body = this.state.body;
        return (
            body.data.map((tr, i) => {
                return <TableElement key={i} {...tr} />;
            }) ||
            body.map((tr, i) => {
                return <TableElement key={i} {...tr} />;
            })
        );
    }
}

const TableElement = props => {
    const { id, date, type, plan_attributes, fact_attributes } = props;
    const nom_class = props.class;
    const delta = (parseInt(plan_attributes.price) - parseInt(fact_attributes.price)).toFixed(2) || 0;
    return (
        <tr>
            <td className="main-table__td">{id}</td>
            <td className="main-table__td">{date}</td>
            <td className="main-table__td">{nom_class}</td>
            <td className="main-table__td">{type}</td>
            <td className="main-table__td">{plan_attributes.codes.join(", ") || "NO DATA"}</td>
            <td className="main-table__td">{fact_attributes.codes.join(", ")}</td>
            <td className="main-table__td">{parseInt(plan_attributes.qty).toFixed(2) || 0}</td>
            <td className="main-table__td">{parseInt(fact_attributes.qty).toFixed(2)}</td>
            <td className="main-table__td">{parseInt(plan_attributes.price).toFixed(2) || 0}</td>
            <td className="main-table__td">{parseInt(fact_attributes.price).toFixed(2)}</td>
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

const el = document.querySelector(".main-table__tbody");
if (el) {
    ReactDOM.render(<TableBody />, el);
}
