import React from 'react';
import {BrowserRouter, Route, Switch} from 'react-router-dom';
import NotFound from './components/NotFound/NotFound';
import DecryptPage from "./components/Decrypt/DecryptPage";
import EncryptPage from "./components/Encrypt/EncryptPage";
import Result from "./components/Result/Result";
import Footer from "./components/Footer/Footer";

const Routes = () => {
    return (
        <BrowserRouter>
            <div>
                <Switch>
                    <Route exact path="/" component={EncryptPage}/>
                    <Route exact path="/decrypt/:uuid" component={DecryptPage}/>
                    <Route exact path="/result" component={Result}/>
                    <Route component={NotFound}/>
                </Switch>
                <Footer/>
            </div>
        </BrowserRouter>
    );
};

export default Routes;