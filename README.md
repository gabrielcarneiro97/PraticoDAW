# Desafio Estágio COLTEC 2016 - eduCOLTEC


Esse desafio tem como objetivo selecionar os alunos para o projeto do portal acadêmico. Para isso, você deverá implementar algumas funcionalidades que estão faltando em um sistema fictício voltado para disponibilização de vídeo aulas, denominado eduCOLTEC.

As seções abaixo descrevem as informações necessárias para participação no desafio.

## O Portal eduCOLTEC

O portal eduCOLTEC é um site de disponibilização e compartilhamento de conteúdo, disponiblizado no formato de vídeo. Usuários podem acessar o portal para assistir aos vídeos cadastrados e, se desejarem, comentar a respeito do vído que foi visto. Ainda, os usuários podem adicionar novos vídos, caso achem necessário.

Estruturalmente, o site possui três módulos principais:

* **Cadastro de vídeos:** Permite o cadastramento de novos vídeos no portal
* **Cadastro de comentários:** Permite o cadastramento de novos comentários relacionados a um vídeo
* **Listagem de vídeos:** Listagem dos vídeos cadastrados junto de seus comentários

Cada módulo possui uma URL própria, podendo ser acessado conforme Tabela abaixo:

| URL                  | Funcionalidade                                                 |
|----------------------|----------------------------------------------------------------|
| `/`                    | Listagem dos vídeos existentes                                 |
| `/novoVideo`           | Cadastro de novo vídeo                                         |
| `/novoComentario/:vid` | Cadastro de novo comentário pertencente a um vídeo de id = vid |


## Como obtenho o projeto?

