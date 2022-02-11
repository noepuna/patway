import { Component, OnInit, OnChanges, AfterViewInit, Input, SimpleChanges, ViewChild, ElementRef } from '@angular/core';
import { BehaviorBaseSafetyService } from '../../behavior-base-safety.service';
import { PropertyService } from '../../property/property.service';
import { iSearchRequest, iFilter } from '../../../api-patway';
import * as moment from 'Moment';

declare var Chart : any;





@Component({
  selector: 'app-property-usage-chart',
  templateUrl: './property-usage-chart.component.html',
  styleUrls: ['./property-usage-chart.component.scss']
})
export class PropertyUsageChartComponent implements OnInit, OnChanges//, AfterViewInit
{
	@Input('categoryId') categoryId ?: string;

	constructor( private $_propertySRV : PropertyService, private $_bbsObservationSRV : BehaviorBaseSafetyService ) { }





	public categoryName ?: string;

	ngOnInit(): void
	{
		this._buildFilterByDateValueOptions();

		//
		// get category information
		//
        let detailsEvt = ( response : any ) =>
        {
            response.find( ( cat : any ) => ( cat.id === this.categoryId ) && ( this.categoryName = cat.label ) );

            return response;
        }

        this.$_propertySRV.searchAssortByCat().then( detailsEvt );

		//
		// initial search
		//
		let categoryFilter : iFilter =
		{
            name : "category_id",
            value : this.categoryId,
            arithmetic_operator : "=",
            logic_operator : "AND"
		};

		this._addFilter(categoryFilter);
		this.filterByDate();
	}





	ngAfterViewInit()
	{
		this._renderChart();
	}





	ngOnChanges( $changes : SimpleChanges )
	{
		//
	}





	public propertyUsageXHR : any;
	public payload : iSearchRequest = { param : { filter : [] } };

    private _getPropertyUsage() : Promise<any>
    {
        let responseCallback = ( response : any ) =>
        {
            let update : any =
            {
            	type : this._chartSettings.type,
            	data: { labels: [], datasets : [] },
            	options: this._chartSettings.options
            };

            let data;

            if( data = response?.result?.data )
            {
                let labels : any = [];

                for( let prop of data )
                {
                    labels.push( prop?.name );
                }

                update.data.labels = labels;
                update.data.datasets = this._mapDataset(data);
            }

            this._chartSettings = update;

            this._renderChart();
        }

        return this.propertyUsageXHR = this.$_bbsObservationSRV.searchPropertyUsage(this.payload).then(responseCallback);
    }

    private _mapDataset( data : any )
    {
        let datasets : any =
        [
            {
                label: 'Safe',
                data: [],
                backgroundColor: [ /*'rgba(54, 162, 235, 0.2)'*/ ],
                borderColor: [ /*'rgba(54, 162, 235, 1)'*/ ],
                borderWidth: 1
            },
            {
                label: 'Unsafe',
                data: [],
                backgroundColor: [ /*'rgba(255, 99, 132, 0.2)' */ ],
                borderColor: [ /*'rgba(255, 99, 132, 1)'*/ ],
                borderWidth: 1
            }
        ];

        for( let entry of data )
        {
            datasets[0].data.push(entry?.values?.safe ?? 0);
            datasets[0].backgroundColor.push("rgba(54, 162, 235, 0.2)");
            datasets[0].borderColor.push("rgba(54, 162, 235, 1)");

            datasets[1].data.push(entry?.values?.unsafe ?? 0);
            datasets[1].backgroundColor.push("rgba(255, 99, 132, 0.2)");
            datasets[1].borderColor.push("rgba(255, 99, 132, 1)");
        }

        return datasets;
    }

    private _addFilter( $filter : iFilter )
    {
    	this.payload.param?.filter?.push($filter);
    }

    private _removeFilter( $name : string )
    {
    	if( this.payload?.param?.filter )
    	{
			this.payload.param.filter = this.payload?.param?.filter?.filter( ( filter : any ) => filter?.name != $name );
    	}
    }





