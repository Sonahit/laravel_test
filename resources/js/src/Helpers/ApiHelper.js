class ApiHelper{
    constructor() {
        this.apiPath = 'api/v1';
        this.apiURL = `https://localhost:8000/${this.apiPath}`;
    }

    get(url, query = null){
        return fetch(url).then(resp =>{
            if(resp.ok) return resp.json;
            throw new Error(`${resp.statusText} status = ${resp.status}`);
        }).then(resp => resp)
        .catch(err => err);
    }
}

export default ApiHelper;