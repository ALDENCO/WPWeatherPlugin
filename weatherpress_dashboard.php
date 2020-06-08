?php
/*
Plugin Name: WeatherPress Dashboard
Plugin URI: https://github.com/ALDENCO/WPWeatherPlugin
Description: An API-driven weather widget for the dashboard
Version: 1.0
Author: Alex Myers
Author URI: https://www.alexrmyers.com
Text Domain: wep
*/

<!-- // https://codex.wordpress.org/Function_Reference/wp_add_dashboard_widget -->
<!-- // Register the new dashboard widget with the 'wp_dashboard_setup' action -->
add_action( 'wp_dashboard_setup', 'wep_add_dashboard_widgets' );



<!-- Load in requirements from vendor folder -->
if( file_exists( __DIR__ . '/vendor/autoload.php' ) ){
require 'vendor/autoload.php';
}

<!-- // Load the Guzzle Library -->
use GuzzleHttp\Client;



function wep_add_dashboard_widgets() {

wp_add_dashboard_widget(
'wep_weather_widget',
'Weather Press Dashboard',
'wep_render_dashboard_widget_contents'
);

}

// Outputs the content into the widget


function wep_render_dashboard_widget_contents() {

$client = new Client( array(
'base_uri' => "http://api.openweathermap.org/data/2.5/"
) );
$response = $client->request('GET', 'weather', [
'query' => [
'q' => 'Phoenix, AZ',
'APPID' => '7440c3cd675318d2807fd4027d91bd3c',
]
]);

$payload = $response->getBody()->getContents();
$weather_data = json_decode( $payload );

echo sprintf('<h2>%s, AZ</h2>', $weather_data->name); // City name
echo sprintf('<h3>%s %s</h3>', date('l'), date('h:00')); // Current day and time


echo '
<pre>';
print_r( $weather_data );
echo '</pre>';



if( ! empty( $weather_data->weather ) ) {

$latest = $weather_data->weather[0];
$icon_url = 'http://openweathermap.org/img/w/' . $latest->icon . '.png';

echo sprintf('<h2>Conditions: %s</h2>', $latest->main);
echo sprintf('<img src="%s" width="%dpx">', $icon_url, 100);
}


}