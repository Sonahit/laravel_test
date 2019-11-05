import cookie from "js-cookie";
import { input } from "./app.js";
import TableHelper from "./helpers/TableHelper.js";
import { dispatchCustomEvent } from './helpers/EventHelper';

export default class Database {
    downloadCSV() {
        const table = TableHelper.prototype.getTable();
        //If no table return
        if (!table) return;

        const tHead = Array.from(table.rows).filter(v => v.rowIndex <= 1);
        const tBody = Array.from(table.rows).filter(v => v.rowIndex > 1);
        const [main, sub] = [Array.from(tHead[0].children), Array.from(tHead[1].children)];
        for (let i = 0; i < main.length - 1; i++) {
            if (main[i].rowSpan === 2) {
                sub.unshift("");
            }
            if (main[i].colSpan === 2) {
                main.splice(i + 1, 0, "");
            }
        }
        const tableHelper = new TableHelper();
        const toCsv = (rawHead, rawBody) => {
            const head = [];
            const body = [];
            rawHead.forEach(h => head.push(h.join(";")));
            rawBody.forEach(b => body.push(b.join(";")));
            return head.concat(body).join("\n");
        };
        const csv = toCsv(tableHelper.values([main, sub]), tableHelper.values(tBody));
        const charset = getOS()
            .toLowerCase()
            .includes("windows")
            ? "windows-1251"
            : "utf-8";
        this.download(csv, "csv.csv", `data:text/csv;charset=${charset}`);
    }

    downloadPDF() {
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        fetch(`${location.origin}/api/v1/pdf?pagination=${sessionStorage.getItem('pagination') || 20}&page=${sessionStorage.getItem('page') || 1}`, {
            method: "GET",
            headers: {
                'X-CSRF-TOKEN': token,
                'Cookie': document.cookie
            }
        }).then(data => data.text())
        .then(data => this.download(data, 'pdf.pdf', 'data:application/pdf'))
        .catch(err => {
            throw err;
        });
    }

    downloadXML() {
        const table = TableHelper.prototype.getTable();
        const xml = new XMLSerializer();
        const xmlTable = xml.serializeToString(table);
        this.download(xmlTable, "xml", "data:text/xml");
    }

    download(data, name, type) {
        const download = document.createElement("a");
        download.style.display = "none";
        download.download = name;
        document.body.appendChild(download);
        download.href = `${type},${data}`;
        download.click();
        document.body.removeChild(download);
    }

    handleSelectCSV(ev){
        const select = ev.target.files[0];
        if(window.FileReader && select){
            const reader = new FileReader();
            if (!select.type.match(/\w+\/csv/gi)) return;
            reader.onloadend = (e) => {
                if (e.target.readyState == FileReader.DONE) {
                    const result = e.target.result;
                    const [_, body] = this.csvAsTable(result);
                    dispatchCustomEvent('import_csv', body);
                }
            } 
            const blob = select.slice(0, select.size - 1);
            reader.readAsText(blob, 'utf-8');
            ev.currentTarget.value = '';
        }
    }

    csvAsTable(rawCSV){
        const csv = rawCSV.split('\n');
        const head = csv.filter((_, i) => i <= 1).map(e => e.split(';'));
        const body = csv.filter((_, i) => i > 1 ).map(e => e.split(';'));
        return [head, body];
    }

    reset(){
        dispatchCustomEvent('table__reset');
    }

    importCSV(){
        const input_csv = document.getElementById('input_csv');
        input_csv.addEventListener('change', (e) => this.handleSelectCSV.call(this, e));
        input_csv.click();
    }

    getMoreData() {
        const paginate = input("input_get-data").value;
        const url = new URL(location.href);
        const page = url.searchParams.get("page");
        if (page) {
            url.searchParams.set("page", page);
        }
        url.searchParams.set("paginate", paginate);
        sessionStorage.setItem("paginate", paginate);
        cookie.set("paginate", paginate);
        location.replace(url);
    }
}

function getOS() {
    let userAgent = window.navigator.userAgent,
        platform = window.navigator.platform,
        macosPlatforms = ["Macintosh", "MacIntel", "MacPPC", "Mac68K"],
        windowsPlatforms = ["Win32", "Win64", "Windows", "WinCE"],
        iosPlatforms = ["iPhone", "iPad", "iPod"],
        os = null;

    if (macosPlatforms.indexOf(platform) !== -1) {
        os = "Mac OS";
    } else if (iosPlatforms.indexOf(platform) !== -1) {
        os = "iOS";
    } else if (windowsPlatforms.indexOf(platform) !== -1) {
        os = "Windows";
    } else if (/Android/.test(userAgent)) {
        os = "Android";
    } else if (!os && /Linux/.test(platform)) {
        os = "Linux";
    }

    return os;
}
