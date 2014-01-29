<?php
class Picasa
{

    public $access_token = FALSE;

    function __construct($sAccessToken = '')
    {
        if ($sAccessToken)
        {
            $this -> access_token = $_SESSION[PICASA_ACCESS_TOKEN] = $sAccessToken;
        }
    }

    function setToken($sAccessToken = '')
    {
        $this -> access_token = $_SESSION[PICASA_ACCESS_TOKEN] = $sAccessToken;
    }

    function getToken()
    {
        return $this -> access_token;
    }

    public function checkToken()
    {

        $result = array();
        try
        {

            if (!isset($_SESSION[PICASA_ACCESS_TOKEN]))
            {
                return array(
                    'error' => 1,
                    'error_message' => 'access token not found. please reconnect!',
                    'error_code' => 'TOKEN_FAILED'
                );
            }
            $client = Zend_Gdata_AuthSub::getHttpClient($_SESSION[PICASA_ACCESS_TOKEN], $this -> getHttpClient());
            $photos = new Zend_Gdata_Photos($client);
            $query = new Zend_Gdata_Photos_UserQuery();
            $query -> setUser($user);
            $query -> setMaxResults(1);
            $userFeed = $photos -> getUserFeed(null, $query);

            $result = array(
                'error' => 0,
                'error_message' => '',
                'error_code' => 0,
            );
            // YouNet::log($result, 'picasa.log');
            return $result();
        }
        catch(Exception $e)
        {
            $result = array(
                'error' => 1,
                'error_message' => $e -> getMessage(),
                'error_code' => $e -> getCode()
            );
        }
    }

    function getAlbums($user = 'default')
    {

        $client = Zend_Gdata_AuthSub::getHttpClient($_SESSION[PICASA_ACCESS_TOKEN], $this -> getHttpClient());
        $photos = new Zend_Gdata_Photos($client);
        $query = new Zend_Gdata_Photos_UserQuery();
        $query -> setUser($user);
        return $photos -> getUserFeed(null, $query);
    }

    function getAlbum($iUserId, $iAlbumId = 0)
    {
        $client = Zend_Gdata_AuthSub::getHttpClient($_SESSION[PICASA_ACCESS_TOKEN], $this -> getHttpClient());
        $photos = new Zend_Gdata_Photos($client);
        $query = new Zend_Gdata_Photos_UserQuery();
        $query -> setAlbumId($iAlbumId);
        $query -> setUser($user);
        return $photos -> getUserFeed(null, $query);
    }

    function getPhotos($iUserId, $iAlbumId, $startIndex = 0, $perPage = 200)
    {
        return $this -> getPhotosFeed($iUserId, $iAlbumId, $startIndex, $perPage);
    }

    function getPhotosFeed($iUserId, $iAlbumId, $startIndex = 0, $perPage = 200)
    {

        $client = Zend_Gdata_AuthSub::getHttpClient($_SESSION[PICASA_ACCESS_TOKEN], $this -> getHttpClient());

        $photos = new Zend_Gdata_Photos($client);

        $query = new Zend_Gdata_Photos_AlbumQuery();

        $query -> setUser($iUserId);

        $query -> setStartIndex($startIndex);

        $query -> setAlbumId($iAlbumId);

        $query -> setMaxResults($perPage);

        return $photos -> getAlbumFeed($query);

    }

    function getAllPhotosOfAlbum($iUserId, $iAlbumId)
    {
        $feeds = array();

        $perPage = 100;

        $startIndex = 0;

        $maxPerPage = 100;

        $maxRequest = 10;

        $totalPhoto = 1;

        try
        {
            while ($startIndex < 300 && $startIndex < $totalPhoto);
            {

                $startTime = microtime(1);

                YouNet::log(sprintf("start request: uid=%d aid=%d  start_index=%d per_page=%d start=%.5f(s)", $iUserId, $iAlbumId, $startIndex, $perPage, $startTime), 'picasa-fetch.log');

                $feed = $this -> getPhotosFeed($iUserId, $iAlbumId, $startIndex, $perPage);

                // fetch feeed for each time.
                $feeds[] = $feed;

                if (0 == $startIndex)
                {
                    try
                    {
                        $xml = simplexml_load_string($feed -> getXML());
                        $xml -> registerXPathNamespace('openSearch', 'http://a9.com/-/spec/opensearchrss/1.0/');
                        $openSearch = $xml -> xpath('//atom:feed/openSearch:totalResults');
                        $totalPhoto = (int)$openSearch[0];
                    }
                    catch(Exception $e)
                    {
                        $totalPhoto = 0;
                    }
                }

                $endTime = microtime(1);

                YouNet::log(sprintf("picasa end=%.5f(s) duration= %.5f(s) total_photo=%d", $startTime, $endTime, $endTime - $startTime, $totalPhoto), 'picasa-fetch.log');

                $startIndex += $perPage;
            }

        }
        catch(Exception $e)
        {
            echo $e -> getMessage();
            if (APPLICATION_ENV == 'development')
            {
                echo $e -> getMessage();
            }
        }
        return $feeds;
    }

    /*
     * Function to create the login with Instagram link
     * @return string Instagram login url
     */
    function picasaLogin()
    {
        $authSubUrl = Zend_Gdata_AuthSub::getAuthSubTokenUri(PICASA_CALLBACK_URL, PICASA_SCOPE, AUTHSUB_SECURE, true);
        return $authSubUrl;
    }

    function picasaLogout()
    {
        if (isset($_SESSION[PICASA_ACCESS_TOKEN]))
        {
            unset($_SESSION[PICASA_ACCESS_TOKEN]);
        }

        if (isset($_SESSION[PICASA_USER_DATA]))
        {
            unset($_SESSION[PICASA_USER_DATA]);
        }
        session_write_close();
    }

    /**
     * initial secure token.
     * @return Zend_Gdata_HttpClient
     */
    function getHttpClient()
    {
        $client = new Zend_Gdata_HttpClient;

        if (AUTHSUB_SECURE)
        {
            YouNet::log("process with secure" . AUTHSUB_PRIVATE_FILE);
            $client -> setAuthSubPrivateKeyFile(AUTHSUB_PRIVATE_FILE, AUTHSUB_PASS);
            $client -> setConfig(array('timeout' => 60));
        }
        return $client;
    }

}
?>