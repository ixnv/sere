import React, {Component} from 'react';
import EncryptForm from "./EncryptForm";
import ErrorsList from "../ErrorsList/ErrorsList";

export default class EncryptPage extends Component {
    constructor(props) {
        super(props);

        this.state = {
            errors: []
        };
    }

    setErrors = errors => {
        this.setState({
            errors
        });
    };

    render() {
        return (
            <section className="container">
                <EncryptForm {...this.props} setErrors={this.setErrors}/>
                <ErrorsList errors={this.state.errors}/>
            </section>
        );
    }
}