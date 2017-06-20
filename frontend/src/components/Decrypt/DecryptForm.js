import React, {Component} from 'react';
import apiClient from '../../apiclient';
import PropTypes from 'prop-types';

/**
 * normally this would be a React Container or some sort of, but for now this approach is 'OK'
 */
export default class DecryptForm extends Component {
    constructor(props) {
        super(props);

        this.state = {
            password: ''
        };

        this.decrypt = this.decrypt.bind(this);
        this.handlePasswordChange = this.handlePasswordChange.bind(this);
    }

    handlePasswordChange(e) {
        e.preventDefault();

        this.setState({
            password: e.target.value
        });
    }

    decrypt() {
        apiClient.decrypt(this.props.uuid, this.state.password).then((response) => {
            this.props.history.push({
                pathname: '/result',
                state: {
                    label: 'Here is the text that was encrypted.',
                    text: response.text
                }
            });
        }).catch((errors) => {
            console.log(errors);
            this.props.setErrors([errors]);
        });
    }

    render() {
        return (
            <form className="form">
                <div className="form-group">
                    <label htmlFor="password">Enter the password you were given:</label>
                    <input name="password" type="text" onChange={this.handlePasswordChange}/>
                </div>
                <div className="form-group">
                    <button type="button" onClick={this.decrypt}>Decrypt</button>
                </div>
            </form>
        );
    }
}

DecryptForm.propTypes = {
    setErrors: PropTypes.func.isRequired
};