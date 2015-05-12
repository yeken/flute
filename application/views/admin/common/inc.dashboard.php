<? $this->load->view("/admin/common/inc.dashboard.header.php", $header_data); ?>
<? $this->load->view("/admin/common/inc.top.menu.php", $header_data); ?>
	<div id="main_message">
    	<span id="loading"></span>
        <div class="clear"></div>
    	<span id="notification"></span>
    </div>
    <div id="breadcrumb"></div>
	<div id="top_menu_actions"></div>
	<div id="content"></div>
<? $this->load->view("/admin/common/inc.footer.php",$footer_data);?>
<div class="panel">
    <script type="text/javascript" src="http://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load('visualization', '1.1', {packages: ['controls']});
    </script>
    <script type="text/javascript">
      function drawVisualization() {
        // Prepare the data
        var data = google.visualization.arrayToDataTable([
          ['Producto', 'Visitas', 'Consultas'],
		  <? foreach($products as $k => $product){ ?>
          ['<?=$product['name'];?>' , <?=rand(20,550)?>, <?=rand(50,200)?>],
		  <? } ?>
        ]);
      
        // Define a slider control for the Age column.
        var slider = new google.visualization.ControlWrapper({
          'controlType': 'NumberRangeFilter',
          'containerId': 'control1',
          'options': {
            'filterColumnLabel': 'Consultas',
          'ui': {'labelStacking': 'vertical'}
          }
        });
      
	  	/*
        // Define a category picker control for the Gender column
        var categoryPicker = new google.visualization.ControlWrapper({
          'controlType': 'CategoryFilter',
          'containerId': 'control2',
          'options': {
            'filterColumnLabel': 'Gender',
            'ui': {
            'labelStacking': 'vertical',
              'allowTyping': false,
              'allowMultiple': false
            }
          }
        });
		*/
      
        // Define a Pie chart
        var pie = new google.visualization.ChartWrapper({
          'chartType': 'PieChart',
          'containerId': 'chart1',
          'options': {
            'width': 300,
            'height': 300,
            'legend': 'none',
            'title': 'Consultas realizadas',
            'chartArea': {'left': 15, 'top': 40, 'right': 0, 'bottom': 0},
            'pieSliceText': 'label'
          },
          // Instruct the piechart to use colums 0 (Name) and 3 (Donuts Eaten)
          // from the 'data' DataTable.
          'view': {'columns': [0, 2]}
        });
      
        // Define a table
        var table = new google.visualization.ChartWrapper({
          'chartType': 'Table',
          'containerId': 'chart2',
          'options': {
            'width': '400px'
          }
        });
      
        // Create a dashboard
        new google.visualization.Dashboard(document.getElementById('dashboard')).
            // Establish bindings, declaring the both the slider and the category
            // picker will drive both charts.
            bind([slider], [pie, table]).
            // Draw the entire dashboard.
            draw(data);
      }
      

      google.setOnLoadCallback(drawVisualization);
    </script>
    <div class="panel-content">
    <h2>Productos</h2>
        <div id="dashboard">

                <div id="control1"></div>
                <div id="control2"></div>
                <div id="control3"></div>
                <div style="float: left;" id="chart1"></div>
                <div style="float: left;" id="chart2"></div>
                <div style="float: left;" id="chart3"></div>

        </div>
    </div>
</div>