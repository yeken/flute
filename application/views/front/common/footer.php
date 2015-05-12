
	<div class="container-fluid" id="bg-footer">
		<div class="container">
			<div class="col-md-12">
            	<div class="pull-left">
                <?
                if($this->company_model->footer_image)
				{
					?><img src="<?= $this->company_model->footer_image?>"><?	
				}?>
                <small style="padding-left: 5px;"><?= lang('support-email')?></small>
                </div>
				<div class="pull-right"><a href="http://www.adyouwish.com"><img src="<?= base_url()?>assets_fe/img/adyouwish.png"></a></div>
			</div>
		</div>
	</div>
	<script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
    
    ga('create', 'UA-51799648-1', 'fantasyfutbol2014.com');
    ga('send', 'pageview');
   
    </script>
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
	<script src="<?= $link_url?>assets_fe/js/jquery.js"></script>
    <script src="<?= $link_url?>assets_fe/js/bootstrap.min.js"></script>
    <script src="<?= $link_url?>assets_fe/js/jquery.parallax-1.1.3.js" type="text/javascript"></script>

	<?
    if(preg_match('/(?i)msie [1-9]/',$_SERVER['HTTP_USER_AGENT']))
	{
		?>
        <script src="<?= $link_url?>assets_fe/js/html5shiv.js"></script>
      	<script src="<?= $link_url?>assets_fe/js/respond.js"></script>
		<script>
		$('[placeholder]').focus(function() {
			  var input = $(this);
			  if (input.val() == input.attr('placeholder')) {
				input.val('');
				input.removeClass('placeholder');
			  }
			}).blur(function() {
			  var input = $(this);
			  if (input.val() == '' || input.val() == input.attr('placeholder')) {
				input.addClass('placeholder');
				input.val(input.attr('placeholder'));
			  }
			}).blur().parents('form').submit(function() {
			  $(this).find('[placeholder]').each(function() {
				var input = $(this);
				if (input.val() == input.attr('placeholder')) {
				  input.val('');
				}
			  })
			});	
		</script>
		<?
	}
	?>
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
	
    <![endif]-->
	<script>
		$('body').parallax("50%", -0.2);
			
	</script>
	<?
	if($section == 'home')
	{
	?>
		<script src="<?= $link_url?>assets_common/js/jquery.form.js"></script>
        <script>
        $('#login-form').ajaxForm({
                	// dataType identifies the expected content type of the server response 
                    dataType:  'json', 
                    // success identifies the function to invoke when the server response 
                    // has been received 
                    success:   validate_login_form 
                });
                
		function validate_login_form(data) {
			$('#error').hide();
			if(data.valid)
			{
				if(data.first_register)
				{
					window.location.href = "<?= $link_url?>primer-ingreso";	
				}
				else
				{
					window.location.href = "<?= $link_url?>posiciones";
				}
			}
			else
			{
			  	$('#error').html(data.error);
				$('#error').fadeIn();	
			}
		};
		
        $('#register-form').ajaxForm({
                	// dataType identifies the expected content type of the server response 
                    dataType:  'json', 
                    // success identifies the function to invoke when the server response 
                    // has been received 
                    success:   validate_register_form 
        });
                
		function validate_register_form(data) {
			$('#error').hide();
			if(data.valid)
			{
					window.location.href = "<?= $link_url?>completar-registro";
			}
			else
			{
			  	$('#error').html(data.error);
				$('#error').fadeIn();	
			}
		};		
        </script>
<?
	}
	if($section == 'first_login')
	{
	?>
		<script src="<?= $link_url?>assets_common/js/jquery.form.js"></script>
        <script>
        $('.ajax_form').ajaxForm({
                	// dataType identifies the expected content type of the server response 
                    dataType:  'json', 
                    // success identifies the function to invoke when the server response 
                    // has been received 
                    success:   validate_ajax_form 
                });
                
		function validate_ajax_form(data) {
			$('#error').hide();
			if(data.valid)
			{
				$('#first-time-content').hide();
				$('#first-time-header').hide();
				$('#first-time-header').html(data.message);
				$('#first-time-header').fadeIn();
			}
			else
			{
				if(data.error)
				{
					$('#error').html(data.error);
					$('#error').fadeIn();	
				}
				
				$.each(data.errors, function(key, value) {

				if(value)
					$('#contact_error_' + key ).html( value ).fadeIn();
				});	
			}
			};
        </script>
	<?	
	}
	if($section == 'complete_register')
	{
	?>
		<script src="<?= $link_url?>assets_common/js/jquery.form.js"></script>
        <script>
        $('.ajax_form').ajaxForm({
                	// dataType identifies the expected content type of the server response 
                    dataType:  'json', 
                    // success identifies the function to invoke when the server response 
                    // has been received 
                    success:   validate_ajax_form 
                });
                
		function validate_ajax_form(data) {
			$('#error').hide();
			if(data.valid)
			{
				$('#register-content').hide();
				$('#register-header').hide();
				$('#register-header').html(data.message);
				$('#register-header').fadeIn();
			}
			else
			{
				if(data.error)
				{
					$('#error').html(data.error);
					$('#error').fadeIn();	
				}
				
				$.each(data.errors, function(key, value) {

				if(value)
					$('#contact_error_' + key ).html( value ).fadeIn();
				});	
			}
			};
        </script>
	<?	
	}
	if($section == 'edit_profile')
	{
	?>
		<script src="<?= $link_url?>assets_common/js/jquery.form.js"></script>
        <script>
        $('#edit-profile-form').ajaxForm({
                	// dataType identifies the expected content type of the server response 
                    dataType:  'json', 
                    // success identifies the function to invoke when the server response 
                    // has been received 
                    success:   validate_ajax_form 
                });
                
		function validate_ajax_form(data) {
			$('#error').hide();
			if(data.valid)
			{
				window.location.href = "<?= $link_url?>mi-cuenta/";
			}
			else
			{
				$.each(data.errors, function(key, value) {

				if(value)
					$('#contact_error_' + key ).html( value ).fadeIn();
				});	
			}
		};
        </script>
	<?	
	}
	
	if($section == 'recover_password')
	{
	?>
		<script src="<?= $link_url?>assets_common/js/jquery.form.js"></script>
        <script>
        $('#recover-form').ajaxForm({
                	// dataType identifies the expected content type of the server response 
                    dataType:  'json', 
                    // success identifies the function to invoke when the server response 
                    // has been received 
                    success:   validate_ajax_form 
                });
                
		function validate_ajax_form(data) {
			$('#error').hide();
			if(data.valid)
			{
				window.location.href = "<?= $link_url?>pronosticos/";
			}
			else
			{
				$.each(data.errors, function(key, value) {

				if(value)
					$('#contact_error_' + key ).html( value ).fadeIn();
				});	
			}
		};
        </script>
	<?	
	}
	
	if($sub_section == 'create_friends_league')
	{
	?>
		<script src="<?= $link_url?>assets_common/js/jquery.form.js"></script>
        <script>
        $('#create-league-form').ajaxForm({
                	// dataType identifies the expected content type of the server response 
                    dataType:  'json', 
                    // success identifies the function to invoke when the server response 
                    // has been received 
                    success:   validate_ajax_form 
                });
                
		function validate_ajax_form(data) {
			$('#error').hide();
			if(data.valid)
			{
				$("#create-league-content").hide();
				$("#create-league-content").html('<div class="alert alert-success"><?= lang('league-created')?></div>');
				$("#create-league-content").fadeIn();
			}
			else
			{
				$("#error").html(data.error);
				$("#error").fadeIn();
			}
		};
        </script>
	<?	
	}
	
	if($sub_section == "join_friends_league")
	{
		?>
		<script src="<?= $link_url?>assets_common/js/jquery.form.js"></script>
        <script>
        $('#join-league-form').ajaxForm({
                	// dataType identifies the expected content type of the server response 
                    dataType:  'json', 
                    // success identifies the function to invoke when the server response 
                    // has been received 
                    success:   validate_ajax_form 
                });
                
		function validate_ajax_form(data) {
			$('#error').hide();
			if(data.valid)
			{
				$("#create-league-content").hide();
				$("#create-league-content").html('<div class="alert alert-success">'+data.message+'</div>');
				$("#create-league-content").fadeIn();
			}
			else
			{
				$("#error").html(data.error);
				$("#error").fadeIn();
			}
		};
        </script>
		<?	
	}
	
	if($sub_section == "view_league")
	{
		?>
		<script src="<?= $link_url?>assets_common/js/jquery.form.js"></script>
        <script>
        	$('.accept-league').click(function(){
				$.get("<?= $link_url?>/front_user/accept_in_league/"+$(this).attr("league_id")+"/"+$(this).attr("user_id"));
				$(this).parent().html('<span class="glyphicon glyphicon-ok" style="color:#5CB85C"></span>');
				});
				
			$('.decline-league').click(function(){
				$.get("<?= $link_url?>/front_user/decline_in_league/"+$(this).attr("league_id")+"/"+$(this).attr("user_id"));
				$(this).parent().html('<span class="glyphicon glyphicon-remove" style="color:#B85C5C"></span>');
				});
        </script>
		<?	
	}	
	if($section == 'forgot_password')
	{
	?>
		<script src="<?= $link_url?>assets_common/js/jquery.form.js"></script>
        <script>
        $('#recover-form').ajaxForm({
                	// dataType identifies the expected content type of the server response 
                    dataType:  'json', 
                    // success identifies the function to invoke when the server response 
                    // has been received 
                    success:   validate_ajax_form 
                });
                
		function validate_ajax_form(data) {
			$('#error').hide();
			if(data.valid)
			{
				$('#recover-content').hide();
				$('#recover-header').hide();
				$('#recover-header').html(data.message);
				$('#recover-header').fadeIn();
			}
			else
			{
				if(data.error)
				{
					$('#error').html(data.error);
					$('#error').fadeIn();	
				}
			}
		};
        </script>
	<?	
	}
	if($section == 'wall')
	{
		?>
        <script src="<?= $link_url?>assets_common/js/jquery.form.js"></script>
		<script src="<?= $link_url?>assets_fe/js/jquery.infinitescroll.js"></script>
		<script type="text/javascript">
        
		 $('#posts-container').infinitescroll({
			navSelector  : "#pagination",            
						   // selector for the paged navigation (it will be hidden)
			nextSelector : "#next-posts",    
						   // selector for the NEXT link (to page 2)
			itemSelector : ".post-list",          
						   // selector for all items you'll retrieve
		  	path: function(number){ return "<?= $link_url."muro/"?>"+number },
		 	}, function(newElements){ 

             	$('.comment-post-form').ajaxForm({
                // dataType identifies the expected content type of the server response 
                    dataType:  'json', 
                    // success identifies the function to invoke when the server response 
                    // has been received 
                    success: function(data){
							if(data.valid)
							{
								$('#post-comments-'+data.post_id).append('<div class="post-comment new-post-comment" style="display:none"><span class="comment-name">'+data.username+'</span> '+data.comment+'<br><small>'+data.date_created+'</small></div>');
								$('.new-post-comment').fadeIn();
								$("#submit-comment-"+data.post_id).attr('disabled',true);
							}
							else
							{
								alert(data.error);
							}
					}
         		});
				
				$(".comment-area").keyup(function(){
					if($(this).val())
					{
						$("#submit-comment-"+$(this).attr("post_id")).attr('disabled',false);	
					}
					else
					{
						$("#submit-comment-"+$(this).attr("post_id")).attr('disabled',true);	
					}
				});                  

            });
		
        $('#leave-comment-form').ajaxForm({
                // dataType identifies the expected content type of the server response 
                    dataType:  'json', 
                    // success identifies the function to invoke when the server response 
                    // has been received 
                    success:   validate_ajax_form 
                });
                
		function validate_ajax_form(data) {
			if(data.valid)
			{
				$('#no-comments').fadeOut();
				$('#posts-container').prepend('<div id="new-message" style="display:none"><div class="row comment-box" id="comment"><div class="col-md-12"><div class="comment-body"><div class="comment-author"><h3><span id="new-message-name">'+data.username+'</span> <small>Hoy <span id="new-message-hour">'+data.hour+'</span></small></h3></div><span id="new-message-post">'+data.comment+'</span><div class="clearfix"></div></div><div class="comments"> <form class="ajax_form comment-post-form" action="<?= $link_url?>front_user/comment_post/'+data.post_id+'" method="post"><textarea name="post_comment" class="form-control comment-area" post_id="'+data.post_id+'" placeholder="<?= $wall_post['total_comments'] ? lang('leave-comment') : lang('first-comment')?>"></textarea><input type="submit" id="submit-comment-'+data.post_id+'" class="btn btn-primary" value="Publicar" disabled="disabled"></form><div id="post-comments-'+data.post_id+'" class="post-comments"></div></div></div></div></div>');
				$('#new-message').fadeIn();
				$("#submit-wall-post").attr('disabled',true);
				
				$('.comment-post-form').ajaxForm({
                // dataType identifies the expected content type of the server response 
                    dataType:  'json', 
                    // success identifies the function to invoke when the server response 
                    // has been received 
                    success: function(data){
							if(data.valid)
							{
								$('#post-comments-'+data.post_id).append('<div class="post-comment new-post-comment" style="display:none"><span class="comment-name">'+data.username+'</span> '+data.comment+'<br><small>'+data.date_created+'</small></div>');
								$('.new-post-comment').fadeIn();
								$("#submit-comment-"+data.post_id).attr('disabled',true);
							}
							else
							{
								alert(data.error);
							}
					}
         		});
				
				$(".comment-area").keyup(function(){
					if($(this).val())
					{
						$("#submit-comment-"+$(this).attr("post_id")).attr('disabled',false);	
					}
					else
					{
						$("#submit-comment-"+$(this).attr("post_id")).attr('disabled',true);	
					}
				 });
				
			}
			else
			{
				$('#error').html(data.error)	
			}
		};		
         $("#new_wall_post").keyup(function(){
			if($(this).val())
			{
				$("#submit-wall-post").attr('disabled',false);	
			}
			else
			{
				$("#submit-wall-post").attr('disabled',true);	
			}
		});
		
		 $(".comment-area").keyup(function(){
			if($(this).val())
			{
				$("#submit-comment-"+$(this).attr("post_id")).attr('disabled',false);	
			}
			else
			{
				$("#submit-comment-"+$(this).attr("post_id")).attr('disabled',true);	
			}
		 });
		 
		 $('.comment-post-form').ajaxForm({
                // dataType identifies the expected content type of the server response 
                    dataType:  'json', 
                    // success identifies the function to invoke when the server response 
                    // has been received 
                    success: validate_comment 
         });
                
		function validate_comment(data) {
			if(data.valid)
			{
				$('#post-comments-'+data.post_id).append('<div class="post-comment new-post-comment" style="display:none"><span class="comment-name">'+data.username+'</span> '+data.comment+'<br><small>'+data.date_created+'</small></div>');
				$('.new-post-comment').fadeIn();
				$("#submit-comment-"+data.post_id).attr('disabled',true);
			}
			else
			{
				alert(data.error);
			}
		};	      
        </script>
	<?
	}
		
	if($section == 'bet')
	{?>
		<script src="<?= $link_url?>assets_common/js/jquery.form.js"></script>
        <script src="<?= $link_url?>assets_fe/js/jquery.ddslick.min.js"></script>
        <script src="<?= $link_url?>assets_fe/js/gridline.js"></script>
		<script>
			<?
			if($phase == "starter")
			{
			?>
				$('#winner1_select').ddslick({
					width:'100%',
					selectText: "<?= lang('select-country')?>",
					imagePosition:"left",
					onSelected: function(selectedData){
						$('#winner1_id').val(selectedData.selectedData.value);
					}   
				});
				$('#winner2_select').ddslick({
					width:'100%',
					selectText: "<?= lang('select-country')?>",
					imagePosition:"left",
					onSelected: function(selectedData){
						$('#winner2_id').val(selectedData.selectedData.value);
					}   
				});
				$('#winner3_select').ddslick({
					width:'100%',
					selectText: "<?= lang('select-country')?>",
					imagePosition:"left",
					onSelected: function(selectedData){
						$('#winner3_id').val(selectedData.selectedData.value);
					}   
				});
				
				$('#winners-form').ajaxForm({
				// dataType identifies the expected content type of the server response 
					dataType:  'json', 
					// success identifies the function to invoke when the server response 
					// has been received 
					success:   validate_winners_form 
				});
				
				function validate_winners_form(data) {
					if(data.valid)
					{
						window.location.href = "<?= $link_url?>mi-pronostico/";
					}
					else
					{
						$('#message').html('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'+data.error+'</div>')	
						setTimeout(function() {
							$('#message').slideDown();				
						}, 300);
					}
				};
				
				$("#modify-winners").click(function(){
					$.get( "<?= $link_url?>front_user/clean_winners/", function( data ) {
					  window.location.href = "<?= $link_url?>pronostico-ganadores/";
					});
	
				});
			<?
			}
			if($phase == "qualys")
			{
				if(!$qualys_completed)
				{
					for($i = 1; $i <= 8; $i++)
					{?>
						$('#qualy_select_<?= $i?>').ddslick({
							width:'100%',
							selectText: "<?= lang('select-country')?>",
							imagePosition:"left",
							onSelected: function(selectedData){
								$('#team_<?= $i?>_id').val(selectedData.selectedData.value);
							}   
						});
				<?
					}
				}
				?>
				
				$('#qualys-form').ajaxForm({
				// dataType identifies the expected content type of the server response 
					dataType:  'json', 
					// success identifies the function to invoke when the server response 
					// has been received 
					success:   validate_qualys_form 
				});
				
				function validate_qualys_form(data){
					if(data.valid)
					{
						window.location.href = "<?= $link_url?>mi-pronostico/";
					}
					else
					{
						$('#message').html('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'+data.error+'</div>');	
						setTimeout(function() {
							$('#message').slideDown();				
						}, 300);
					}
				};
				$("#modify-qualys").click(function(){
					$.get( "<?= $link_url?>front_user/clean_qualys/", function( data ) {
					  window.location.href = "<?= $link_url?>pronostico-qualys/";
					});
	
				});
				
				$(".select-qualy-mobile").on('change', function() {
					  $("#"+$(this).attr("q_id")).val(this.value); // or $(this).val()
					
					});
			<?
			}
			
			if(($phase == "initial") || ($phase = "final"))
			{
			?>
				function enable_match(match_id){
					$("#input-goals-1-"+match_id).prop('disabled', false).val("");
					$("#input-goals-2-"+match_id).prop('disabled', false).val("");
					$("#middle-col-"+match_id).html("-");
				}
				
				function disable_match(match_id)
				{
					$("#input-goals-1-"+match_id).prop('disabled', true);
					$("#input-goals-2-"+match_id).prop('disabled', true);
					
					$("#middle-col-"+match_id).html('<div class="spinner"><div class="double-bounce1"></div><div class="double-bounce2"></div></div>');				
					
					setTimeout(function() {
						$("#middle-col-"+match_id).html('<small><?= lang('Guardado')?></small><br><span class="btn btn-success btn-edit-match" match_id="'+match_id+'" onclick="enable_match('+match_id+')"><?= lang('editar')?></span>');				
					}, 1100);
					
				}
							
				$(".btn-edit-match").click(function(){
					var match_id = $(this).attr("match_id");	
					enable_match(match_id);
				});
	
				$(".input-goals").keyup(function(){
					var value = $(this).val();	
					if(!(value % 1 === 0))
					{
						$(this).val(0);
					}
					var result = -2;
					var match_id = $(this).attr('match_id');
					
					$("#matchrow-"+match_id).removeClass("danger");
					var goals1 = $("#input-goals-1-"+match_id).val();
					var goals2 = $("#input-goals-2-"+match_id).val();
					var match_code = $("#matchrow-"+match_id).attr("match-code");
	
					if((goals1 != "")&&(goals2 != ""))
					{	
						result = goals1 > goals2 ? -1 : (goals1 == goals2 ? 0 : 1);
					}
	
					if(result > -2)
					{
						$.ajax({url: "<?= $link_url?>front_user/auto_save/"+match_code+"/"+match_id+"/"+goals1+"/"+goals2+"/"+result});
						disable_match(match_id);
					}
				});
				
				$("#submit-btn").click(function(){
					window.location.href = "<?= $link_url?>mi-pronostico";
				});
				
				$(".choose-date").click(function(){
					$(".choose-date").removeClass("active");
					$(this).addClass("active");
					$(".zone-table").hide();
					$("#date_"+$(this).attr("date_id")).fadeIn();	
				
				});
				
				$("#complete-later").click(function(){
					$("#prognostics-form").submit();
				});
			
				function hide_form_button(formData, jqForm, options)
				{
					$('#send_button').hide();
				}
			
				$('#prognostics-form').ajaxForm({
				// dataType identifies the expected content type of the server response 
					dataType:  'json', 
					// success identifies the function to invoke when the server response 
					// has been received 
					success:   validate_prognostics_form 
				});
				
				function validate_prognostics_form(data) {
					if(data.valid)
					{
						window.location.href = "<?= $link_url?>mi-pronostico";
					}
				};
			<?
			}
			?>
            </script>
		<?
	}?>    
  </body>
</html>