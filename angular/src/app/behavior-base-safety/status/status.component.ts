import { Component, OnInit, AfterViewInit, ViewChild, ElementRef } from '@angular/core';
import { BehaviorBaseSafetyService } from '../behavior-base-safety.service';
import { iSearchRequest, iFilter } from '../../api-patway';
import * as moment from 'Moment';

declare var Chart : any;





@Component({
  selector: 'app-bbs-status',
  templateUrl: './status.component.html',
  styleUrls: ['./status.component.scss']
})
export class StatusComponent implements OnInit, AfterViewInit
{
    constructor( private $_bbsObservationSRV : BehaviorBaseSafetyService ) { }





    public payload : iSearchRequest = { param : { filter : [] } };
    private _bbsEntriesCache = [];

    ngOnInit(): void
    {
        let searchEvt = ( response : any ) =>
        {
            let data;

            if( data = response?.result?.data )
            {
                this._renderChart( this._chartSettings, this._bbsEntriesCache = data );
            }
        }

        this.$_bbsObservationSRV.search().then( searchEvt );
    }





    ngAfterViewInit()
    {
        this._renderChart( this._chartSettings, this._bbsEntriesCache );
    }





    private _chartSettings : any =
    {
        type: 'bar',
        data:
        {
            labels: [ 'msds if needed', 'Lock out', 'Tools are safe', 'Adjacent work', 'Signage if needed', 'Spill control' ],
            datasets:
            [
                {
                    label: 'Safe',
                    data: [ /*61, 27, 56, 25*/ ],
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
                    data: [ /*7, 2, 10, 8*/ ],
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
            ]
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

    private _renderChart( $settings : any, $data = [] )
    {
        this._chartSettings.data.labels = this.onChangeLabels();
        this._chartSettings.data.datasets = this._mapDataset($data);

        this._iChart && this._iChart.destroy();
        this._iChart = new Chart(this.chart?.nativeElement, this._chartSettings);
    }

    private _mapDataset( data : any )
    {
        let datasets : any =
        [
            {
                label: 'Safe',
                data: [],
                backgroundColor: [],
                borderColor: [],
                borderWidth: 1
            },
            {
                label: 'Unsafe',
                data: [],
                backgroundColor: [],
                borderColor: [],
                borderWidth: 1
            }
        ];

        let dateRanges : { max: number, min : number }[] = [];
        let startDate = moment().set({ hour:0, minute:0, second:0, millisecond:0 });
        let endDate = moment().set({ hour:0, minute:0, second:0, millisecond:0 });

        switch( +this.selectedRange )
        {
            default:
            case 1:
                for( let monthIndex in [ ...Array(12).keys() ] )
                {
                    startDate
                        .startOf("month")
                        .set("month", parseInt(monthIndex));

                    endDate
                        .endOf("month")
                        .set( "month", parseInt(monthIndex) );

                    dateRanges.push({min: startDate.unix(), max: endDate.unix()});
                }
                break;

            case 2:
                for( let quarterIndex of [ 1, 2, 3, 4 ] )
                {
                    startDate
                        .startOf("quarter")
                        .set("quarter", quarterIndex);

                    endDate
                        .endOf("quarter")
                        .set("quarter", quarterIndex);

                    dateRanges.push({min: startDate.unix(), max: endDate.unix()});
                }
                break;

            case 3:
                for( let yearIndex of this._chartSettings.data.labels )
                {
                    startDate
                        .startOf("year")
                        .set("year", yearIndex);

                    endDate
                        .endOf("year")
                        .set("year", yearIndex);

                    dateRanges.push({min: startDate.unix(), max: endDate.unix()});                    
                }
            break;
        }

        for( let range of dateRanges )
        {
            let deletedEntries = data.filter( (entry : any ) => entry?.deleted && ( entry?.date_created >= range.min && entry?.date_created <= range.max ) );

            datasets[0].data.push( deletedEntries.length );
            datasets[0].backgroundColor.push("rgba(54, 162, 235, 0.2)");
            datasets[0].borderColor.push("rgba(54, 162, 235, 1)");

            let availableEntries = data.filter( (entry : any ) => !entry?.deleted && ( entry?.date_created >= range.min && entry?.date_created <= range.max ) );

            datasets[1].data.push( availableEntries.length );
            datasets[1].backgroundColor.push("rgba(255, 99, 132, 0.2)");
            datasets[1].borderColor.push("rgba(255, 99, 132, 1)");            
        }

        return datasets;
    }





    public selectedRange : number = 1;

    public onChangeLabels()
    {
        let labels : string[] = [];

        switch( +this.selectedRange )
        {
            default:
            case 1:
                labels = moment.months();
                break;

            case 2:
                labels = [1, 2, 3, 4].map( ( num : number ) => moment().quarter(num).format("Qo") + " Quarter" );
                break;

            case 3:
                let currentYear = moment().isoWeekYear();

                for( let i = 0; i < 5; i++ )
                {
                    labels.unshift( String(currentYear--) )
                }
                break;
        }

        return labels;
    }
}
