# Behat-Unit

> Este projeto é uma PoC, construído para testes e aprendizado. Não leve o código daqui muito a sério ;)

## Instalação e configuração
Você precisará, basicamente, de PHP, Composer e MySQL.

Clone o repositório:

```
git clone git@github.com:beeblebrox3/testing-behat-unit
```

Instale as dependências:

```
cd testing-behat-unit
composer install
```

Copie o arquivo de configuração do exemplo:
```
cp config.sample.ini config.ini
```

Você precisa editar este arquivo e colocar os dados de acesso ao seu banco de dados (MySQL).
Depois de configurar isso, rode o comando abaixo para configurar as tabelas:

```
php install.php
```

Para rodar o projeto, use o servidor web integrado do PHP (ou use o servidor que quiser ;)):

```
php -S localhost:8000 -t public/
``` 

Acesse no seu navegador http://localhost:8000.


Para rodar os testes, utilize o comando:

```
./vendor/bin/behat
```


## O que está rolando aqui
Neste projeto há uma pequena estrutura simulando uma aplicação. Dentro do diretório `app` temos um arquivo que configura a conexão com o banco de dados, algumas classes de negócio e um arquivo de rotas.
No diretório `features` temos um arquivo de features do behat e o `FeatureContext` com a implementação dos steps.
O context irá realizar todo o step dentro de uma única transação e ao final irá dar rollback para que o banco não seja alterado.
