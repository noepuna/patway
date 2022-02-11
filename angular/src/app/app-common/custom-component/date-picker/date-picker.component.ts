import { Component, OnInit, AfterViewInit, Input,/* Output, EventEmitter,*/ forwardRef, /*Renderer2,*/ ElementRef, ViewChild, /*QueryList*/ } from '@angular/core';
import { ControlValueAccessor, NG_VALUE_ACCESSOR } from '@angular/forms';

import * as moment from 'Moment';

declare var $ : any;





@Component({
  selector: 'app-date-picker',
  templateUrl: './date-picker.component.html',
  styleUrls: ['./date-picker.component.scss'],
	providers:
	[
		{
			provide: NG_VALUE_ACCESSOR,
			useExisting: forwardRef(() => DatePickerComponent),
			multi: true
		}
	]
})
export class DatePickerComponent implements OnInit, ControlValueAccessor, AfterViewInit
{
	@ViewChild('datepicker') datePicker ?: ElementRef;

	constructor() { }





	private _datepickerSettings =
	{
		inline : true,
		dateFormat: "mm/dd/yy",
		onSelect : (e : any) =>
		{
			let startTimeSettings = { hour:0, minute:0, second:0, millisecond:0 };

			this._updateValue( moment(e, "MM/DD/YYYY").set(startTimeSettings).unix() );
		}
	};

	ngOnInit(): void
	{
		//		
	}





	ngAfterViewInit() : void
	{
		$( this.datePicker?.nativeElement ).datepicker(this._datepickerSettings);
	}





	@Input() useDefault : boolean = false;

	public writeValue( value: any )
	{
		let dateStr = value ? moment.unix(value).format("MM/DD/YYYY") : null;

		if( value === null || value === undefined )
		{
			if( this.useDefault )
			{
				let now = moment();

				dateStr = now.format("MM/DD/YYYY");

				this._updateValue( now.unix() );
			}
			else
			{
				dateStr = null;
			}
		}
		else
		{
			dateStr = moment.unix(value).format("MM/DD/YYYY");
		}

		$(this.datePicker?.nativeElement).datepicker("setDate", dateStr);
	}

	public propagateChange = (_: any) => {};

	public registerOnChange(fn : () => any )
	{
		this.propagateChange = fn;
	}

	public registerOnTouched() {}





	private _value : any;

	private _updateValue( value ?: string | number )
	{
		if( value )
		{
			this._value = value;
		}
		else
		{

		}

		this.propagateChange(value);
	}
}
