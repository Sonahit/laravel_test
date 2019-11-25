export const apiPath = '/api/v1';

class ApiHelper {
  // See index.blade for public.path
  // eslint-disable-next-line no-undef
  constructor(base = public_path || location.origin, path = apiPath) {
    this.base = base;
    this.apiPath = path;
    this.isFetching = false;
  }

  get url() {
    return `${this.base}${apiPath}`;
  }

  setFetch(fetch) {
    this.isFetching = fetch;
  }

  get(url, params) {
    const query = params.map(param => `${param.key}=${param.value}`).join('&');
    this.isFetching = true;
    return fetch(`${this.base}${this.apiPath}${url}?${query}`, {
      method: 'GET'
    })
      .then(data => {
        this.isFetching = false;
        if (data.status === 204) return [];
        if (data.ok) return data.json();
        throw new Error(data.statusText);
      })
      .then(json => {
        return json;
      })
      .catch(err => err);
  }

  post(url, data) {
    return fetch(url, {
      method: 'POST',
      body: data
    })
      .then(raw => {
        return raw.json();
      })
      .then(json => json)
      .catch(err => err);
  }
}

export default ApiHelper;
