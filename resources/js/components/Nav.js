import React, { Component } from "react";
import ReactDOM from "react-dom";
import parse from "html-react-parser";
import { dispatchCustomEvent } from "../helpers/EventHelper";

export default class Nav extends Component {
    constructor(props) {
        super(props);
        this.state = {
            html: false
        };
        this.changeNav = this.changeNav.bind(this);
    }
    componentDidMount() {
        window.addEventListener("prepare_nav", this.changeNav);
    }
    componentWillUnmount() {
        window.removeEventListener("prepare_nav", this.changeNav);
    }

    componentDidUpdate() {
        const nav = document.getElementsByTagName("nav")[0];
        Array.from(nav.children[0].children).forEach(li => {
            const a = li.firstElementChild;
            if (a.href) {
                const apiURL = new URL(a.href);
                const page = apiURL.searchParams.get("page");
                const url = `${apiURL.origin}/?page=${page}`;
                a.addEventListener("click", e => {
                    e.preventDefault();
                    sessionStorage.setItem("page", page);
                    const paginate = sessionStorage.getItem("paginate");
                    window.history.pushState(null, "", `?page=${page}&paginate=${paginate}`);
                    dispatchCustomEvent("fetch_data");
                });
                a.href = url;
            }
        });
    }

    changeNav({ detail }) {
        const nav = createElementFromHTML(detail);
        this.setState({ html: nav.innerHTML });
    }

    render() {
        if (!this.state.html) {
            return (
                <ul className="pagination">
                    <li className="page-item">
                        <a className="page-link" href="#">
                            1
                        </a>
                    </li>
                </ul>
            );
        }
        return parse(this.state.html);
    }
}

function createElementFromHTML(htmlString) {
    var div = document.createElement("div");
    div.innerHTML = htmlString.trim();
    return div.firstChild;
}

const el = document.querySelector("nav");
if (el) {
    ReactDOM.render(<Nav />, el);
}
