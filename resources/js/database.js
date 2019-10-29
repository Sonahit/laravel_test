import cookie from 'js-cookie';

export default class Database{

    downloadCSV(){
        //TODO: sub column csv problem
        //Handles sub headers
        // const table = document.getElementById('main-table');
        // const items = [].reduce.call(table.rows, function(res, row) {
        //     res[row.cells[0].textContent.slice(0,-1)] = row.cells[1].textContent;
        //     return res;
        //     }, {});
        // const replacer = (key, value) => value === null ? "NO DATA" : value; // specify how you want to handle null values here
        // const header = Object.keys(items[0]);
        // let csv = items
        //     .map(row => header.map( fieldName => JSON.stringify(row[fieldName], replacer)).join(','));
        // csv.unshift(header.join(','));
        // csv = csv.join('\r\n');
        // const downloadLink = document.createElement("a");
        // const blob = new Blob(["\ufeff", csv]);
        // const url = URL.createObjectURL(blob);
        // downloadLink.href = url;
        // downloadLink.download = "data.csv";

        // document.body.appendChild(downloadLink);
        // downloadLink.click();
        // document.body.removeChild(downloadLink);
    }

    downloadPDF(){
        const table = document.getElementsByClassName('main-table')[0].outerHTML;
        fetch('/api/v1/pdf', {
            method: "POST",
            body: JSON.stringify(table)
        })
        .catch(err => {
            throw err;
        });

    }
    downloadXML(){
        const table = document.getElementsByClassName('main-table')[0];
        const xml = new XMLSerializer();
        const xmlTable = xml.serializeToString(table);
        this.download(xmlTable, "xml", "data:text/xml,");
    }
    download(data, name, type){
        const download = document.createElement('a');
        download.style.display = 'none';
        download.download = name;
        document.body.appendChild(download);
        download.href = type + data;
        download.click();
        document.body.removeChild(download);
    }
    getMoreData() {
        const paginate = document.getElementById('input_getData').value;
        const url = new URL(location.href);
        const page = url.searchParams.get('page');
        if(page){
            url.searchParams.set('page', page);
        }
        url.searchParams.set('paginate', paginate);
        sessionStorage.setItem('paginate', paginate);
        cookie.set('paginate', paginate);
        location.replace(url);
    }
}