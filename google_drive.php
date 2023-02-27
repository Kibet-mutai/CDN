<?php
//connect to Google Drive

require_once 'vendor/autoload.php';


$client = new Google\Client();
$client->setAuthConfig('credentials.json');
$client->addScope(Google\AccessToken\Service\Drive::DRIVE);
$driveService = new Google\Service\Drive($client);

if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
}
try {
    if(isset($_GET['keyword'])) {
    $keyword = $_GET['keyword'];
    $query = "mimeType='image/jpeg' || imaje/jpg || image/png and trashed = false and name contains '".$keyword."'";
    $results = $driveService->files->listFiles(array(
        'q' => $query,
        'fields' => 'nextPageToken, ' .
            'files(id, name, webViewLink)'
    ));

    foreach ($results->getFiles() as $image) {
        $googleDriveLinks[] = $image->webViewLink;
    }

    }
} catch (Google_Service_Exception $e) {
    echo 'An error occurred: ' . $e->getMessage();
}

if(count($images->getItems()) > 0) {
    // start building the RSS feed
    $rss = '<?xml version="1.0" encoding="UTF-8"?>';
    $rss .= '<rss version="2.0">';
    $rss .= '<channel>';
    $rss .= '<title>Google Drive Images</title>';
    $rss .= '<link>https://drive.google.com</link>';
    $rss .= '<description>A list of images from Google Drive</description>';
    foreach ($images->getItems() as $image) {
        if($image->getMimeType() == "image/jpeg" || $image->getMimeType() == "image/png"){
            $rss .= '<item>';
            $rss .= '<title>' . $image->getName() . '</title>';
            $rss .= '<link>' . $image->getWebContentLink() . '</link>';
            $rss .= '<guid>' . $image->getId() . '</guid>';
            $rss .= '<description>' . $image->getDescription() . '</description>';
            $rss .= '</item>';
        }
    }
    $rss .= '</channel>';
    $rss .= '</rss>';
    // set the content type to XML
    header("Content-Type: application/rss+xml");
    // output the RSS feed
    echo $rss;
} else {
    echo "No images found.";
}


