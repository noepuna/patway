import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute, NavigationEnd } from '@angular/router';
import { AppSettings } from '../../standard/app-settings';
import { filter, mergeMap } from 'rxjs/operators';





@Component({
  selector: 'app-nav-header',
  templateUrl: './nav-header.component.html',
  styleUrls: ['./nav-header.component.scss']
})
export class NavHeaderComponent implements OnInit
{
	public pageTypes : any = {};

	constructor( private _router : Router, private _route : ActivatedRoute )
	{
		this._handlePageSettings();

		this.pageTypes = AppSettings.pageType;
	}





	ngOnInit(): void
	{

	}





	public routeData : any;

	private _handlePageSettings()
	{
		let filterEvt = ( event : any ) =>
		{
			return event instanceof NavigationEnd
		};
		
		let mergeMapEvt = ( nav : any ) =>
		{
			let route : any = this._route;
			let data = route.data;

			while( route.firstChild )
			{
				route = route.firstChild;

				//set data property as settings if present in a route
				for( let prop in route.data['value'] )
				{
					data = route.data;
					break;
				}
			}

			return data;
		}

		this.routeData = this._router.events .pipe(filter(filterEvt)) .pipe( mergeMap(mergeMapEvt) );
	}
}
