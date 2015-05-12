<? $this->load->view("/admin/dashboard/inc.dashboard.header.php", $header_data); ?>
<? $this->load->view("/admin/common/inc.top.menu.php", $header_data); ?>
<?
/*
<script type="text/javascript" src="http://www.google.com/jsapi"></script>
    <script type='text/javascript'>
     google.load('visualization', '1', {'packages': ['geochart', 'table', 'corechart']});
     google.setOnLoadCallback(drawMarkersMap);
	 google.setOnLoadCallback(drawGralInfoChart);
	 google.setOnLoadCallback(drawProvincesTable);
	 google.setOnLoadCallback(drawSectionsTable);
	 google.setOnLoadCallback(drawSectionsChart);

	function drawMarkersMap() {
		var data = google.visualization.arrayToDataTable([
			['City',   'Consultas', 'Clientes'],
			<? 
			foreach($provinces as $province)
			{
			?>
			['<?=$province->name?>', <?=$province->total?>, <?=$province->clientes?>],
			<? 
			}
			?>
		]);
	
		var options = {
			region: 'AR',
			displayMode: 'markers',
			colorAxis: {colors: ['green', 'blue']}
		};
	
		var chart = new google.visualization.GeoChart(document.getElementById('map_div'));
		chart.draw(data, options);
	}

	function drawGralInfoChart() {
		var data = google.visualization.arrayToDataTable([
		  ['Segmentacion', 'Consultas'],
		  <?
		  if(is_array($players))
		  {
			  foreach($players as $player)
			  {
			  ?>['<?= $player['name']?>',<?=$player['hits']?>],<?
			  }
		  }
		?>
		]);
		var chart = new google.visualization.PieChart(document.getElementById('gral_info_chart_div'));
		chart.draw(data);
	}
	
	function drawProvincesTable() {
		var data = new google.visualization.DataTable();
		data.addColumn('string', 'Provincia');
		data.addColumn('number', 'Consultas totales');
		data.addColumn('number', 'Clientes');
		data.addRows([
		<? 
		foreach($provinces as $province)
		{
		?>
		  ['<?=$province->name?>',  {v: <?=$province->total?>, f: '<?=$province->total?>'},  {v: <?=$province->clientes?>, f: '<?=$province->clientes?>'}],
		<?
		}
		?>
		]);
	
		var table = new google.visualization.Table(document.getElementById('provinces_table_div'));
		table.draw(data, {showRowNumber: true});
	}
	
	function drawSectionsTable() {
		var data = new google.visualization.DataTable();
		data.addColumn('string', 'Seccion');
		data.addColumn('number', 'Consultas totales');
		data.addRows([
		<? 
		foreach($sections as $section)
		{
		?>
		  ['<?=$section->section ?>', {v: <?=$section->consultas?>, f: '<?=$section->consultas?>'}],
		<?
		}
		?>
		]);
	
		var table = new google.visualization.Table(document.getElementById('sections_table_div'));
		table.draw(data, {showRowNumber: true});
	}

	function drawSectionsChart() {
		var data = google.visualization.arrayToDataTable([
		  ['Secciones', 'Consultas'],
		<? 
		foreach($sections as $section)
		{
		?>
		  ['<?=$section->section ?>', <?=$section->consultas?>],
		<?
		}
		?>
		]);

		var options = {
		  title: 'Consultas por secciones'
		};

		var chart = new google.visualization.PieChart(document.getElementById('sections_charts_div'));
		chart.draw(data, options);
	}


$(document).ready(function () {

	var oneDay = 24*60*60*1000;
	var rangeFormat = "%Y-%m-%d";
	var rangeConv = new AnyTime.Converter({format:rangeFormat});
	$("#rangeToday").click( function(e) {
	  $("#rangeStart").val(rangeConv.format(new Date())).change(); } );
	$("#rangeClear").click( function(e) {
	  $("#rangeStart").val("").change(); } );
	$("#rangeStart").AnyTime_picker({format:rangeFormat});
	$("#rangeStart").change( function(e) { try {
	  var fromDay = rangeConv.parse($("#rangeStart").val()).getTime();
	  var dayLater = new Date(fromDay+oneDay);
	  dayLater.setHours(0,0,0,0);
	  var ninetyDaysLater = new Date(fromDay+(90*oneDay));
	  ninetyDaysLater.setHours(23,59,59,999);
	  $("#rangeFinish").
		  AnyTime_noPicker().
		  removeAttr("disabled").
		  val(rangeConv.format(dayLater)).
		  AnyTime_picker(
			  { earliest: dayLater,
				format: rangeFormat,
				latest: ninetyDaysLater
			  } );
	  } catch(e){ $("#rangeFinish").val("").attr("disabled","disabled"); } } );

});

	
    </script>
<? $this->load->view("/admin/common/inc.top.menu.php", $header_data); ?>
	<div id="main_message">
    	<span id="loading"></span>
        <div class="clear"></div>
    	<span id="notification"></span>
    </div>
    <div id="breadcrumb"></div>
	<div id="top_menu_actions">
	<?
		if(is_array($this->data['top_menu_actions']))
		{
			?>
			<div id="top_menu_actions_container" class="black_buttons">
			<?
			foreach($this->data['top_menu_actions'] as $action_id => $action)
			{
				?>
				<a href="<?= $action['url'] ? $action['url'] : '#'.$this->class_name."/".$action['method']?>" <?= $action['target'] ? "target='".$action['target']."'" : ""?>>
				<div class="action_button <?= $action['method'] == $current_method ? "selected" : ""?>" id="<?= $action_id?>">
						<span class="ui-icon <?= $action['icon']?>"></span> <?= $action['label']?> 
				</div>
				</a>
				<?
			}
			?>
			<div class="clear"></div>
			</div>
			<?
		}
	?>
	</div>
	<div id="content">

		<div class="panel">

			<div class="panel-content">

				<h1 class="ui-widget-header">Reportes Generales
				&nbsp;&nbsp;&nbsp;&nbsp;
				<form name="filter" method="post" style="float:right;">
					Desde: <input type="text" name="rangeStart" id="rangeStart" size="14" value="<?=$rangeStart?>" />
					Hasta: <input type="text" name="rangeFinish" id="rangeFinish" size="14" <? if($rangeFinish==""){ ?>disabled="disabled"<? } ?> value="<?=$rangeFinish?>" />
					<input type="button" id="rangeToday" value="Hoy" />
					<input type="button" id="rangeClear" value="Borrar" />
					<input type="submit" name="Filtrar" value="Filtrar" />
				</form>
				</h1>

				<div class="dashboard_subpanel" style="width:48%; padding:1%;">
					<div class="" style="border:1px solid #CCC; padding:10px; margin-bottom:10px;">
						<p style="font-size:14px; font-weight:bold;">Total de usuarios: <?=$total_consultas?></p>
					</div>
					<h1 class="ui-widget-header">Respondiendo a la siguiente Segmentaci&oacute;n Geogr&aacute;fica:</h1>
					<div id='gral_info_chart_div' style="width:600px; height:300px;"></div>						

				</div>
				<br clear="all" />
			
				<div class="dashboard_subpanel" style="width:98%; padding:1%;">
					<h1 class="ui-widget-header" style="margin-bottom:10px;">Informaci&oacute;n por secci&oacute;n:</h1>
					<div style="width:50%; float:left;">

						<div id='sections_table_div' style="width:90%;"></div>

					</div>
					
					<div style="width:50%; float:left;">
						
						<div id='sections_charts_div' style="width:600px; height:400px;"></div>
						
					</div>
					
				</div>
				
				<br clear="all" />
			
				<div class="dashboard_subpanel" style="width:98%; padding:1%;">
					<h1 class="ui-widget-header" style="margin-bottom:10px;">Informaci&oacute;n por provincia:</h1>
					<div style="width:50%; float:left;">
						<div id='provinces_table_div' style="width:90%;"></div>
					</div>
					<div style="width:50%; float:left;">
						<div id="map_div" style="width: 100%; height: 500px;"></div>
					</div>
				</div>

			</div>
			<br clear="all" />
			<br clear="all" />
		</div>
	
	</div>
*/?>
<? $this->load->view("/admin/common/inc.footer.php",$footer_data);?>