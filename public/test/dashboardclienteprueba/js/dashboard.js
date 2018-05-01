/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function (){
    $( "#iniDatepicker" ).datepicker({
        regional: "mx",
        dateFormat: 'dd/M/yy'
    });
    $( "#endDatepicker" ).datepicker({
        regional: "mx",
        dateFormat: 'dd/M/yy'
    });
    $( "#iniDatepickerB" ).datepicker({
        regional: "mx",
        dateFormat: 'dd/M/yy'
    });
    $( "#endDatepickerB" ).datepicker({
        regional: "mx",
        dateFormat: 'dd/M/yy'
    });
});

google.charts.load('current', {'packages':['bar']});
google.charts.setOnLoadCallback(drawChart);

function drawChart() {
    var data = google.visualization.arrayToDataTable([
        ['Fecha', 'Copas ', 'Botellas '],
        ['14 Ago 2017', 200, 50],
        ['15 Ago 2017', 150, 40],
        ['16 Ago 2017', 300, 20],
        ['17 Ago 2017', 800, 40]
    ]);

    var options = {
    chart: {
      title: 'Zona Condesa',
      subtitle: ''
    },
    axes: {
        x: {
            0: { side: 'top', label: ''} // Top x-axis.
        },
        y: {
            0: { side: 'right', label: ''}
        }
    },
    legend: { position: "none" },
    bar: {groupWidth: "60%"},
    colors: ['#d8d8d8', '#9fa9ba']
    };

    var chart = new google.charts.Bar(document.getElementById('columnchart_material'));
    chart.draw(data, google.charts.Bar.convertOptions(options));

    var data2 = google.visualization.arrayToDataTable([
        ['Acumulado', 'Copas ', 'Botellas '],
        [' ', 200, 50]
    ]);

    var data3 = google.visualization.arrayToDataTable([
        ['Acumulado', 'Copas ', 'Botellas '],
        [' ', 100, 10]
    ]);

    var data4 = google.visualization.arrayToDataTable([
        ['Acumulado', 'Copas ', 'Botellas '],
        [' ', 50, 5]
    ]);

    var options2 = {
    chart: {
      title: '',
      subtitle: ''
    },
    axes: {
        x: {
            0: { side: 'top', label: ''} // Top x-axis.
        },
        y: {
            0: { side: 'right', label: ''}
        }
    },
    legend: { position: "none" },
    bar: {groupWidth: "20%"},
    colors: ['#d8d8d8', '#9fa9ba']
    };

    var chart2 = new google.charts.Bar(document.getElementById('bartender1'));
    chart2.draw(data2, google.charts.Bar.convertOptions(options2));

    var chart3 = new google.charts.Bar(document.getElementById('bartender2'));
    chart3.draw(data3, google.charts.Bar.convertOptions(options2));

    var chart4 = new google.charts.Bar(document.getElementById('bartender3'));
    chart4.draw(data4, google.charts.Bar.convertOptions(options2));
}            
