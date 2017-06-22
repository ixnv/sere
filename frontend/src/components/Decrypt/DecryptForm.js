import React, {Component} from 'react';
import apiClient from '../../apiclient';
import PropTypes from 'prop-types';

/**
 * normally this would be a React Container or some sort of, but for now this approach is 'OK'
 */
export default class DecryptForm extends Component {
    state = {
        password: ''
    };

    handlePasswordChange = e => {
        e.preventDefault();

        this.setState({
            password: e.target.value
        });
    };

    decrypt = () => {
        apiClient.decrypt(this.props.uuid, this.state.password).then((response) => {
            this.props.history.push({
                pathname: '/result',
                state: {
                    label: 'Here is the text that was encrypted.',
                    text: response.text
                }
            });
        }).catch((error) => {
            switch (error.code) {
                case 400:
                    this.props.setErrors([error.data.error + '. Attempts left: ' + error.data.attempts_left]);
                    break;
                case 422:
                    this.props.setErrors(['Please fill in all fields']);
                    break;
                case 404:
                    this.props.setErrors(['Secret expired or already has been decrypted']);
                    break;
                default:
                    this.props.setErrors(['Unexpected error happened :(']);
                    break;
            }
        });
    };

    render() {
        return (
            <form className="form">
                <div className="form-group">
                    <label className="label" htmlFor="password">Enter the password you were given:</label>
                    <input className="form__field" name="password" type="text" autoComplete="off" autoFocus={true}
                           onChange={this.handlePasswordChange}
                           onKeyDown={(e) => {if (e.keyCode === 13) {e.preventDefault(); this.decrypt();} }}
                    />
                </div>
                <div className="form-group">
                    <button type="button" className="button" onClick={this.decrypt}>Decrypt</button>
                </div>
            </form>
        );
    }
}

DecryptForm.propTypes = {
    setErrors: PropTypes.func.isRequired
};