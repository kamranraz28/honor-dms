@extends('layouts.master_admin')

@section('title')
	{{"Sales Automation Process :: Dashboard"}}
@endsection


@section('content')

<!-- content part================================ -->

    <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- bc part================================ -->
      @include('admin.bc.bc')
    <!-- bc part================================ -->


<style>
    .DTFC_LeftBodyLiner{overflow-y:unset !important}
    .DTFC_RightBodyLiner{overflow-y:unset !important}
</style>




    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-lg-4 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3>{{$data['totalSale']}} </h3>

              <p>Total Sale</p>
            </div>
            <div class="icon">
              <i class="ion ion-pie-graph"></i>
            </div>
          </div>
        </div>

        <!-- ./col -->
        <div class="col-lg-4 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3>{{$data['monthlySale']}}</h3>

              <p>Monthly Sale</p>
            </div>
            <div class="icon">
              <i class="ion ion-pie-graph"></i>
            </div>
          </div>
        </div>

        <!-- ./col -->
        <div class="col-lg-4 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3>{{$data['todaySale']}}</h3>

              <p>Today sales</p>
            </div>
            <div class="icon">
              <i class="ion ion-pie-graph"></i>
            </div>
          </div>
        </div>
        <!-- ./col -->
      </div>
      <!-- /.row -->  


<!-- for table========= -->
{{-- <div class="row">
  <div class="col-md-12">
<table id="example" width="100%">
         

    <thead>
      <tr>
        <!-- <th> # </th> -->
        <th title="Product"> Product</th>
        <th title="Product Category"> PCT </th>
        <th title="Product Brand"> PBR </th>
        <th title="National Distributor Purchase"> NDP </th>
        <th title="National Distributor Sale"> NDSL </th>
        <th title="National Distributor Stock"> NDST </th>
        <th title="Distributor Purchase"> DP </th>
        <th title="Distributor Sale"> DSL </th>
        <th title="Distributor Stock"> DST </th>

        <th title="Tertiary Sale"> TSL </th>
        <th title="Retailer Stock"> RST </th>

        

        <th title="Distributor Daily Sale"> DDSL </th>
        <th title="Distributor Weekly Sale"> DWSL </th>
        <th title="Distributor Monthly Sale"> DMSL </th>
        

        <th title="Retailer Daily Sale"> RDSL </th>
        <th title="Retailer Weekly Sale"> RWSL </th>
        <th title="Retailer Monthly Sale"> RMSL </th>

        <th title="Distributor Day Of Sale"> DDOS </th>
        <th title="Retailer Day Of Sale"> RDOS </th>
        <th title="Product"> Product </th>

      </tr>

    </thead>
    <tbody>

    @foreach ($level1Data as $key=>$element)


      <tr>
        <!-- <td> {{$key + 1}} </td> -->
        <td> {{$element['pdt']}} </td>
        <td> {{$element['cat']}} </td>
        <td> {{$element['brand']}} </td>

        <td> {{$element['ndpr']}} </td>
        <td> {{$element['ndsl']}} </td>
        <td> {{$element['ndst']}} </td>
        <td> {{$element['dpr']}} </td>
        <td> {{$element['dsl']}} </td>
        <td> {{$element['dst']}} </td>
        <td> {{$element['tsl']}} </td>
        <td> {{$element['rst']}} </td>

    

        <td> {{$element['ddlysl']}} </td>
        <td> {{$element['dwsl']}} </td>
        <td> {{$element['dmsl']}} </td>

        <td> {{$element['cdlysl']}} </td>
        <td> {{$element['cwsl']}} </td>
        <td> {{$element['cmsl']}} </td>

        <td> {{$element['ddos']}} </td>
        <td> {{$element['cdos']}} </td>

        <td> {{$element['pdt']}} </td>
      </tr>
    @endforeach
      
    </tbody>
  </table>

  </div>
</div> --}}

