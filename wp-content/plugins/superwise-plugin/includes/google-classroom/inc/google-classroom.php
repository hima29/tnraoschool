<?php

// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

// https://developers.google.com/classroom/reference/rest/

class Aislin_Classroom {

	const OPTION_NAME_TOKEN = 'aislin_gc_token';

	private static $service;
	private static $client;
	private static $application_name = 'Classroom API';
	private static $conf = array(
		'installed' => array(
			'client_id'                   => '288238359911-o4krvp9j3qtfrn60bgnh3otfu2af35pn.apps.googleusercontent.com',
			'client_secret'               => 'cUs8_ls7dDIgZZ8BHpFDSoht',
			'project_id'                  => 'voltaic-pilot-180012',
			'auth_uri'                    => 'https://accounts.google.com/o/oauth2/auth',
			'token_uri'                   => 'https://accounts.google.com/o/oauth2/token',
			'auth_provider_x509_cert_url' => 'https://www.googleapis.com/oauth2/v1/certs',
			'redirect_uris'               => array('urn:ietf:wg:oauth:2.0:oob'),
		)
	);

	public static function revoke_token() {
		self::init();

		$client = self::$client;

		if ($client) {
			$client->revokeToken();
			self::destroy_token();
		}
	}

	public static function set_client() {

		$client = new Google_Client();
		$client->setApplicationName(self::$application_name);
		$client->setScopes(implode(' ', array(
			Google_Service_Classroom::CLASSROOM_COURSES_READONLY,
			Google_Service_Classroom::CLASSROOM_ROSTERS_READONLY,
			// Google_Service_Classroom::CLASSROOM_PROFILE_EMAILS,
			// Google_Service_Classroom::CLASSROOM_PROFILE_PHOTOS,
			)
		));
		$client->setAuthConfig(self::$conf);
		$client->setAccessType('offline');

		self::$client = $client;
	}

	public static function get_auth_url() {
		return self::$client->createAuthUrl();
	}

	private static function set_service($service) {
		self::$service = $service;
	}

	public static function get_teacher_courses($teacher_email) {

		$service = self::$service;

		if (!$service) {
			return false;
		}

		$options = array(
			// 'pageSize' => 10,
			'teacherId'    => $teacher_email,
			'courseStates' => 'ACTIVE',
	    );

    	$courses = array();
    	try {

	    	$results = $service->courses->listCourses($options);
	    	if (count($results->getCourses())) {

				foreach ($results->getCourses() as $item) {
					$courses[$item->getId()] = new Aislin_Classroom_Course($item);
				}
			}
    		
    	} catch (Exception $e) {

    		echo esc_html( $e->getMessage() );
    	}

		return $courses;
	}

	public static function get_course($id) {

		$service = self::$service;
		if (!$service) {
			return;
		}
    	return $service->courses->get($id);
	}

	public static function get_course_students($course_id) {
		$service = self::$service;
		if (!$service) {
			return false;
		}

    	$results = $service->courses_students->listCoursesStudents($course_id);

		return $results->getStudents();
	}

	public static function get_students_batch($courses) {
		
		$service = self::$service;
		if (!$service) {
			return false;
		}

		$service->getClient()->setUseBatch(true);
		$batch = $service->createBatch();

		$course_ids = array();
		foreach ($courses as $course) {
			$course_id = $course->get_id();

			$request = $service->courses_students->listCoursesStudents($course_id);
			$requestId = $course_id;
			$batch->add($request, $requestId);


			$course_ids[] = $course_id;
		}


		$res = array();

		try {
			
			$response = $batch->execute();
			foreach($response as $responseId => $results) {
				$course_id = substr($responseId, strlen('response-'));
				if ($results instanceof Google_Service_Exception) {
					$e = $results;
					printf("Error fetching students for the course %s: %s\n", $course_id, $e->getMessage());
				} else {

					$res[$course_id] = $results->getStudents();
				}
			}

		} catch (Exception $e) {
			
		}
		$service->getClient()->setUseBatch(false);

		return $res;

	}

	public static function get_course_teachers($course_id) {
		$service = self::$service;
		if (!$service) {
			return false;
		}

    	$results = $service->courses_teachers->listCoursesTeachers($course_id);

		return $results->getTeachers();
	}

	public static function init_admin() {
		self::set_client();
	}

	public static function init() {
		if (self::$service) {
			return true;
		}

		$access_token = self::get_access_token();

		if (!$access_token) {
			return false;
		}

		$access_token = json_decode($access_token, true);

		self::set_client();

		$client = self::$client;
		$client->setAccessToken($access_token);

		if ($client->isAccessTokenExpired()) {
		    $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
		    self::save_access_token($client->getAccessToken());
	    }

	    $service = new Google_Service_Classroom($client);

	    self::set_service($service);

	    return true;

	}

	private static function save_access_token($access_token) {
		update_option( self::OPTION_NAME_TOKEN, wp_json_encode( $access_token ) );
	}

	private static function get_access_token() {
		return get_option(self::OPTION_NAME_TOKEN);
	}

	public static function is_validated() {
		if (self::get_access_token()) {
			return true;
		}
		return false;
	}

	public static function fetch_access_token($auth_code) {
		$client = self::$client;
		$access_token = $client->fetchAccessTokenWithAuthCode($auth_code);
		self::save_access_token($access_token);
	}

	protected static function destroy_token() {
		return delete_option( self::OPTION_NAME_TOKEN );
	}

}



