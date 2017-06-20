import React from 'react';
import {BrowserRouter, Route, Switch} from 'react-router-dom';
import NotFound from './components/NotFound/NotFound';
import DecryptPage from "./components/Decrypt/DecryptPage";
import AppHeader from "./components/AppHeader/AppHeader";
import EncryptPage from "./components/Encrypt/EncryptPage";
import Result from "./components/Result/Result";

const Routes = () => {
    return (
        <BrowserRouter>
            <div>
                <AppHeader/>
                <Switch>
                    <Route exact path="/" component={EncryptPage}/>
                    <Route exact path="/decrypt/:uuid" component={DecryptPage}/>
                    <Route exact path="/result" component={Result}/>
                    <Route component={NotFound}/>
                </Switch>
            </div>
        </BrowserRouter>
    );
};

export default Routes;