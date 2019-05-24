<?php
//error_reporting(0);
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

require_once(__DIR__.'/../classes/Docxpresso/Utils.php');

use SDK_Docxpresso as SDK;


// Register the script
wp_register_script( 'dxo_thickbox', plugins_url('/js/dxo-thickbox.js',__DIR__ ) );
wp_register_script( 'dxo_peity', plugins_url('/lib/vendor/js/jquery.peity.min.js',__DIR__ ) );
wp_enqueue_style( 'dxosaas', plugins_url('/css/dxosaas.css',__DIR__));
wp_enqueue_style('font-awesome', 'https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');

wp_enqueue_script( 'dxo_thickbox', plugins_url('/js/dxo-thickbox.js',__DIR__ ) );
wp_enqueue_script( 'dxo_peity', plugins_url('/lib/vendor/js/jquery.peity.min.js',__DIR__ ) );

if( !class_exists( 'WP_Http' ) ) {
	include_once( ABSPATH . WPINC. '/class-http.php' );
}

add_thickbox();

$Templates = array();
$Categories = array();
$options = get_option('docxpressoSaaS', array());

$optionsDocxpresso = array();
$optionsDocxpresso['pKey'] = $options['pKey'];
$optionsDocxpresso['docxpressoInstallation'] = $options['DocxpressoUrl'];
$email = $options['email'];
$APICall = new SDK\Utils($optionsDocxpresso);

//get the categories and check connection
$url_categories = $APICall->categoryTree();
$catDXO = wp_remote_retrieve_body(wp_remote_request($url_categories, array('timeout' => 10)));

$categories = json_decode($catDXO);

//Log into the Docxpresso instance
$dxo_logged = false;
//Link to log in remotely in the notice-warning
$url_remote_logging = $APICall->accessByTokenAction(array('email' => $email, 'url' => '/users/remote_login', 'referer' => get_site_url()));

$accByTok = $APICall->accessByTokenAction(array('email' => $email, 'url' => '/documents/plugin/tree', 'referer' => get_site_url()));
$selectTemplate = $APICall->accessByTokenAction(array('email' => $email, 'url' => '/documents/plugin/select_template', 'referer' => get_site_url()));
$templateList = $APICall->listTemplatesPaginated(1, array('sort' => 'name', 'order' => 'ASC'));
$lastUsedTemplates = $APICall->lastUsedTemplates(10);
$latestTemplates = $APICall->latestTemplates(10);
echo '<script>' . PHP_EOL;
echo 'var DXO = {};' . PHP_EOL;
echo 'DXO.siteURL = "' . get_site_url() . '";' . PHP_EOL;
echo 'DXO.installation = "' . $options['DocxpressoUrl'] . '";' . PHP_EOL;
echo 'DXO.remoteLogin = "' . $accByTok . '&TB_iframe=true";' . PHP_EOL;
echo 'DXO.selectTemplate = "' . $selectTemplate . '&TB_iframe=true";' . PHP_EOL;
echo 'DXO.templateList = "' . $templateList . '";' . PHP_EOL;
echo 'DXO.lastUsedTemplates = "' . $lastUsedTemplates . '";' . PHP_EOL;
echo 'DXO.latestTemplates = "' . $latestTemplates . '";' . PHP_EOL;
echo 'DXO.closeConnection = 0;' . PHP_EOL;
echo 'DXO.templateSelected = 0;' . PHP_EOL;
echo 'function LogOutDXO(){window.location.href="' . get_site_url() . '/wp-admin";}' . PHP_EOL;

//add the required tranlations
echo 'var trans_DXO = {};' . PHP_EOL;
echo 'trans_DXO.selectTemplate = "' . __('Select Docxpresso Template', 'document-data-automation') . '";' . PHP_EOL;
echo 'trans_DXO.name = "' . __('Name', 'document-data-automation') . '";' . PHP_EOL;
echo 'trans_DXO.actions = "' . __('Actions', 'document-data-automation') . '";' . PHP_EOL;
echo 'trans_DXO.comp = "' . __('Comp.', 'document-data-automation') . '";' . PHP_EOL;
echo 'trans_DXO.last = "' . __('Last use', 'document-data-automation') . '";' . PHP_EOL;
echo 'trans_DXO.uses = "' . __('# uses', 'document-data-automation') . '";' . PHP_EOL;
echo 'trans_DXO.created = "' . __('Created', 'document-data-automation') . '";' . PHP_EOL;
echo 'trans_DXO.identifier = "' . __('Identifier', 'document-data-automation') . '";' . PHP_EOL;
echo 'trans_DXO.reference = "' . __('Reference', 'document-data-automation') . '";' . PHP_EOL;
echo 'trans_DXO.of = "' . __('of', 'document-data-automation') . '";' . PHP_EOL;
echo 'trans_DXO.loading = "' . __('Loading data', 'document-data-automation') . '";' . PHP_EOL;
echo 'trans_DXO.document = "' . __('Document', 'document-data-automation') . '";' . PHP_EOL;
echo 'trans_DXO.data = "' . __('Data', 'document-data-automation') . '";' . PHP_EOL;
echo 'trans_DXO.template = "' . __('Template', 'document-data-automation') . '";' . PHP_EOL;
echo 'trans_DXO.usage = "' . __('Usage', 'document-data-automation') . '";' . PHP_EOL;
echo '</script>' . PHP_EOL;
?>

