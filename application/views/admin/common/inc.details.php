<div class="panel panel-default">
    <div class="panel-content">
    <?= $title?><? $this->load->view('admin/common/inc.tabs.php'); ?>
	<?
		foreach($page_fields[$current_tab] as $field => $attr)
        {
			$str = "";
			?>
            <dl class="dl-horizontal">
			<dt tag="<?= $attr['tag']?>"><?= $attr['label']?></dt>
				<dd>
					<?
					
					if($attr['array'])
					{
						$ar = $this->main_model->$attr['array'];
						$field_val = $attr['tag'] ? $ar[$attr['tag']] : $ar[$field]; 
					}
					else
					{	
						$field_val = $attr['tag'] ? $this->main_model->$attr['tag'] : $this->main_model->$field;
					}
                    switch($attr['type'])
					{
						case 'checkbox':
										$str = (int)$field_val? '<span class="glyphicon glyphicon-ok-sign glyph-ok" title="SI"></span>' : '<span class="glyphicon glyphicon-remove-sign glyph-no" title="NO"></span>'; 
										break;
						case 'date_time':
										$str = dateFormat($field_val, 'd/m/Y H:i');
										break;
						case 'date':
										$str = dateFormat($field_val, 'd/m/Y');
										break;
						case 'image':
										if(!$this->main_model->file_types[$attr['tag']] || ($this->main_model->file_types[$attr['tag']] == 'image'))
										{
											$str = '<a href="'.$field_val.'" target="_blank"><img src="'.thumb_image($field_val).'" height="70px"></a>';
										}
										break;
						case 'video':
										if(!$this->main_model->file_types[$attr['tag']] || ($this->main_model->file_types[$attr['tag']] == 'video'))
										{
											$str = '<iframe width="250" height="150" src="http://www.youtube.com/embed/'.$field_val.'" frameborder="0" allowfullscreen></iframe>';
										}
										break;	
						case 'script':
										$str = $this->main_model->sanitize($field_val);
										break;
						case 'select':
						case 'radio':
										if($attr['options'])
										{
											foreach($attr['options'] as $option)
											{
												if($option['value'] == $field_val)
												{
													$str = $option['label'];
													break;	
												}	
											}	
										}
										else
										{
											$str = $field_val;		
										}
										break;				
						default:
										$str = $field_val;
										break;
					}
					echo $str;
					?>
                </dd>
				
			</dl><?
        }?>
        <? $this->load->view('admin/common/inc.tabsbuttons.php'); ?>
        
    </div>
</div>