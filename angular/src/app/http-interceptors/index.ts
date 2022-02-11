/* "Barrel" of Http Interceptors */
import { HTTP_INTERCEPTORS } from '@angular/common/http';
import { ResponseHeaderInterceptor } from './response-header-interceptor';
import { AuthInterceptor } from './auth-interceptor';


/** Http interceptor providers in outside-in order */
export const httpInterceptorProviders =
[
  { provide: HTTP_INTERCEPTORS, useClass: ResponseHeaderInterceptor, multi: true },
  { provide: HTTP_INTERCEPTORS, useClass: AuthInterceptor, multi: true }
];