/**
 * First we will load all of this project's JavaScript dependencies which
 * includes React and other helpers. It's a great starting point while
 * building robust, powerful web applications using React + Laravel.
 */

require("./bootstrap");

/**
 * Next, we will create a fresh React component instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

require("./components/Filtering.js");

// Pre-load tasks
import Database from "./database.js";
import cookie from "js-cookie";
import TableHelper from "./helpers/TableHelper.js";
export const input = id => document.getElementById(id);

window.Database = new Database();

const tableHelper = new TableHelper();
tableHelper.listenToChangeSorting().listenFiltering();
const int = v => parseInt(v);

window.onload = () => {
    const inputData = input("input_get-data");
    const url = new URL(location.href);
    const page = url.searchParams.get("page") || sessionStorage.getItem("page");
    const paginate = url.searchParams.get("paginate") || sessionStorage.getItem("paginate");
    cookie.set("paginate", paginate);
    if (page) {
        sessionStorage.setItem("page", page);
    }
    if (paginate) {
        inputData.value = paginate;
        sessionStorage.setItem("paginate", paginate);
    } else {
        inputData.value = 20;
        sessionStorage.setItem("paginate", 20);
    }
    if (window.history && page && paginate) {
        history.pushState(null, "", `?page=${page}&paginate=${paginate}`);
    }
};

input("input_get-data").addEventListener("input", e => {
    const paginate = int(e.currentTarget.value);
    if (paginate) {
        sessionStorage.setItem("paginate", paginate);
        cookie.set("paginate", paginate);
    }
});
