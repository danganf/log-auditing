# Configuração

1 - no arquivo config/app.php, configure a Facades:

```
Ufox\AuditoriaServiceProvider::class,

e o Alias:

'Auditoria' => Ufox\AuditoriaFacades::class,

```
2 - certifique-se de existir na sessão do sistema uma variavel chamada `login_usuario_logado`, que guarda o login utilizado para acessar o sistema