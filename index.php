<?php

/**
 * Library Requirements
 *
 * 1. Install composer (https://getcomposer.org)
 * 2. On the command line, change to this directory (api-samples/php)
 * 3. Require the google/apiclient library
 *    $ composer require google/apiclient:~2.0
 */
if (!file_exists(__DIR__ . '/vendor/autoload.php')) {
  throw new \Exception('please run "composer require google/apiclient:~2.0" in "' . __DIR__ .'"');
}

require_once __DIR__ . '/vendor/autoload.php';

$htmlBody = <<<END
<div id="buttons">
<form method="GET">
  <div>
    <input type="search" id="query" name="q" placeholder="введите название">
  </div>
  <input type="submit" id="search-button" value="Search">
</form>
</div>
END;

// This code will execute if the user entered a search query in the form
// and submitted the form. Otherwise, the page displays the form above.
if (isset($_GET['q'])) {
  /*
   * Set $DEVELOPER_KEY to the "API key" value from the "Access" tab of the
   * {{ Google Cloud Console }} <{{ https://cloud.google.com/console }}>
   * Please ensure that you have enabled the YouTube Data API for your project.
   */
  $DEVELOPER_KEY = 'AIzaSyCAKghrx-iQarm9RfDWOH8ssToUzuiSyTo';

  $client = new Google_Client();
  $client->setDeveloperKey($DEVELOPER_KEY);
  $guzzleClient = new \GuzzleHttp\Client(array( 'curl' => array( CURLOPT_SSL_VERIFYPEER => false, ), ));
  $client->setHttpClient($guzzleClient);

  // Define an object that will be used to make all API requests.
  $youtube = new Google_Service_YouTube($client);

  $htmlBody = '';
  try {

    // Call the search.list method to retrieve results matching the specified
    // query term.
    $searchResponse = $youtube->search->listSearch('id,snippet', array(
      'q' => $_GET['q'],
      'maxResults' => 20,
      'order' => 'date',
    ));

    $videos = '';

    // Add each result to the appropriate list, and then display the lists of
    // matching videos, channels, and playlists.
    foreach ($searchResponse['items'] as $searchResult) {
      switch ($searchResult['id']['kind']) {
        case 'youtube#video':
          $videos .= sprintf('
          <li class="search-item">
          <a href="" class="search-item-title">%s</a><br />
          %s<br />
          %s<br />
          <div class="search-item-content">
          <iframe width="640" height="360" src="https://www.youtube.com/embed/%s" frameborder="0" allowfullscreen></iframe>
          </div>
          </li>',
              $searchResult['snippet']['title'],
              $searchResult['snippet']['channelTitle'],
              $searchResult['snippet']['publishedAt'],
              $searchResult['id']['videoId']);
          break;
      }
    }

    // text of search
    $text_input = $_GET['q'];

    $htmlBody .= <<<END
    <div id="buttons">
    <form method="GET">
      <div>
        <input type="search" id="query" name="q" placeholder="введите название">
      </div>
      <input type="submit" id="search-button" value="Искать">
    </form>
    </div>
    <h1 id="search-header">Результаты по запросу: $text_input</h1>
    <div id="search-container">
      <ul id="search-cointainer-list">$videos</ul>
    </div>
END;
  } catch (Google_Service_Exception $e) {
    $htmlBody .= sprintf('<p>A service error occurred: <code>%s</code></p>',
      htmlspecialchars($e->getMessage()));
  } catch (Google_Exception $e) {
    $htmlBody .= sprintf('<p>An client error occurred: <code>%s</code></p>',
      htmlspecialchars($e->getMessage()));
  }
}
?>

<!doctype html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>YouTube Search</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/media.css">
  </head>
  <body>
    <section id="search">
      <div class="container">
        <?=$htmlBody?>
      </div>
    </section>
  </body>
</html>
