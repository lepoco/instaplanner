/**
 * This Source Code Form is subject to the terms of the GPL-3.0 License.
 * If a copy of the GNU GPL-3.0 was not distributed with this file, You can obtain one at https://github.com/lepoco/instaplanner/blob/main/LICENSE.
 * Copyright (C) Leszek Pomianowski and InstaPlanner Contributors.
 * All Rights Reserved.
 */

import { Suspense } from 'react';
import ReactDOM from 'react-dom';
import { BrowserRouter, Route, Routes } from 'react-router-dom';

import Layout from './components/Layout';
import Home from './components/Home';
import NotFound from './components/NotFound';
import * as serviceWorkerRegistration from './serviceWorkerRegistration';

import './styles/app.scss';

const baseUrl: string =
  document.getElementsByTagName('base')[0].getAttribute('href') ?? '';

ReactDOM.render(
  <BrowserRouter basename={baseUrl}>
    <Suspense fallback={<div>Loading...</div>}>
      <Layout>
        <Routes>
          <Route index element={<Home />} />
          <Route path="*" element={<NotFound />} />
        </Routes>
      </Layout>
    </Suspense>
  </BrowserRouter>,
  document.getElementById('root'),
);

serviceWorkerRegistration.register();
