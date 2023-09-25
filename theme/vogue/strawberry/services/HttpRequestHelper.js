import { app } from "../app";
app.service('HttpRequestHelper', () => {
    const runAjax = (config) => {
        return new Promise((resolve, reject) => {
            $.ajax({
                method: config.method,
                url: config.url,
                contentType: 'application/json',
                data: JSON.stringify(config.data),
                success: resolve,
                error: reject
            });
        });
    };
    return {
        post: (config) => {
            return new Promise((resolve, reject) => {
                runAjax({ method: 'POST', url: config.url, data: config.data }).then(resolve).catch(reject);
            });
        },
        get: (config) => {
            return new Promise((resolve, reject) => {
                runAjax({
                    method: 'GET',
                    url: config.url
                }).then(resolve).catch(reject);
            });
        }
    };
});
