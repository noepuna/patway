import { Injectable } from '@angular/core';
import { Router } from '@angular/router';
import { HttpEvent, HttpInterceptor, HttpHandler, HttpRequest, HttpResponse, HttpErrorResponse } from '@angular/common/http';
import { Observable } from 'rxjs';
import { AuthService } from '../standard/auth.service';

@Injectable()

export class AuthInterceptor implements HttpInterceptor
{
    private _loginToken : string | null = null;

    constructor( private _router: Router,  private authSRV: AuthService )
    {
        this.authSRV.getLoginAuthToken.subscribe( token => this._loginToken = token );
    }

    intercept( $request: HttpRequest<any>, $next: HttpHandler ): Observable<HttpEvent<any>>
    {
        if( this._loginToken )
        {
            $request = $request.clone({
                setHeaders: {
                    "Auth-Login-Token": this._loginToken
                }
            }); 
        }

        return $next.handle($request);
    }
}