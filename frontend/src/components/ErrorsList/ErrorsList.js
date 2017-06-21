import React, {Component} from "react";
import PropTypes from 'prop-types';
import {randomString} from "../../utils";
import './ErrorsList.css';

export default class ErrorsList extends Component {
    render() {
        if (!this.props.errors.length) {
            return null;
        }

        return (
            <ul className="errors-list">
                {this.props.errors.map((error) => {
                    return (
                        <li className="errors-list__item" key={randomString()}>{error}</li>
                    );
                })}
            </ul>
        );
    }
}

ErrorsList.propTypes = {
    errors: PropTypes.array
};