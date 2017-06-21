const httpBuildQuery = (queryObj) => {
    let query = [];
    for (let prop in queryObj) {
        if (queryObj.hasOwnProperty(prop)) {
            query.push(prop + '=' + queryObj[prop]);
        }
    }

    return query.join('&');
};


/**
 * i do not want to pull entire packages just for these
 */

const randomString = () => {
    const alphabet = 'abcdefghijklmnopqrstuvwxyz';

    const length = Math.floor(Math.random() * 10) + 5;

    let randomString = '';

    for (let index, isUpper, char, i = 0; i < length; i++) {
        index = Math.floor(Math.random() * 26);
        isUpper = Math.random() > 0.5;

        char = alphabet.charAt(index);
        randomString += isUpper ? char: char.toUpperCase();
    }

    return randomString;
};

const classnames = (classesMap) => {
    let classes = [];


    for (let klass in classesMap) {
        if (classesMap.hasOwnProperty(klass) && classesMap[klass]) {
            classes.push(klass);
        }
    }

    return classes.join(' ');
};

export {httpBuildQuery, randomString, classnames};