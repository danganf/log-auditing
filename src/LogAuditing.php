<?php

namespace Ufox;

class LogAuditing
{
    public function getUrl($apiName){
        $retorno = $this->curl( config('app.url_api_center').$apiName.'/'.config('app.env') );
        return $retorno['url'];
    }

    private function parseReturn( $jsonString ){

        $json = json_decode( $jsonString, TRUE );
        $retorno = FALSE;
        if( !isset( $json['error'] ) ) {
            $retorno = $json;
        }
        return $retorno;

    }

    private function curl ($url, array $options = [])
    {
        $timeout           = 5;
        $connectionTimeout = 3;

        $ch = curl_init();

        if (count($options) > 0) {

            if (!empty($options['timeout']))
                $timeout = $options['timeout'];

            if (!empty($options['connectionTimeout']))
                $connectionTimeout = $options['connectionTimeout'];

            if (!empty($options['header'])) {
                if (is_array($options['header'])) {
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $options['header']);
                } else {
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array($options['header']));
                }
            }

            if (!empty($options['method']))
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $options['method']);

            if (!empty($options['post']))
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');

            if (!empty($options['data']))
                curl_setopt($ch, CURLOPT_POSTFIELDS, $options['data']);

            if (!empty($options['json'])) {
                $dados[] = 'Content-Type: application/json';
                $dados[] = 'Content-Length: ' . strlen($options['data']);
            }

            if ( !empty( $options['backend'] ) ) {
                $dados[] = 'api-token: '.config('app.api_token');
                if( !is_bool( $options['backend'] ) ) {
                    $dados[] = 'session-id: '.$options['backend'];
                }
            }

            if( isset( $dados ) ){
                curl_setopt($ch, CURLOPT_HTTPHEADER, $dados);
            }
        }

        $refer = ( isset( $_SERVER['HTTP_X_ALT_REFERER'] ) ? $_SERVER['HTTP_X_ALT_REFERER'] : ( isset( $_SERVER['HTTP_REFERER'] ) ? $_SERVER['HTTP_REFERER'] : \Request::server('HTTP_REFERER') ) );

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $connectionTimeout);
        curl_setopt($ch, CURLOPT_REFERER, $refer );

        $result = curl_exec($ch);
        if( strpos($url,'get-url-api') === FALSE ) {
            //var_dump($url.' > '.$result);
        }
        curl_close($ch);

        return $this->parseReturn( $result );
    }
}