<!-- for table========= -->



      <div class="row">
        
        <div class="col-md-6">
          <div id="dayinmonthchartdata" style="height: 300px"></div>
          <p class="text-info" align="center">Current Month Daily Sales Chart</p>
        </div>

        <div class="col-md-6">
          <div id="monthinyearchartdata" style="height: 300px"></div>
          <p class="text-info" align="center">Current Year Monthly Sales Chart</p>
        </div>


        <div class="col-md-6">
          <div id="todaysalebrandwise" style="height: 300px"></div>
          <p class="text-info" align="center">Brand Wise Todays Tertiary Sales Chart</p>

        </div> 

        <div class="col-md-6">
          <div id="monthlysalebrandwise" style="height: 300px"></div>
          <p class="text-info" align="center">Brand Wise Current Month Tertiary Sales Chart</p>
        </div> 


        <div class="col-md-6">
          <div id="topproduct" style="height: 300px"></div>
          <p class="text-info" align="center">Current Month Top Product Chart</p>
        </div>

        <div class="col-md-6">
          <div id="topretailer" style="height: 300px"></div>
          <p class="text-info" align="center">Current Month Top Retailer Chart</p>
        </div>

        <div class="col-md-6">
          <div id="topseconderysale" style="height: 300px"></div>
          <p class="text-info" align="center">Current Month Top Secondery Sale Chart</p>
        </div>

        <div class="col-md-6">
          <div id="topdistributor" style="height: 300px"></div>
          <p class="text-info" align="center">Current Month Top Distributor Chart</p>
        </div>

        <!-- <div class="col-md-12">
          <div id="curve_chart" style="height: 300px"></div>
        </div> -->


        
      </div>
      <!-- /.row -->

    </section>
    <!-- /.content -->
 
  </div>
<!-- /.content-wrapper -->




<!-- code for linechart--------------------- -->


@php
  $length = 6;
  $color_code = substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);

$color_code_array = ['#808000','#00FF00','#008000','#00FFFF','#008080','#0000FF','#000080','#FF00FF','#800080','#FFFF00','#800000','#FF0000','#000000','#808080','#C0C0C0'];

//$color_code_array = ['#808000','#00FF00','#008000','#00FFFF','#008080','#0000FF','#000080','#FF00FF','#800080','#FFFF00','#800000','#FF0000','#000000','#808080','#C0C0C0','#FAEBD7','#00FFFF','#7FFFD4','#F5F5DC','#FFE4C4','#000000','#FFEBCD','#0000FF','#8A2BE2','#A52A2A','#DEB887','#5F9EA0','#7FFF00','#D2691E','#FF7F50','#6495ED','#FFF8DC','#DC143C','#00FFFF','#00008B','#008B8B','#B8860B','#A9A9A9','#006400','#BDB76B','#8B008B','#556B2F','#FF8C00','#9932CC','#8B0000','#E9967A','#8FBC8F','#483D8B','#2F4F4F','#2F4F4F','#00CED1','#9400D3',' #FF1493','#00BFFF','#696969','#1E90FF','#B22222','#228B22','#FF00FF','#DCDCDC','#FFD700','#DAA520','#808080','#008000','#ADFF2F','#FF69B4','#CD5C5C','#4B0082','#F0E68C','#E6E6FA','#FFF0F5','#7CFC00','#FFFACD','#ADD8E6','#F08080','#E0FFFF','#FAFAD2','#D3D3D3','#90EE90','#FFB6C1','#FFA07A','#20B2AA','#87CEFA','#778899','#FFFFE0','#00FF00','#32CD32','#FAF0E6','#FF00FF','#800000','#66CDAA','#0000CD','#BA55D3','#9370DB','#3CB371','#7B68EE','#00FA9A','#48D1CC','#C71585','#191970','#FFE4E1','#FFE4B5','#000080','#FDF5E6','#808000','#6B8E23','#FFA500','#FF4500','#DA70D6','#EEE8AA','#98FB98','#AFEEEE','#DB7093','#FFEFD5','#FFDAB9','#CD853F','#FFC0CB','#DDA0DD','#B0E0E6','#800080','#663399','#FF0000','#BC8F8F','#4169E1','#8B4513','#FA8072','#F4A460','#2E8B57','#A0522D','#C0C0C0','#87CEEB','#6A5ACD','#708090','#708090','#FFFAFA','#00FF7F','#4682B4','#D2B48C','#008080','#D8BFD8','#FF6347','#40E0D0','#EE82EE','#F5DEB3','#FFFFFF','#F5F5F5','#FFFF00','#9ACD32'];
//$index=array_rand($color_code_array,9);


@endphp



<script type="text/javascript">
  
google.charts.load('current', {packages: ['corechart', 'line']});
google.charts.setOnLoadCallback(drawBasic);




