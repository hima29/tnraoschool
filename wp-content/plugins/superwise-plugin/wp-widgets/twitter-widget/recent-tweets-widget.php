<?php

/**
 * Class TL_Recent_Tweets
 */
class TL_Recent_Tweets extends WP_Widget
{

	public function __construct()
	{
		parent::__construct(
			'tl_recent_tweets', // Base ID
			SCP_PLUGIN_NAME . ' - Recent Tweets', // Name
			array('description' => esc_html__('Display recent tweets', 'superwise-plugin'))
		);
	}


	//widget output
	public function widget($args, $instance)
	{
		extract($args);
		if (!empty($instance['title'])) {
			$title = apply_filters('widget_title', $instance['title']);
		}

		echo $before_widget;
		if (!empty($title)) {
			echo $before_title . esc_html($title) . $after_title;
		}

		if (empty($instance['consumerkey']) ||
		    empty($instance['consumersecret']) ||
		    empty($instance['accesstoken']) ||
		    empty($instance['accesstokensecret']) ||
		    empty($instance['cachetime']) ||
		    empty($instance['username'])
		) {

			echo '<strong>' . esc_html__('Please fill all required widget settings!', 'superwise-plugin') . '</strong>' . $after_widget;
			return;
		} else {
			//check if cache needs update
			$tl_twitter_last_cache_time = get_option('tl_twitter_last_cache_time');
			$diff = time() - $tl_twitter_last_cache_time;
			$crt = $instance['cachetime'] * 3600;

			//	yes, it needs update
			if ($diff >= $crt || empty($tl_twitter_last_cache_time)) {

				if (!require_once('twitteroauth.php')) {
					echo '<strong>' . esc_html__('Couldn\'t find twitteroauth.php!', 'superwise-plugin') . '</strong>' . $after_widget;
					return;
				}

				function getConnectionWithAccessToken($cons_key, $cons_secret, $oauth_token, $oauth_token_secret)
				{
					$connection = new TwitterOAuth($cons_key, $cons_secret, $oauth_token, $oauth_token_secret);
					return $connection;
				}

				$connection = getConnectionWithAccessToken($instance['consumerkey'], $instance['consumersecret'], $instance['accesstoken'], $instance['accesstokensecret']);
				$tweets = $connection->get("https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=" . $instance['username'] . "&count=10&exclude_replies=" . $instance['excludereplies']);

				if (!$tweets) {
					echo '<strong>' . esc_html__('Unable to connect!', 'superwise-plugin') . '</strong>' . $after_widget;
					return;
				}

				if (!empty($tweets->errors)) {
					if ($tweets->errors[0]->message == 'Invalid or expired token') {
						echo '<strong>' . esc_html($tweets->errors[0]->message) . '!</strong><br />' . esc_html__('You\'ll need to regenerate it <a href="https://apps.twitter.com/" target="_blank">here</a>!', 'superwise-plugin') . $after_widget;
					} else {
						echo '<strong>' . esc_html($tweets->errors[0]->message) . '</strong>' . $after_widget;
					}
					return;
				}

				$tweets_array = array();
				for ($i = 0; $i <= count($tweets); $i++) {
					if (!empty($tweets[$i])) {
						$tweets_array[$i]['created_at'] = $tweets[$i]->created_at;

						//clean tweet text
						$tweets_array[$i]['text'] = preg_replace('/[\x{10000}-\x{10FFFF}]/u', '', $tweets[$i]->text);

						if (!empty($tweets[$i]->id_str)) {
							$tweets_array[$i]['status_id'] = $tweets[$i]->id_str;
						}
					}
				}

				//save tweets to wp option
				update_option('tl_twitter_tweets', serialize($tweets_array));
				update_option('tl_twitter_last_cache_time', time());
				echo '<!-- twitter cache has been updated! -->';
			}
			$tl_twitter_plugin_tweets = maybe_unserialize(get_option('tl_twitter_tweets'));
		}


		if (!empty($tl_twitter_plugin_tweets) && is_array($tl_twitter_plugin_tweets)) {
			print '<div class="tl-recent-tweets">
							<ul>';
			$fctr = '1';
			foreach ($tl_twitter_plugin_tweets as $tweet) {
				if (!empty($tweet['text'])) {
					if (empty($tweet['status_id'])) {
						$tweet['status_id'] = '';
					}
					if (empty($tweet['created_at'])) {
						$tweet['created_at'] = '';
					}

					// Get reply name @username
					$at_username = '';
					if (preg_match('/@[a-zA-Z0-9\-_\.]+/i', $tweet['text'], $matches)) {
						$at_username = $matches[0];
					}

					$u = '#';
					if (isset($at_username) && $at_username != '') {
						$u = 'http://twitter.com/' . $instance['username'] . '/statuses/' . $tweet['status_id'];
						$at_username = '<a class="tweet-user" target="_blank" href="' . esc_attr($u) . '">' . esc_html($at_username) . '</a>';
					}

					print '<li>
                                <div class="tweet-meta">
                                    <i class="fa fa-twitter"></i>
                                    ' . $at_username . '
                                    <span class="twitter-time">' . tl_relative_time($tweet['created_at']) . '</span>
                                </div>
                                <p class="tweet-text">' . tl_convert_links($tweet['text']) . '</p>
                           </li>';
					if ($fctr == $instance['tweetstoshow']) {
						break;
					}
					$fctr++;
				}
			}
			print '</ul>';
			print '</div>';
		} else {
			print '<div class="tl-recent-tweets">
						' . esc_html__('<b>Error!</b> Couldn\'t retrieve tweets for some reason!', 'superwise-plugin') . '
				   </div>';
		}

