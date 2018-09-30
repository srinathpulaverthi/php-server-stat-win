<?php
exec( 'systeminfo', $output );

foreach ( $output as $value ) {
    if ( preg_match( '|Available Physical Memory\:([^$]+)|', $value, $m ) ) {
        $memory = trim( $m[1] );
}
}

exec( 'systeminfo', $out );

foreach ( $out as $val ) {
    if ( preg_match( '|Total Physical Memory\:([^$]+)|', $val, $n ) ) {
        $memtotal = trim( $n[1] );
}
}

$memused=$memtotal-$memory;

$percent=(float)((float)$memory/(float)$memtotal)*100;
$used=100-$percent;
$free=$percent;

exec('wmic cpu get loadpercentage',$outcpu);
$incpu=100-$outcpu[1];
?>


<html>
  <head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChart);
      google.charts.setOnLoadCallback(drawChart2);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Memory', 'Percentage'],
          ['Free Memory', <?php echo $free;?>],
          ['Used Memory', <?php echo $used;?>     ],
        ]);

        var options = {
          title: 'Live Ram Usage',
          pieHole: 0.4,
        };

        var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
        chart.draw(data, options);
      }
    
      function drawChart2() {
        var data = google.visualization.arrayToDataTable([
          ['CPU Usage', 'Percentage'],
          ['CPU Usage Current', <?php echo $outcpu[1];?>],
          ['CPU left', <?php echo $incpu;?>     ],
        ]);

        var options = {
          title: 'Live CPU Usage',
          pieHole: 0.6,
        };

        var chart = new google.visualization.PieChart(document.getElementById('donutchart2'));
        chart.draw(data, options);
      }
    

    </script>
  </head>
  <body>
  <div id="container1" style="width:100%">
    <div id="donutchart" style = "float: left; width: 40%;height: 60%"></div>
    <div id="donutchart2" style = "float: right; width: 40%; height:60%"></div>
</div>
    <div style="width:100%">
    <h3> <?php echo ' Free memory = '.$memory; ?> </h3>
    <h3> <?php echo ' Total memory = '.$memtotal; ?> </h3>
    <h3> <?php echo ' CPU Usage in % = '.$outcpu[1]; ?> </h3>
    </div>
  </body>
</html>
