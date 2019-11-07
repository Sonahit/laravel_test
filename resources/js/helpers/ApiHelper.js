export const apiPath = "/api/v1";

export class ApiHelper {
    constructor(base, path = apiPath) {
        this.base = base;
        this.apiPath = path;
        this.isFetching = false;
    }

    get url() {
        return `${this.base}${apiPath}`;
    }

    get(url, params) {
        const query = params.map(param => `${param.key}=${param.value}`).join("&");
        this.isFetching = true;
        return fetch(`http://${url}?${query}`, {
            method: "GET"
        })
            .then(data => {
                this.isFetching = false;
                if (data.ok) return data.json();
                throw new Error(data.statusText);
            })
            .then(json => json)
            .catch(err => err);
    }

    post(url, data) {
        return fetch(url, {
            method: "POST",
            body: data
        })
            .then(data => {
                return data.json();
            })
            .then(json => json)
            .catch(err => err);
    }
}

export default ApiHelper;
