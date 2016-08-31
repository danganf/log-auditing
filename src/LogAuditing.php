<?php

namespace Ufox;

/*
variavel sessao => login_usuario_logado
*/
class LogAuditing
{
    private $dados = [];

    public function __construct()
    {
        $this->dados['trace']         = base_convert( rand(100000000,999999999) ,20,36);
        $this->dados['login_name']    = \Session::get('login_usuario_logado');
        $this->dados['origem']        = $_SERVER['HTTP_HOST'];
        $this->dados['evento']        = null;
        $this->dados['ip']            = null;
        $this->dados['path_arquivo']  = null;
        $this->dados['dados_request'] = null;
    }

    public function login(){
        $this->dados['evento'] = 'LOGON';
        $this->send();
    }

    public function logon(){
        $this->dados['evento'] = 'LOGOFF';
        $this->send();
    }

    public function event($arrayValores=null){

        $this->dados['evento']       = 'SYSTEM';
        $this->dados['evento_dados'] = $arrayValores;
        $this->send();
    }

    public function error($arrayValores=null){
        $this->dados['evento']       = 'ERROR';
        $this->dados['evento_dados'] = $arrayValores;
        $this->send();
    }

    private function send(){
        $this->setRequest();
        $this->setTrace();
        return \ApiCenter::saveLogAuditing( $this->dados );
    }

    private function setRequest(){

        $this->dados['ip']            = implode(',',\Request::getClientIps());
        $this->dados['dados_request'] = [
            'URI_FULL' => \Request::getUri(),
            'REFER'    => ( isset( $_SERVER['HTTP_X_ALT_REFERER'] ) ? $_SERVER['HTTP_X_ALT_REFERER'] : ( isset( $_SERVER['HTTP_REFERER'] ) ? $_SERVER['HTTP_REFERER'] : \Request::server('HTTP_REFERER') ) ),
            'URI'      => \Request::getRequestUri(),
            'METHOD'   => \Request::getMethod(),
        ];
    }

    private function setTrace(){
        $TRACE = debug_backtrace();
        foreach( $TRACE AS $key => $label ) {

            #eliminar a chamada da facades
            if( isset( $label['file'] ) && strpos( $label['file'], 'Facades' ) !== FALSE ) {
                unset($TRACE[$key]);
            }

        }

        $retorno = [];
        foreach( $TRACE AS $key => $label ) {
            if($TRACE[$key]['class'] != 'Ufox\LogAuditing') {
                $retorno[] = $TRACE[$key];
                break;
            }
        }

        $file  = ( isset( $retorno[0]['file'] ) ? $retorno[0]['file'] : '' ) . ':' . ( isset( $retorno[0]['line'] ) ? $retorno[0]['line'] : '' );
        $file  = ( $file != ':' ? $file : ( isset( $retorno[0]['class'] ) ? $retorno[0]['class'] : '' ) );

        $this->dados['path_arquivo'] = $file;
    }
}