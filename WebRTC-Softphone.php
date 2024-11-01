<?php
/*
Plugin Name: WebRTC Softphone
Plugin URI: http://echobyte.net/webrtc-softphone-wordpress-plugin/
Description: WebRTC Softphone for ip Calling.
Version: 0.1.1
Author: Nabeel Yasin
Author URI: http://echobyte.net
License: GPL2
*/
?>
<?php
/*  Copyright 2017  Nabeel Yasin  (email : coderslearning@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    along with this program; if not, write to the Free Software
    You should have received a copy of the GNU General Public License
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
?>
<?php
define('websp_VERSION','0.1.1');
add_action('admin_menu', 'register_websp_page');
add_action('admin_init', 'websp_options_init');

function register_websp_page() {
	add_submenu_page('options-general.php', 'WebRTC Softphone', 'WebRTC Softphone', 'manage_options', 'WebRTC-Softphone', 'websp_webrtc_softphone_settings_page');
}
websp_set_basic_options();
// add the color picker
add_action( 'admin_enqueue_scripts', 'websp_enqueue_color_picker' );
add_action('wp_enqueue_scripts', 'websp_callback_for_setting_scripts');



function websp_callback_for_setting_scripts() {

  wp_enqueue_script( 'websp-script-handle1', plugins_url('gui.js', __FILE__), array( 'jquery' ),false, true );
  wp_enqueue_script( 'websp-script-handle2', plugins_url('init.js', __FILE__), array( 'jquery' ),false, true );
  wp_enqueue_script( 'websp-script-handle3', plugins_url('sip-0.7.3.js', __FILE__), array( 'jquery' ),false, true );
  wp_enqueue_script('jquery-ui-draggable');
		
  wp_enqueue_script( 'websp-script-handle5', plugins_url('ua3.js', __FILE__), array( 'jquery' ),false, true );
// wp_enqueue_script( 'namespaceformyscript5', plugins_url('ua3.js'), array( 'jquery' ) );
}


function websp_enqueue_color_picker( $hook_suffix ) {
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'websp-script-handle', plugins_url('call.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
}
function websp_getstyle()
{
    // moved the js to an external file, you may want to change the path
   wp_enqueue_style('hrw', plugins_url('css.css', __FILE__ ), null, null, false);
}
add_action('wp_enqueue_scripts', 'websp_getstyle');

function websp_options_init() {
	register_setting('websp_options','websp');
}
function websp_webrtc_softphone_settings_page() { ?>
<div class="wrap"><h2>WebRTC Softphone <span > by <a href="http://www.echobyte.net" rel="help">Nabeel Yasin</a></span></h2>


<form method="post" action="options.php">
 <?php settings_fields('websp_options'); ?>
<?php $options = websp_get_options(); ?>
			<h4 style="max-width:700px; text-align:right; margin:0;cursor:pointer; color:#21759b" class="websp_settings"><span class="plus">+</span><span class="minus">-</span> Advanced settings</h4>
            <table class="form-table">
            	<tr valign="top"><th scope="row">Enabled/Disabled</th>
                	<td>
                    	<input name="websp[active]" type="radio" value="1" <?php checked('1', $options['active']); ?> /> Enabled<br />
                        <input name="websp[active]" type="radio" value="0" <?php checked('0', $options['active']); ?> /> Disabled
                    </td>
                </tr>
                 <tr valign="top"><th scope="row">Sip Domain</th>
                    <td><input type="text" name="websp[sipdomain]" value="<?php echo $options['sipdomain']; ?>" /></td>
                 </tr>
                 <tr valign="top"><th scope="row">WSS Server</th>
                    <td><input type="text" name="websp[wss]" value="<?php echo $options['wss']; ?>" /></td>
                 </tr>
                  <tr valign="top"><th scope="row">Auth Username</th>
                    <td><input type="text" name="websp[authusername]" value="<?php echo $options['authusername']; ?>" /></td>
                 </tr>
                 <tr valign="top"><th scope="row">Sip User Name</th>
                    <td><input type="text" name="websp[sipusername]" value="<?php echo $options['sipusername']; ?>" /></td>
                 </tr>
                 <tr valign="top"><th scope="row">Sip Password</th>
                    <td><input type="text" name="websp[sippassword]" value="<?php echo $options['sippassword']; ?>" /></td>
                 </tr>
			</table>
            <div id="settings">
            	<table class="form-table">
				<tr valign="top"><th scope="row">Icon color</th>
                	<td><input name="websp[color]" type="text" value="<?php echo $options['color']; ?>" class="websp-color-field" data-default-color="#F20000" /></td>
                </tr>
                <tr valign="top"><th scope="row">Icon color hover</th>
                	<td><input name="websp[colorhover]" type="text" value="<?php echo $options['colorhover']; ?>" class="websp-color-field-hover" data-default-color="#75eb50" /></td>
                </tr>
				<tr valign="top"><th scope="row">Appearance</th>
                	<td>
                    	<label title="right">
                        	<input type="radio" name="websp[appearance]" value="right" <?php checked('right', $options['appearance']); ?>>
                            <span>Right bottom</span>
                        </label><br />
                    	<label title="left">
                        	<input type="radio" name="websp[appearance]" value="left" <?php checked('left', $options['appearance']); ?>>
                            <span>Left bottom</span>
                        </label><br />
                    </td>
                </tr>
             </table>
			</div><!--#settings-->
             <p class="submit">
             <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
             </p>
             </form>
    </div>
<?php }
if(get_option('websp') && !is_admin()) {
	
	// Color functions to calculate borders
	function websp_changeColor($color, $direction) {
		if(!preg_match('/^#?([0-9a-f]{2})([0-9a-f]{2})([0-9a-f]{2})$/i', $color, $parts));
		if(!isset($direction) || $direction == "lighter") { $change = 45; } else { $change = -50; }
		for($i = 1; $i <= 3; $i++) {
		  $parts[$i] = hexdec($parts[$i]);
		  $parts[$i] = round($parts[$i] + $change);
		  if($parts[$i] > 255) { $parts[$i] = 255; } elseif($parts[$i] < 0) { $parts[$i] = 0; }
		  $parts[$i] = dechex($parts[$i]);
		} 
		$output = '#' . str_pad($parts[1],2,"0",STR_PAD_LEFT) . str_pad($parts[2],2,"0",STR_PAD_LEFT) . str_pad($parts[3],2,"0",STR_PAD_LEFT);
		return $output;
	}
	
	
	$options = get_option('websp');
	if(isset($options['active'])) $enabled = $options['active']; else $enabled = 0;
	if($enabled == '1') {
		// it's enables so put footer stuff here
		function websp_head() {
			$options = get_option('websp');
	       $credits = "\n<!-- WebRTC Softphone ".websp_VERSION." by Nabeel Yasin -->\n";
			$credits .="<style>";  
			$credits .=".mypage-alo-ph-circle {border-color: ".websp_changeColor($options['color'], 'darker').";}
                        .mypage-alo-ph-circle-fill {background-color:".websp_changeColor($options['color'], 'darker').";}
                        .mypage-alo-ph-img-circle {background-color: ".websp_changeColor($options['color'], 'darker').";}";
			$credits .=".mypage-alo-phone:hover .mypage-alo-ph-circle {border-color: ".websp_changeColor($options['colorhover'], 'darker').";}
                        .mypage-alo-phone:hover .mypage-alo-ph-circle-fill {background-color:".websp_changeColor($options['colorhover'], 'darker').";}
                        .mypage-alo-phone:hover .mypage-alo-ph-img-circle {background-color: ".websp_changeColor($options['colorhover'], 'darker').";}";
			$credits .="</style>";
			echo $credits;
				}
		add_action('wp_head', 'websp_head');
		
		function websp_footer() {
			$alloptions = get_option('websp');
			if($alloptions['appearance'] == 'left') {
			    $ButtonAppearance = "left:0px !important;";
			} else {
			    $ButtonAppearance = "right:0px !important;";
			}
		
			?>
			
       <!--  <a href="tel: <?php //echo $alloptions['wss']?>" class="hotlinemp" rel="nofollow"> -->
        <input type="hidden" id="sipdomain" value="<?php echo $alloptions['sipdomain']?>"/>
        <input type="hidden" id="sipwss" value="<?php echo $alloptions['wss']?>"/>
        <input type="hidden" id="sipusername" value="<?php echo $alloptions['sipusername']?>"/>
        <input type="hidden" id="authusername" value="<?php echo $alloptions['authusername']?>"/>
         <input type="hidden" id="sippassword" value="<?php echo $alloptions['sippassword']?>"/>
        <div id='phonemainbtn' class="mypage-alo-phone" style="">
        <div class="animated infinite zoomIn mypage-alo-ph-circle">
        </div>
        <div class="animated infinite pulse mypage-alo-ph-circle-fill">
        </div>
        <div class="animated infinite tada mypage-alo-ph-img-circle">
        </div>
        </div>
      <!--   </a>-->
        
        
        



        


<div id="toPopup">
<p  class="cancel"  onclick="HidePhone()">&times;</p>
 <center>
<div class="ext" id="ext">Extension:</div>
                    <div class="status">
                      
                        <div id="conn-status">
                            <span class="field">status: </span>
                            <span id="ua-status">Disconnected</span>
<!--                            <span id="ua-status" class="value"></span>-->
   <button id="ua-register"  style="display:none" class="btnclass">Register</button>
<!--          <span class="field">user: </span>
  <span class="value user"></span>-->
                        </div>
                    </div>
                </center>

  <div class='phonebook'>
    <ul id="session-list"></ul>

                           <ul id="templates">
      <li id="session-template" class="template session">
        <h2><strong class="display-name"></strong> <span style="display:none" class="uri"></span></h2>
<div>
        <button    class="green btnclass">Green</button>
        <button   class="red btnclass">Red</button>
</div>
<div style="margin-top: 10px;">
  <button  style=""  id="hold" class="Hold btnclass">Hold</button>           <button  style=""  id="Transfer" class="Transfer btnclass">Transfer</button>
</div> 
        <form class="dtmf" action="" style="display:none">
          <label>DTMF <input type="text" maxlength="1" /></label>
          <input type="submit" value="Send" />
        </form>
        <video autoplay>Video Disabled or Unavailable</video>
<!--        <ul class="messages"></ul>
        <form class="message-form" action="">
          <input type="text" placeholder="Type to send a message"/><input type="submit" value="Send" />
        </form>-->
      </li>

    </ul>  
                  
                        </div>

 <div id="phone">
                        <div class="controls">

                            <div class="ws-disconnected"></div>

                            <div class="dialbox">
                                <input type="text" id="ua-uri" class="destination" value=""/>
                                <div class="to">To:</div>
                                <div class="dial-buttons">
                                    <center><input type="submit" class="btnclass" id="ua-invite-submit" value="Call" /> 
				      <input type="submit" style="display:none" id="ua-invite-hangup" value="HangUp" /> 
</center>
                                     
<!--                                    <div class="button call">call</div> -->
                                </div>
                            </div>

                            <div class="dialpad">
                                <div class="line">
                                    <div class="button digit-1">1</div>
                                    <div class="button digit-2">2</div>
                                    <div class="button digit-3">3</div>
                                </div>
                                <div class="line-separator"></div>
                                <div class="line">
                                    <div class="button digit-4">4</div>
                                    <div class="button digit-5">5</div>
                                    <div class="button digit-6">6</div>
                                </div>
                                <div class="line-separator"></div>
                                <div class="line">
                                    <div class="button digit-7">7</div>
                                    <div class="button digit-8">8</div>
                                    <div class="button digit-9">9</div>
                                </div>
                                <div class="line-separator"></div>
                                <div class="line">
                                    <div class="button digit-asterisk">*</div>
                                    <div class="button digit-0">0</div>
                                    <div class="button digit-pound">#</div>
                                </div>
                            </div><!-- .dialpad -->



    <!-- Templates to clone Sessions and Messages -->

                        </div><!-- .controls -->


                    </div>
</div>

        
        
        
        
        
        
        
        
        
        <?php

		}
		add_action('wp_footer', 'websp_footer');
	}
} 

function websp_get_options() { // Checking and setting the default options
	if(!get_option('websp')) {
		$default_options = array(
							  'active' => 0,
							  'number' => '',
							  'color' => '#FECC02',
		                      'colorhover' => '#75eb50',
							  'appearance' => 'right',
							  'tracking' => 0,
							  'show' => ''
							  );
		add_option('websp',$default_options);
		$options = get_option('websp');
	} 
	
	$options = get_option('websp');
	
	return $options;
}
function websp_set_basic_options() {
	if(get_option('websp') && !array_key_exists('color', get_option('websp'))) {
		$options = get_option('websp');
		$default_options = array(
							  'active' => $options['active'],
							  'number' => $options['number'],
							  'color' => '#FECC02',
							  'appearance' => 'right',
		                      'colorhover' => '#75eb50',
							  'tracking' => 0,
							  'show' => ''
							  );
		update_option('websp',$default_options);
	}
}
?>