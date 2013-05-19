<?php

/**
 * Fetches data from the GitHub API
 *
 * @package forge
 * @subpackage util
 * @author Andi Dittrich
 * @link https://github.com/mootools/mootools-forge/issues/7#issuecomment-18118801
 **/
class GitHubFetcher {

	public static function fetchContent($command){
		// http options
		$opts = array(
			'http'=> array(
				'header' => 'Connection: close',
				'method' => 'GET',
				'protocol_version' => '1.1',
				'user_agent' => 'MooTools-Forge/1.0'
			)
		);

		// create new stream context
		$context = stream_context_create($opts);

		// fetch data with given http options
		$url = 'https://api.github.com' . $command;
		return file_get_contents($url, false, $context);
	}

}
