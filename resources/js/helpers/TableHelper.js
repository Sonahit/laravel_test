export default class TableHelper{
    listenToChangeSorting(){
        const thead = document.getElementsByClassName("main-table__thead")[0];
        thead.addEventListener('click', (e) => {
            const node = e.target;
            const parent = node.parentNode;
            let sortBy = 0;
            if(parent.classList.contains('main-table__th--sortable')){
                if(node.classList.contains('asc')){
                    if(node.classList.contains('active')) {
                        sortBy = `${parent.getAttribute('data-sort')}_desc`;
                        toDesc(node);
                    } else {
                        sortBy = `${parent.getAttribute('data-sort')}_asc`;
                        toAsc(node);
                    };
                } else if(node.classList.contains('desc') && parent.classList.contains('main-table__th--sortable')){
                    if(node.classList.contains('active')) {
                        sortBy = `${parent.getAttribute('data-sort')}_asc`;
                        toAsc(node);
                    } else {
                        sortBy = `${parent.getAttribute('data-sort')}_desc`;
                        toDesc(node);
                    };
                }
                this.active(node);
            } else if(node.classList.contains('main-table__th--sortable')){
                const child = node.children[0];
                if(child.classList.contains('desc')){
                    if(child.classList.contains('active')) {
                        sortBy = `${node.getAttribute('data-sort')}_asc`;
                        toAsc(child);
                    } else {
                        sortBy = `${node.getAttribute('data-sort')}_desc`;
                        toDesc(child);
                    };
                } else if(child.classList.contains('asc')){
                    if(child.classList.contains('active')) {
                        sortBy = `${node.getAttribute('data-sort')}_desc`;
                        toDesc(child);
                    } else {
                        sortBy = `${node.getAttribute('data-sort')}_asc`;
                        toAsc(child);
                    };
                }
                this.active(child);
            }
            if(sortBy){
                this.sortTable(e.currentTarget.parentNode, sortBy);
            }
        })
    }

    active(node){
        if(!node.classList.contains('active')){
            const prevActive = document.getElementsByClassName('active')[0];
            if(prevActive){
                prevActive.classList.remove('active');
               
            }
            node.classList.add('active');
        }
    }

    sortTable(table, sortBy){
        const isAsc = sortBy.includes('asc');
        const key = isAsc ? sortBy.split('_asc')[0] : sortBy.split('_desc')[0];
        const getCellValue = (tr, idx) => tr.children[idx].innerText || tr.children[idx].textContent;
        const comparer = (idx, asc) => (a, b) => ((v1, v2) => 
            v1 !== '' && v2 !== '' && !isNaN(v1) && !isNaN(v2) ? v1 - v2 : v1.toString().localeCompare(v2)
            )(getCellValue(asc ? a : b, idx), getCellValue(asc ? b : a, idx));
        //Sort table by key
        const index = this.getSortIndex(this.flatTHead(table).indexOf(this.th(table, key)));
        if(index === 9999) return;
        Array
            .from(table.rows)
            .filter(v => {if(v.rowIndex > 1) return v})
            .sort(comparer(index, isAsc))
            .forEach(tr => table.tBodies[0].appendChild(tr));
    }

    flattTHead(table) {return Array.from(table.tHead.rows).map(thead => Array.from(thead.children).map(tr => tr)).flat()};

    th(table, key) {return this.flatTHead(table).find(th => th.getAttribute('data-sort') === key)};

    getSortIndex(index){
        //table rows
        const   FLIGHTID_R = 0,
                FLIGHTDATE_R = 1,
                CLASS_R = 2,
                NOMENCLATURE_R = 3,
                CODEPLAN_R = 4,
                CODEFACT_R = 5,
                QTYPLAN_R = 6,
                QTYFACT_R = 7,
                PRICEPLAN_R= 8,
                PRICEFACT_R = 9,
                DELTA_R = 10;
        //table headers
        const   FLIGHTID_H = 0,
                FLIGHTDATE_H = 1,
                CLASS_H = 2,
                NOMENCLATURE_H = 3,
                CODE_H = 4,
                QTY_H = 5,
                PRICE_H = 6,
                DELTA_H = 7,
                CODEPLAN_H = 8,
                CODEFACT_H = 9,
                QTYPLAN_H = 10,
                QTYFACT_H = 11,
                PRICEPLAN_H = 12,
                PRICEFACT_H = 13,
                NOTSORTABLE = 9999;
        switch(index){
            case FLIGHTID_H: return FLIGHTID_R
            case FLIGHTDATE_H: return FLIGHTDATE_R
            case CLASS_H: return NOTSORTABLE
            case NOMENCLATURE_H: return NOTSORTABLE
            case CODE_H: return NOTSORTABLE
            case QTY_H: return NOTSORTABLE
            case PRICE_H: return NOTSORTABLE
            case DELTA_H: return DELTA_R
            case CODEPLAN_H: return CODEPLAN_R
            case CODEFACT_H: return CODEFACT_R
            case QTYPLAN_H: return QTYPLAN_R
            case QTYFACT_H: return QTYFACT_R
            case PRICEPLAN_H: return PRICEPLAN_R
            case PRICEFACT_H: return PRICEFACT_R
            default: return NOTSORTABLE;
        }
    }
}


function toAsc(node){
    node.classList.remove('desc');
    node.classList.add('asc');
}

function toDesc(node){
    node.classList.remove('asc');
    node.classList.add('desc');
}