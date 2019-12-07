class TemplateHelper{
    constructor() {
        this.publicUrl = `${location.origin}/templates`;
    }

    getTemplate(url, query = null){
        return fetch(url).then(resp =>{
            if(resp.ok) return resp.json();
            throw new Error(`${resp.statusText} ${url} status = ${resp.status}`);
        }).then(resp => resp)
        .catch(err => {
            return err.message;
        });
    }
}

export default TemplateHelper;