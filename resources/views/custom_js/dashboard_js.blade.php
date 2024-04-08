<script>
document.addEventListener("DOMContentLoaded", function () {

    'use strict'

    var ticksStyle = {
        fontColor: '#495057',
        fontStyle: 'bold'
    }

    var mode = 'index'
    var intersect = true
    
    var $monthlyCount = $('#monthly-count')
    var monthlyCount = new Chart($monthlyCount, {
        type: 'bar',
        data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
        datasets: [
            {
            backgroundColor: '#007bff',
            borderColor: '#007bff',
            data: [12, 15, 8, 10, 21, 28]
            },
            {
            backgroundColor: '#ced4da',
            borderColor: '#ced4da',
            data: [4, 10, 12, 5, 8, 20]
            }
        ]
        },
        options: {
        maintainAspectRatio: false,
        tooltips: {
            mode: mode,
            intersect: intersect
        },
        hover: {
            mode: mode,
            intersect: intersect
        },
        legend: {
            display: false
        },
        scales: {
            yAxes: [{
            // display: false,
            gridLines: {
                display: true,
                lineWidth: '4px',
                color: 'rgba(0, 0, 0, .2)',
                zeroLineColor: 'transparent'
            },
            ticks: $.extend({
                beginAtZero: true,

                
            }, ticksStyle)
            }],
            xAxes: [{
            display: true,
            gridLines: {
                display: false
            },
            ticks: ticksStyle
            }]
        }
        }
    })
});


document.addEventListener("DOMContentLoaded", function () {

    'use strict'

    var ticksStyle = {
        fontColor: '#495057',
        fontStyle: 'bold'
    }

    var mode = 'index'
    var intersect = true


    var $categoryCount = $('#category-count')
    var categoryCount = new Chart($categoryCount, {
        type: 'bar',
        data: {
        labels: ["Counseling", "Kamustahan", "Mentoring", "Performance Mentoring"],
        datasets: [
            {
            backgroundColor: '#007bff',
            borderColor: '#007bff',
            data:  [3, 20, 24, 18],
            }
        ]
        },
        options: {
        maintainAspectRatio: false,
        tooltips: {
            mode: mode,
            intersect: intersect
        },
        hover: {
            mode: mode,
            intersect: intersect
        },
        legend: {
            display: false
        },
        scales: {
            yAxes: [{
            // display: false,
            gridLines: {
                display: true,
                lineWidth: '4px',
                color: 'rgba(0, 0, 0, .2)',
                zeroLineColor: 'transparent'
            },
            ticks: $.extend({
                beginAtZero: true,

                
            }, ticksStyle)
            }],
            xAxes: [{
            display: true,
            gridLines: {
                display: false
            },
            ticks: ticksStyle
            }]
        }
        }
    })

});


</script>