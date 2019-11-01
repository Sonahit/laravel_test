import * as sortKeys from "./SortKeys";

export default class TableHelper {
    listenToChangeSorting() {
        const thead = this.getTable();
        thead.addEventListener("click", e => {
            const node = e.target;
            const parent = node.parentNode;
            let sortBy = 0;
            if (parent.classList.contains("main-table__th--sortable")) {
                if (node.classList.contains("asc")) {
                    if (node.classList.contains("active")) {
                        sortBy = `${parent.getAttribute("data-sort")}_desc`;
                        toDesc(node);
                    } else {
                        sortBy = `${parent.getAttribute("data-sort")}_asc`;
                        toAsc(node);
                    }
                } else if (node.classList.contains("desc") && parent.classList.contains("main-table__th--sortable")) {
                    if (node.classList.contains("active")) {
                        sortBy = `${parent.getAttribute("data-sort")}_asc`;
                        toAsc(node);
                    } else {
                        sortBy = `${parent.getAttribute("data-sort")}_desc`;
                        toDesc(node);
                    }
                }
                this.active(node);
            } else if (node.classList.contains("main-table__th--sortable")) {
                const child = node.children[0];
                if (child.classList.contains("desc")) {
                    if (child.classList.contains("active")) {
                        sortBy = `${node.getAttribute("data-sort")}_asc`;
                        toAsc(child);
                    } else {
                        sortBy = `${node.getAttribute("data-sort")}_desc`;
                        toDesc(child);
                    }
                } else if (child.classList.contains("asc")) {
                    if (child.classList.contains("active")) {
                        sortBy = `${node.getAttribute("data-sort")}_desc`;
                        toDesc(child);
                    } else {
                        sortBy = `${node.getAttribute("data-sort")}_asc`;
                        toAsc(child);
                    }
                }
                this.active(child);
            }
            if (sortBy) {
                this.sortTable(this.getTable(), sortBy);
            }
        });
        return this;
    }

    getTable() {
        return document.getElementsByClassName("main-table")[0];
    }

    listenFiltering() {
        window.addEventListener("filter_table__date", e => {
            const { startDate, endDate } = e.detail;
            this.filterTable(this.getTable(), startDate, endDate, "flight_date", "date");
        });
        window.addEventListener("filter_table__number", e => {
            const { startValue, endValue, key } = e.detail;
            this.filterTable(this.getTable(), startValue, endValue, key, "number");
        });
        window.addEventListener("filter_table__string", e => {
            const { string, key } = e.detail;
            this.filterTable(this.getTable(), string, "", key, "string");
        });
        return this;
    }

    filterTable(table, startValue, endValue, key, method) {
        const index = this.getSortIndex(this.flatTHead(table).indexOf(this.th(table, key)));
        if (method === "date") {
            this.filterByDate(table, startValue, endValue || startValue, index);
        } else if (method === "number") {
            this.filterByNumber(table, startValue, endValue || startValue, index);
        } else if (method === "string") {
            this.filterByString(table, startValue, index);
        }
    }

    showTable() {
        this.getTBody(this.getTable()).forEach(tr => (tr.style.display = ""));
    }

    filterByNumber(table, startValue, endValue, index) {
        const start = parseInt(startValue);
        const end = parseInt(endValue);
        if (end < start) return;
        // TODO: array.filter(tBody).forEach(tr => table.tBodies[0].appendChild(tr));
        this.getTBody(table).forEach(tr => {
            const trNumber = parseInt(tr.children[index].innerText);
            if (trNumber >= start && trNumber <= end) {
                tr.style.display = "";
            } else {
                tr.style.display = "none";
            }
        });
    }

    filterByString(table, string, index) {
        const s = string.toLocaleLowerCase();
        // TODO: array.filter(tBody).forEach(tr => table.tBodies[0].appendChild(tr));
        this.getTBody(table).forEach(tr => {
            const trString = tr.children[index].innerText.toLocaleLowerCase();
            if (trString.includes(s)) {
                tr.style.display = "";
            } else {
                tr.style.display = "none";
            }
        });
    }

    filterByDate(table, startDate, endDate, index) {
        const start = Date.parse(startDate);
        const end = Date.parse(endDate);
        // TODO: array.filter(tBody).forEach(tr => table.tBodies[0].appendChild(tr));
        this.getTBody(table).forEach(tr => {
            const trDate = Date.parse(tr.children[index].innerText);
            if (trDate >= start && trDate <= end) {
                tr.style.display = "";
            } else {
                tr.style.display = "none";
            }
        });
    }

    getTBody(table) {
        return Array.from(table.rows).filter(v => {
            if (v.rowIndex > 1) return v;
        });
    }

    active(node) {
        if (!node.classList.contains("active")) {
            const prevActive = document.getElementsByClassName("active")[0];
            if (prevActive) {
                prevActive.classList.remove("active");
            }
            node.classList.add("active");
        }
    }

    sortTable(table, sortBy) {
        const isDesc = !sortBy.includes("asc");
        const key = isDesc ? sortBy.split("_desc")[0] : sortBy.split("_asc")[0];
        const getCellValue = (tr, idx) => tr.children[idx].innerText || tr.children[idx].textContent;
        const comparer = (idx, asc) => (a, b) =>
            ((v1, v2) => (v1 !== "" && v2 !== "" && !isNaN(v1) && !isNaN(v2) ? v1 - v2 : v1.toString().localeCompare(v2)))(
                getCellValue(asc ? a : b, idx),
                getCellValue(asc ? b : a, idx)
            );
        //Sort table by key
        const index = this.getSortIndex(this.flatTHead(table).indexOf(this.th(table, key)));
        if (index === 9999) return;
        this.getTBody(table)
            .sort(comparer(index, isDesc))
            .forEach(tr => table.tBodies[0].appendChild(tr));
    }

    flatTHead(table) {
        return Array.from(table.tHead.rows)
            .map(thead => Array.from(thead.children).map(tr => tr))
            .flat();
    }

    th(table, key) {
        return this.flatTHead(table).find(th => th.getAttribute("data-sort") === key);
    }

    getSortIndex(index) {
        const { FLIGHTDATE_H, FLIGHTID_H, DELTA_H, PRICEFACT_H, PRICEPLAN_H, CODEFACT_H, CODEPLAN_H, QTYPLAN_H, QTYFACT_H } = sortKeys.headerKeys;
        //prettier-ignore
        const {
            FLIGHTDATE_R,
            FLIGHTID_R, 
            QTYPLAN_R, 
            QTYFACT_R, 
            CODEFACT_R, 
            CODEPLAN_R, 
            PRICEFACT_R, 
            PRICEPLAN_R, 
            DELTA_R 
        } = sortKeys.rowKeys;
        if (index === FLIGHTID_H) return FLIGHTID_R;
        if (index === FLIGHTDATE_H) return FLIGHTDATE_R;
        if (index === DELTA_H) return DELTA_R;
        if (index === CODEPLAN_H) return CODEPLAN_R;
        if (index === CODEFACT_H) return CODEFACT_R;
        if (index === QTYPLAN_H) return QTYPLAN_R;
        if (index === QTYFACT_H) return QTYFACT_R;
        if (index === PRICEPLAN_H) return PRICEPLAN_R;
        if (index === PRICEFACT_H) return PRICEFACT_R;
        return NOTSORTABLE;
    }
}

function toAsc(node) {
    node.classList.remove("desc");
    node.classList.add("asc");
}

function toDesc(node) {
    node.classList.remove("asc");
    node.classList.add("desc");
}
