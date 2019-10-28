import cookie from 'js-cookie';

export default class Database{

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