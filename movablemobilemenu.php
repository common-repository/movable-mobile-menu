<?php
/**
 * Plugin Name:  Movable Mobile Menu
 * Description: Movable Mobile Menu is a menu which appeares on mobile devices for improved navigation.
 * Plugin URI:  https://qanva.tech/movablemobilemenu
 * Version:     1.0.1
 * Author:      qanva.tech - ukischkel
 * Author URI:  https://qanva.tech
 * License:		GPL v2
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: movablemobilemenu
 * Domain Path: languages
*/

if ( ! defined( 'ABSPATH' ) ) exit; 

	add_action( 'plugins_loaded', 'ladesprachdateifuerfloatmenu' );

		function ladesprachdateifuerfloatmenu() {
			load_plugin_textdomain( 'movablemobilemenu', false, dirname( plugin_basename(__FILE__) ) . '/languages' ); 
		} 
		
		$movablemobilemenudescription = __( "Movable Mobile Menu is a menu which appeares on mobile devices for improved navigation.", "movablemobilemenu" );
	
		/** Link on Plugin page **/
		function floatmenuadddashlinks( array $links, $plugin_file_name, $plugin_data, $status ) {
            if( strpos( $plugin_file_name, basename(__FILE__) ) ){
                $url = get_admin_url() . 'options-general.php?page=' . basename( __DIR__ ) . '/' . basename( __FILE__ );
                $teamlinks = '<a href="' . $url . '" style="color:#39b54a;">' .  __( "Help", "movablemobilemenu" ) . '</a>';
                $links[ 'floatmenu-info' ] = $teamlinks;   
                $urlb = 'https://qanva.tech/movablemobilemenupro';
                $teamlinksb = '<a href="' . $urlb . '" style="color:red;font-weight:bold" target="_blank">' .  __( "Go Pro", "movablemobilemenu" ) . '</a>';
                $links[ 'movablemobilemenu-pro' ] = $teamlinksb;  
            }
    		return $links;
		}
		
		add_filter( 'plugin_row_meta', 'floatmenuadddashlinks', 10, 4 );	

		/** help page **/
		function floatmenusettingmenue() {
		   add_options_page( '', '', 'manage_options', __FILE__, 'floatmenusettingpage' );
		}
		
		add_action( 'admin_menu', 'floatmenusettingmenue' );

		function floatmenusettingpage() {
            include_once 'admin/help.php';
		}	

	final class Mobilefloatmenu{
			
		const MINIMUM_PHP_VERSION = '7.0';

		public function __construct() {
			add_action( 'after_setup_theme', array( $this, 'floatmenuregistermobilemenu' ) );	
			add_action( 'wp_footer', array( $this, 'floatmenucss' ) );
			add_action( 'wp_footer', array( $this, 'floatmenudivs' ) );
			add_action( 'wp_footer', array( $this, 'floatmenujs' ) );
			add_action( 'plugins_loaded', array( $this, 'floatmenuinit' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'floatmenuscripts' ) );
			add_action( 'admin_footer', array( $this, 'movablemobilemenuremovelinks' ) );
		}
				
		/** add scripts **/
		public function floatmenuscripts() {
			wp_enqueue_script( 'jquery', false, array(), null, true );
			wp_enqueue_script( 'draganddrop', plugin_dir_url( __FILE__ ) . 'js/draggableTouch.js', 'jquery', true );
			/** add dashicons to frontend **/
			wp_enqueue_style( 'dashicons' );
		}
        
        /** remove links  **/
        public function movablemobilemenuremovelinks(){
            ?>
            <script id="movablemobilemenulinks">
                setTimeout(function(){
                    jQuery( 'li a[href="options-general.php?page=movablemobilemenu/movablemobilemenu.php"]' ).parent( 'li' ).remove();
                    jQuery( 'li a[href="admin.php?page=movablemobilemenu/movablemobilemenu.php"]' ).parent( 'li' ).remove();
                },400);
            </script>
            <?php
        }
		
		/** register mobile menu **/	
		public function floatmenuregistermobilemenu() {
			add_theme_support( 'nav-menus' );
			register_nav_menus( array( 'movable-mobile-menu' => 'Movable Mobile Menu' ) );
		}
		
		/** adding CSS rules **/
		public function floatmenucss(){
			echo "<style id='floatmenucss'>\n";
			echo ".floatmenumodal{position:fixed;top:0;left:0;text-align:center;z-index:99999;width:100%;height:100%;margin:0;background:rgba( 0,0,0,0.5 );padding:10px;display:none}\n";
			echo ".floatmenu{max-width:75%;background:white;min-height:30px;padding: 10px 0 10px 0;position:relative;z-index:999999;margin:auto auto;border-radius:10px;overflow-x:hidden;}\n";
			echo ".floatmenudiv{position:fixed;z-index:99999;width:50px;height:120px;display:none;background:rgba( 0,0,0,0.4 );color:white;padding:5px;backdrop-filter:blur(20px);backdrop-filter:saturate(180%) blur(20px);-webkit-backdrop-filter:saturate(180%) blur(20px);}\n";
			echo ".floatmenusubrowdiv{display:table-row}\n";
			echo "#floatmenuburger{cursor:pointer}\n";
			echo "#floatmenumover{padding-top:20px;cursor:move;border-top:1px solid white;}\n";
			echo ".floatmenusubdiv{display:table-cell}\n";
			echo ".floatmenusubdiv span:before{font-size:40px}\n";
			echo ".floatmenu ul{padding:0;margin:0}\n";
			echo ".movable-mobile-menu{padding:0}\n";
			echo ".movable-mobile-menu li{list-style:none;}\n";
			echo ".movable-mobile-menu li a{display:flex;width:100%;text-decoration:none;min-height: 40px;justify-content:center;padding-top: 6px;color:black;font-size:18px}\n";
            echo ".arrowdiv{position: relative;top: -38px;height: 0;width: 50px;float:left;border:0;background:none;}\n";
            echo ".switchicon{padding-top:3px;}\n";
            echo ".floatmenu .dashicons, .floatmenu .dashicons-before::before{width:50px;height: 40px;font-size: 30px;}\n";
			echo ".floatmenu .sub-menu{display:none}\n";
			echo ".floatmenu .sub-menu li a{background:#eee; margin-top:1px;color:black}\n";
			echo "</style>\n";
		}
		
		/** adding modal and floating menu **/
		public function floatmenudivs(){
			echo "<div class='floatmenumodal' >\n";
				echo "<div class='floatmenu' >\n";
					if ( function_exists( 'has_nav_menu' ) && has_nav_menu( 'movable-mobile-menu' ) ) { 
						wp_nav_menu( array(
							'menu' => 'movable-mobile-menu',
							'depth' => 0,
							'sort_column' => 'menu_order',
							'container' => 'ul',
							'menu_id' => 'main-nav',
							'menu_class' => 'nav movable-mobile-menu',
							'theme_location' => 'movable-mobile-menu'
						) );
						} else {
						   echo "<ul class='nav mobile-menu'> <font style='color:red'>" . __( "Mobile Menu has not been set", "movablemobilemenu" ) . "</font> </ul>";
					}
				echo "</div>\n";
			echo "</div>\n";
			echo "<div class='floatmenudiv' >\n";
				echo "<div class='floatmenusubdiv' id='floatmenuburger' >\n";
					echo "<div class='floatmenusubrowdiv' >";
						echo "<span class='dashicons dashicons-menu'></span>\n";
						echo "</div>\n";
					echo "</div>\n";
					echo "<div class='floatmenusubrowdiv' >";
						echo "<div class='floatmenusubdiv' id='floatmenumover' >\n";
							echo "<span class='dashicons dashicons-move' ></span>\n";
						echo "</div>\n";
					echo "</div>\n";
			echo "</div>\n";
		}
		
		/** javascript to manage dragging, saving position, open and closing modal **/
		public function floatmenujs(){
			
			/* check if on mobile */
			$useragent=$_SERVER['HTTP_USER_AGENT'];
				if( preg_match( '/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|iphone|xiino/i', $useragent ) || preg_match( '/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr( $useragent, 0, 4 ) ) || strpos(  'iphone', $useragent ) !== false ){
            ?>
			<script id='floatmenujs' >
			var $ = jQuery;
			$(document).ready(function(){
				setTimeout(function(){ $('.floatmenudiv').css( { 'display':'table' } ); }, 100);
                /* remove WP submenu toggle button */
                $('.floatmenu button').remove();
                /* set variables */
                var savingname = '<?php echo  basename(__DIR__) . "-" . str_replace( " ","-", get_bloginfo( "name" ) ); ?>';
                var screenw = $(window).width();
                var screenh = $(window).height();
                var starth = screenh / 2 - 60;
                var mepos = localStorage.getItem( savingname ) ?? '5#' + starth;
                var meposl = mepos.split('#')[0];
                var mepost = mepos.split('#')[1];
                var breakpoint = screenw / 2;
                /* open modal and hide movable menu */
                $( '#floatmenuburger' ).on('click tap touchstart', function(){
                    $('.floatmenudiv').draggableTouch('disable');
                    $( 'body a' ).not( '.floatmenu a' ).bind( 'click', function( e ){
                        e.preventDefault();
                    });
                });
                $( '#floatmenuburger' ).on('mouseup touchstop', function(){
                    var menuhigh = screenh - $('.floatmenudiv').position().top - 20;
                    $('.floatmenu').css({'margin-top': $('.floatmenudiv').position().top + 'px', 'max-height': menuhigh + 'px', 'overflow-y': 'auto'});
                    setTimeout(function(){
                        $('.floatmenumodal').slideDown();
                    },200);
                    $('.floatmenudiv').hide();
                });
                /* close modal and show movable menu */
                $('.floatmenumodal').on( 'click tap touchstart', function(event){;
                    if(!$(event.target).closest('.floatmenu, .floatmenudiv').length) {
                        $('.floatmenudiv').draggableTouch();
                        $('.floatmenumodal').hide();
                        $.when( $('.floatmenudiv').show(200)).then( function(){
                            $( 'body a' ).unbind( 'click' );
                        });
                    }
                });
                /* add class for icon and open/close sub menu */
                var arrowadd = 1;
                $('.floatmenu .menu-item-has-children').each(function(){
                   $('<div class="arrowdiv" data-id="' + arrowadd + '"><span class="switchicon-' + arrowadd + ' dashicons dashicons-insert"></span></div>').insertAfter($(this).find('a').eq(0));
                    arrowadd++;
                });
                $('.arrowdiv').on('click tap',function(){
                    switch($(this).parent().find('ul').eq(0).is(':visible')){
                        case false:$(this).parent().find('ul').eq(0).show();$(this).find('.switchicon-' + $(this).attr('data-id')).toggleClass('dashicons-insert dashicons-remove');break;
                        case true: $(this).parent().find('ul').hide();$(this).parent().find('[class^=switchicon]').removeClass('dashicons-remove').addClass('dashicons-insert');break;
                    }
                });
                /* for testing in browser check if movable menu is outside view and reset */
                    if(meposl > screenw || mepost > screenh - 120){
                        meposl = 5;
                        mepost = starth;
                    }
                    $('.floatmenudiv').css({'left':meposl + 'px', 'top':mepost + 'px'});
                        /* drag start and end and correct end position */
                        $('.floatmenudiv').draggableTouch().bind('dragstart', function(){
                            $('.floatmenudiv').css('background','rgba(0,0,0,0.8)');})
                            .bind( 'dragend', function(event, pos) {
                                $('.floatmenudiv').css('background','rgba(0,0,0,0.4)');
                                var istposl = pos.left ;
                                var istpost = pos.top;
                                    if( istposl < 0 ){;
                                        istposl = 5;
                                        $('.floatmenudiv').css({'left':'5px'});
                                    };
                                    if( istposl > screenw - 55 ){;
                                        istposl = screenw - 55;
                                        $('.floatmenudiv').css({'left':istposl + 'px'});
                                    };
                                    /* move submenu icon */
                                    if( istposl < breakpoint ){
                                        $('.arrowdiv').each(function(){
                                            $(this).css('float','left');
                                        });
                                    }
                                    else{
                                        $('.arrowdiv').each(function(){
                                            $(this).css('float','right');
                                        });
                                    }
                                    /* logged in user has admin bar on top */
                                    <?php
                                    if( is_user_logged_in() ){
                                       echo 'var subme = 55;' . "\n";
                                    }
                                    else{
                                        echo 'var subme = 5;' . "\n";
                                    }
                                    ?>
                                    if( istpost < subme ){;
                                        istpost = subme;
                                        $('.floatmenudiv').css({'top':subme + 'px'});
                                    };
                                    if( istpost > screenh - 125 ){;
                                        istpost = screenh - 125;
                                        $('.floatmenudiv').css({'top':istpost + 'px'});
                                    };
                                        /* save position in local storage */
                                        localStorage.setItem( savingname, '' + istposl + '#' + istpost + '' )		
                            });
                            /* check where floatmenu is saved and adjust submenu-icon */ 
                            if( parseInt(meposl) > breakpoint ){
                                $('.arrowdiv').css({'float':'right'});
                            }
			});
			</script>
            <?php
			}
		}	
		
			public function floatmenuinit() {
				if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
					add_action( 'admin_notices', array( $this, 'floatmenuadminnoticeminimumphpversion' ) );
					return;
				}
			}

			public function floatmenuadminnoticeminimumphpversion() {
				if ( isset( $_GET['activate'] ) ) {
					unset( $_GET['activate'] );
				}

				$message = sprintf(
					esc_html__( '"%1$s" requires %2$s version %3$s or greater.', 'movablemobilemenu' ),
					'<strong>' . esc_html__( 'Movable Mobile Menu', 'movablemobilemenu' ) . '</strong>',
					'<strong>' . esc_html__( 'PHP', 'movablemobilemenu' ) . '</strong>',
					self::MINIMUM_PHP_VERSION
				);

				printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
			}
			
	}
		new Mobilefloatmenu();
		
		function movablemobilemenuplugindeactivated(){
			unregister_nav_menu( 'movable-mobile-menu' );
		}

		register_deactivation_hook( __FILE__, "movablemobilemenuplugindeactivated");