O projeto está disponível publicamente em um repositório git hospedado no GitHub. O link de acesso ao repositório está disponível neste [link](https://github.com/jemaf/desafio-COLTEC-2016).

Para participar do desafio você deverá fazer fork do projeto, uma vez que a entrega deverá ser feita por meio de pull-request.

## Como executo meu projeto?


Para executar o projeto, você deverá navegar para a pasta raiz do projeto, e então utilizar o servidor built-in do PHP para executar o projeto. Você pode executar o servidor built-in por meio do seguinte comando:

```
php -S localhost:8000
```

**OBS: Você precisa ter o interpretador do PHP 5.4 ou superior instalado na máquina.**

Esse comando irá criar um servidor virtual com base nos arquivos do diretório onde o comando foi executado (no nosso caso, todo o projeto).

Uma vez executado o comando, você poderá acessar o site por meio da URL `http://localhost:8000/frontend/#/`.

## Estrutura do Projeto

O projeto foi dividido em dois diretórios principais: `backend` e `frontend`, os quais serão explicados detalhadamente a seguir.


### Backend

O diretório backend contém todo o código do projeto para funcionamento do serviço que irá lidar com os vídeos e comentários do portal. Por opção, o backend do projeto foi desenvolvido em [PHP][PHP]. 

Ainda, optamos por fornecer os dados por meio de um serviço [REST][REST]. Para implementação do serviço utilizamos o [Slim framework][SLIM], um framework PHP para construção de aplicações REST.

No que diz respeito a estrutura do código fonte, o backend possui dois diretórios principais: `classes` e `public`. 

O diretório `classes` possui as classes de entidade, além das classes de acesso a dados responsáveis por fazer a manipulação (inserir, deletar, atualizar, ler) de cada entidade do sistema. Vale ressaltar que essas classes foram desenvolvidas usando PHP puro (não possuem contato com framework).

Já o diretório `public` contém um único arquivo: `index.php`. Esse arquivo contém implementação para do serviço REST planejado para o projeto. Mais especificamente,  no arquivo `index.php` você poderá visualizar quais são os tipos de requisições permitidas e as rotas de serviço implementadas até o momento.

### Frontend

O diretório frontend contém todo o código relacionado a interface do projeto. Isto é, nesse diretório serão encontrados os arquivos HTML, CSS, e javascript utilizados para funcionamento da interface.

Para implementação da interface utilizamos a biblioteca [Materialize CSS][materialize] para estilização do portal, e o framework [AngularJS][ANGULAR] para consumo e preenchimento dos dados provenientes do serviço no portal.

Diferentemente da pasta `backend`, a pasta `frontend` possui vários arquivos. Porém, para resolução do desafio, você deverá ficar atento aos seguintes arquivos e pastas:

* `js/app.js`: Arquivo que contém os arquivos JavaScript necessário para consumo dos dados provenientes do serviço.
* `templates`: Pasta que contém templates de código HTML utilizados para construção da página.
* `index.html`: Arquivo HTML por onde o usuário acessa as funcionalidades.


## O que deve ser feito?

O portal eduCOLTEC possui algumas funcionalidades que estão incompletas. Sua tarefa terminar a implementação do portal de forma que as três funcionalidades funcionem corretamente. 

Mais especificamente, você deverá implementar as tarefas listadas abaixo.

#### TAREFA 01: Implementar frontend do serviço responsável por cadastrar novos comentários.

O cadastro de comentários não está funcionando corretamente. Isso acontece porque, apesar das rotas para cadastro de comentários estarem funcionando, o código necessário para enviar os dados para o serviço não está implementado.

Sua tarefa é implementar o trecho no frontend necessário para envio dos dados do comentário a ser cadastrado no serviço.


**Testando a funcionalidade:** A melhor forma de se testar a funcionalidade é, após tentativa de implementação, fazer cadastro no site e verificar pelo próprio site se comentário foi cadastrado.

#### TAREFA 02: Implementar o backend do serviço para cadastro de novos vídeos.

O portal atualmente consegue listar os vídeos cadastrados no sistema. Porém, o cadastro de novos vídeos não está funcionando corretamente. Isso acontece porque o serviço de backend para cadastro de novos vídeos não foi implementado. 

Sua tarefa é implementar a rota do backend que será responsável por cadastrar um vídeo qualquer.

O cadastramento deverá ser feito por meio de uma requisição do tipo `POST` para a URL `/videos`. Os dados do vídeo a ser cadastrado deverão ser enviados no corpo da requisição no formato JSON.


**Testando a funcionalidade:** A melhor forma de se testar a funcionalidade é, após tentativa de implementação, utilizar o [Postman][postman] para simulação de requisições do tipo `POST` com o JSON do vídeo a ser cadastrado.

## Como será a entrega do projeto?

A entrega será feita por meio de pull requests ao projeto original.


## O que será cobrado?

Esse desafio tem como objetivo principal avaliar sua habilidade no desenvolvimento de novos produtos. Mais especificamente, iremos analisar:

* Como você lida com novos problemas
* Sua iniciativa/proatividade na busca de soluções para esses problemas
* Sua habilidade em utilizar as tecnologias mais atuais para resolução dos problemas encontrados
* A elegância da solução proposta: Você utilizou os recursos corretos?
* Seu código: É legível? segue o padrão de desenvolvimento adotado no projeto?


## Qual material vocês me indicam para o desafio?

Os links abaixo serão seus melhores amigos durante a resolução do desafio. Consulte-os sempre que achar necessário!!

**Backend**

* [Material: Requisições GET & POST][aula_requisicoes]
* [Material: PHP Basics][daw_2015]: Seção backend
* [Material: Criando Serviços REST][aula_rest_php]: 
* [Documentação: PHP][php_manual]
* [Documentação: Slim framework][slim_manual]
* [Tutorial: Slim PHP][slim_tutorial]
* [Software: Postman][postman]

**Frontend**

* [Material: AngularJS][daw_2015]: Seção AngularJS
* [Material: JavaScript][daw_2015]: Seção JavaScript
* [Material: Consumindo Serviços REST][aula_angular_http]
* [Documentação: AngularJS][angular_manual]
* [Documentação: Materialize CSS][materialize]
* [Tutorial: AngularJS][angular_tutorial]
* [Exemplo: AngularJS + Serviços REST][angular_example]


[daw_2015]: http://webservercoltec.coltec.ufmg.br/~joaoeduardo/daw/2015/
[aula_requisicoes]: http://webservercoltec.coltec.ufmg.br/~joaoeduardo/daw/2015/public/aulas/daw-web-intro/index.html
[aula_rest_php]: http://webservercoltec.coltec.ufmg.br/~joaoeduardo/daw/2015/public/aulas/daw-rest-server/rest%20pt2.pdf
[aula_angular_http]: http://webservercoltec.coltec.ufmg.br/~joaoeduardo/daw/2015/public/aulas/daw-angular/angular-http.pdf
[PHP]: http://php.net/
[php_manual]: http://php.net/manual/pt_BR/
[SLIM]: http://www.slimframework.com/
[slim_manual]: http://www.slimframework.com/docs/
[slim_tutorial]: https://github.com/slimphp/Tutorial-First-Application
[REST]: http://webservercoltec.coltec.ufmg.br/~joaoeduardo/daw/2015/public/aulas/daw-rest-server/rest%20pt1.pdf
[ANGULAR]: https://code.angularjs.org/1.5.0/docs/api
[angular_manual]: https://code.angularjs.org/1.5.0/docs/api
[angular_tutorial]: http://www.w3schools.com/angular/ 
[angular_example]: http://plnkr.co/edit/Bgmk0Jtz0zEC6nXa7Z8n?p=preview
[materialize]: http://materializecss.com/
[postman]: https://www.getpostman.com/

