import React, {Component} from 'react';
import {Link} from 'react-router-dom';
import './AppHeader.css';

export default class AppHeader extends Component {
    render() {
        return (
            <header className="app-header">
                <ul>
                    <li>
                        <Link to="/">Foo</Link>
                    </li>
                </ul>
            </header>
        );
    }
}