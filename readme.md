## Ambiente utilizado

> Mysql 5.7.24

> Nginx 1.16

> php 7.2.19

> Laravel 5.8

## Setup initial

Execute os seguintes passos abaixo, em um console, após clonar esse repositório:

> composer install

> php artisan key:generate

> php artisan vendor:publish --provider "L5Swagger\L5SwaggerServiceProvider"

> php artisan l5-swagger:generate

Agora, configure o arquivo `app/database.php` e seto o nome da base de dados.
Lembre-se que o usuário necessitará de acessos *root* para criar as tabelas

> php artisan migrate:install

> php artisan migrate

Se ocorreu tudo bem, as tabelas foram criadas no banco definido no `app/database.php`

## Fazendo o crawler das Marcas e Modelos

> php artisan carbel:crawler-models-brands

Esse processo pode demorar um pouco... Ao final desse processo, as tabelas `brands` e `models` estaram populadas.

## Fazendo o crawler dos carros

Antes de rodar, alguns aviso:

1 - Na tabela `brands` existe a coluna `sync` que marcar se o registro foi sincronizado 
    pelo job abaixo. ZERO para pedente e UM para processado;
    
2 - Cada executação do job, ele pegará 10 registros da `brands` e processa. Para cada registros, 
    ele varre apenas 3 paginas (para agilizar o processo de validação). Ambas as variáveis
    podem ser editadas no arquivo `app/Console/Commands/CrawlerCarsComamnd.php`;

3 - Em meus testes, algumas chamadas ao site fonte falhou. Eu tratei essa falha e, se ocorrer
    durante o processo, basta executar o job novamente que ele não incluirá o que já fora 
    processado;
    
4 - O job mostrará, em execução, a pagina q estará sendo consumida para facilitar o
    acompanhamento;
          
> php artisan carbel:crawler-car

Esse processo irá demorar um pouco... Ao final, a tabela `cars` estará populada.

## Endpoints

Segue abaixo o link da documentation para consumo:

> http://_DNS_/api/documentation

É preciso se autenticar para consumir os endpoints que retornam dados de carros.

Execute a api `api/auth` com *login* `carbel` e *password* `carbel123#` para obter
o token de autenticação. O retorno será algo parecido com:

``
{
  "sucess": true,
  "token": {
    "label": "x-auth-token",
    "value": "LVwibEtjwrR8eS08P2lAX2vCtCQuMU9ALCV7JCkjaTswbMKjJC41JGMsYFwiwqcubFtAX2w1wqpfwqw6JV8jX8KsXCJePsKnIWt3JF/CrDo+MF1xwqzCqsKnW0BrXzEkLjR3Wy4+MyXCqnwzLSktai1qZyNPwqo+NcKiwqpfND5qazPCosK0Oms+wrTCpyklwqpeak/Cqi1e"
  }
}
``

A *label* e *value* deverão ser informados no `header` das consultas de carro.

## Obrigado!

