<?php

class API {
    /**
     * the doc info will be generated automatically into service info page.
     * @params
     * @return string
     */
    public function myapi($parameter, $option = "foo") {
        return $parameter;
    }

    protected function client_can_not_see() {
    }
}

$service = new Yar_Server(new API());
$service->handle();