import React, {Component} from 'react';
import {Link} from "react-router-dom";

export default class Result extends Component {
    constructor(props) {
        super(props);

        this.state = {
            label: this.props.location.state.label,
            text: this.props.location.state.text
        };
    }

    render() {
        const {label, text} = this.state;

        return (
            <section className="container">
                <div className="form-group">
                    <label className="label" htmlFor="result">{label}</label>
                    <textarea className="result-field" name="result" rows="3" value={text}/>
                </div>
                <Link to="/">Protect some more important texts</Link>
            </section>
        );
    }
}