class TableHelper{
    listenToChangeSorting(){
        const thead = document.getElementsByClassName("main-table__thead")[0];
        thead.addEventListener('click', (e) => {
            const node = e.target;
            const parent = node.parentNode;
            let sortBy = 0;
            if(parent.classList.contains('main-table__th--sortable')){
                if(node.classList.contains('asc')){
                    sortBy = `${parent.getAttribute('data-sort')}_desc`;
                    toDesc(node);
                } else if(node.classList.contains('desc') && parent.classList.contains('main-table__th--sortable')){
                    sortBy = `${parent.getAttribute('data-sort')}_asc`;
                    toAsc(node);
                }
                this.active(node);
            } else if(node.classList.contains('main-table__th--sortable')){
                const child = node.children[0];
                if(child.classList.contains('desc')){
                    sortBy = `${node.getAttribute('data-sort')}_asc`;
                    toAsc(child);
                } else if(child.classList.contains('asc')){
                    sortBy = `${node.getAttribute('data-sort')}_desc`;
                    toDesc(child);
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
            prevActive.classList.remove('active');
            node.classList.add('active');
        }
    }

    sortTable(table, sortBy){
        if(table instanceof HTMLTableElement){
            let exit = false;
            while(!exit){
                table.rows.length;
                exit = true;
            }
        }
        console.log(sortBy);
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

const instance = new TableHelper();
instance.listenToChangeSorting();

export default instance;