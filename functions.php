<?php
wp_enqueue_script( 'jquery' );
add_action( 'after_setup_theme', 'blankslate_setup' );
function blankslate_setup() {
load_theme_textdomain( 'blankslate', get_template_directory() . '/languages' );
add_theme_support( 'title-tag' );
add_theme_support( 'post-thumbnails' );
add_theme_support( 'responsive-embeds' );
add_theme_support( 'automatic-feed-links' );
add_theme_support( 'html5', array( 'search-form', 'navigation-widgets' ) );
add_theme_support( 'woocommerce' );
global $content_width;
if ( !isset( $content_width ) ) { $content_width = 1920; }
register_nav_menus( array( 'main-menu' => esc_html__( 'Main Menu', 'blankslate' ) ) );
}
add_action( 'admin_notices', 'blankslate_notice' );
function blankslate_notice() {
$user_id = get_current_user_id();
$admin_url = ( isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http' ) . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$param = ( count( $_GET ) ) ? '&' : '?';
if ( !get_user_meta( $user_id, 'blankslate_notice_dismissed_9' ) && current_user_can( 'manage_options' ) )
echo '<div class="notice notice-info"><p><a href="' . esc_url( $admin_url ), esc_html( $param ) . 'dismiss" class="alignright" style="text-decoration:none"><big>' . esc_html__( 'Ⓧ', 'blankslate' ) . '</big></a>' . wp_kses_post( __( '<big><strong>🏆 Thank you for using BlankSlate!</strong></big>', 'blankslate' ) ) . '<p>' . esc_html__( 'Powering over 10k websites! Buy me a sandwich! 🥪', 'blankslate' ) . '</p><a href="https://opencollective.com/blankslate" class="button-primary" style="background-color:green;border-color:green" target="_blank"><strong>' . esc_html__( 'Donate', 'blankslate' ) . '</strong> ' . esc_html__( '(New Open Collective)', 'blankslate' ) . '</a> <a href="https://wordpress.org/support/theme/blankslate/reviews/#new-post" class="button-primary" style="background-color:purple;border-color:purple" target="_blank"><strong>' . esc_html__( 'Review', 'blankslate' ) . '</strong></a> <a href="https://github.com/tidythemes/blankslate/issues" class="button-primary" style="background-color:orange;border-color:orange" target="_blank"><strong>' . esc_html__( 'Support', 'blankslate' ) . '</strong></a></p></div>';
}
add_action( 'admin_init', 'blankslate_notice_dismissed' );
function blankslate_notice_dismissed() {
$user_id = get_current_user_id();
if ( isset( $_GET['dismiss'] ) )
add_user_meta( $user_id, 'blankslate_notice_dismissed_9', 'true', true );
}
add_action( 'wp_enqueue_scripts', 'blankslate_enqueue' );
function blankslate_enqueue() {
wp_enqueue_style( 'blankslate-style', get_stylesheet_uri() );

}
add_action( 'wp_footer', 'blankslate_footer' );
function blankslate_footer() {
?>
<script>
jQuery(document).ready(function($) {
var deviceAgent = navigator.userAgent.toLowerCase();
if (deviceAgent.match(/(iphone|ipod|ipad)/)) {
$("html").addClass("ios");
$("html").addClass("mobile");
}
if (deviceAgent.match(/(Android)/)) {
$("html").addClass("android");
$("html").addClass("mobile");
}
if (navigator.userAgent.search("MSIE") >= 0) {
$("html").addClass("ie");
}
else if (navigator.userAgent.search("Chrome") >= 0) {
$("html").addClass("chrome");
}
else if (navigator.userAgent.search("Firefox") >= 0) {
$("html").addClass("firefox");
}
else if (navigator.userAgent.search("Safari") >= 0 && navigator.userAgent.search("Chrome") < 0) {
$("html").addClass("safari");
}
else if (navigator.userAgent.search("Opera") >= 0) {
$("html").addClass("opera");
}
});
</script>
<?php
}
add_filter( 'document_title_separator', 'blankslate_document_title_separator' );
function blankslate_document_title_separator( $sep ) {
$sep = esc_html( '|' );
return $sep;
}
add_filter( 'the_title', 'blankslate_title' );
function blankslate_title( $title ) {
if ( $title == '' ) {
return esc_html( '...' );
} else {
return wp_kses_post( $title );
}
}
function blankslate_schema_type() {
$schema = 'https://schema.org/';
if ( is_single() ) {
$type = "Article";
} elseif ( is_author() ) {
$type = 'ProfilePage';
} elseif ( is_search() ) {
$type = 'SearchResultsPage';
} else {
$type = 'WebPage';
}
echo 'itemscope itemtype="' . esc_url( $schema ) . esc_attr( $type ) . '"';
}
add_filter( 'nav_menu_link_attributes', 'blankslate_schema_url', 10 );
function blankslate_schema_url( $atts ) {
$atts['itemprop'] = 'url';
return $atts;
}
if ( !function_exists( 'blankslate_wp_body_open' ) ) {
function blankslate_wp_body_open() {
do_action( 'wp_body_open' );
}
}
add_action( 'wp_body_open', 'blankslate_skip_link', 5 );
function blankslate_skip_link() {
//echo '<a href="#page0" class="skip-link screen-reader-text">' . esc_html__( 'Skip to the content', 'blankslate' ) . '</a>';
}
add_filter( 'the_content_more_link', 'blankslate_read_more_link' );
function blankslate_read_more_link() {
if ( !is_admin() ) {
return ' <a href="' . esc_url( get_permalink() ) . '" class="more-link">' . sprintf( __( '...%s', 'blankslate' ), '<span class="screen-reader-text">  ' . esc_html( get_the_title() ) . '</span>' ) . '</a>';
}
}
add_filter( 'excerpt_more', 'blankslate_excerpt_read_more_link' );
function blankslate_excerpt_read_more_link( $more ) {
if ( !is_admin() ) {
global $post;
return ' <a href="' . esc_url( get_permalink( $post->ID ) ) . '" class="more-link">' . sprintf( __( '...%s', 'blankslate' ), '<span class="screen-reader-text">  ' . esc_html( get_the_title() ) . '</span>' ) . '</a>';
}
}
add_filter( 'big_image_size_threshold', '__return_false' );
add_filter( 'intermediate_image_sizes_advanced', 'blankslate_image_insert_override' );
function blankslate_image_insert_override( $sizes ) {
unset( $sizes['medium_large'] );
unset( $sizes['1536x1536'] );
unset( $sizes['2048x2048'] );
return $sizes;
}
add_action( 'widgets_init', 'blankslate_widgets_init' );
function blankslate_widgets_init() {
register_sidebar( array(
'name' => esc_html__( 'Sidebar Widget Area', 'blankslate' ),
'id' => 'primary-widget-area',
'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
'after_widget' => '</li>',
'before_title' => '<h3 class="widget-title">',
'after_title' => '</h3>',
) );
}
add_action( 'wp_head', 'blankslate_pingback_header' );
function blankslate_pingback_header() {
if ( is_singular() && pings_open() ) {
printf( '<link rel="pingback" href="%s">' . "\n", esc_url( get_bloginfo( 'pingback_url' ) ) );
}
}
add_action( 'comment_form_before', 'blankslate_enqueue_comment_reply_script' );
function blankslate_enqueue_comment_reply_script() {
if ( get_option( 'thread_comments' ) ) {
wp_enqueue_script( 'comment-reply' );
}
}
function blankslate_custom_pings( $comment ) {
?>
<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>"><?php echo esc_url( comment_author_link() ); ?></li>
<?php
}
add_filter( 'get_comments_number', 'blankslate_comment_count', 0 );
function blankslate_comment_count( $count ) {
if ( !is_admin() ) {
global $id;
$get_comments = get_comments( 'status=approve&post_id=' . $id );
$comments_by_type = separate_comments( $get_comments );
return count( $comments_by_type['comment'] );
} else {
return $count;
}
}

function identifyBaseRepoURL() {
	$baseUrl = 'https://' . $_SERVER['SERVER_NAME'];
	if (strpos($baseUrl,'uat') !== false) {
    	return 'eLearning1.0-uat';
	} else if(strpos($baseUrl,'dev') !== false) {
    	return 'eLearning1.0-development';
	} else {
		return 'eLearning1.1';
	}
}

function validateHasKey($hashToCheck) {
	$fullURL = $_SERVER['REQUEST_URI'];
	if (strpos($fullURL,$hashToCheck) !== false) {
    	return true;
	}  else {
		$baseUrl = 'https://' . $_SERVER['SERVER_NAME'].'/404';
		header("Location:".$baseUrl);
		exit();
	}
}

function amazeAplusJS() {
	$hashNotNeed = is_user_logged_in();
    if(!$hashNotNeed) {
		$hasKeyTrue = validateHasKey('31d6cfe0d16ae931b73c59d7e0c089c0');
		if($hasKeyTrue){
			$repoPath = identifyBaseRepoURL();
			ob_start();
			echo "<script>var head = document.head; var script = document.createElement('script'); script.type = 'text/javascript'; script.src = '/wp-content/plugins/".strval($repoPath)."/repoPath.js';head.appendChild(script);</script>";
			echo "<script>var head = document.head; var script = document.createElement('script'); script.type = 'text/javascript'; script.src = '/wp-content/plugins/".strval($repoPath)."/clients/amaze/aplus/courseVariableseLearning.js';head.appendChild(script);</script>";
			$output = ob_get_contents();
    		ob_end_clean();
    		return $output;
		}
	}
}
add_shortcode( 'addAplusJS' , 'amazeAplusJS');

function foundationActionPlanJS() {
	$hashNotNeed = is_user_logged_in();
    if(!$hashNotNeed) {
		$hasKeyTrue = validateHasKey('31d6cfe0d16ae931b73c59d7e0c089c0');
		if($hasKeyTrue){
			$repoPath = identifyBaseRepoURL();
			ob_start();
			echo "<script>var head = document.head; var script = document.createElement('script'); script.type = 'text/javascript'; script.src = '/wp-content/plugins/".strval($repoPath)."/repoPath.js';head.appendChild(script);</script>";
			echo "<script>var head = document.head; var script = document.createElement('script'); script.type = 'text/javascript'; script.src = '/wp-content/plugins/".strval($repoPath)."/clients/amaze/aplus/actionPlan.js';head.appendChild(script);</script>";
			echo "<script>var csslink = document.createElement('link');csslink.setAttribute('rel', 'stylesheet');csslink.setAttribute('href', '/wp-content/plugins/".strval($repoPath)."/clients/amaze/aplus/actionplan.css');document.head.appendChild(csslink);</script>";
			$output = ob_get_contents();
    		ob_end_clean();
    		return $output;
		}
	}
}
add_shortcode( 'addFoundationActionPlanJS' , 'foundationActionPlanJS');

function aracyJS() {
	$repoPath = identifyBaseRepoURL();
	ob_start();
	echo "<script>var head = document.head; var script = document.createElement('script'); script.type = 'text/javascript'; script.src = '/wp-content/plugins/".strval($repoPath)."/repoPath.js';head.appendChild(script);</script>";
	echo "<script>var csslink = document.createElement('link');csslink.setAttribute('rel', 'stylesheet');csslink.setAttribute('href', '/wp-content/plugins/".strval($repoPath)."/css/genesisBaseStyle.css');document.head.appendChild(csslink);</script>";
    echo "<script>var head = document.head; var script = document.createElement('script'); script.type = 'text/javascript'; script.src = '/wp-content/plugins/".strval($repoPath)."/clients/aracy/courseVariableseLearning.js';head.appendChild(script);</script>";
    $output = ob_get_contents();
    ob_end_clean();
    return $output;
}
add_shortcode( 'aracy', 'aracyJS' );

function splJS() {
    $repoPath = identifyBaseRepoURL();
	ob_start();
	echo "<script>var head = document.head; var script = document.createElement('script'); script.type = 'text/javascript'; script.src = '/wp-content/plugins/".strval($repoPath)."/repoPath.js';head.appendChild(script);</script>";
	echo "<script>var csslink = document.createElement('link');csslink.setAttribute('rel', 'stylesheet');csslink.setAttribute('href', '/wp-content/plugins/".strval($repoPath)."/css/genesisBaseStyle.css');document.head.appendChild(csslink);</script>";
	echo "<script>var head = document.head; var script = document.createElement('script'); script.type = 'text/javascript'; script.src = '/wp-content/plugins/".strval($repoPath)."/clients/spl/courseVariableseLearning.js';head.appendChild(script);</script>";
    $output = ob_get_contents();
    ob_end_clean();
    return $output;
}
add_shortcode( 'spl', 'splJS' );

function svhaJS() {
	$repoPath = identifyBaseRepoURL();
    ob_start();
	echo "<script>var head = document.head; var script = document.createElement('script'); script.type = 'text/javascript'; script.src = '/wp-content/plugins/".strval($repoPath)."/repoPath.js';head.appendChild(script);</script>";
	echo "<script>var csslink = document.createElement('link');csslink.setAttribute('rel', 'stylesheet');csslink.setAttribute('href', '/wp-content/plugins/".strval($repoPath)."/css/genesisBaseStyle.css');document.head.appendChild(csslink);</script>";
    echo "<script>var head = document.head; var script = document.createElement('script'); script.type = 'text/javascript'; script.src = '/wp-content/plugins/".strval($repoPath)."/clients/svha/safetyLeadership-courseVariableseLearning.js';head.appendChild(script);</script>";
	$output = ob_get_contents();
    ob_end_clean();
    return $output;
}
add_shortcode( 'svha', 'svhaJS' );

function amazeAddJS() {
	$repoPath = identifyBaseRepoURL();
    ob_start();
	echo "<script>var head = document.head; var script = document.createElement('script'); script.type = 'text/javascript'; script.src = '/wp-content/plugins/".strval($repoPath)."/repoPath.js';head.appendChild(script);</script>";
	echo "<script>var head = document.head; var script = document.createElement('script'); script.type = 'text/javascript'; script.src = '/wp-content/plugins/".strval($repoPath)."/clients/amaze/courseVariableseLearning.js';head.appendChild(script);</script>";
	echo "<script>var csslink = document.createElement('link');csslink.setAttribute('rel', 'stylesheet');csslink.setAttribute('href', '/wp-content/plugins/".strval($repoPath)."/clients/amaze/amazeQuizzes.css');document.head.appendChild(csslink);</script>";
	$output = ob_get_contents();
    ob_end_clean();
    return $output;
}
add_shortcode( 'amazeJS', 'amazeAddJS' );

function addPPSJS() {
    $repoPath = identifyBaseRepoURL();
    ob_start();
	echo "<script>var head = document.head; var script = document.createElement('script'); script.type = 'text/javascript'; script.src = '/wp-content/plugins/".strval($repoPath)."/repoPath.js';head.appendChild(script);</script>";
	echo "<script>var head = document.head; var script = document.createElement('script'); script.type = 'text/javascript'; script.src = '/wp-content/plugins/".strval($repoPath)."/clients/psa/pps/courseVariableseLearning.js';head.appendChild(script);</script>";
  	$output = ob_get_contents();
    ob_end_clean();
    return $output;
}
add_shortcode( 'psaPpsJS', 'addPPSJS' );

function splTextToSpeech() {
    ob_start();
    ?>
    <script>
    	var head = document.head;
    	var script = document.createElement('script');
    	script.type = 'text/javascript';
    	script.src = 'https://code.responsivevoice.org/responsivevoice.js?key=tLOnRM4g';
		head.appendChild(script);
    </script>
<?php	
	$output = ob_get_contents();
    ob_end_clean();
    return $output;
}
add_shortcode( 'splReponsiveVoice', 'splTextToSpeech' );

function embedHTML2PDF() {
	$repoPath = identifyBaseRepoURL();
	ob_start();
	echo "<script>var head = document.head; var script = document.createElement('script'); script.type = 'text/javascript'; script.src = '/wp-content/plugins/".strval($repoPath)."/repoPath.js';head.appendChild(script);</script>";
	echo "<script>var csslink = document.createElement('link');csslink.setAttribute('rel', 'stylesheet');csslink.setAttribute('href', '/wp-content/plugins/".strval($repoPath)."/css/genesisBaseStyle.css');document.head.appendChild(csslink);</script>";
	echo "<script>var head = document.head; var script = document.createElement('script'); script.type = 'text/javascript'; script.src = '/wp-content/plugins/".strval($repoPath)."/developmentPlan/displayDevelopmentPlan.js';head.appendChild(script);</script>";
  	echo "<script>var csslink = document.createElement('link');csslink.setAttribute('rel', 'stylesheet');csslink.setAttribute('href', '/wp-content/plugins/".strval($repoPath)."/clients/svha/developmentPlan.css');document.head.appendChild(csslink);</script>";
    ?>
<script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<?php	
	$output = ob_get_contents();
    ob_end_clean();
    return $output;
}
add_shortcode( 'PDFexport', 'embedHTML2PDF' );

function addPPSCSS() {
    $repoPath = identifyBaseRepoURL();
	ob_start();
	echo "<script>var head = document.head; var script = document.createElement('script'); script.type = 'text/javascript'; script.src = '/wp-content/plugins/".strval($repoPath)."/repoPath.js';head.appendChild(script);</script>";
	echo "<script>var csslink2 = document.createElement('link');csslink2.setAttribute('rel', 'stylesheet');csslink2.setAttribute('href', '/wp-content/plugins/".strval($repoPath)."/css/genesisBaseStyle.css');document.head.appendChild(csslink2);</script>";
	echo "<script>var csslink = document.createElement('link');csslink.setAttribute('rel', 'stylesheet');csslink.setAttribute('href', '/wp-content/plugins/".strval($repoPath)."/clients/psa/pps/eLearning.css');document.head.appendChild(csslink);</script>";
  	$output = ob_get_contents();
    ob_end_clean();
    return $output;
}
add_shortcode( 'ppsAPCSS', 'addPPSCSS' );



function workRespectJS() {
    $repoPath = identifyBaseRepoURL();
	ob_start();
	echo "<script>var head = document.head; var script = document.createElement('script'); script.type = 'text/javascript'; script.src = '/wp-content/plugins/".strval($repoPath)."/repoPath.js';head.appendChild(script);</script>";
	echo "<script>var csslink = document.createElement('link');csslink.setAttribute('rel', 'stylesheet');csslink.setAttribute('href', '/wp-content/plugins/".strval($repoPath)."/css/genesisBaseStyle.css');document.head.appendChild(csslink);</script>";
	echo "<script>var head = document.head; var script = document.createElement('script'); script.type = 'text/javascript'; script.src = '/wp-content/plugins/".strval($repoPath)."/clients/ywca/work-respect/wr-courseVariableseLearning.js';head.appendChild(script);</script>";
	$output = ob_get_contents();
    ob_end_clean();
    return $output;
}
add_shortcode( 'addWorkRespectJS' , 'workRespectJS');

function workRespectEmployerJS() {
	$repoPath = identifyBaseRepoURL();
	ob_start();
	echo "<script>var head = document.head; var script = document.createElement('script'); script.type = 'text/javascript'; script.src = '/wp-content/plugins/".strval($repoPath)."/repoPath.js';head.appendChild(script);</script>";
	echo "<script>var csslink = document.createElement('link');csslink.setAttribute('rel', 'stylesheet');csslink.setAttribute('href', '/wp-content/plugins/".strval($repoPath)."/css/genesisBaseStyle.css');document.head.appendChild(csslink);</script>";
	echo "<script>var head = document.head; var script = document.createElement('script'); script.type = 'text/javascript'; script.src = '/wp-content/plugins/".strval($repoPath)."/clients/ywca/work-respect/wrEmployer-courseVariableseLearning.js';head.appendChild(script);</script>";
	$output = ob_get_contents();
    ob_end_clean();
    return $output;
}
add_shortcode( 'addWorkRespectEmployerJS' , 'workRespectEmployerJS');

function certificateThreeJS() {
    $repoPath = identifyBaseRepoURL();
	ob_start();
	echo "<script>var head = document.head; var script = document.createElement('script'); script.type = 'text/javascript'; script.src = '/wp-content/plugins/".strval($repoPath)."/repoPath.js';head.appendChild(script);</script>";
	echo "<script>var head = document.head; var script = document.createElement('script'); script.type = 'text/javascript'; script.src = '/wp-content/plugins/".strval($repoPath)."/clients/ywca/certificate-three/courseVariableseLearning.js';head.appendChild(script);</script>";
	echo "<script>var csslink = document.createElement('link');csslink.setAttribute('rel', 'stylesheet');csslink.setAttribute('href', '/wp-content/plugins/".strval($repoPath)."/clients/ywca/certificate-three/eLearning.css');document.head.appendChild(csslink);</script>";
    ?>
		<script>
		var script2 = document.createElement('script');
    	script2.type = 'text/javascript';
    	script2.src = 'https://code.responsivevoice.org/responsivevoice.js?key=2zn27hln';
		head.appendChild(script2);

		</script>

<?php	
	$output = ob_get_contents();
    ob_end_clean();
    return $output;
}
add_shortcode( 'addCertificateThreeJS' , 'certificateThreeJS');

function psaTravelHealthJS() {
	$repoPath = identifyBaseRepoURL();
    ob_start();
	echo "<script>var head = document.head; var script = document.createElement('script'); script.type = 'text/javascript'; script.src = '/wp-content/plugins/".strval($repoPath)."/repoPath.js';head.appendChild(script);</script>";
	echo "<script>var head = document.head; var script = document.createElement('script'); script.type = 'text/javascript'; script.src = '/wp-content/plugins/".strval($repoPath)."/clients/psa/travel-health/courseVariableseLearning.js';head.appendChild(script);</script>";
	$output = ob_get_contents();
    ob_end_clean();
    return $output;
}
add_shortcode( 'addpsaTravelHealthJS' , 'psaTravelHealthJS');

function wellbeingLiteracyJS() {
    $repoPath = identifyBaseRepoURL();
	ob_start();
	echo "<script>var head = document.head; var script = document.createElement('script'); script.type = 'text/javascript'; script.src = '/wp-content/plugins/".strval($repoPath)."/repoPath.js';head.appendChild(script);</script>";
	echo "<script>var csslink = document.createElement('link');csslink.setAttribute('rel', 'stylesheet');csslink.setAttribute('href', '/wp-content/plugins/".strval($repoPath)."/css/genesisBaseStyle.css');document.head.appendChild(csslink);</script>";
	echo "<script>var head = document.head; var script = document.createElement('script'); script.type = 'text/javascript'; script.src = '/wp-content/plugins/".strval($repoPath)."/clients/aracy/wellbeingliteracy/courseVariableseLearning.js';head.appendChild(script);</script>";
	$output = ob_get_contents();
    ob_end_clean();
    return $output;
}
add_shortcode( 'addWellbeingLiteracyJS' , 'wellbeingLiteracyJS');

function wellbeingLiteracyCSS() {
	$repoPath = identifyBaseRepoURL();
	ob_start();
	echo "<script>var head = document.head; var script = document.createElement('script'); script.type = 'text/javascript'; script.src = '/wp-content/plugins/".strval($repoPath)."/repoPath.js';head.appendChild(script);</script>";
	echo "<script>var csslink = document.createElement('link');csslink.setAttribute('rel', 'stylesheet');csslink.setAttribute('href', '/wp-content/plugins/".strval($repoPath)."/css/genesisBaseStyle.css');document.head.appendChild(csslink);</script>";
	echo "<script>var csslink = document.createElement('link');csslink.setAttribute('rel', 'stylesheet');csslink.setAttribute('href', '/wp-content/plugins/".strval($repoPath)."/clients/aracy/wellbeingliteracy/actionPlan.css');document.head.appendChild(csslink);</script>";
	$output = ob_get_contents();
    ob_end_clean();
    return $output;
}
add_shortcode( 'addWellbeingLiteracyCSS' , 'wellbeingLiteracyCSS');

function clearDefaultJS() {
    $repoPath = identifyBaseRepoURL();
	ob_start();
	echo "<script>var head = document.head; var script = document.createElement('script'); script.type = 'text/javascript'; script.src = '/wp-content/plugins/".strval($repoPath)."/repoPath.js';head.appendChild(script);</script>";
	echo "<script>var head = document.head; var script = document.createElement('script'); script.type = 'text/javascript'; script.src = '/wp-content/plugins/".strval($repoPath)."/clients/clear/default/courseVariableseLearning.js';head.appendChild(script);</script>";
	$output = ob_get_contents();
    ob_end_clean();
    return $output;
}
add_shortcode( 'addClearDefaultJS' , 'clearDefaultJS');

function ppsReportsCSS() {
    $repoPath = identifyBaseRepoURL();
	ob_start();
	echo "<script>var head = document.head; var script = document.createElement('script'); script.type = 'text/javascript'; script.src = '/wp-content/plugins/".strval($repoPath)."/repoPath.js';head.appendChild(script);</script>";
	echo "<script>var csslink = document.createElement('link');csslink.setAttribute('rel', 'stylesheet');csslink.setAttribute('href', '/wp-content/plugins/".strval($repoPath)."/clients/psa/pps/reports.css');document.head.appendChild(csslink);</script>";
	$output = ob_get_contents();
    ob_end_clean();
    return $output;
}
add_shortcode( 'addPPSReportsCSS' , 'ppsReportsCSS');

function tilDefaultJS() {
    $repoPath = identifyBaseRepoURL();
	ob_start();
	echo "<script>var head = document.head; var script = document.createElement('script'); script.type = 'text/javascript'; script.src = '/wp-content/plugins/".strval($repoPath)."/repoPath.js';head.appendChild(script);</script>";
	echo "<script>var head = document.head; var script = document.createElement('script'); script.type = 'text/javascript'; script.src = '/wp-content/plugins/".strval($repoPath)."/clients/thisislearning/base/courseVariableseLearning.js';head.appendChild(script);</script>";
	$output = ob_get_contents();
    ob_end_clean();
    return $output;
}
add_shortcode( 'addTilDefaultJS' , 'tilDefaultJS');