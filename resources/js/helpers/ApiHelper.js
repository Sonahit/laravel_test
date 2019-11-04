export const apiPath = "/api/v1";

export class ApiHelper {
    constructor(base, path = apiPath) {
        this.base = base;
        this.apiPath = path;
    }

    get url() {
        return `${this.base}${apiPath}`;
    }

    get(url, params) {
        const query = params.map(param => `${param.key}=${param.value}`).join("&");
        return fetch(`http://${url}?${query}`, {
            method: "GET"
        })
            .then(data => {
                return data.json();
            })
            .then(json => json)
            .catch((err) => err);
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
            .catch((err) => err);
    }
}
const instance = new ApiHelper("localhost:8000", apiPath);
export default instance;
