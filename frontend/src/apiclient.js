import {httpBuildQuery} from './utils';

const request = (method, url, payload) => {
    return new Promise((resolve, reject) => {
        const xhr = new XMLHttpRequest();

        if (method === 'GET') {
            url += '?' + httpBuildQuery(payload);
        }

        xhr.open(method, url, true);

        // FIXME: couldn't send with application/json header, why?
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
        xhr.setRequestHeader('Accept', 'application/json');

        xhr.onload = () => {
            if (xhr.status >= 200 && xhr.status < 300) {
                resolve(JSON.parse(xhr.response));
            } else {
                reject({code: xhr.status, data: JSON.parse(xhr.response)});
            }
        };

        xhr.onerror = () => {
            reject(xhr.statusText);
        };

        xhr.send(httpBuildQuery(payload));
    });
};

const ENDPOINT_ROOT = 'http://localhost:8000/api/secret';

const apiClient = {
    encrypt: (secret, password, expire_sec) => {
        return request('POST', ENDPOINT_ROOT, {
            secret,
            password,
            expire_sec
        });
    },
    decrypt: (uuid, password) => {
        return request('GET', ENDPOINT_ROOT + `/${uuid}`, {
            password
        });
    }
};

export default apiClient;