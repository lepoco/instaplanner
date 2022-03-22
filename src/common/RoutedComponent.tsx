/**
 * This Source Code Form is subject to the terms of the GPL-3.0 License.
 * If a copy of the GNU GPL-3.0 was not distributed with this file, You can obtain one at https://github.com/lepoco/instaplanner/blob/main/LICENSE.
 * Copyright (C) Leszek Pomianowski and InstaPlanner Contributors.
 * All Rights Reserved.
 */

 import { Component } from 'react';
 import INavigableComponent from './../interfaces/INavigableComponent';
 import IRouterProps from './../interfaces/IRouterProps';
 import IRouter from './../interfaces/IRouter';
 
 /**
  * Contains the logic for a component that is part of the DOM router.
  */
 export default class RoutedComponent<S = {}>
   extends Component<IRouterProps, S>
   implements INavigableComponent
 {
   private currentPath: string = '\\';
   public router: IRouter;
 
   public constructor(props: IRouterProps) {
     super(props);
 
     this.router = props.router;
     this.currentPath = props.router.location.pathname;
   }
 
   public navigated(): void {}
 
   public getSnapshotBeforeUpdate(
     prevProps: Readonly<IRouterProps>,
     prevState: Readonly<any>,
   ): void {
     if (this.router.location.pathname !== this.currentPath) {
       this.currentPath = this.router.location.pathname;
       this.navigated();
     }
   }
 
   public shouldComponentUpdate(
     nextProps: Readonly<IRouterProps>,
     nextState: Readonly<any>,
     nextContext: any,
   ): boolean {
     if (nextProps.router !== undefined) {
       this.router = nextProps.router;
     }
 
     return true;
   }
 }
 