(function(Views, Models, Collections, $) {
    $(document).ready(function() {
        var ctx = document.getElementById("dashboard-chart").getContext("2d");
        // This will get the first returned node in the jQuery collection.
        var data = {
            labels: ae_globals.date_range,
            datasets: [
                {
                    label: "",
                    fillColor: ae_globals.primary_chart_color,
                    strokeColor: ae_globals.primary_color,
                    pointColor: "#fff",
                    pointStrokeColor: ae_globals.primary_color,
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: ae_globals.primary_color,
                    data: ae_globals.data_chart
                }
            ]
        };

        Chart.defaults.global.animationSteps = 50;
        Chart.defaults.global.tooltipCornerRadius = 3;
        Chart.defaults.global.tooltipTitleFontStyle = "normal";
        Chart.defaults.global.animationEasing = "easeOutBounce";
        Chart.defaults.global.responsive = true;

        var options = {

            ///Boolean - Whether grid lines are shown across the chart
            scaleShowGridLines : true,

            //String - Colour of the grid lines
            scaleGridLineColor : "rgba(0,0,0,.05)",

            //Number - Width of the grid lines
            scaleGridLineWidth : 1,

            //Boolean - Whether to show horizontal lines (except X axis)
            scaleShowHorizontalLines: true,

            //Boolean - Whether to show vertical lines (except Y axis)
            scaleShowVerticalLines: true,

            //Boolean - Whether the line is curved between points
            bezierCurve : true,

            //Number - Tension of the bezier curve between points
            bezierCurveTension : 0.4,

            //Boolean - Whether to show a dot for each point
            pointDot : true,

            //Number - Radius of each point dot in pixels
            pointDotRadius : 6,

            //Number - Pixel width of point dot stroke
            pointDotStrokeWidth : 2,

            //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
            pointHitDetectionRadius : 20,

            //Boolean - Whether to show a stroke for datasets
            datasetStroke : true,

            //Number - Pixel width of dataset stroke
            datasetStrokeWidth : 3,

            //Boolean - Whether to fill the dataset with a colour
            datasetFill : true,

            //String - A legend template
            legendTemplate : "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].strokeColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>"

        };

        var myNewChart = new Chart(ctx).Line(data, options);

        // update height
        var setMaxHeight = function() {
          if($('body').width() > 991) {
            var max = 0;
            $('.dashboard .information-items-detail').each(function() {
              if($(this).height() > max) {
                max = $(this).height();
              }
            });
            $('.dashboard .information-items-detail').height(max);
          }
        }
        setMaxHeight();
        $(window).resize(function() {
          setMaxHeight();
        });
    });
})(window.AE.Views, window.AE.Models, window.AE.Collections, jQuery);