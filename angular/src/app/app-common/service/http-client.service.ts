import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders, HttpParams } from '@angular/common/http';
import { Observable, from } from 'rxjs';
import { mergeMap } from 'rxjs/operators';

@Injectable()
export class HttpClientService
{
	constructor( private _http : HttpClient ){}

	public post( $url : string, $body : {}, $options ?: { headers ?: {[key:string]: string}, withCredentials ?: boolean } )
	{
		let httpOptions;

		if( $options )
		{
			let headers = $options.headers;

			if( headers && "Content-Type" in headers )
			{
				switch( headers["Content-Type"] )
				{
					case "application/json":
						$body = JSON.stringify($body);
					break;

					case "application/x-www-form-urlencoded":
						$body = this._convertToHttpParams($body);
					break;
				}
			}

			httpOptions =
			{
				headers : new HttpHeaders($options.headers),
				withCredentials : !!$options.withCredentials
			};
		}

		return this._http.post($url, $body, httpOptions).toPromise();
	}

	private _convertToHttpParams( $data : any )
	{
		let param = new HttpParams;

		let convertFn = ( data : any[], $dimension ?: string ) =>
		{
			for( let key in data )
			{
				let currentDimension = $dimension ? $dimension+"["+key+"]" : key;
				let entry = data[key];

				switch( typeof entry )
				{
					case "object":
						convertFn(entry, currentDimension);
					break;

					case "undefined":
						// noop
					break;

					case "number":
						param = param.append( currentDimension, entry.toString() );
					break;

					default:
						entry = entry.replace("+", "%2B")
						param = param.append( currentDimension, entry );
				}
			}
		}

		convertFn($data);

		return param;
	}

	public fetchAll( $url : string, $body : {}, $options ?: { headers ?: {[key:string]: string}, withCredentials ?: boolean } )
	{
		let records : any[] = [];

		let pageTokenHandlerFn : ( res : any ) => Promise<any> = ( response ) =>
		{
			let result = response.result;

			if( "data" in result && result.data.length )
			{
				for( let category of response.result.data )
				{
					records.push(category);
				}
			}

			if( "pagetoken" in result && result.data && result.data.length )
			{
				let body = JSON.parse(JSON.stringify($body));

				if( false === "param" in body )
				{
					body['param'] = {};
				}

				body['param']['pagetoken'] = result.pagetoken;

				return this.post($url, body, $options).then(pageTokenHandlerFn);
			}

			return new Promise( resolveFn => { resolveFn(records) } );
		}

		return from( this.post($url, $body, $options) ).pipe( mergeMap(pageTokenHandlerFn) ).toPromise();
	}

	public upload( $url : string, $body : {[key:string]: any}, $options ?: { headers ?: {[key:string]: string}, withCredentials ?: boolean } )
	{
    	$body = this._convertToFormData($body);

		return this._http.post($url, $body, $options).toPromise();
		//return this._http.post($url, $body, { reportProgress: true, observe: 'events' }).toPromise();

				//'Content-Type': multipart/form-data
		/*return this.httpClient.post<any>(uploadURL, data, {
		      reportProgress: true,
		      observe: 'events'
		    })/.pipe(map((event) => {

		      switch (event.type) {

		        case HttpEventType.UploadProgress:
		          const progress = Math.round(100 * event.loaded / event.total);
		          return { status: 'progress', message: progress };

		        case HttpEventType.Response:
		          return event.body;
		        default:
		          return `Unhandled event: ${event.type}`;
		      }
		    });*/
	}

	private _convertToFormData( $data : any )
	{
		let param = new FormData;

		let convertFn = ( data : any[], $dimension ?: string ) =>
		{
			for( let key in data )
			{
				let currentDimension = $dimension ? $dimension+"["+key+"]" : key;
				let entry = data[key];

				switch( typeof entry )
				{
					case "object":
						if( entry instanceof File )
						{
							param.append( currentDimension, entry );
						}
						else
						{
							convertFn(entry, currentDimension);
						}
					break;

					case "undefined":
						// noop
					break;

					case "number":
						param.append( currentDimension, entry.toString() );
					break;

					default:
						entry = entry.replace("+", "%2B")
						param.append( currentDimension, entry );
				}
			}
		}

		convertFn($data);

		return param;
	}
}

type API_PAGINATION_FILTER =
{
	'name' : string,
	'value' ?: string | number,
	'arithmetic' : "=" | "<" | ">" | "CONTAINS" | "STARTS WITH",
	'logic' : "AND" | "OR"
}

type API_PAGINATION_METADATA =
{
	'param' :
	{
		'limit' ?: number,
		'pagetoken' ?: string,
		'filters' ?: Array<API_PAGINATION_FILTER>,
		[key:string] : any
	},

	[key:string] : any
};

export { API_PAGINATION_METADATA }