<?php


// Connect to OneDrive
    require_once __DIR__.'/vendor/autoload.php';
    
    use Microsoft\Graph\Graph;
    use Microsoft\Graph\Model;

    $accessToken = "YOUR_ACCESS_TOKEN";
    
    $graph = new Graph();
    $graph->setAccessToken($accessToken);
    $graph->setRedirectUri('http://localhost:2500/callback');


    // search for images
    try {
        $url = 'https://api.onedrive.com/v1.0/drive/root/children';
        $response = $client->request('GET', $url, [
            'headers' => [
                'Authorization' => 'Bearer YOUR_ACCESS_TOKEN',
            ],
        ]);
        $data = json_decode($response->getBody(), true);
        foreach ($data['value'] as $file) {
            if($file['file']['mimeType'] == 'image/jpeg' || $file['file']['mimeType'] == 'image/png' || $file['file']['mimeType'] == 'image/jpg' || $file['file']['mimeType'] == 'image/webp'){
                echo '<a href="'.$file['@microsoft.graph.downloadUrl'].'">'.$file['name'].'</a><br>';
            }
        }
    } catch (GuzzleHttp\Exception\ClientException $e) {
        // Handle error
        echo 'An error occurred: ' . $e->get();
    }
?>
