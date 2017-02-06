<?php

include 'view.php';
require 'config.php';

class Video
{
    public $title = '';
    public $author = '';
    public $date = '';
    public $id = 0;
    public $views = 0;
}

/**
 * Sorting videos by view
 *
 * @return array() sort videos
 */
function sortByView($array_videos)
{
    usort($array_videos, function($a, $b){
        return ($b->views - $a->views);
    });
    return $array_videos;
}

/**
 * Create youtube client from api
 */
function createYoutubeClient()
{
    global $DEVELOPER_KEY;
    $client = new Google_Client();
    $client->setDeveloperKey($DEVELOPER_KEY);
    $guzzleClient = new \GuzzleHttp\Client(array( 'curl' => array( CURLOPT_SSL_VERIFYPEER => false, ), ));
    $client->setHttpClient($guzzleClient);
    // Define an object that will be used to make all API requests.
    $youtube = new Google_Service_YouTube($client);
    return $youtube;
}

/**
 * Result of processing videos and output tpl
 */
function output($searched_video)
{    // sort by view all videos
    $videos = sortByView($searched_video);

    // output video tpl
    $video_view = new View();
    $video_view = $video_view->render('tpl/video.php', array('videos' => $videos));

    // output main tpl
    $view = new View();
    echo $view->render('tpl/main.php', array('search_text' => 'Результаты по запросу: '.$_GET['q'], 'videos' => $video_view));
}

/**
 * Get view with json from statistics by id
 * @return integer count
 */
function getViews($id)
{
    global $DEVELOPER_KEY;
    return json_decode(file_get_contents(sprintf('https://www.googleapis.com/youtube/v3/videos?id=%s&key='.$DEVELOPER_KEY.'&part=statistics', $id)))->{'items'}[0]->statistics->viewCount;
}

/**
 * Do request to get videos with sort by view
 */
function getVideos()
{
    $youtube = createYoutubeClient();
    try {
        // Call the search.list method to retrieve results matching the specified
        // query term.
        $searchResponse = $youtube->search->listSearch('id,snippet', array(
            'q' => $_GET['q'],
            'maxResults' => 20,
            'order' => 'date',
        ));
        // Add each result to the appropriate list, and then display the lists of
        // matching videos, channels, and playlists.
        foreach ($searchResponse['items'] as $searchResult) {
            switch ($searchResult['id']['kind']) {
                case 'youtube#video':
                    // create object video with fields
                    $current_video = new Video();
                    $current_video->title = $searchResult['snippet']['title'];
                    $current_video->author = $searchResult['snippet']['channelTitle'];
                    $current_video->date = $searchResult['snippet']['publishedAt'];
                    $current_video->id = $searchResult['id']['videoId'];
                    $current_video->views = getViews($searchResult['id']['videoId']);
                    // add current video in array
                    $searched_video[] = $current_video;
                    break;
            }
        }
        // output video with sorting
        output($searched_video);
    } catch (Exception $e) {
        $text_error = sprintf('<p>A service error occurred: <code>%s</code></p>', htmlspecialchars($e->getMessage()));
        $view = new View();
        $view->render('tpl/main.php', array('search_text' => $text_error, 'videos' => ''));
    }
}

if (isset($_GET['q'])) {
    getVideos();
}
?>