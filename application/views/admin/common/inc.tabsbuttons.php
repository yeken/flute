<div class='pull-right' style="margin:5px">
<? 
	foreach($this->main_model_tabs as $tab_id => $tab)
	{ 
		if($tab['tab'] && ($tab['tab'] == $current_tab)) // only root tabs are shown here.
		{
			if($tab['vars'])
			{
				$tab_vars = array();
				foreach($tab['vars'] as $model_var)
				{
					$tab_vars[] = $this->main_model->get_field($model_var);	
				}
				$tab_vars = implode('|',$vars);
			}
			else
			{
				$tab_vars = $this->main_model->get_id();	
			}
			
			?><a id="<?= $tab_id?>" href="<?= $tab['url']?><?=$tab_vars?>" class="ajax-links btn btn-primary"><?= $tab['label']?></a><? 
			unset($tab_vars);
		}
	} ?>
</div>
<div class="clearfix"></div>