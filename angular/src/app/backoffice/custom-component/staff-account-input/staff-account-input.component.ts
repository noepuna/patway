import { Component, OnInit, AfterViewInit, Input, Output, EventEmitter, forwardRef, Renderer2, ElementRef, ViewChild, QueryList } from '@angular/core';
import { ControlValueAccessor, NG_VALUE_ACCESSOR } from '@angular/forms';

import { iStaff } from '../../staff/i-staff';
import { StaffService } from '../../staff/staff.service';





@Component({
	selector: 'app-staff-account-input',
	templateUrl: './staff-account-input.component.html',
	styleUrls: ['./staff-account-input.component.scss'],
	providers:
	[
		{ 
			provide: NG_VALUE_ACCESSOR,
			useExisting: forwardRef(() => StaffAccountInputComponent),
			multi: true
		}
	]
})

export class StaffAccountInputComponent implements OnInit, ControlValueAccessor, AfterViewInit
{
	@Input("keyword") keyword ?: string;
	//@Input("inputClass") inputClass : string;
	//@Output() onSelect = new EventEmitter();
	//@ViewChild('searchBox') searchBox ?: ElementRef;
	@ViewChild('menu') menuEl ?: ElementRef;
	@ViewChild('content') contentEl ?: ElementRef;

	constructor( private $_el : ElementRef, private $_renderer : Renderer2, private $_staffSRV : StaffService ) { }





	public isMultiple = false;

	ngOnInit(): void
	{
		this.isMultiple = null !== this.$_el.nativeElement.getAttribute("multiple");
	}





	public searchBox : any;

	ngAfterViewInit() : void
	{
		let DOMSearch = this?.contentEl?.nativeElement.querySelectorAll("input");

		if( DOMSearch.length )
		{
			this.searchBox = DOMSearch[0];
			this.$_renderer.addClass( this.searchBox, "btn" );
			//this.$_renderer.addClass( this.searchBox, "btn-secondary" );
			this.$_renderer.addClass( this.searchBox, "dropdown-toggle" );
			this.$_renderer.listen( this.searchBox, "keydown", () => this.search(this.searchBox.value) );
		}
	}





	public writeValue( value: any )
	{
		switch( value )
		{
			case null:
			case undefined:
				this.staffCollection = [];
				this._updateValue();

				this.searchBox.value = "";
			break

			default:
				if( value?.length )
				{
					this.staffCollection = value;
				}
				else
				{
					this.searchBox.value = ( value?.firstname ?? 1 ) + ( value?.lastname ? " " + value?.lastname : "" );
					this.staffCollection = [ value ];
				}

				this._updateValue();
		}
	}

	public propagateChange = (_: any) => { console.log('propagateChange called') };

	public registerOnChange(fn : () => any )
	{
		this.propagateChange = fn;
	}

	public registerOnTouched() {}





	public menuHidden : boolean = true;





	public searchResult : iStaff[] = [];

	public search( $keyword : string )
	{
		let searchHdl = ( response : any ) =>
		{
			let result = response?.result?.data;

			if( result?.length )
			{
				this.searchResult = [];

				for(let data of result)
				{
					let iStaff : iStaff =
					{
						id: data?.id,
						firstname: data?.firstname,
						lastname: data?.lastname,
						email: data?.email,
						deleted : false
					};

					this.searchResult.push(iStaff);
				}

				this.showResult(this.searchResult);
			}
		}

		this.$_staffSRV.searchCoworkers({}).then(searchHdl);
	}





	public showResult( $kAccounts : any[] )
	{
		this.menuHidden = false;
		this.searchResult = $kAccounts;
	}





	public staffCollection : iStaff[] = [];

	public selectStaff( $iStaff : iStaff )
	{
		this.menuHidden = true;

		if( $iStaff.id )
		{
			this.searchBox.value = $iStaff?.firstname + " " + $iStaff?.lastname;
		}

		//
		// check if staff is already added
		//
		for( let existingStaff of this.staffCollection )
		{
			if( (existingStaff.id == $iStaff.id) && !existingStaff.deleted  )
			{
				return;
			}
		}

		//
		// if is not multiple, remove the previous value
		// to keep only a single value in the staff collection
		//
		!this.isMultiple && this.staffCollection.pop();

		this.staffCollection.push($iStaff);
		this._updateValue();
	}





	public removeStaff( id : string )
	{
		for( let staff of this.staffCollection )
		{
			if( staff.id == id )
			{
				staff.deleted = true;
			}
		}

		this._updateValue();
	}





	public selectedStaff : iStaff[] = [];

	private _updateValue()
	{
		this.selectedStaff = this.staffCollection.filter( iStaff => !iStaff?.deleted );

		this.propagateChange( this.isMultiple ? this.staffCollection : this.staffCollection[0] );
	}
}
