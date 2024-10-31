<?php

function get_listicor_api($query, $limit=20, $offset=0, $video=0, $debug=false) {

       $LISTICOR_API = "https://listicor.com/listicles_api?";
       /*
       API Parameters list:
       'q=' query
       'l=' limit  (default 10) (maximum 100)
       'o=' offset (default: 0)
       'video='   1 - ask for video   articles, 0 - not (default: 1)
       'article=' 1 - ask for regular articles, 0 - not (default: 1)
       'df=' min published date (format: 'mm-dd-yyyy')
       'dt=' max published date
       ====== unused =====
       'bns=' min number in the title (e,g. if 10, will pick articles having number >=10 in the title)
       'ens=' max number in the title

       RESULTS Parameters list:
       results object properties:
       't'  = title       --garanteed
       'au' = article_url --garanteed
       'iu' = image_url   --currently configured to be garanteed
       'pu' = published_date FORMAT: "YYYY-MM-DD"
       'c'  = content --almost always empty
       's'  = summary --this seems to exist more often than full content
       'un' = author_name
       'uu' = author_url
       'dm' = domain
       ====== unused =====
        'vu' = video url
        'id' = unique id
        'tg' = tags
        'ns' = number of sections
        'ou' = origin url
       */

       $query       = urlencode($query);
       $uri         = explode('?',$_SERVER['REQUEST_URI'])[0];
       $uri         = preg_replace('|more_remote/.*|', 'more_remote/', $uri);
       $request_url = urlencode($_SERVER['HTTP_HOST'] . $uri);
       $URL         = "q={$query}&l={$limit}&o={$offset}&video={$video}&the_url={$request_url}"; //NOTE: last part is for backend debug logs only

       //NOTE: for some reason 'http://listicor.com/' breaks the error_log???
       if ($debug) error_log("DEBUG: Requesting content from Listicor API listicles_api $URL");

       $ch = curl_init();
       curl_setopt($ch, CURLOPT_URL,            "{$LISTICOR_API}{$URL}" );
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
       curl_setopt($ch, CURLOPT_TRANSFERTEXT,   false);
       curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1    ); //setting some timeouts to make sure any problems here don't hang the client.
       curl_setopt($ch, CURLOPT_TIMEOUT,        3    ); //NOTE: this should be less than the backend (hhvm/php) timeout
       $results_raw = curl_exec(   $ch);
       $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE); //  http://php.net/manual/en/function.curl-getinfo.php
       $results = array();

       if ($results_raw === false) { //eg the curl failed
            echo "<!-- ERROR occurred retrieving $URL with curl_exec '". curl_error($ch)."' -->";
            error_log("ERROR occurred retrieving $URL with curl_exec FROM URL {$_SERVER["HTTP_HOST"]}{$_SERVER["REQUEST_URI"]} '". curl_error($ch));
       }
       elseif ($status_code != 200) {
            echo "<!-- ERROR occurred retrieving $URL with curl_exec got http/'$status_code' -->";
            error_log("ERROR occurred retrieving $URL with curl_exec got http/'$status_code' FROM URL {$_SERVER["HTTP_HOST"]}{$_SERVER["REQUEST_URI"]}");
       }
       else {
            $tmp = json_decode( $results_raw ); //NOTE: returns null on failure
            if (is_array($tmp->res_list)) {     //NOTE: handles non existant and non object values fine
               $results = $tmp->res_list;       //get the list of objects
            }
       }

       curl_close($ch);

       return $results; //NOTE: should be a php array
}

?>
