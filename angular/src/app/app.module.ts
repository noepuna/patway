import { BrowserModule } from '@angular/platform-browser';
import { NgModule, APP_INITIALIZER  } from '@angular/core';
import { HashLocationStrategy, LocationStrategy } from '@angular/common';
import { HttpClientModule } from '@angular/common/http';
import { FormsModule } from '@angular/forms';

import { AppRoutingModule } from './app-routing.module';
//import { AppCommonModule } from './app-common/app-common.module';
import { StandardModule } from './standard/standard.module';
import { NavModule } from './nav/nav.module';
import { AccountModule } from './account/account.module';
import { EventModule } from './event/event.module';
import { SupportTicketModule } from './support-ticket/support-ticket.module';

import { HttpClientService } from './app-common/service/http-client.service';
import { httpInterceptorProviders } from './http-interceptors';

import { AppComponent } from './app.component';
import { Page404Component } from './standard/page404/page404.component';
import { LoginComponent } from './standard/login/login.component';

//import { LoginGuardService } from './core/component-guard/login-guard.service';*/
import { AuthService } from './standard/auth.service';
import { BehaviorBaseSafetyModule } from './behavior-base-safety/behavior-base-safety.module';
import { EnvironmentHealthSafetyModule } from './environment-health-safety/environment-health-safety.module';
import { BackOfficeModule } from './backoffice/backoffice.module';    
import { HomeModule } from './home/home.module';
import { HomeComponent } from './home/home.component'; 

export function repeatUser( appInitService: AuthService )
{
  return (): Promise<any> =>
  {
    return appInitService.getAccessToken();
  }
}

@NgModule({
  declarations:
  [
    AppComponent,
    Page404Component,
    LoginComponent,
    HomeComponent,
  ],
  imports:
  [
    BrowserModule,
    HttpClientModule,
    FormsModule,
    AppRoutingModule,
    //AppCommonModule,
    StandardModule,
    NavModule,
    AccountModule,
    EventModule,
    SupportTicketModule,
    BehaviorBaseSafetyModule,
    EnvironmentHealthSafetyModule,
    BackOfficeModule,
    HomeModule,
  ],
  providers:
  [
    HttpClientService,
    httpInterceptorProviders,
    AuthService,
    { provide: APP_INITIALIZER, useFactory: repeatUser, deps: [AuthService], multi: true},
    { provide: LocationStrategy, useClass: HashLocationStrategy }
  ],
  bootstrap: [AppComponent]
})
export class AppModule { }
