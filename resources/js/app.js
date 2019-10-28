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

require("./components/BilledMealsTable");


// Pre-load tasks
import Database from './database.js';
import cookie from 'js-cookie';
import _ from './helpers/TableHelper.js';

window.Database = new Database();

const intInputValue = (input) => parseInt(input.value);

window.onload = () => {
    const inputData = document.getElementById('input_getData');
    const url = new URL(location.href);
    const page = url.searchParams.get('page') || sessionStorage.getItem('page');
    const paginate = url.searchParams.get('paginate') || sessionStorage.getItem('paginate');
    cookie.set('paginate', paginate);
    if(page){
        sessionStorage.setItem('page', page);
    }
    if (paginate){
        inputData.value = paginate;
        sessionStorage.setItem('paginate', paginate);
    } else {
        inputData.value = 20;
        sessionStorage.setItem('paginate', 20);
    }
    if(window.history && page && paginate){
        history.pushState(null, '',`?page=${page}&paginate=${paginate}`);
    }
}

window.onbeforeunload = () => {
    //If user forgot to press send button
    const paginate = intInputValue(document.getElementById('input_getData'));
    sessionStorage.setItem('paginate', paginate);
    cookie.set('paginate', paginate);
};