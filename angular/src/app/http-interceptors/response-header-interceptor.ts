import { Injectable } from '@angular/core';
import { Router } from '@angular/router';
import { HttpEvent, HttpInterceptor, HttpHandler, HttpHeaders, HttpRequest, HttpResponse, HttpErrorResponse } from '@angular/common/http';
import { AuthService } from '../standard/auth.service';

import { Observable } from 'rxjs';
import { map, tap } from 'rxjs/operators';





@Injectable()

export class ResponseHeaderInterceptor implements HttpInterceptor
{
    private _loginToken : string | null = null;

    constructor( private _router: Router,  private $_authSRV: AuthService )
    {
        this.$_authSRV.getLoginAuthToken.subscribe( token => this._loginToken = token );
    }

    intercept( $request: HttpRequest<any>, next: HttpHandler ): Observable<HttpEvent<any>>
    {
        //
        // if auth token is present, include it in every api request
        //
        if( this._loginToken )
        {
            $request = $request.clone({ headers : $request.headers.set("Auth-Login-Token", this._loginToken) });
        }

        let mapFn = (event: HttpEvent<any>) =>
        {
            if (event instanceof HttpResponse)
            {
                let response = event.clone();

                response.body['xxx'] = "xxxx";
                return response;
            }

            return event;            
        }

        let doTryFn = (event: HttpEvent<any>) =>
        {
            //console.log('do');
        }

        let doCatchFn = (error: any) =>
        {
            if( error instanceof HttpErrorResponse )
            {
                switch( error.status )
                {
                    case 401:
                        console.log('The authentication session has expired or the user is not authorised. Redirecting to login page.');
                        this._router.navigate(['/login']);
                    break;

                    case 403:
                        this._router.navigate(['403.html'], { skipLocationChange: true });
                    break;

                    case 404:
                        this._router.navigate(['404.html']);
                    break;

                    default:
                        throw "unexpected Http Response Status found, " + error.status;
                }
            }
        }

        return next.handle($request).pipe( map(mapFn) ).pipe( tap(doTryFn, doCatchFn) );
    }
}