function drawBasic() {

console.log(new Date (2016, 8, 6));
      var data = new google.visualization.DataTable();
      data.addColumn('date', 'Date');
      data.addColumn('number', 'Product ');

      data.addRows([
        @php
          foreach ($dayinmonthchartdata as $key => $value) { @endphp
            [new Date ({{$value['year']}}, {{$value['month'] - 1}}, {{$value['day']}}), {{$value['sale']}}], 
        @php  }
        @endphp
      ]);

      /*data.addRows([
        [new Date(2014, 0),  5.7],
        [new Date(2014, 1),  8.7],
        [new Date(2014, 2),   12],
        [new Date(2014, 3), 15.3],
        [new Date(2014, 4), 18.6],
        [new Date(2014, 5), 20.9],
        [new Date(2014, 6), 19.8],
        [new Date(2014, 7), 16.6],
        [new Date(2014, 8), 13.3],
        [new Date(2014, 9),  9.9],
        [new Date(2014, 10),  6.6],
        [new Date(2014, 11),  4.5]
      ]);*/


      var options = {
        hAxis: {
          title: 'Days',
          format: 'MM/dd'
        },
        vAxis: {
          title: 'Sales'
        }

      };

      var chart = new google.visualization.LineChart(document.getElementById('dayinmonthchartdata'));

      chart.draw(data, options);
    }

</script>



<script type="text/javascript">
  
google.charts.load('current', {packages: ['corechart', 'line']});
google.charts.setOnLoadCallback(drawBasic);




function drawBasic() {

console.log(new Date (2016, 8, 6));
      var data = new google.visualization.DataTable();
      data.addColumn('date', 'Month');
      data.addColumn('number', 'Product ');

      data.addRows([
        @php
          foreach ($monthinyearchartdata as $key => $value) { @endphp
            [new Date ({{$value['year']}}, {{$value['month'] - 1}}), {{$value['sale']}}], 
        @php  }
        @endphp
      ]);

      /*data.addRows([
        [new Date(2014, 0),  5.7],
        [new Date(2014, 1),  8.7],
        [new Date(2014, 2),   12],
        [new Date(2014, 3), 15.3],
        [new Date(2014, 4), 18.6],
        [new Date(2014, 5), 20.9],
        [new Date(2014, 6), 19.8],
        [new Date(2014, 7), 16.6],
        [new Date(2014, 8), 13.3],
        [new Date(2014, 9),  9.9],
        [new Date(2014, 10),  6.6],
        [new Date(2014, 11),  4.5]
      ]);*/


      var options = {
        hAxis: {
          title: 'Month',
          format: 'MMM'
        },
        vAxis: {
          title: 'Sales'
        }

      };

      var chart = new google.visualization.LineChart(document.getElementById('monthinyearchartdata'));

      chart.draw(data, options);
    }

</script>








<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <script type="text/javascript">
    google.charts.load("current", {packages:['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
      var data = google.visualization.arrayToDataTable([
        ["Element", "Quantity", { role: "style" } ],
        /*["Copper", 8.94, "#b87333"],
        ["Silver", 10.49, "silver"],
        ["Gold", 19.30, "gold"],
        ["Platinum", 21.45, "color: #e5e4e2"],
        ["Lithioum", 21.45, "color: #e5e4e2"]*/

        @php
          foreach ($todaybrandwisesalechart as $key => $value) { @endphp
            

            ["{{$value['name']}}", {{$value['sale']}},"stroke-color: {{$color_code_array[array_rand($color_code_array,1)]}}; stroke-opacity: 0.6; stroke-width: 0; fill-color: {{$color_code_array[array_rand($color_code_array,1)]}}; fill-opacity: 0.6"], 
        @php  }
        @endphp



      ]);

      var view = new google.visualization.DataView(data);
      view.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" },
                       2]);

      var options = {
        //title: "Density of Precious Metals, in g/cm^3",
        bar: {groupWidth: "30%"},
        legend: { position: "none" },
      };
      var chart = new google.visualization.ColumnChart(document.getElementById("todaysalebrandwise"));
      chart.draw(view, options);
  }
  </script>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <script type="text/javascript">
    google.charts.load("current", {packages:['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
      var data = google.visualization.arrayToDataTable([
        ["Element", "Quantity", { role: "style" } ],
        /*["Copper", 8.94, "#b87333"],
        ["Silver", 10.49, "silver"],
        ["Gold", 19.30, "gold"],
        ["Platinum", 21.45, "color: #e5e4e2"],
        ["Lithioum", 21.45, "color: #e5e4e2"]*/

        @php
          foreach ($monthlybrandwisesalechart as $key => $value) { @endphp
            ["{{$value['name']}}", {{$value['sale']}},"stroke-color: {{$color_code_array[array_rand($color_code_array,1)]}}; stroke-opacity: 0.6; stroke-width: 0; fill-color: {{$color_code_array[array_rand($color_code_array,1)]}}; fill-opacity: 0.6"], 
        @php  }
        @endphp



      ]);

      var view = new google.visualization.DataView(data);
      view.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" },
                       2]);

      var options = {
        //title: "Density of Precious Metals, in g/cm^3",
        bar: {groupWidth: "30%"},
        legend: { position: "none" },
      };
      var chart = new google.visualization.ColumnChart(document.getElementById("monthlysalebrandwise"));
      chart.draw(view, options);
  }
  </script>








