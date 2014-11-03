<?php $version = '342' ?>
<!DOCTYPE html> 
<html dir="ltr" lang="en-US"> 
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width" />
    <title>FontAwesome</title>
    <link rel="profile" href="http://gmpg.org/xfn/11" />
    <link rel="stylesheet" href="/wp-content/plugins/wp-fontawesome/media/font-awesome.min.css" />
    <style>
    	html { font: 300 14px/20px 'Helvetica Neue', Helvetica, Arial, sans-serif !important; color: #555; }
    	body { position: relative; }
    	ul.the-icons { list-style: none; padding-left: 0; float: left; width: 100%; margin: 0 0 10px; }
    	ul.the-icons li { float: left; min-width: 29%; padding: 6px 1% 6px 1%; background-color: #F6F6F6; border: 1px solid #E9E9E9; margin: 0 1% 10px 0; cursor: pointer; font-size: 1em; }
    	ul.the-icons li:hover { background-color: #EDEDED; border-color: #E0E0E0; }
    	h3 { margin: 20px 0 10px !important; text-align: center; font-weight: 600; color: #454545 !important; }    	
    	form { border-top: 1px solid #DFDFDF; border-bottom: 1px solid #D9D9D9; width: 240px; margin: 40px auto; padding: 20px 10px; background-color: #FCFCFC;}
    	label { display: block; font-weight: 600; color: #454545; font-size: 12px; margin-bottom: 16px; }
    	select, input { float: right; margin-right: 50px; font-size: 11px !important; padding: 4px 6px; margin-top: -2px; }
    	input { width: 80px; }
    	input[type=checkbox] { width: auto; margin-top: 2px; }
    	form label:last-child { margin-bottom: 0; }
    	#fa-colorpicker { position: absolute; top: 0; right: 0; margin-top: -40px; }
    </style>
    <link rel="stylesheet" href="/wp-admin/css/farbtastic.css?v=<?php echo $version ?>" /> 
    <script src="/wp-includes/js/tinymce/tiny_mce_popup.js?v=<?php echo $version ?>"></script>
    <script src="/wp-includes/js/jquery/jquery.js?v=<?php echo $version ?>"></script>
    <script src="/wp-admin/js/farbtastic.js?v=<?php echo $version ?>"></script>
    <script>
        (function($){
        	var FontAwesome_Dialog = {
	    		local_ed : 'ed',
	    		$icons : '',
	    		$size_select : '',
	    		$custom_size : '',
	    		$custom_size_wrapper : '',
	    		$colour_input : '',
	    		farb : '',	    		

	    		// Runs on initialization of window
	    		init : function( ed ) {
	    			FontAwesome_Dialog.local_ed = ed;
	    			FontAwesome_Dialog.$icons = $('li');
	    			FontAwesome_Dialog.$size_select = $('#fa-icon-size');
	    			FontAwesome_Dialog.$custom_size = $('#fa-icon-px');
	    			FontAwesome_Dialog.$custom_size_wrapper = FontAwesome_Dialog.$custom_size.parent();
	    			FontAwesome_Dialog.$colour_input = $('#fa-icon-color');

	    			FontAwesome_Dialog.$icons.on( 'click', function() {
	    				FontAwesome_Dialog.insert( $(this) );
		    		});

		    		FontAwesome_Dialog.$size_select.on( 'change', function() {
		    			FontAwesome_Dialog.toggleSizeSelect( $(this) );
		    		});

		    		FontAwesome_Dialog.$custom_size.on( 'change', function() {
		    			FontAwesome_Dialog.toggleCustomSize( $(this) );
		    		});

		    		// Set up color picker
		    		FontAwesome_Dialog.farb = $.farbtastic('#fa-colorpicker', '#fa-icon-color');
		    	
	    			tinyMCEPopup.resizeToInnerSize();
	    		},

	    		// Insert HTML into editor
	    		insert : function( $icon ) {
	    			var as_shortcode = $('#fa-shortcode').is(':checked'), 
	    				output = as_shortcode ? this.getShortcodeOutput( $icon ) : this.getHtmlOutput( $icon );

	    			// Send HTML to editor
	    			tinyMCEPopup.execCommand( 'mceInsertContent', false, output );

	    			// Return
	    			tinyMCEPopup.close();
	    		},

	    		// Get shortcode output
	    		getShortcodeOutput : function( $icon ) {
	    			var size = this.$size_select.val(),
	    				output = '[fa_icon icon="'+ $icon.data().icon +'" ';

	    			if ( this.$colour_input.val() !== '#333333' ) {
	    				output += 'colour="'+ this.$colour_input.val() +'" ';
	    			}

	    			if ( size === 'icon-large' ) {
	    				output += 'size="large"';
	    			}
	    			else if ( size === 'other' ) {
	    				output += 'size="'+ $icon.children('div').css('font-size') +'"';
	    			}

	    			output += ']';

	    			return output;
	    		},

	    		// Get HTML output 
	    		getHtmlOutput : function( $icon ) {
	    			var output;

	    			if ( FontAwesome_Dialog.$size_select.val() === 'other' ) {
	    				var $i = $icon.find('i');
	    				$i.css('font-size', $i.parent().css('font-size'));
	    				output = $i[0].outerHTML;
	    			}
	    			else {
	    				output = $icon.find('i')[0].outerHTML;
	    			}

	    			return output;
	    		},

	    		// Choose the icon size
	    		toggleSizeSelect : function( $select ) {
	    			var $icons = FontAwesome_Dialog.$icons, 
		    			i = 0,     		
		    			max = $icons.length 
		    			selected = $select.val();

	    			if ( selected === 'icon-large' ) {
	    				$icons.each( function() {    				
			    			FontAwesome_Dialog.toggleLarge( true, $(this) );
			    		});
			    		FontAwesome_Dialog.$custom_size_wrapper.hide()
	    			}
	    			else if ( selected === 'other' ) {
	    				FontAwesome_Dialog.$custom_size_wrapper.show();
	    			}
	    			else {
	    				$icons.each( function() {
							FontAwesome_Dialog.toggleLarge( false, $(this) );    						
	    				});		    		
	    				FontAwesome_Dialog.$custom_size_wrapper.hide();
	    			}
	    		},

	    		// Choose a custom item size
	    		toggleCustomSize : function( $select ) {
	    			var $icons = FontAwesome_Dialog.$icons, 
		    			i = 0,     		
		    			max = $icons.length;

		    		$icons.each( function() {
		    			// Set icon size
		    			FontAwesome_Dialog.setIconSize( $select.val(), $(this) )

		    			// Don't apply icon-large -- that just confuses things
		    			FontAwesome_Dialog.toggleLarge( false, $(this) );
		    		});    		
	    		},

	    		// Set the size of the icon
	    		setIconSize : function( size, $icon ) {
	    			$icon.children('div').css({ 'font-size' : size, 'line-height' : size });
	    		},

	    		// Set the colour of the icon
	    		setIconColour : function( color, $icon ) {
	    			$icon.find('i').first().css({ 'color' : color });
	    		},

	    		// Toggle icon-large class on/off
	    		toggleLarge : function( on, $icon ) {
	    			var $i = $icon.find('i').first();

	    			$i.toggleClass('icon-large', !on);    			
					if ( on ) {$i.css( 'font-size', '1.333em' ); }
					else { $i.css( 'font-size', '1em'); }
	    		}
	    	}

	    	tinyMCEPopup.onInit.add(FontAwesome_Dialog.init, FontAwesome_Dialog);

	    	$(document).ready( function(){
	    		$('#fa-colorpicker').on( 'mouseover', function() {
	    			$('#fa-colorpicker').on( 'mouseup', function() {	
	    				var color = FontAwesome_Dialog.farb.color;	
	    				FontAwesome_Dialog.$icons.each( function() {	    					
	    					FontAwesome_Dialog.setIconColour( color, $(this) );
	    				});	    				
	    			});
	    		});
	    		$('#fa-colorpicker').on( 'mouseout', function() {
	    			$(document).off( 'mouseup', '#fa-colorpicker' );
	    		});
	    	});

        })(jQuery);    	
    </script>
</head>
<body>
	
	<form>
		<label for="fa-icon-size">		
			Icon size
			<select name="fa-icon-size" id="fa-icon-size">
				<option value="">Small</option>
				<option value="icon-large">Large</option>
				<option value="other">Other</option>
			</select>			
		</label>		

		<label for="fa-icon-px" id="fa-icon-px-wrapper" style="display: none;">
			Custom size:
			<select name="fa-icon-px" id="fa-icon-px">
			<?php for ( $i = 4; $i < 101; $i += 1 ) : ?>
				<option value="<?php echo $i ?>px" <?php if ($i == 11) : ?>selected<?php endif ?>><?php echo $i ?>px</option>
			<?php endfor ?>
			</select>
		</label>

		<label for="fa-icon-color">
			Icon colour:
			<input type="text" name="fa-icon-color" id="fa-icon-color" value="#333333" />
			<div id="fa-colorpicker"></div>			
		</label>		

		<label for="fa-shortcode">
			Output as shortcode:
			<input type="checkbox" name="fa-shortcode" id="fa-shortcode" />
		</label>
	</form>
	
	<h3>Web Application Icons</h3>
	<ul class="the-icons">
		<li data-icon="adjust"><div><i class="icon-adjust"></i> icon-adjust</div></li>
		<li data-icon="asterisk"><div><i class="icon-asterisk"></i> icon-asterisk</div></li>
		<li data-icon="ban-circle"><div><i class="icon-ban-circle"></i> icon-ban-circle</div></li>
		<li data-icon="bar-chart"><div><i class="icon-bar-chart"></i> icon-bar-chart</div></li>
		<li data-icon="barcode"><div><i class="icon-barcode"></i> icon-barcode</div></li>
		<li data-icon="beaker"><div><i class="icon-beaker"></i> icon-beaker</div></li>
		<li data-icon="bell"><div><i class="icon-bell"></i> icon-bell</div></li>
		<li data-icon="bolt"><div><i class="icon-bolt"></i> icon-bolt</div></li>
		<li data-icon="book"><div><i class="icon-book"></i> icon-book</div></li>
		<li data-icon="bookmark"><div><i class="icon-bookmark"></i> icon-bookmark</div></li>
		<li data-icon="bookmark-empty"><div><i class="icon-bookmark-empty"></i> icon-bookmark-empty</div></li>
		<li data-icon="briefcase"><div><i class="icon-briefcase"></i> icon-briefcase</div></li>
		<li data-icon="bullhorn"><div><i class="icon-bullhorn"></i> icon-bullhorn</div></li>
		<li data-icon="calendar"><div><i class="icon-calendar"></i> icon-calendar</div></li>
		<li data-icon="camera"><div><i class="icon-camera"></i> icon-camera</div></li>
		<li data-icon="camera-retro"><div><i class="icon-camera-retro"></i> icon-camera-retro</div></li>
		<li data-icon="certificate"><div><i class="icon-certificate"></i> icon-certificate</div></li>
		<li data-icon="check"><div><i class="icon-check"></i> icon-check</div></li>
		<li data-icon="check-empty"><div><i class="icon-check-empty"></i> icon-check-empty</div></li>
		<li data-icon="cloud"><div><i class="icon-cloud"></i> icon-cloud</div></li>
		<li data-icon="cog"><div><i class="icon-cog"></i> icon-cog</div></li>
		<li data-icon="cogs"><div><i class="icon-cogs"></i> icon-cogs</div></li>
		<li data-icon="comment"><div><i class="icon-comment"></i> icon-comment</div></li>
		<li data-icon="comment-alt"><div><i class="icon-comment-alt"></i> icon-comment-alt</div></li>
		<li data-icon="comments"><div><i class="icon-comments"></i> icon-comments</div></li>
		<li data-icon="comments-alt"><div><i class="icon-comments-alt"></i> icon-comments-alt</div></li>
		<li data-icon="credit-card"><div><i class="icon-credit-card"></i> icon-credit-card</div></li>
		<li data-icon="dashboard"><div><i class="icon-dashboard"></i> icon-dashboard</div></li>
		<li data-icon="download"><div><i class="icon-download"></i> icon-download</div></li>
		<li data-icon="download-alt"><div><i class="icon-download-alt"></i> icon-download-alt</div></li>
		<li data-icon="edit"><div><i class="icon-edit"></i> icon-edit</div></li>
		<li data-icon="envelope"><div><i class="icon-envelope"></i> icon-envelope</div></li>
		<li data-icon="envelope-alt"><div><i class="icon-envelope-alt"></i> icon-envelope-alt</div></li>
		<li data-icon="exclamation-sign"><div><i class="icon-exclamation-sign"></i> icon-exclamation-sign</div></li>
		<li data-icon="external-link"><div><i class="icon-external-link"></i> icon-external-link</div></li>
		<li data-icon="eye-close"><div><i class="icon-eye-close"></i> icon-eye-close</div></li>
		<li data-icon="eye-open"><div><i class="icon-eye-open"></i> icon-eye-open</div></li>
		<li data-icon="facetime-video"><div><i class="icon-facetime-video"></i> icon-facetime-video</div></li>
		<li data-icon="film"><div><i class="icon-film"></i> icon-film</div></li>
		<li data-icon="filter"><div><i class="icon-filter"></i> icon-filter</div></li>
		<li data-icon="fire"><div><i class="icon-fire"></i> icon-fire</div></li>
		<li data-icon="flag"><div><i class="icon-flag"></i> icon-flag</div></li>
		<li data-icon="folder-close"><div><i class="icon-folder-close"></i> icon-folder-close</div></li>
		<li data-icon="folder-open"><div><i class="icon-folder-open"></i> icon-folder-open</div></li>
		<li data-icon="gift"><div><i class="icon-gift"></i> icon-gift</div></li>
		<li data-icon="glass"><div><i class="icon-glass"></i> icon-glass</div></li>
		<li data-icon="globe"><div><i class="icon-globe"></i> icon-globe</div></li>
		<li data-icon="group"><div><i class="icon-group"></i> icon-group</div></li>
		<li data-icon="hdd"><div><i class="icon-hdd"></i> icon-hdd</div></li>
		<li data-icon="headphones"><div><i class="icon-headphones"></i> icon-headphones</div></li>
		<li data-icon="heart"><div><i class="icon-heart"></i> icon-heart</div></li>
		<li data-icon="heart-empty"><div><i class="icon-heart-empty"></i> icon-heart-empty</div></li>
		<li data-icon="home"><div><i class="icon-home"></i> icon-home</div></li>
		<li data-icon="inbox"><div><i class="icon-inbox"></i> icon-inbox</div></li>
		<li data-icon="info-sign"><div><i class="icon-info-sign"></i> icon-info-sign</div></li>
		<li data-icon="key"><div><i class="icon-key"></i> icon-key</div></li>
		<li data-icon="leaf"><div><i class="icon-leaf"></i> icon-leaf</div></li>
		<li data-icon="legal"><div><i class="icon-legal"></i> icon-legal</div></li>
		<li data-icon="lemon"><div><i class="icon-lemon"></i> icon-lemon</div></li>
		<li data-icon="lock"><div><i class="icon-lock"></i> icon-lock</div></li>
		<li data-icon="unlock"><div><i class="icon-unlock"></i> icon-unlock</div></li>
		<li data-icon="magic"><div><i class="icon-magic"></i> icon-magic</div></li>
		<li data-icon="magnet"><div><i class="icon-magnet"></i> icon-magnet</div></li>
		<li data-icon="map-marker"><div><i class="icon-map-marker"></i> icon-map-marker</div></li>
		<li data-icon="minus"><div><i class="icon-minus"></i> icon-minus</div></li>
		<li data-icon="minus-sign"><div><i class="icon-minus-sign"></i> icon-minus-sign</div></li>
		<li data-icon="money"><div><i class="icon-money"></i> icon-money</div></li>
		<li data-icon="move"><div><i class="icon-move"></i> icon-move</div></li>
		<li data-icon="music"><div><i class="icon-music"></i> icon-music</div></li>
		<li data-icon="off"><div><i class="icon-off"></i> icon-off</div></li>
		<li data-icon="ok"><div><i class="icon-ok"></i> icon-ok</div></li>
		<li data-icon="ok-circle"><div><i class="icon-ok-circle"></i> icon-ok-circle</div></li>
		<li data-icon="ok-sign"><div><i class="icon-ok-sign"></i> icon-ok-sign</div></li>
		<li data-icon="pencil"><div><i class="icon-pencil"></i> icon-pencil</div></li>
		<li data-icon="picture"><div><i class="icon-picture"></i> icon-picture</div></li>
		<li data-icon="plane"><div><i class="icon-plane"></i> icon-plane</div></li>
		<li data-icon="plus"><div><i class="icon-plus"></i> icon-plus</div></li>
		<li data-icon="plus-sign"><div><i class="icon-plus-sign"></i> icon-plus-sign</div></li>
		<li data-icon="print"><div><i class="icon-print"></i> icon-print</div></li>
		<li data-icon="pushpin"><div><i class="icon-pushpin"></i> icon-pushpin</div></li>
		<li data-icon="qrcode"><div><i class="icon-qrcode"></i> icon-qrcode</div></li>
		<li data-icon="question-sign"><div><i class="icon-question-sign"></i> icon-question-sign</div></li>
		<li data-icon="random"><div><i class="icon-random"></i> icon-random</div></li>
		<li data-icon="refresh"><div><i class="icon-refresh"></i> icon-refresh</div></li>
		<li data-icon="remove"><div><i class="icon-remove"></i> icon-remove</div></li>
		<li data-icon="remove-circle"><div><i class="icon-remove-circle"></i> icon-remove-circle</div></li>
		<li data-icon="remove-sign"><div><i class="icon-remove-sign"></i> icon-remove-sign</div></li>
		<li data-icon="reorder"><div><i class="icon-reorder"></i> icon-reorder</div></li>
		<li data-icon="resize-horizontal"><div><i class="icon-resize-horizontal"></i> icon-resize-horizontal</div></li>
		<li data-icon="resize-vertical"><div><i class="icon-resize-vertical"></i> icon-resize-vertical</div></li>
		<li data-icon="retweet"><div><i class="icon-retweet"></i> icon-retweet</div></li>
		<li data-icon="road"><div><i class="icon-road"></i> icon-road</div></li>
		<li data-icon="rss"><div><i class="icon-rss"></i> icon-rss</div></li>
		<li data-icon="screenshot"><div><i class="icon-screenshot"></i> icon-screenshot</div></li>
		<li data-icon="search"><div><i class="icon-search"></i> icon-search</div></li>
		<li data-icon="share"><div><i class="icon-share"></i> icon-share</div></li>
		<li data-icon="share-alt"><div><i class="icon-share-alt"></i> icon-share-alt</div></li>
		<li data-icon="shopping-cart"><div><i class="icon-shopping-cart"></i> icon-shopping-cart</div></li>
		<li data-icon="signal"><div><i class="icon-signal"></i> icon-signal</div></li>
		<li data-icon="signin"><div><i class="icon-signin"></i> icon-signin</div></li>
		<li data-icon="signout"><div><i class="icon-signout"></i> icon-signout</div></li>
		<li data-icon="sitemap"><div><i class="icon-sitemap"></i> icon-sitemap</div></li>
		<li data-icon="sort"><div><i class="icon-sort"></i> icon-sort</div></li>
		<li data-icon="sort-down"><div><i class="icon-sort-down"></i> icon-sort-down</div></li>
		<li data-icon="sort-up"><div><i class="icon-sort-up"></i> icon-sort-up</div></li>
		<li data-icon="star"><div><i class="icon-star"></i> icon-star</div></li>
		<li data-icon="star-empty"><div><i class="icon-star-empty"></i> icon-star-empty</div></li>
		<li data-icon="star-half"><div><i class="icon-star-half"></i> icon-star-half</div></li>
		<li data-icon="tag"><div><i class="icon-tag"></i> icon-tag</div></li>
		<li data-icon="tags"><div><i class="icon-tags"></i> icon-tags</div></li>
		<li data-icon="tasks"><div><i class="icon-tasks"></i> icon-tasks</div></li>
		<li data-icon="thumbs-down"><div><i class="icon-thumbs-down"></i> icon-thumbs-down</div></li>
		<li data-icon="thumbs-up"><div><i class="icon-thumbs-up"></i> icon-thumbs-up</div></li>
		<li data-icon="time"><div><i class="icon-time"></i> icon-time</div></li>
		<li data-icon="tint"><div><i class="icon-tint"></i> icon-tint</div></li>
		<li data-icon="trash"><div><i class="icon-trash"></i> icon-trash</div></li>
		<li data-icon="trophy"><div><i class="icon-trophy"></i> icon-trophy</div></li>
		<li data-icon="truck"><div><i class="icon-truck"></i> icon-truck</div></li>
		<li data-icon="umbrella"><div><i class="icon-umbrella"></i> icon-umbrella</div></li>
		<li data-icon="upload"><div><i class="icon-upload"></i> icon-upload</div></li>
		<li data-icon="upload-alt"><div><i class="icon-upload-alt"></i> icon-upload-alt</div></li>
		<li data-icon="user"><div><i class="icon-user"></i> icon-user</div></li>
		<li data-icon="user-md"><div><i class="icon-user-md"></i> icon-user-md</div></li>
		<li data-icon="volume-off"><div><i class="icon-volume-off"></i> icon-volume-off</div></li>
		<li data-icon="volume-down"><div><i class="icon-volume-down"></i> icon-volume-down</div></li>
		<li data-icon="volume-up"><div><i class="icon-volume-up"></i> icon-volume-up</div></li>
		<li data-icon="warning-sign"><div><i class="icon-warning-sign"></i> icon-warning-sign</div></li>
		<li data-icon="wrench"><div><i class="icon-wrench"></i> icon-wrench</div></li>
		<li data-icon="zoom-in"><div><i class="icon-zoom-in"></i> icon-zoom-in</div></li>
		<li data-icon="zoom-out"><div><i class="icon-zoom-out"></i> icon-zoom-out</div></li>
	</ul>

	<h3>Text Editor Icons</h3>

	<ul class="the-icons">
		<li data-icon="file"><div><i class="icon-file"></i> icon-file</div></li>
		<li data-icon="cut"><div><i class="icon-cut"></i> icon-cut</div></li>
		<li data-icon="copy"><div><i class="icon-copy"></i> icon-copy</div></li>
		<li data-icon="paste"><div><i class="icon-paste"></i> icon-paste</div></li>
		<li data-icon="save"><div><i class="icon-save"></i> icon-save</div></li>
		<li data-icon="undo"><div><i class="icon-undo"></i> icon-undo</div></li>
		<li data-icon="repeat"><div><i class="icon-repeat"></i> icon-repeat</div></li>
		<li data-icon="paper-clip"><div><i class="icon-paper-clip"></i> icon-paper-clip</div></li>
		<li data-icon="text-height"><div><i class="icon-text-height"></i> icon-text-height</div></li>
		<li data-icon="text-width"><div><i class="icon-text-width"></i> icon-text-width</div></li>
		<li data-icon="align-left"><div><i class="icon-align-left"></i> icon-align-left</div></li>
		<li data-icon="align-center"><div><i class="icon-align-center"></i> icon-align-center</div></li>
		<li data-icon="align-right"><div><i class="icon-align-right"></i> icon-align-right</div></li>
		<li data-icon="align-justify"><div><i class="icon-align-justify"></i> icon-align-justify</div></li>
		<li data-icon="indent-left"><div><i class="icon-indent-left"></i> icon-indent-left</div></li>
		<li data-icon="indent-right"><div><i class="icon-indent-right"></i> icon-indent-right</div></li>
		<li data-icon="font"><div><i class="icon-font"></i> icon-font</div></li>
		<li data-icon="bold"><div><i class="icon-bold"></i> icon-bold</div></li>
		<li data-icon="italic"><div><i class="icon-italic"></i> icon-italic</div></li>
		<li data-icon="strikethrough"><div><i class="icon-strikethrough"></i> icon-strikethrough</div></li>
		<li data-icon="underline"><div><i class="icon-underline"></i> icon-underline</div></li>
		<li data-icon="link"><div><i class="icon-link"></i> icon-link</div></li>
		<li data-icon="columns"><div><i class="icon-columns"></i> icon-columns</div></li>
		<li data-icon="table"><div><i class="icon-table"></i> icon-table</div></li>
		<li data-icon="th-large"><div><i class="icon-th-large"></i> icon-th-large</div></li>
		<li data-icon="th"><div><i class="icon-th"></i> icon-th</div></li>
		<li data-icon="th-list"><div><i class="icon-th-list"></i> icon-th-list</div></li>
		<li data-icon="list"><div><i class="icon-list"></i> icon-list</div></li>
		<li data-icon="list-ol"><div><i class="icon-list-ol"></i> icon-list-ol</div></li>
		<li data-icon="list-ul"><div><i class="icon-list-ul"></i> icon-list-ul</div></li>
		<li data-icon="list-alt"><div><i class="icon-list-alt"></i> icon-list-alt</div></li>
	</ul>

	<h3>Directional Icons</h3>

	<ul class="the-icons">
		<li data-icon="arrow-down"><div><i class="icon-arrow-down"></i> icon-arrow-down</div></li>
		<li data-icon="arrow-left"><div><i class="icon-arrow-left"></i> icon-arrow-left</div></li>
		<li data-icon="arrow-right"><div><i class="icon-arrow-right"></i> icon-arrow-right</div></li>
		<li data-icon="arrow-up"><div><i class="icon-arrow-up"></i> icon-arrow-up</div></li>
		<li data-icon="chevron-down"><div><i class="icon-chevron-down"></i> icon-chevron-down</div></li>
		<li data-icon="circle-arrow-down"><div><i class="icon-circle-arrow-down"></i> icon-circle-arrow-down</div></li>
		<li data-icon="circle-arrow-left"><div><i class="icon-circle-arrow-left"></i> icon-circle-arrow-left</div></li>
		<li data-icon="circle-arrow-right"><div><i class="icon-circle-arrow-right"></i> icon-circle-arrow-right</div></li>
		<li data-icon="circle-arrow-up"><div><i class="icon-circle-arrow-up"></i> icon-circle-arrow-up</div></li>
		<li data-icon="chevron-left"><div><i class="icon-chevron-left"></i> icon-chevron-left</div></li>
		<li data-icon="caret-down"><div><i class="icon-caret-down"></i> icon-caret-down</div></li>
		<li data-icon="caret-left"><div><i class="icon-caret-left"></i> icon-caret-left</div></li>
		<li data-icon="caret-right"><div><i class="icon-caret-right"></i> icon-caret-right</div></li>
		<li data-icon="caret-up"><div><i class="icon-caret-up"></i> icon-caret-up</div></li>
		<li data-icon="chevron-right"><div><i class="icon-chevron-right"></i> icon-chevron-right</div></li>
		<li data-icon="hand-down"><div><i class="icon-hand-down"></i> icon-hand-down</div></li>
		<li data-icon="hand-left"><div><i class="icon-hand-left"></i> icon-hand-left</div></li>
		<li data-icon="hand-right"><div><i class="icon-hand-right"></i> icon-hand-right</div></li>
		<li data-icon="hand-up"><div><i class="icon-hand-up"></i> icon-hand-up</div></li>
		<li data-icon="chevron-up"><div><i class="icon-chevron-up"></i> icon-chevron-up</div></li>
	</ul>

	<h3>Video Player Icons</h3>

	<ul class="the-icons">
		<li data-icon="play-circle"><div><i class="icon-play-circle"></i> icon-play-circle</div></li>
		<li data-icon="play"><div><i class="icon-play"></i> icon-play</div></li>
		<li data-icon="pause"><div><i class="icon-pause"></i> icon-pause</div></li>
		<li data-icon="stop"><div><i class="icon-stop"></i> icon-stop</div></li>
		<li data-icon="step-backward"><div><i class="icon-step-backward"></i> icon-step-backward</div></li>
		<li data-icon="fast-backward"><div><i class="icon-fast-backward"></i> icon-fast-backward</div></li>
		<li data-icon="backward"><div><i class="icon-backward"></i> icon-backward</div></li>
		<li data-icon="forward"><div><i class="icon-forward"></i> icon-forward</div></li>
		<li data-icon="fast-forward"><div><i class="icon-fast-forward"></i> icon-fast-forward</div></li>
		<li data-icon="step-forward"><div><i class="icon-step-forward"></i> icon-step-forward</div></li>
		<li data-icon="eject"><div><i class="icon-eject"></i> icon-eject</div></li>
		<li data-icon="fullscreen"><div><i class="icon-fullscreen"></i> icon-fullscreen</div></li>
		<li data-icon="resize-full"><div><i class="icon-resize-full"></i> icon-resize-full</div></li>
		<li data-icon="resize-small"><div><i class="icon-resize-small"></i> icon-resize-small</div></li>
	</ul>

	<h3>Social Icons</h3>

	<ul class="the-icons">
		<li data-icon="phone"><div><i class="icon-phone"></i> icon-phone</div></li>
		<li data-icon="phone-sign"><div><i class="icon-phone-sign"></i> icon-phone-sign</div></li>
		<li data-icon="facebook"><div><i class="icon-facebook"></i> icon-facebook</div></li>
		<li data-icon="facebook-sign"><div><i class="icon-facebook-sign"></i> icon-facebook-sign</div></li>
		<li data-icon="twitter"><div><i class="icon-twitter"></i> icon-twitter</div></li>
		<li data-icon="twitter-sign"><div><i class="icon-twitter-sign"></i> icon-twitter-sign</div></li>
		<li data-icon="github"><div><i class="icon-github"></i> icon-github</div></li>
		<li data-icon="github-sign"><div><i class="icon-github-sign"></i> icon-github-sign</div></li>
		<li data-icon="linkedin"><div><i class="icon-linkedin"></i> icon-linkedin</div></li>
		<li data-icon="linkedin-sign"><div><i class="icon-linkedin-sign"></i> icon-linkedin-sign</div></li>
		<li data-icon="pinterest"><div><i class="icon-pinterest"></i> icon-pinterest</div></li>
		<li data-icon="pinterest-sign"><div><i class="icon-pinterest-sign"></i> icon-pinterest-sign</div></li>
		<li data-icon="google-plus"><div><i class="icon-google-plus"></i> icon-google-plus</div></li>
		<li data-icon="google-plus-sign"><div><i class="icon-google-plus-sign"></i> icon-google-plus-sign</div></li>
		<li data-icon="sign-blank"><div><i class="icon-sign-blank"></i> icon-sign-blank</div></li>
	</ul>
	
</body>
</html>