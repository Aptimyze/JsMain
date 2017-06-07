import React from 'react';
import asyncComponent from './asyncComponent';
import {getRoutePath} from './UrlDecoder';

import {
  BrowserRouter as Router,
  Route,
  browserHistory,
  Redirect,
  Link
} from "react-router-dom";
const LoginPage = asyncComponent(() => import('./../../login/containers/LoginPage')
  .then(module => module.default), { name: 'LoginPage' });
const MyjsPage = asyncComponent(() => import('./../../login/containers/MyjsPage')
  .then(module => module.default), { name: 'MyjsPage' });


       
const hash = getRoutePath(window.location.href);
const App = () => (
          <div>
				<Router>
					<div>
          				<Redirect to={hash}/>
						<Route exact path="/" component={LoginPage}/>
						<Route path='/login' component={LoginPage}/>
						<Route path='/myjs' component={MyjsPage} />
					</div>
				</Router>
			</div>
);

export default App;
