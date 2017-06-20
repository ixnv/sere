import React, {Component} from "react";
import PropTypes from 'prop-types';
import {randomString} from "../../utils";

export default class ErrorsList extends Component {
    render() {
        return (
            <ul className="errors">
                {this.props.errors.map((error) => {
                    return (
                        <li className="errors__item" key={randomString()}>{error}</li>
                    );
                })}
            </ul>
        );
    }
}

ErrorsList.propTypes = {
    errors: PropTypes.array
};