<script type="text/javascript">
  google.charts.load('current', {packages: ['corechart', 'bar']});
google.charts.setOnLoadCallback(drawBasic);

function drawBasic() {

      var data = google.visualization.arrayToDataTable([
        ['Product', 'Total Sale',{ role: 'style' }],
        @php
          foreach ($monthlytopproductchart as $key => $value) { @endphp
            ['{{$value->product}}', {{$value->sale}}, "stroke-color: {{$color_code_array[array_rand($color_code_array,1)]}}; stroke-opacity: 0.6; stroke-width: 0; fill-color: {{$color_code_array[array_rand($color_code_array,1)]}}; fill-opacity: 0.6"], 
        @php  }
        @endphp
        //['New York City, NY', 8175000],
        //['Los Angeles, CA', 3792000],
      ]);

      var options = {
        //title: 'Population of Largest U.S. Cities',
        chartArea: {width: '50%'},
        hAxis: {
          title: 'Total Sale',
          minValue: 0
        },
        vAxis: {
          title: 'Product'
        }
      };

      var chart = new google.visualization.BarChart(document.getElementById('topproduct'));

      chart.draw(data, options);
    }
</script>









<script type="text/javascript">
  google.charts.load('current', {packages: ['corechart', 'bar']});
google.charts.setOnLoadCallback(drawBasic);

function drawBasic() {

      var data = google.visualization.arrayToDataTable([
        ['Product', 'Total Sale',{ role: 'style' }],
        @php
          foreach ($monthlytopretailerchart as $key => $value) { @endphp
            ['{{$value->user}}', {{$value->sale}},"stroke-color: {{$color_code_array[array_rand($color_code_array,1)]}}; stroke-opacity: 0.6; stroke-width: 0; fill-color: {{$color_code_array[array_rand($color_code_array,1)]}}; fill-opacity: 0.6"], 
        @php  }
        @endphp
        //['New York City, NY', 8175000],
        //['Los Angeles, CA', 3792000],
      ]);

      var options = {
        //title: 'Population of Largest U.S. Cities',
        chartArea: {width: '50%'},
        hAxis: {
          title: 'Total Sale',
          minValue: 0
        },
        vAxis: {
          title: 'Retaile'
        }
      };

      var chart = new google.visualization.BarChart(document.getElementById('topretailer'));

      chart.draw(data, options);
    }
</script>










<script type="text/javascript">
  google.charts.load('current', {packages: ['corechart', 'bar']});
google.charts.setOnLoadCallback(drawBasic);

function drawBasic() {

      var data = google.visualization.arrayToDataTable([
        ['Product', 'Total Sale',{ role: 'style' }],
        @php
          foreach ($monthlytopproductsalechart as $key => $value) { @endphp
            ['{{$value->product}}', {{$value->sale}}, "stroke-color: {{$color_code_array[array_rand($color_code_array,1)]}}; stroke-opacity: 0.6; stroke-width: 0; fill-color: {{$color_code_array[array_rand($color_code_array,1)]}}; fill-opacity: 0.6"], 
        @php  }
        @endphp
        //['New York City, NY', 8175000],
        //['Los Angeles, CA', 3792000],
      ]);

      var options = {
        //title: 'Population of Largest U.S. Cities',
        chartArea: {width: '50%'},
        hAxis: {
          title: 'Total Sale',
          minValue: 0
        },
        vAxis: {
          title: 'Product'
        }
      };

      var chart = new google.visualization.BarChart(document.getElementById('topseconderysale'));

      chart.draw(data, options);
    }
</script>









<script type="text/javascript">
  google.charts.load('current', {packages: ['corechart', 'bar']});
google.charts.setOnLoadCallback(drawBasic);

function drawBasic() {

      var data = google.visualization.arrayToDataTable([
        ['Product', 'Total Sale',{ role: 'style' }],
        @php
          foreach ($monthlytopdistributorchart as $key => $value) { @endphp
            ['{{$value->user}}', {{$value->sale}},"stroke-color: {{$color_code_array[array_rand($color_code_array,1)]}}; stroke-opacity: 0.6; stroke-width: 0; fill-color: {{$color_code_array[array_rand($color_code_array,1)]}}; fill-opacity: 0.6"], 
        @php  }
        @endphp
        //['New York City, NY', 8175000],
        //['Los Angeles, CA', 3792000],
      ]);

      var options = {
        //title: 'Population of Largest U.S. Cities',
        chartArea: {width: '50%'},
        hAxis: {
          title: 'Total Sale',
          minValue: 0
        },
        vAxis: {
          title: 'Retaile'
        }
      };

      var chart = new google.visualization.BarChart(document.getElementById('topdistributor'));

      chart.draw(data, options);
    }