		echo $after_widget;
	}


	/**
	 * @param array $new_instance
	 * @param array $old_instance
	 * @return array
	 */
	public function update($new_instance, $old_instance)
	{
		$instance = array();
		$instance['title'] = isset($new_instance['title']) ? strip_tags($new_instance['title']) : '';
		$instance['consumerkey'] = isset($new_instance['consumerkey']) ? strip_tags($new_instance['consumerkey']) : '';
		$instance['consumersecret'] = isset($new_instance['consumersecret']) ? strip_tags($new_instance['consumersecret']) : '';
		$instance['accesstoken'] = isset($new_instance['accesstoken']) ? strip_tags($new_instance['accesstoken']) : '';
		$instance['accesstokensecret'] = isset($new_instance['accesstokensecret']) ? strip_tags($new_instance['accesstokensecret']) : '';
		$instance['cachetime'] = isset($new_instance['cachetime']) ? strip_tags($new_instance['cachetime']) : '';
		$instance['username'] = isset($new_instance['username']) ? strip_tags($new_instance['username']) : '';
		$instance['tweetstoshow'] = isset($new_instance['tweetstoshow']) ? strip_tags($new_instance['tweetstoshow']) : '';
		$instance['excludereplies'] = isset($new_instance['excludereplies']) ? strip_tags($new_instance['excludereplies']) : '';

		if ((!isset($new_instance['username']) || !isset($new_instance['username'])) ||
		    $old_instance['username'] != $new_instance['username']
		) {
			delete_option('tl_twitter_last_cache_time');
		}
		return $instance;
	}


	/**
	 * @param array $instance
	 * @return string|void
	 */
	public function form($instance)
	{
		$defaults = array('title' => '',
		                  'consumerkey' => '',
		                  'consumersecret' => '',
		                  'accesstoken' => '',
		                  'accesstokensecret' => '',
		                  'cachetime' => '',
		                  'username' => '',
		                  'tweetstoshow' => '');

		$instance = wp_parse_args((array)$instance, $defaults);

		echo '
				<p>' . esc_html__('Get your API keys & tokens at', 'superwise-plugin') . ':<br />
				    <a href="https://apps.twitter.com/" target="_blank">https://apps.twitter.com/</a>
                </p>
				<p>
				    <label>' . esc_html__('Title:', 'superwise-plugin') . '</label>
					<input type="text" name="' . esc_attr($this->get_field_name('title')) . '" id="' . esc_attr($this->get_field_id('title')) . '" value="' . esc_attr($instance['title']) . '" class="widefat" />
                </p>
				<p>
				    <label>' . esc_html__('Consumer Key:', 'superwise-plugin') . '</label>
					<input type="text" name="' . esc_attr($this->get_field_name('consumerkey')) . '" id="' . esc_attr($this->get_field_id('consumerkey')) . '" value="' . esc_attr($instance['consumerkey']) . '" class="widefat" />
                </p>
				<p>
				    <label>' . esc_html__('Consumer Secret:', 'superwise-plugin') . '</label>
					<input type="text" name="' . esc_attr($this->get_field_name('consumersecret')) . '" id="' . esc_attr($this->get_field_id('consumersecret')) . '" value="' . esc_attr($instance['consumersecret']) . '" class="widefat" />
                </p>
				<p>
				    <label>' . esc_html__('Access Token:', 'superwise-plugin') . '</label>
					<input type="text" name="' . esc_attr($this->get_field_name('accesstoken')) . '" id="' . esc_attr($this->get_field_id('accesstoken')) . '" value="' . esc_attr($instance['accesstoken']) . '" class="widefat" />
                </p>
				<p>
				    <label>' . esc_html__('Access Token Secret:', 'superwise-plugin') . '</label>
					<input type="text" name="' . esc_attr($this->get_field_name('accesstokensecret')) . '" id="' . esc_attr($this->get_field_id('accesstokensecret')) . '" value="' . esc_attr($instance['accesstokensecret']) . '" class="widefat" />
                </p>
				<p>
				    <label>' . esc_html__('Cache Tweets in every:', 'superwise-plugin') . '</label>
					<input type="text" name="' . esc_attr($this->get_field_name('cachetime')) . '" id="' . esc_attr($this->get_field_id('cachetime')) . '" value="' . esc_attr($instance['cachetime']) . '" class="small-text" /> hours
                </p>
				<p>
				    <label>' . esc_html__('Twitter Username:', 'superwise-plugin') . '</label>
					<input type="text" name="' . esc_attr($this->get_field_name('username')) . '" id="' . esc_attr($this->get_field_id('username')) . '" value="' . esc_attr($instance['username']) . '" class="widefat" />
                </p>
				<p>
				    <label>' . esc_html__('Tweets to display:', 'superwise-plugin') . '</label>
					<select type="text" name="' . esc_attr($this->get_field_name('tweetstoshow')) . '" id="' . esc_attr($this->get_field_id('tweetstoshow')) . '">';
		$i = 1;
		for ($i; $i <= 10; $i++) {
			echo '<option value="' . $i . '"';
			if ($instance['tweetstoshow'] == $i) {
				echo ' selected="selected"';
			}
			echo '>' . esc_html($i) . '</option>';
		}
		echo '</select></p>
				<p>
				    <label>' . esc_html__('Exclude replies:', 'superwise-plugin') . '</label>
					<input type="checkbox" name="' . esc_attr($this->get_field_name('excludereplies')) . '" id="' . esc_attr($this->get_field_id('excludereplies')) . '" value="true"';

		if (!empty($instance['excludereplies']) && esc_attr($instance['excludereplies']) == 'true') {
			print ' checked="checked"';
		}
		print ' /></p>';
	}
}


