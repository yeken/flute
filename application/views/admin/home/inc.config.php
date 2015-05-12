<script>

$(function() {
		  
	$(".module, .top_menu_data_container h2, .main_model_tabs_container h2, .datatable_actions_container h2, .fields_container h2, .relations_container h2 ").click(function(){
		$(this).next().toggle();
	});
		  
});

</script>
<link href="<?=base_url()?>assets_be/css/config.css" rel="stylesheet" type="text/css" media="screen" />
<? foreach( $this->config->item('modules') as $module => $config_items ){ ?>
<div class="config_panel">
	<h1 class="module"><?=$module?></h1>
    <div class="module_container" style="display:none;">
		<? 
            foreach($config_items as $item_name => $item_value )
            {
                switch($item_name)
                {
					case 'main_model_tabs':
						echo "<div class='clear'></div>";
							echo "<div class='main_model_tabs_container'>";
							echo "<h2>".$item_name."</h2>";
							echo "<div class='main_model_items_container'>";
							foreach($item_value as $relation => $attrs)
							{
								echo "<div class='tab_container'>";
								echo "<h3>".$relation."</h3>";
								echo "<div class='main_model_tabs_attrs_container'>";
								foreach($attrs as $attr => $value)
								{
									if($attr == 'fields')
									{
										echo "<div class='relations_fields_container'>
											  <h4>Fields</h4>";
												foreach($value as $field => $attrr)
												{
													echo "<h5>".$field."</h5>";
													echo "<div class='fields_attrs_container'>";
														foreach($attrr as $attr => $value)
														{
															echo $attr." &raquo; ".$value."<br>";
														}
													echo "</div>";
												}
										echo "</div>";
									}
									else
									{
										echo $attr." &raquo; ".$value."<br>";
									}
								}
								echo "</div>";
								echo "</div>";
							}
							echo "</div>";	
						echo "</div>";							
						break;

					case 'datatable_actions':
						echo "<div class='clear'></div>";
							echo "<div class='datatable_actions_container'>";
							echo "<h2>".$item_name."</h2>";
							echo "<div class='datatable_actions_items_container'>";
							foreach($item_value as $actions => $attrs)
							{
								echo "<div class='datatable_action_container' >";
								echo "<h3>".$actions."</h3>";
								foreach($attrs as $attr => $value)
								{
									echo "<div class='datatable_attrs_container'>";
									    echo $attr." &raquo; ".$value."<br>";
									echo "</div>";
								}
								echo "</div>";
							}
							echo "</div>";
						echo "</div>";							
						break;

					case 'top_menu_actions':
						echo "<div class='clear'></div>";
							echo "<div class='top_menu_data_container'>";
							echo "<h2>".$item_name."</h2>";
							echo "<div class='top_menu_data_items_container'>";
							foreach($item_value as $actions => $attrs)
							{
								echo "<div class='top_menu_container' >";
								echo "<h3>".$actions."</h3>";
								foreach($attrs as $attr => $value)
								{
									echo "<div class='top_menu_actions_container'>";
									    echo $attr." &raquo; ".$value."<br>";
									echo "</div>";
								}
								echo "</div>";
							}
							echo "</div>";
						echo "</div>";							
						break;
                    case 'fields':
						echo "<div class='clear'></div>";
						echo "<div class='fields_container'>";
							echo "<h2>Fields:</h2>";
							echo "<div class='fields_items_container'>";
							foreach($item_value as $field => $attrs )
							{
								echo "<div class='field_container' >";
									echo "<h3>".$field."</h3>";
									echo "<div class='fields_attrs_container'>";
										foreach($attrs as $attr => $value)
										{
											echo $attr." &raquo; ".$value."<br>";
										}
									echo "</div>";
								echo "</div>";
							}
							echo "</div>";
						echo "</div>";
                        break;
					case 'relation':
						echo "<div class='clear'></div>";
						echo "<div class='relations_container'>";
						echo "<h2>Relations:</h2>";
						echo "<div class='relations_items_container'>";
						foreach($item_value as $relation => $attrs)
						{
							echo "<div class='relation_container' >";
							echo "<h3>".$relation."</h3>";
							echo "<div class='relations_attrs_container'>";
								foreach($attrs as $attr => $value)
								{
									if($attr == 'fields')
									{
										echo "<div class='relations_fields_container'>
											  <h4>Fields</h4>";
												foreach($value as $field => $attrr)
												{
													echo "<div class='relation_field_container'>";
													echo "<h5>".$field."</h5>";
														echo "<div class='fields_attrs_container'>";
															foreach($attrr as $attr => $value)
															{
																echo $attr." &raquo; ".$value."<br>";
															}
														echo "</div>";
													echo "</div>";
												}
										echo "</div>";
									}
									elseif( $attr == '')
									{
										
									}
									else
									{
										echo $attr." &raquo; ".$value."<br>";
									}
								}
							echo "</div>";
							echo "</div>";
						}
						echo "</div>";
						echo "</div>";
						break;

                    default: 
						echo "<div class='default_container'>";
						echo "<strong>".$item_name."</strong> &raquo; ".$item_value."<br>";
						echo "</div>";
						break;
                }
            }
        ?>
    </div>
</div>
<? } ?>