<?php if ($categories === NULL) {
	echo '<div class="wrap">';
	echo '<div class="notice notice-error"><p> <i class="fa fa-times-circle" style="color: #dc3232"> </i>' . __('There where problems connecting to your Docxpresso SaaS account. Please, check your <strong>configuration options</strong>.', 'document-data-automation') . '</p></div>';
	echo '</div>';
} else {
?>
	<!--div class="wrap"-->
	<div class="wrap"> 
		<div class="notice notice-warning inline"><iframe src="<?php echo $url_remote_logging; ?>" width="99%" height="55px" scrolling="no" style="overflow:hidden;"><?php _e('Loading content', 'document-data-automation'); ?></iframe></div>
		
		<div class="notice notice-error is-dismissible" id="errorDXO" style="display: none"><p><?php _e('It seems like the connection to Docxpresso <strong>is lost</strong>, please, <strong>reload</strong> this page.', 'document-data-automation'); ?></p></div>
		
		<h1 class="wp-heading-inline dxo-logo"><?php _e('Welcome to Docxpresso', 'document-data-automation'); ?></h1>
	</div>
	<h2 class="nav-tab-wrapper">
		<a href="#" class="nav-tab nav-tab-active" id="tab_1" onclick="return false"><i class="fa fa-file-o"> </i> <?php _e('Templates', 'document-data-automation'); ?></a>
		<a href="#" class="nav-tab" id="tab_2" onclick="return false"><i class="fa fa-database"> </i> <?php _e('Data', 'document-data-automation'); ?></a>
	</h2>
	<div id="selectionTemplateDXO">
		<div class="wrap">
			<div id="col-container">
				<div id="col-right">
					<div class="col-wrap" style="margin-bottom: 25px">
						<h2><i class="fa fa-search"> </i> <?php _e('Search Templates by name', 'document-data-automation'); ?></h2>				
							
						<p><i class="fa fa-tag"> </i> <strong><?php _e('Name', 'document-data-automation'); ?>:</strong> <input class="regular-text" type="text" id="filterName" value="" placeholder="<?php _e('Template name', 'document-data-automation'); ?>" style="width: 220px" /> 
						<button id="dxo_search_templates" class="button-primary" onclick="return false"><i class="fa fa-filter"> </i>  <?php _e('Filter by name', 'document-data-automation'); ?></button><br/>
						&emsp;<small><?php _e('Leave "name" empty for the complete list.', 'document-data-automation'); ?></small></p>
					</div>
					<div class="col-wrap">
						<div class="inside">
							<div style="float: right; margin-bottom: 10px"><button class="button-primary" id="selectTemplateFromCategory" onclick="return false"><i class="fa fa-sitemap"> </i> <?php _e('Select template by category', 'document-data-automation'); ?></button></div> <h2><i class="fa fa-file-text-o"> </i> <span id="listTemplates"><?php _e('Last used templates', 'document-data-automation'); ?></span> </h2>
							<div id="templateFilters" style="display:none; float: left">
								<p style="margin:0"><?php _e('Filtered by name', 'document-data-automation'); ?>: <strong id="filterTemplateValue"></strong></p>
							</div>
							<div id="templateLoaderDXO" style="clear: left">
								<div class="spinner is-active" style="float:none;width:auto;height:auto;padding:10px 0 10px 50px;background-position:20px 10px;"><?php _e('Loading data', 'document-data-automation'); ?></div>
							</div>
						</div>

					</div>
					<!-- /col-wrap -->

				</div>
				<!-- /col-right -->

				<div id="col-left">
					<!-- list of recent templates -->
					<div class="col-wrap">
						<h2><i class="fa fa-clock-o"> </i> <?php _e('Latest templates', 'document-data-automation'); ?></h2>	
					</div>
					<div class="col-wrap">
						<div class="inside">
							<div id="latestTemplatesDXO" style="clear: left">
								<div class="spinner is-active" style="float:none;width:auto;height:auto;padding:10px 0 10px 50px;background-position:20px 10px;"><?php _e('Loading templates', 'document-data-automation'); ?></div>
							</div>
						</div>
					</div>
					<!-- /col-wrap -->
				</div>
				<!-- /col-left -->
			</div>
			<!-- /col-container -->
		</div> <!-- .wrap -->
	</div>		
	<div id="selectionDataDXO" style="display: none">
		<div class="wrap">
			<div id="col-container">
				<div id="col-right">
					<div class="col-wrap">
						<div id="yesTemplate" style="float: right; margin-bottom: 10px; display: none"><button class="button-primary" id="downloadExcel" onclick="return false"><i class="fa fa-file-excel-o"> </i> <?php _e('Download as Excel file', 'document-data-automation'); ?></button></div> <h2><i class="fa fa-database"> </i> <?php _e('Usage data', 'document-data-automation'); ?></h2>				
						<div class="inside">
							<div id="templateDataFilter" style="display:none">
								<p><?php _e('Filtered by template', 'document-data-automation'); ?>: <strong id="filterDataTemplateValue"></strong></p>
							</div>
							<div class="notice notice-error inline" id="noTemplate">
								<p><?php _e('You should <a href="#" id="templateTabButton" onclick="return false">select a template</a> first.', 'document-data-automation'); ?></p>
							</div>
							<div id="dataLoaderDXO">
								
							</div>
						</div>

					</div>
					<!-- /col-wrap -->

				</div>
				<!-- /col-right -->

				<div id="col-left">
					<div class="col-wrap">
						<h2><i class="fa fa-search"> </i> <?php _e('Search Data', 'document-data-automation'); ?></h2>				
						<div class="inside">
						</div>
						<p><i class="fa fa-tag"> </i> <strong><?php _e('Identifier', 'document-data-automation'); ?>:</strong> <input class="regular-text filterData" type="text" id="filterIdentifier" value="" placeholder="<?php _e('Identifier', 'document-data-automation'); ?>" style="width: 220px" /> </p>
						<p><i class="fa fa-tag"> </i> <strong><?php _e('Reference', 'document-data-automation'); ?>:</strong> <input class="regular-text filterData" type="text" id="filterReference" value="" placeholder="<?php _e('Reference', 'document-data-automation'); ?>" style="width: 220px" /> </p>
						<p>
							<i class="fa fa-clock-o"> </i> <strong><?php _e('Time period', 'document-data-automation'); ?>:</strong>
							<select class="filterData" id="filterPeriod">
								<option value=""> </option>
								<option value="today"><?php _e('Today', 'document-data-automation'); ?></option>
								<option value="1week"><?php _e('Last week', 'document-data-automation'); ?></option>
								<option value="1month"><?php _e('Last month', 'document-data-automation'); ?></option>
								<option value="3month"><?php _e('Last 3 months', 'document-data-automation'); ?></option>
								<option value="1year"><?php _e('Last year', 'document-data-automation'); ?></option>
								<option value="range"><?php _e('Date range', 'document-data-automation'); ?></option>
							</select>
						<p>
						<div id="dateRange" style="display: none">
							<table>
							<tr>
								<td></td>
								<td><strong><?php _e('Day', 'document-data-automation'); ?></strong></td>
								<td><strong><?php _e('Month', 'document-data-automation'); ?></strong></td>
								<td><strong><?php _e('Year', 'document-data-automation'); ?></strong></td>
								</tr>
							<tr>
								<td><strong><?php _e('After', 'document-data-automation'); ?>:</strong></td>
								<td>
									<select id="afterDay">
										<option value="01">1</option>
										<option value="02">2</option>
										<option value="03">3</option>
										<option value="04">4</option>
										<option value="05">5</option>
										<option value="06">6</option>
										<option value="07">7</option>
										<option value="08">8</option>
										<option value="09">9</option>
										<option value="10">10</option>
										<option value="11">11</option>
										<option value="12">12</option>
										<option value="13">13</option>
										<option value="14">14</option>
										<option value="15">15</option>
										<option value="16">16</option>
										<option value="17">17</option>
										<option value="18">18</option>
										<option value="19">19</option>
										<option value="20">20</option>
										<option value="21">21</option>
										<option value="22">22</option>
										<option value="23">23</option>
										<option value="24">24</option>
										<option value="25">25</option>
										<option value="26">26</option>
										<option value="27">27</option>
										<option value="28">28</option>
										<option value="29">29</option>
										<option value="30">30</option>
										<option value="31">31</option>
									</select>
								</td>
								<td>
									<select id="afterMonth">
										<option value="01"><?php _e('Jan.', 'document-data-automation'); ?></option>
										<option value="02"><?php _e('Feb.', 'document-data-automation'); ?></option>
										<option value="03"><?php _e('Mar.', 'document-data-automation'); ?></option>
										<option value="04"><?php _e('Apr.', 'document-data-automation'); ?></option>
										<option value="05"><?php _e('May', 'document-data-automation'); ?></option>
										<option value="06"><?php _e('Jun.', 'document-data-automation'); ?></option>
										<option value="07"><?php _e('Jul.', 'document-data-automation'); ?></option>
										<option value="08"><?php _e('Aug.', 'document-data-automation'); ?></option>
										<option value="09"><?php _e('Sep.', 'document-data-automation'); ?></option>
										<option value="10"><?php _e('Oct.', 'document-data-automation'); ?></option>
										<option value="11"><?php _e('Nov.', 'document-data-automation'); ?></option>
										<option value="12"><?php _e('Dic.', 'document-data-automation'); ?></option>
									</select>
								</td>
								<td>
									<select id="afterYear">
										<option value="2018">2018</option>
										<option value="2019">2019</option>
									</select>
								</td>
							</tr>
							<tr>
								<td><strong><?php _e('Before', 'document-data-automation'); ?>:</strong></td>
								<td>
									<select id="beforeDay">
										<option value="01">1</option>
										<option value="02">2</option>
										<option value="03">3</option>
										<option value="04">4</option>
										<option value="05">5</option>
										<option value="06">6</option>
										<option value="07">7</option>
										<option value="08">8</option>
										<option value="09">9</option>
										<option value="10">10</option>
										<option value="11">11</option>
										<option value="12">12</option>
										<option value="13">13</option>
										<option value="14">14</option>
										<option value="15">15</option>
										<option value="16">16</option>
										<option value="17">17</option>
										<option value="18">18</option>
										<option value="19">19</option>
										<option value="20">20</option>
										<option value="21">21</option>
										<option value="22">22</option>
										<option value="23">23</option>
										<option value="24">24</option>
										<option value="25">25</option>
										<option value="26">26</option>
										<option value="27">27</option>
										<option value="28">28</option>
										<option value="29">29</option>
										<option value="30">30</option>
										<option value="31">31</option>
									</select>
								</td>
								<td>
									<select id="beforeMonth">
										<option value="01"><?php _e('Jan.', 'document-data-automation'); ?></option>
										<option value="02"><?php _e('Feb.', 'document-data-automation'); ?></option>
										<option value="03"><?php _e('Mar.', 'document-data-automation'); ?></option>
										<option value="04"><?php _e('Apr.', 'document-data-automation'); ?></option>
										<option value="05"><?php _e('May', 'document-data-automation'); ?></option>
										<option value="06"><?php _e('Jun.', 'document-data-automation'); ?></option>
										<option value="07"><?php _e('Jul.', 'document-data-automation'); ?></option>
										<option value="08"><?php _e('Aug.', 'document-data-automation'); ?></option>
										<option value="09"><?php _e('Sep.', 'document-data-automation'); ?></option>
										<option value="10"><?php _e('Oct.', 'document-data-automation'); ?></option>
										<option value="11"><?php _e('Nov.', 'document-data-automation'); ?></option>
										<option value="12"><?php _e('Dic.', 'document-data-automation'); ?></option>
									</select>
								</td>
								<td>
									<select id="beforeYear">
										<option value="2018">2018</option>
										<option value="2019">2019</option>
									</select>
								</td>
							</tr>
							</table>
						</div>
						<p><button id="dxo_search_data" class="button-primary" onclick="return false"><i class="fa fa-filter"> </i>  <?php _e('Filter data', 'document-data-automation'); ?></button></p>
					</div>
					<!-- /col-wrap -->
				</div>
				<!-- /col-left -->
			</div>
			<!-- /col-container -->
		</div> <!-- .wrap -->
	</div>
	<div style="clear: both">&nbsp;</div>
<?php } ?>
<!--  /div-->

