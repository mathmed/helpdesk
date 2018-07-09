# Php Helpdesk System
Um exemplo de sistema básico de helpdesk genérico utilizando Bootstrap, Php 7 e MySqli sem a utilização de nenhum framework.

## PhP
Para criação do sistema foi utilizada a versão 7.2.1 do PhP.

## Bootstrap
Para criação do sistema foi utilizada a versão 4 do bootstrap.

## Instalação
Para fazer uso do sistema
```html
$ git clone https://github.com/mathmed/helpdesk.git
```
ou utilizando npm

```html
$ npm install --save php-helpdesk-system
```

Após instalado é necessário realizar alguns ajustes para começar a utilizar.

## Como utilizar

Em sua base de dados MySQL (phpmyadmin), utilize a função "importar" e selecione o arquivo .sql localizado em `'/helpdesk/sql'`.

Feito isso, navegue até `'/helpdesk/db/config'` e preencha os campos com as informações do seu banco de dados.

Após feitos os dois passos anteriores, é necessário hospedar os arquivos em algum servidor externo ou local. 
Com os arquivos hospedados, acesse `'/helpdesk/index.php'` em seu navegador.
Existe por padrão um usuário com privilégios máximos já cadastrado com as seguintes credenciais:
`Login: admin
  Senha: admin`
  
 ## Configuração de envio de e-mails
  
  Para utilizar a função de envio automático de emails é necessário configurar alguns campos.
  Acesse `'/helpdesk/system/receive'`, no arquivo `email.class.php`, atualizes o construtor da classe definindo os campos $email e , $senha para o e-mail que deseja utilizar para fazer os envios. Atualize o campo $dominio para o seu domínio (ex: dominio.com.br/helpdesk', o link do mesmo será enviado juntamente ao e-mail. 
  
  ### Envio automático de e-mails
  No caminho `'/helpdesk/system/insert/'`, o arquivo `check_atrasos.php` é responsável por enviar e-mails de aviso além de alterar o status de chamados atrasados. É possível utilizar essa função executando o script manualmente ou configurando o crontabs de seu servidor (linux).
  

##  Código fonte

O código fonte é open source, modifique e personalize como quiser.