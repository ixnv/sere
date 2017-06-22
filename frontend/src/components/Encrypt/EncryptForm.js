import React, {Component} from 'react';
import './Encrypt.css';
import apiClient from '../../apiclient';

export default class EncryptForm extends Component {
    state = {
        secret: '',
        password: '',
        ttl: 300, // in secs
        ttl_hr: '5 min' // 300 secs in minutes
    };

    handleSecretChange = e => {
        e.preventDefault();

        this.setState({
            secret: e.target.value
        });
    };

    handlePasswordChange = e => {
        e.preventDefault();

        this.setState({
            password: e.target.value
        });
    };

    handleTtlChange = e => {
        e.preventDefault();

        const seconds = isNaN(e.target.value) ? 0: e.target.value;
        const hr = `${Math.floor(seconds / 60)} min` + (seconds % 60 ? ` ${seconds % 60} sec`: '');

        this.setState({
            ttl: e.target.value,
            ttl_hr: hr
        })
    };

    handleKeyDown = e => {
        e.preventDefault();
        this.encrypt();
    };

    encrypt = () => {
        if (isNaN(this.state.ttl) || !this.state.secret.length || this.state.password.length) {

        }

        // FIXME: handle empty values, etc
        apiClient.encrypt(this.state.secret, this.state.password, this.state.ttl).then((response) => {
            this.props.history.push({
                pathname: '/result',
                state: {
                    label: 'Share the resulting link. WARNING: content will be deleted after encrypting',
                    text: window.location.origin + `/decrypt/${response.uuid}`
                }
            });
        }).catch((error) => {
            switch (error.code) {
                case 422:
                    this.props.setErrors(['Please fill in all fields']);
                    break;
                default:
                    this.props.setErrors(['Unexpected error happened :(']);
            }
        });
    };


    render() {
        return (
            <form className="form">
                <p className="encrypt-desc">Protect any text with password and share the resulting link with anyone</p>

                <div className="form-group">
                    <label className="label" htmlFor="secret">Text you want to protect:</label>
                    <textarea className="form__field" name="secret" cols="30" rows="10" value={this.state.secret} autoFocus={true}
                              onChange={this.handleSecretChange}
                              onKeyDown={this.handleKeyDown}
                    />
                </div>
                <div className="form-group">
                    <label className="label" htmlFor="password">Password (you need to give it to the receiver):</label>
                    <input className="form__field" name="password" type="text" autoComplete="off"
                           onChange={this.handlePasswordChange}
                    />
                    <div>
                        <span className="form__field-hint">at least any 5 characters</span>
                    </div>
                </div>
                <div className="form-group">
                    <label className="label" htmlFor="ttl">Lifespan (seconds):</label>
                    <input className="form__field" name="ttl" type="number" value={this.state.ttl} autoComplete="off"
                           onChange={this.handleTtlChange}
                    />
                    <div>
                        <span className="form__field-hint">{this.state.ttl_hr}</span>
                    </div>
                </div>
                <div className="form-group">
                    <button className="button" type="button" onClick={this.encrypt}>Continue</button>
                </div>
            </form>
        );
    }
}

