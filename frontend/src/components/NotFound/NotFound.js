import React, {Component} from 'react';
import './NotFound.css';
import {Link} from "react-router-dom";

export default class NotFound extends Component {
    render() {
        return (
            <section className="container">
                <div className="not-found">
                    <h1 className="not-found__sign">Not Found :(</h1>
                    <Link to="/">Back to main page</Link>
                </div>
            </section>
        );
    }
}