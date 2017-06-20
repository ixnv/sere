import React, {Component} from 'react';

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
            <section>
                <label htmlFor="result">{label}</label>
                <textarea name="result" cols="30" rows="10" value={text} readOnly/>
            </section>
        );
    }
}