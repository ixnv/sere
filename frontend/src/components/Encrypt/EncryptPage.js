import React, {Component} from 'react';
import EncryptForm from "./EncryptForm";
import ErrorsList from "../ErrorsList/ErrorsList";

export default class EncryptPage extends Component {
    constructor(props) {
        super(props);

        this.state = {
            errors: []
        };

        this.setErrors = this.setErrors.bind(this);
    }

    setErrors(errors) {
        this.setState({
            errors
        });
    }

    render() {
        return (
            <section>
                <ErrorsList errors={this.state.errors}/>
                <EncryptForm {...this.props} setErrors={this.setErrors}/>
            </section>
        );
    }
}