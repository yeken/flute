<script type="text/javascript">
$(document).ready(function() {
	$( "#radio" ).buttonset();
});
</script>
<script type="text/javascript" class="source below">
$(function () {

$("#tree_container")
	.jstree({ 
		// List of active plugins
		"plugins" : [ 
			"themes", "json_data","ui","crrm","cookies", "search", "types", "hotkeys"
		],

		// I usually configure the plugin that handles the data first
		// This example uses JSON  	as it is most common
		"json_data" : { 
			// This tree is ajax enabled - as this is most common, and maybe a bit more complex
			// All the options are almost the same as jQuery's AJAX (read the docs)
			"ajax" : {
				// the URL to fetch the data
				"url" : "<?=base_url().$controller_name.'/ajax_tree';?>",
				// the `data` function is executed in the instance's scope
				// the parameter is the node being loaded 
				// (may be -1, 0, or undefined when loading the root nodes)
				"data" : function (n) { 
					// the result is fed to the AJAX request `data` option
					return { 
						"operation" : "get_children", 
						"id" : n.attr ? n.attr("id").replace("node_","") : 1 
					}; 
				}
			}
		},
		// Configuring the search plugin
		"search" : {
			// As this has been a common question - async search
			// Same as above - the `ajax` config option is actually jQuery's AJAX object
			"ajax" : {
				"url" : "<?=base_url().$controller_name.'/ajax_tree';?>",
				// You get the search string as a parameter
				"data" : function (str) {
					return { 
						"operation" : "search", 
						"search_str" : str 
					}; 
				}
			}
		},
		// Using types - most of the time this is an overkill
		// read the docs carefully to decide whether you need types
		"types" : {
			// I set both options to -2, as I do not need depth and children count checking
			// Those two checks may slow jstree a lot, so use only when needed
			"max_depth" : -2,
			"max_children" : -2,
			// I want only `drive` nodes to be root nodes 
			// This will prevent moving or creating any other type as a root node
			"valid_children" : [ "drive" ],
			"types" : {
				// The default type
				"default" : {
					// can have files and other folders inside of it, but NOT `drive` nodes
					"valid_children" : [ "default", "folder" ],
					"icon" : {
						"image" : "<?=base_url().'assets_be/images/tree/';?>folder.png"
					}
				},
				// The `folder` type
				"folder" : {
					// can have files and other folders inside of it, but NOT `drive` nodes
					"valid_children" : [ "default", "folder" ],
					"icon" : {
						"image" : "<?=base_url().'assets_be/images/tree/';?>folder.png"
					}
				},
				// The `drive` nodes 
				"drive" : {
					// can have files and folders inside, but NOT other `drive` nodes
					"valid_children" : [ "default", "folder" ],
					"icon" : {
						"image" : "<?=base_url().'assets_be/images/tree/';?>root.png"
					},
					// those prevent the functions with the same name to be used on `drive` nodes
					// internally the `before` event is used
					"start_drag" : false,
					"move_node" : false,
					"delete_node" : false,
					"remove" : false
				}
			}
		},
		// UI & core - the nodes to initially select and open will be overwritten by the cookie plugin

		// the UI plugin - it handles selecting/deselecting/hovering nodes
		"ui" : {
			// this makes the node with ID node_4 selected onload
			"initially_select" : [ "node_0" ]
		},
		// the core plugin - not many options here
		"core" : { 
			// just open those two nodes up
			// as this is an AJAX enabled tree, both will be downloaded from the server
			"initially_open" : [ "node_2" , "node_3" ] 
		},
		

	})
	.bind("create.jstree", function (e, data) {
		$.post(
			"<?=base_url().$controller_name.'/ajax_tree';?>", 
			{ 
				"operation" : "create_node", 
				"id" : data.rslt.parent.attr("id").replace("node_",""), 
				"position" : data.rslt.position,
				"title" : data.rslt.name,
				"type" : 'folder'
			}, 
			function (r) {
				if(r.status) {
					$(data.rslt.obj).attr("id", "node_" + r.id);
					
				}
				else {
					$.jstree.rollback(data.rlbk);
				}
			}
		);
	})
	.bind("remove.jstree", function (e, data) {
		data.rslt.obj.each(function () {
			$.ajax({
				async : false,
				type: 'POST',
				url: "<?=base_url().$controller_name.'/ajax_tree';?>",
				data : { 
					"operation" : "remove_node", 
					"id" : this.id.replace("node_","")
				}, 
				success : function (r) {
					if(!r.status) {
						data.inst.refresh();
					}
				}
			});
		});
	})
	.bind("rename.jstree", function (e, data) {
		$.post(
			"<?=base_url().$controller_name.'/ajax_tree';?>", 
			{ 
				"operation" : "rename_node", 
				"id" : data.rslt.obj.attr("id").replace("node_",""),
				"title" : data.rslt.new_name
			}, 
			function (r) {
				if(!r.status) {
					$.jstree.rollback(data.rlbk);
				}
			}
		);
	})
	.bind("dblclick.jstree", function (e, data) {
            var node = $(e.target).closest("li");
            var id = node[0].id; //id of the selected node
			id = id.split("_")[1];
			
			panel_container = true;
			
			load_panel_2_ajax( "<?=$this->class_name?>/details/"+id);
			
	 })

	
	.bind("move_node.jstree", function (e, data) {
		data.rslt.o.each(function (i) {
			$.ajax({
				async : false,
				type: 'POST',
				url: "<?=base_url().$controller_name.'/ajax_tree';?>",
				data : { 
					"operation" : "move_node", 
					"id" : $(this).attr("id").replace("node_",""), 
					"ref" : data.rslt.cr === -1 ? 1 : data.rslt.np.attr("id").replace("node_",""), 
					"position" : data.rslt.cp + i,
					"title" : data.rslt.name,
					"copy" : data.rslt.cy ? 1 : 0
				},
				success : function (r) {
					if(!r.status) {
						$.jstree.rollback(data.rlbk);
					}
					else {
						$(data.rslt.oc).attr("id", "node_" + r.id);
						if(data.rslt.cy && $(data.rslt.oc).children("UL").length) {
							data.inst.refresh(data.inst._get_parent(data.rslt.oc));
						}
					}
					$("#analyze").click();
				}
			});
		});
	});

});
</script>
<script type="text/javascript" class="source below">
// Code for the menu buttons
$(function () { 
	$("#tree_menu input").click(function () {
		switch(this.id) {
			case "add_default":
			case "add_folder":
				$("#tree_container").jstree("create", null, "last", { "attr" : { "rel" : this.id.toString().replace("add_", "") } });
				break;
			case "search":
				$("#tree_container").jstree("search", document.getElementById("text").value);
				break;
			case "text": break;
			default:
				$("#tree_container").jstree(this.id);
				break;
		}
	});
	
	$('.button').button();
});
</script>

<div class="panel panel_30">

    <div id="tree_menu" style="height:75px; overflow:auto; margin:5px;">
		
        <input type="button" class="button" id="add_folder" value="Agregar secci&oacute;n" style="display:block; float:left;"/>
        <input type="button" class="button"id="rename" value="Renombrar" style="display:block; float:left;"/>
        <input type="button" class="button"id="remove" value="Eliminar" style="display:block; float:left;"/>
		<input type="button" class="button" style=" float:left;" value="Refrezcar" onclick="$('#tree_container').jstree('refresh',-1);" />

		<!--
        <input type="button" id="cut" value="Cortar" style="display:block; float:left;"/>
        <input type="button" id="copy" value="Copiar" style="display:block; float:left;"/>
        <input type="button" id="paste" value="Pegar" style="display:block; float:left;"/>
		-->
        <br clear="all" />
        <div style="float:left; margin-top:5px;">
            <input type="text" id="text" value="" style="display:block; float:left;" />
            <input type="button" class="button" id="search" value="Buscar" style="display:block; float:left;"/>
		</div>
        
    </div>

    <div id="tree_container" class="tree_container" style="margin-top:10px;"></div>

    <div style="height:30px; text-align:center;">

    </div>
</div>

<div id="panel_2">

</div>