<div id="navbar-model">
    <ul class="nav nav-tabs  nav-justified">
    <? 
        $highlighted_tab = $this->main_model_tabs[$current_tab]['tab'] ? $this->main_model_tabs[$current_tab]['tab'] : $current_tab; 
        foreach($this->main_model_tabs as $tab_id => $tab)
        { 
            if($tab['admin_only'] && !($this->bitauth->is_admin()))
				continue;
			
			if($tab['vars'])
            {
                $tab_vars = array();
                foreach($tab['vars'] as $model_var)
                {
                    $tab_vars[] = $this->main_model->get_field($model_var);	
                }
                $tab_vars = implode('|',$tab_vars);
            }
            else
            {
                $tab_vars = $this->main_model->get_id();	
            }
            if(!$tab['tab']) // only root tabs are shown here.
            {
                ?><li <?= $highlighted_tab == $tab_id ? "class='active'" : ""?>><a href="<?= $tab['url']?><?=$tab_vars?>" class="ajax-links "><?= $tab['label']?></a></li><? 
            }
            unset($tab_vars);
        } ?>
    </ul>
</div>

<div class="clearfix"></div>