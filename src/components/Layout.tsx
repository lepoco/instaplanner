/**
 * This Source Code Form is subject to the terms of the GPL-3.0 License.
 * If a copy of the GNU GPL-3.0 was not distributed with this file, You can obtain one at https://github.com/lepoco/instaplanner/blob/main/LICENSE.
 * Copyright (C) Leszek Pomianowski and InstaPlanner Contributors.
 * All Rights Reserved.
 */

import RoutedPureComponent from './../common/RoutedPureComponent';
import withRouter from './../common/withRouter';
import IRouterProps from './../interfaces/IRouterProps';
import { Link, NavLink } from 'react-router-dom';

interface ILayoutState {}

class Layout extends RoutedPureComponent<ILayoutState> {
  public static displayName: string = Layout.name;

  public constructor(props: IRouterProps) {
    super(props);
  }

  public render(): JSX.Element {
    return <>Hello World</>;
  }
}

export default withRouter(Layout);
