import React, { Component } from 'react';
import AppHeader from './../AppHeader/AppHeader';
import './App.css';

export default class App extends Component {
    render() {
        return (
            <div>
                <AppHeader/>
                {this.props.children}
            </div>
        );
    }
}