if (!function_exists('tl_convert_links')) {
	/**
	 * Convert links to clickable format
	 *
	 * @param $status
	 * @param bool $targetBlank
	 * @param int $linkMaxLen
	 * @return mixed
	 */
	function tl_convert_links($status, $targetBlank = true, $linkMaxLen = 250)
	{
		// the target
		$target = $targetBlank ? " target=\"_blank\" " : "";

		// convert link to url
		$status = preg_replace('/\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[A-Z0-9+&@#\/%=~_|]/i', '<a href="\0" target="_blank">\0</a>', $status);

		// convert @ to follow
		$status = preg_replace("/(@([_a-z0-9\-]+))/i", "<a href=\"http://twitter.com/$2\" title=\"Follow $2\" $target >$1</a>", $status);

		// convert # to search
		$status = preg_replace("/(#([_a-z0-9\-]+))/i", "<a href=\"https://twitter.com/search?q=$2\" title=\"Search $1\" $target >$1</a>", $status);

		// return the status
		return $status;
	}
}


//convert dates to readable format
if (!function_exists('tl_relative_time')) {
	function tl_relative_time($a)
	{
		//get current timestampt
		$b = strtotime('now');
		//get timestamp when tweet created
		$c = strtotime($a);
		//get difference
		$d = $b - $c;
		//calculate different time values
		$minute = 60;
		$hour = $minute * 60;
		$day = $hour * 24;
		$week = $day * 7;

		if (is_numeric($d) && $d > 0) {
			//if less then 3 seconds
			if ($d < 3) return esc_html__('right now', 'superwise-plugin');
			//if less then minute
			if ($d < $minute) return floor($d) . esc_html__(' seconds ago', 'superwise-plugin');
			//if less then 2 minutes
			if ($d < $minute * 2) return esc_html__('about 1 minute ago', 'superwise-plugin');
			//if less then hour
			if ($d < $hour) return floor($d / $minute) . esc_html__(' minutes ago', 'superwise-plugin');
			//if less then 2 hours
			if ($d < $hour * 2) return esc_html__('about 1 hour ago', 'superwise-plugin');
			//if less then day
			if ($d < $day) return floor($d / $hour) . esc_html__(' hours ago', 'superwise-plugin');
			//if more then day, but less then 2 days
			if ($d > $day && $d < $day * 2) return esc_html__('yesterday', 'superwise-plugin');
			//if less then year
			if ($d < $day * 365) return floor($d / $day) . esc_html__(' days ago', 'superwise-plugin');
			//else return more than a year
			return esc_html__('over a year ago', 'superwise-plugin');
		}
	}
}
register_widget('TL_Recent_Tweets');