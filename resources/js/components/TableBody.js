import React, { Component } from "react";
import ReactDOM from "react-dom";
import PropTypes from "prop-types";

import ApiHelper from "../helpers/ApiHelper";
import Modal from "./Modal/Modal";
import TableHelper from "../helpers/TableHelper";

export default class TableBody extends Component {
    constructor(props) {
        super(props);
        this.state = {
            pages: false,
            table: false,
            filteredTable: false,
            error: false,
        };
        this.filterTable = this.filterTable.bind(this);
        this.filterByString = this.filterByString.bind(this);
        this.filterByNumber = this.filterByNumber.bind(this);
        this.filterByDate = this.filterByDate.bind(this);
        this.showTable = this.showTable.bind(this);
        this.listenFiltering = this.listenFiltering.bind(this);
        this.handleCSV = this.handleCSV.bind(this);
        this.handleTableReset = this.handleTableReset.bind(this);
    }

    componentDidMount() {
        const pagination = sessionStorage.getItem("pagination") || 20;
        const page = sessionStorage.getItem("page") || 1;
        ApiHelper.get(`${ApiHelper.url}/billed_meals`, [
            {
                key: "pagination",
                value: pagination
            },
            {
                key: "page",
                value: page
            }
        ]).then(response => {
            let table = '';
            if(response.pages && response.pages.data){
                table = response.pages.data;
            } else if(response.pages) {
                table = response.pages;
            }
            this.setState({ 
                pages: response.pages, 
                table, 
                html: response.html });
        }).catch(() => {
            this.setState({error: true});
        });
        window.addEventListener('table__reset', this.handleTableReset);
        this.listenFiltering();
    }

    componentWillUnmount(){
        window.removeEventListener('filter_table__reset', null);
        window.removeEventListener('filter_table__number', null);
        window.removeEventListener('filter_table__date', null);
        window.removeEventListener('filter_table__string', null);
        window.removeEventListener('import_csv', null);
        window.removeEventListener('table__reset', null);
    }

    handleTableReset(){
        let table = '';
        if(this.state.pages && this.state.pages.data){
            table = this.state.pages.data;
        } else if(this.state.pages) {
            table = this.state.pages;
        }
        this.setState({table});
    }

    handleCSV(e){
        const { detail } = e;
        this.setState({ table: TableHelper.prototype.csvToJson(detail) });
    }

    listenFiltering() {
        window.addEventListener('import_csv', this.handleCSV);
        window.addEventListener('filter_table__reset', this.showTable);
        window.addEventListener("filter_table__date", e => {
            const { startDate, endDate } = e.detail;
            this.filterTable(startDate, endDate, "flight_date", "date");
        });
        window.addEventListener("filter_table__number", e => {
            const { startValue, endValue, key } = e.detail;
            this.filterTable(startValue, endValue, key, "number");
        });
        window.addEventListener("filter_table__string", e => {
            const { string, key } = e.detail;
            this.filterTable(string, "", key, "string");
        });
    }

    filterTable(startValue, endValue, key, method) {
        if(!startValue){
            this.showTable();
        }
        const [index, subIndex] = this.getIndex(key);
        if(!index) return;
        if (method === "date") {
            this.filterByDate(this.state.table, startValue, endValue || startValue, index, subIndex);
        } else if (method === "number") {
            this.filterByNumber(this.state.table, startValue, endValue, index, subIndex);
        } else if (method === "string") {
            this.filterByString(this.state.table, startValue, index, subIndex);
        }
    }

    getIndex(key){
        switch(key){
            case 'flight_date':return ['date',false];
            case 'flight_id': return ['id',false];
            case 'plan_code':return ['plan_attributes', 'codes'];
            case 'plan_qty':return ['plan_attributes', 'qty'];
            case 'plan_price':return ['plan_attributes', 'price'];
            case 'fact_code':return ['fact_attributes', 'codes'];
            case 'fact_qty':return ['fact_attributes', 'qty'];
            case 'fact_price':return ['fact_attributes', 'price'];
            case 'delta':return ['delta',false];
            default: return [false, false];
        }
    }

    showTable() {
        this.setState({ filteredTable: false });
    }

    filterByNumber(table, startValue, endValue, index, subIndex){
        const start = parseInt(startValue);
        const end = parseInt(endValue);
        if (end < start) return;
        this.setState({
            filteredTable: table.filter(tr => {
                if(subIndex){
                    const trNumber = parseInt(tr[index][subIndex]);
                    if(!trNumber) return;
                    return trNumber >= start && trNumber <= end;
                }
                const trNumber = index === 'delta'
                    ? tr.plan_attributes.price - tr.fact_attributes.price
                    : parseInt(tr[index]);
                return trNumber >= start && trNumber <= end;
            })
        })
    }

    filterByString(table, string, index, subIndex){
        const s = string.toLocaleLowerCase();
        this.setState({
            filteredTable: table.filter(tr => {
                if(subIndex){
                    const trString = tr[index][subIndex];
                    if(!trString) return;
                    return trString.toLocaleLowerCase().includes(s);
                }
                const trString = tr[index].toLocaleLowerCase();
                return trString.includes(s);
            })
        });
    }

    filterByDate(table, startDate, endDate, index){
        const start = Date.parse(startDate);
        const end = Date.parse(endDate);
        this.setState({
            filteredTable: table.filter(tr => {
                if(subIndex){
                    const trDate = Date.parse(tr[index][subIndex]);
                    if(!trDate) return;
                    return trDate >= start && trDate <= end;
                }
                const trDate = Date.parse(tr[index]);
                return trDate >= start && trDate <= end;
            })
        });
    }

    render() {
        if(this.state.error){
            return (
                <Modal>
                    <img src="https://cdn.pixabay.com/photo/2017/02/12/21/29/false-2061132_960_720.png"></img>
                </Modal>
            );
        }
        if (!this.state.pages) {
            return (
                <Modal>
                    <div className="loader"></div>
                </Modal>
            );
        }
        const table = this.state.filteredTable || this.state.table;
        document.getElementsByTagName('nav')[0].outerHTML = this.state.html || '<nav></nav>';
        return table.map((tr, i) => <TableElement key={i} {...tr} />);
    }
}

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
            <td className="main-table__td">{(plan_attributes.price).toFixed(2)}</td>
            <td className="main-table__td">{(fact_attributes.price).toFixed(2)}</td>
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
