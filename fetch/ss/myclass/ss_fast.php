<?php

/**
 * Created by PhpStorm.
 * User: zhaohongyu
 * Date: 2016/10/10
 * Time: 18:44
 */

require_once('./myclass/Base.php');

class ss_fast extends Base {

    public function getHtml() {

        // Get cURL resource
        $ch = curl_init();

        // Set url
        curl_setopt($ch, CURLOPT_URL, $this->fetch_url);

        // Set method
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

        // Set options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // Set headers
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Pragma: no-cache",
                "Cookie: " . $this->user_cookie,
                "User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.143 Safari/537.36",
            ]
        );

        // Send the request & save response to $resp
        $resp = curl_exec($ch);

        if (!$resp) {
            $err_msg = 'Error: "' . curl_error($ch) . '" - Code: ' . curl_errno($ch);
            throw new Exception($err_msg, curl_errno($ch));
        }

        // Close request to clear up some resources
        curl_close($ch);

        return $resp;
    }

    public function parseHtml($response_html) {
        $this->simple_html_dom = new simple_html_dom($response_html);
        $table                 = $this->simple_html_dom->find('table');
        echo '<pre>';
        foreach ($table as $table_info) {
            $tbody = $table->find('tbody', 0);
            var_dump($table_info);
            echo "------------------------------------------------------------------------------------<br/>";

        }

    }

    public function getSsInfo() {
        $response_html = $this->getHtml();
        if (empty($response_html)) {
            return null;
        }
        return $this->parseHtml($response_html);
    }


}