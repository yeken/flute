<div id="sidebar-wrapper">
	<div id="main-controls">
      <div class="list-group panel">
            <?
            $site_config = $this->config->item('general');
			$site_config = $site_config[$this->uri->segment(1)];
            foreach($site_config['sections'] as $section_id => $section)
            {
				if($section['admin_only'] && !$this->bitauth->is_admin()) continue;
				if($section['client_only'] && $this->bitauth->is_admin()) continue;
				$top_menu_actions = $this->config->item('modules');
				$top_menu_actions = $top_menu_actions[$section_id]['top_menu_actions'];

				if(is_array($top_menu_actions))
				{
				?>
				  <a href="#drop-<?= $section_id ?>" class="list-group-item <?= $class_name == $section_id ? "list-group-item-success" : ""?>" data-toggle="collapse" data-parent="#main-controls">
					<div class="pull-left">
						<div class="section-icon"><span class="fa <?= $section['icon']?>"></span></div>
						<div class="section-name"><?= $section['name']?></div></div>
					<div class="pull-right menu-caret"><span class="expand-section fa fa-angle-down"></span></div>
					<div class="clearfix"></div>
				  </a>
				  <div class="collapse" id="drop-<?= $section_id ?>">
				  <?
				  foreach($top_menu_actions as $action => $attrs)
				  {
				  ?>
				  <a href="<?=$attrs['url']?>" >
					<div class="sidebar-action">
						<span><?=$attrs['label']?></span>
					</div>
					</a>
				  <? 
				  }
				  ?>
				  </div>
                 <?
                 }
				 else
				 {
					 ?>
					 <a href="<?= $section['url']."/".$this->bitauth->user_id ?>" class="list-group-item <?= $class_name == $section_id ? "list-group-item-success" : ""?>" data-parent="#main-controls">
					<div class="pull-left">
						<div class="section-icon"><span class="fa <?= $section['icon']?>"></span></div>
						<div class="section-name"><?= $section['name']?></div></div>
					<div class="clearfix"></div>
				  </a>
					 
					 <?
				 }
				 ?>
				  
				  <!--
				  <div class="dropdown menu-small dropdown-menu-right">
					  <div class="section-icon dropdown-toggle" style="display:block; color:#fff"  id="drmenu-<?= $section_id ?>" data-toggle="dropdown"><span class="fa <?= $section['icon']?>"></span></div>
					  <ul class="dropdown-menu" role="menu" aria-labelledby="drmenu-<?= $section_id ?>" style="position:relative; z-index:100;">
						<?
						  foreach($top_menu_actions as $action => $attrs)
						  {
						  ?>
						  <a href="<?=$attrs['url']?>" >
							<div class="sidebar-action">
								<span><?=$attrs['label']?></span>
							</div>
							</a>
						  <? 
						  }
						  ?>
					  </ul>
				  </div>
				  -->
            <?	
            }
            ?>            
    	</div>
        <footer>
        	<a href="http://www.adyouwish.com" target="_blank" id="adyou-logo"><img src="<?= base_url()?>assets_be/img/adyouwish.png" style="opacity:0.15"></a>
        	<div class="pull-right toggle-menu">
        		<a href="#menu-toggle" id="menu-toggle" class="btn btn-default" style="padding:3px 6px;"><span class="fa fa-chevron-left " style="font-size:12px; color: #3D5368;"></span></a>
        	</div>
        </footer>
    </div>

    <div class="navbar navbar-default navbar-fixed-top" style="min-height:0px; border-bottom:0px;">
        <div class="container-fluid">
            <div class="navbar-header">
              <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
            </div>
            <div class="navbar-collapse collapse" id="navbar-main">
            	<?
				
                if($site_config['admin_logo'])
				{
					?>
                    <div id="top-logo">
                        <img src="<?= base_url()."assets_be/img/".$site_config['admin_logo']?>">
                    </div>				
					<?
				}
				?>
                <div class='pull-left hidden-xs' style="left:210px; position:absolute"><div id="breadcrumb"></div></div>
                <ul class="nav navbar-nav visible-xs">
					<?
                    
                    foreach($site_config['sections'] as $section_id => $section)
                    {
                    if($section['admin_only'] && !$this->bitauth->is_admin()) continue;
					if($section['client_only'] && $this->bitauth->is_admin()) continue;
                    $top_menu_actions = $this->config->item('modules');
                    $top_menu_actions = $top_menu_actions[$section_id]['top_menu_actions'];
                    ?>
                    
                    <li class="main-navbar-li dropdown">
						<?
                        if(is_array($top_menu_actions))
                        {
						  ?>
						  
                            <a href="#" <?= $active ? "class='active'" : ""?> class="dropdown-toggle" data-toggle="dropdown"><?= $section['name']?> <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                            <?
                            foreach($top_menu_actions as $action => $attrs)
                            {
                            ?>
                            <li><a href="<?=$attrs['url']?>"><?=$attrs['label']?></a></li>
                            <? 
                            }
                            ?>
                            </ul>
                    	<?
						}
						else
						{
							?><a href="<?= $section['url']."/".$this->bitauth->user_id?>" <?= $active ? "class='active'" : ""?> class="dropdown-toggle ajax-links" data-toggle="dropdown"><?= $section['name']?> <b class="caret"></b></a><?	
						}
						?>
                    </li>
                    <?	
                    }
                    ?>
                </ul>
                <div class="btn-group pull-right">
                        
                <button type="button" class="btn btn-link dropdown-toggle" style="font-size:12px; margin-top:5px" data-toggle="dropdown">
                    <span class="glyphicon glyphicon-user"></span> <?= $this->bitauth->username?>
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                </button>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="#user/details/<?= $this->bitauth->user_id?>" class="ajax-links-top-menu">Mi cuenta</a></li>
                        <li><a href="#user/edit_password/<?= $this->bitauth->user_id?>" class="ajax-links-top-menu">Modificar password</a></li>
                        <li class="divider"></li>
                        <li><a href="<?=base_url()?>admin/user/logout"><i class="icon-share"></i> Logout</a></li>
                    </ul>
                </div>
            </div>
    	</div>
	</div>
</div>
