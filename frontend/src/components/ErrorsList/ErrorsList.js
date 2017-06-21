import React from "react";
import PropTypes from 'prop-types';
import {randomString} from "../../utils";
import './ErrorsList.css';

const ErrorsList = ({errors}) => {
    if (!errors.length) {
        return null;
    }

    return (
        <ul className="errors-list">
            {errors.map((error) => {
                return (
                    <li className="errors-list__item" key={randomString()}>{error}</li>
                );
            })}
        </ul>
    );
};

ErrorsList.propTypes = {
    errors: PropTypes.array
};

export default ErrorsList;