</script>











<!-- 
<script type="text/javascript">
google.charts.load('current', {packages: ['corechart', 'line']});
google.charts.setOnLoadCallback(drawAxisTickColors);

function drawAxisTickColors() {
      var data = new google.visualization.DataTable();
      data.addColumn('date', 'Month');
      data.addColumn('number', 'Dogs');
      data.addColumn('number', 'Cats');

      data.addRows([
        [0, 0, 0],    [1, 10, 5],   [2, 23, 15],  [3, 17, 9],   [4, 18, 10],  [5, 9, 5],
        [6, 11, 3],   [7, 27, 19],  [8, 33, 25],  [9, 40, 32],  [10, 32, 24], [11, 35, 27],
        [12, 30, 22], [13, 40, 32], [14, 42, 34], [15, 47, 39], [16, 44, 36], [17, 48, 40],
        [18, 52, 44], [19, 54, 46], [20, 42, 34], [21, 55, 47], [22, 56, 48], [23, 57, 49],
        [24, 60, 52], [25, 50, 42], [26, 52, 44], [27, 51, 43], [28, 49, 41], [29, 53, 45],
        [30, 55, 47], [31, 60, 52], [32, 61, 53], [33, 59, 51], [34, 62, 54], [35, 65, 57],
        [36, 62, 54], [37, 58, 50], [38, 55, 47], [39, 61, 53], [40, 64, 56], [41, 65, 57],
        [42, 63, 55], [43, 66, 58], [44, 67, 59], [45, 69, 61], [46, 69, 61], [47, 70, 62],
        [48, 72, 64], [49, 68, 60], [50, 66, 58], [51, 65, 57], [52, 67, 59], [53, 70, 62],
        [54, 71, 63], [55, 72, 64], [56, 73, 65], [57, 75, 67], [58, 70, 62], [59, 68, 60],
        [60, 64, 56], [61, 60, 52], [62, 65, 57], [63, 67, 59], [64, 68, 60], [65, 69, 61],
        [66, 70, 62], [67, 72, 64], [68, 75, 67], [69, 80, 72]
      ]);

      data.addRows([
        [new Date(2014, 0),  5,20],
        [new Date(2014, 1),  8,30],
        [new Date(2014, 2), 12,40],
        [new Date(2014, 3), 15,50],
        [new Date(2014, 4), 18,60],
        [new Date(2014, 5), 20,70],
        [new Date(2014, 6), 19,80],
        [new Date(2014, 7), 16,90],
        [new Date(2014, 8), 13,100],
        [new Date(2014, 9),  9,110],
        [new Date(2014, 10), 6,120],
        [new Date(2014, 11), 4,130]
      ]);

      var options = {
        hAxis: {
          title: 'Time',
          format: 'MMM',
          textStyle: {
            color: '#01579b',
            fontSize: 20,
            fontName: 'Arial',
            bold: true,
            italic: true
          },
          titleTextStyle: {
            color: '#01579b',
            fontSize: 16,
            fontName: 'Arial',
            bold: false,
            italic: true
          }
        },
        vAxis: {
          title: 'Popularity',
          textStyle: {
            color: '#1a237e',
            fontSize: 24,
            bold: true
          },
          titleTextStyle: {
            color: '#1a237e',
            fontSize: 24,
            bold: true
          }
        },
        colors: ['#a52714', '#097138']
      };
      var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
      chart.draw(data, options);
    }

</script>

 -->



<!-- <script type="text/javascript">
  google.charts.load('current', {'packages':['corechart']});
  google.charts.setOnLoadCallback(drawChart);

  function drawChart() {
    var data = google.visualization.arrayToDataTable([
      ['Year', 'Sales', 'Expenses'],
      ['2004',  1000,      400],
      ['2005',  1170,      460],
      ['2006',  660,       1120],
      ['2007',  1030,      540]
    ]);

    var options = {
      title: 'Company Performance',
      curveType: 'function',
      legend: { position: 'bottom' }
    };

    var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

    chart.draw(data, options);
  }
</script> -->




<!-- code for linechart--------------------- -->

<!-- content part================================ -->
@endsection