	private _chartSettings : any =
	{
		type: 'bar',
		data:
		{
            labels: ['msds if needed', 'Lock out', 'Tools are safe', 'Adjacent work', 'Signage if needed', 'Spill control'],
            /*datasets:
            [
                {
                    label: 'Safe',
                    data: [61, 27, 56, 25, 61, 38],
                    backgroundColor:
                    [
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(54, 162, 235, 0.2)'
                    ],
                    borderColor:
                    [
                        'rgba(54, 162, 235, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(54, 162, 235, 1)'
                    ],
                    borderWidth: 1
                },
                { 
                    label: 'Unsafe',
                    data: [ 7, 2, 16, 21, 22, 19 ],
                    backgroundColor:
                    [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(255, 99, 132, 0.2)'
                    ],
                    borderColor:
                    [
                        'rgba(255, 99, 132, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(255, 99, 132, 1)'
                    ],
                    borderWidth: 1
                }
            ]*/
		},
		options:
		{
		    scales: {
		        yAxes: [{
		            ticks: {
		                beginAtZero: true,
                        callback: function(value: any) {if (value % 1 === 0) {return value;}}
		            }
		        }]
		    }
		},
	}

	@ViewChild('chart') chart ?: ElementRef;

	private _iChart : any;

    private _renderChart()
    {
    	this._iChart && this._iChart.destroy();
    	this._iChart = new Chart(this.chart?.nativeElement, this._chartSettings);
    }





    public filterByDateType : string = '1';
    public filterByDateValue : string | number | null = null;

    public filterByDate()
    {
    	let startDate = moment();
    	let endDate = moment();
    	let offset : number;

    	switch( this.filterByDateType )
    	{
    		case '1':
    			startDate.startOf("month");
    			endDate.endOf("month");

    			this.filterByDateValue = this.filterByDateValue ?? parseInt(startDate.format("M"));

    			//
    			// apply zero index in moments set method for month
    			//
				startDate.set("month", <number>this.filterByDateValue - 1);
				endDate.set("month", <number>this.filterByDateValue - 1);
			break;

			case '2':
				startDate.startOf("quarter");
				endDate.endOf("quarter");

				this.filterByDateValue = this.filterByDateValue ?? startDate.format("Q");

				startDate.set("quarter", <number>this.filterByDateValue);
				endDate.set("quarter", <number>this.filterByDateValue);
			break;

			case '3':
				startDate.startOf("year");
				endDate.endOf("year");

				this.filterByDateValue = this.filterByDateValue ?? startDate.format("Y");

				startDate.set("year", <number>this.filterByDateValue);
				endDate.set("year", <number>this.filterByDateValue);
			break;

    		default:
				// code...
			break;
    	}

    	let dateMinFilter : iFilter =
        {
            name : "date_created",
            value : startDate.set({ hour:0, minute:0, second:0, millisecond:0 }).unix(),
            arithmetic_operator : ">=",
            logic_operator : "AND"
        };

        let dateMaxFilter : iFilter =
        {
            name : "date_created",
            value : endDate.set({ hour:23, minute:59, second:0, milliseconds:0 }).unix(),
            arithmetic_operator : "<=",
            logic_operator : "AND"
        };

        this._removeFilter("date_created");
		this._addFilter(dateMinFilter);
        this._addFilter(dateMaxFilter);
        this._getPropertyUsage();
        this._initFilterDisplayInfo();
    }





    public filterDisplayInfo = "";

    private _initFilterDisplayInfo()
    {
    	let findEvt = ( data : any ) =>
    	{
    		return data.value === this.filterByDateValue?.toString()
    	}

    	let selectedValue = this.moreOptions[this.filterByDateType].find( findEvt );

    	this.filterDisplayInfo = selectedValue?.name;
    }





    public moreOptions : any =
    {
    	1 : [],
    	2 : [],
    	3 : [ { name : "2020", value : 2020 }, { name : "2021", value : 2021 } ]
    };

    private _buildFilterByDateValueOptions()
    {
    	//
    	// month
    	//
    	let monthEvt = ( name : string ) =>
    	{
			return { value : moment().month(name).format("M"), name : name };
    	}

    	this.moreOptions[1] = moment.months().map( monthEvt );

    	//
    	// quarter
    	//
    	let quarterEvt = ( num : number ) =>
    	{
    		return { value : num, name : moment().quarter(num).format("Qo") + " Quarter" };
    	}

    	this.moreOptions[2] = [1, 2, 3, 4].map(quarterEvt);
    }
}
