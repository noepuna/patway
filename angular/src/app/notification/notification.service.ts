import { Injectable } from '@angular/core';
import { Observable, Subject } from 'rxjs';
import { map } from 'rxjs/operators';

import { environment } from '../../environments/environment'
import { HttpClientService } from '../app-common/service/http-client.service';
import { iNotification } from './i-notification';





@Injectable({
  providedIn: 'root'
})
export class NotificationService
{
	public readonly _API_BASE_URL : string = environment._API_URL_NOTIFICATION;

	private _recentNotifPayload =
	{

	};

	constructor( private $_http: HttpClientService )
	{
		this.fetchRecent({ param: { limit: 100 } });
	}





	private _entries = new Subject<iNotification[]>();

	get getEntries() : Observable<iNotification[]>
	{
		return this._entries.asObservable();
	}





	public fetchRecent( $payload : any )
	{
		let broadcastEvt = ( response : any ) =>
		{
			let data = response?.result?.data;
			let kNotifications : iNotification[] = [];

			if( data && data?.length )
			{
				for(let notification of data)
				{
					let iNotification : iNotification =
					{
						id : notification?.id,
						appComponent : notification?.app_component,
						payload : notification?.payload,
						createdBy :
						{
							id : notification?.created_by?.id,
							name : notification?.created_by?.name
						},
						dateCreated : notification.date_created
					};

					kNotifications.push(iNotification);
				}
			}

			this._entries.next(kNotifications);

			return response;
		}

		let getNextResultsEvt = ( response : any ) =>
		{
			let nextPageToken;

			if( nextPageToken = response?.result?.pagetoken )
			{
				let nextPagePayload =
				{
					param : { pagetoken: nextPageToken }
				};

				setTimeout( () => this.fetchRecent(nextPagePayload), 5000 );
			}

			return response;
		}

		return this.search($payload).then(broadcastEvt).then(getNextResultsEvt);
	}





	public search( $payload : {} )
	{
		let httpOptions = 
		{
			headers :
			{
				'Content-Type': "application/x-www-form-urlencoded"
			},
			withCredentials : true
		};

		return this.$_http.post( this._API_BASE_URL + "search.php", $payload, httpOptions );
	}
}