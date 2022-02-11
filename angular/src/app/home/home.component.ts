import { Component, OnInit } from '@angular/core';

declare var Chart : any;

@Component({
  selector: 'app-home',
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.scss']
})
export class HomeComponent implements OnInit
{ 
	ngOnInit(): void
	{
    var ctx = document.getElementById('pieChart');
    // pie chart
    var myChart = new Chart(ctx, {
      type: 'pie',
      data: {
          labels: ['Progress','Total'],
          datasets: [{
              axis: 'y',
              label: 'Data',
              data: [34, 66],
              backgroundColor: [ 
                'rgba(255, 99, 132, 0.2)',
                'rgba(153, 102, 255, 0.2)',
              ],
              borderColor: [ 
                'rgba(255, 99, 132, 1)',
                'rgba(153, 102, 255, 1)',
              ],
              borderWidth: 1
          }]
      },
      options: {
          scales: {
              y: {
                  beginAtZero: true,
              }
          },
          responsive: true,
          legend: {
              position: 'bottom',
          },
          title: {
              display: true,
              text: 'OVERALL PROGRESS',
              fontSize: 12
          },
          animation: {
              animateScale: true,
              animateRotate: true
          }
      }
    });

    // bar chart
    var ctx = document.getElementById('barChart');
    var myChart = new Chart(ctx, {
      type: 'bar',
      data: {
          labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
          datasets: [{
              axis: 'y',
              label: 'Bar Report',
              data: [12, 19, 23, 7, 14, 18],
              backgroundColor: [
                  'rgba(255, 99, 132, 0.2)',
                  'rgba(54, 162, 235, 0.2)',
                  'rgba(255, 206, 86, 0.2)',
                  'rgba(75, 192, 192, 0.2)',
                  'rgba(153, 102, 255, 0.2)',
                  'rgba(255, 159, 64, 0.2)'
              ],
              borderColor: [
                  'rgba(255, 99, 132, 1)',
                  'rgba(54, 162, 235, 1)',
                  'rgba(255, 206, 86, 1)',
                  'rgba(75, 192, 192, 1)',
                  'rgba(153, 102, 255, 1)',
                  'rgba(255, 159, 64, 1)'
              ],
              borderWidth: 1
          }]
      },
      options: {
          scales: {
              y: {
                  beginAtZero: true,
              }
          }
      }
    });

    // line chart
    var ctx = document.getElementById('lineChart');
    var myChart = new Chart(ctx, {
      type: 'line',
      data: {
          labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
          datasets: [{
              axis: 'y',
              label: 'Line Report',
              data: [12, 19, 3, 5, 14, 3],
              backgroundColor: [
                'rgba(75, 192, 192, 0.2)',
              ],
              borderColor: [
                'rgba(75, 192, 192, 1)',
              ],
              borderWidth: 1
          }]
      },
      options: {
          scales: {
              y: {
                  beginAtZero: true,
              }
          }
      }
    });

	}
}
