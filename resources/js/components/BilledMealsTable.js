import React, { Component } from "react";
import ReactDOM from "react-dom";

//TODO: make component
export default class BilledMealsTable extends Component {
    render() {
        console.log(this.props);
        return <span>hello</span>;
    }
}

const el = document.getElementById("main_table");
if (el) {
    const props = Object.assign({}, el.dataset);
    ReactDOM.render(<BilledMealsTable {...props} />, el);
}
