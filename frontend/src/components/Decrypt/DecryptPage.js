import React, {Component} from 'react';
import ErrorsList from "../ErrorsList/ErrorsList";
import DecryptForm from "./DecryptForm";

export default class DecryptPage extends Component {
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
                <DecryptForm {...this.props} setErrors={this.setErrors} uuid={this.props.match.params.uuid}/>
            </section>
        );
    }
}