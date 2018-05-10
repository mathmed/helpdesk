# helpdesk system
Um exemplo de sistema básico de helpdesk genérico utilizando bootstrap, PhP 7 e MySqli.

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

Após adquirir o pacote é necessário em algum servidor podendo ser local.

## Como usar

Em seu servidor de banco de dados, importe o arquivo sql localizado em `'helpdesk/sql'`.

Navegue até `'helpdesk/db/config'` e preencha os campos com as informações do seu banco de dados.

Acesse `'helpdesk/index.php'` através de seu servidor.
Existe um usuário com privilégios máximos já cadastrado:
`Login: admin
  Senha: admin`
  
 ## Configuração de envio de e-mails
  
  Acesse `$ cd 'helpdesk/system/receive'`, no arquivo email.class.php atualizes os campos $email->Username, $mail->Password e $mail->FromName e o corpo das mensagens com suas necessidades.
  
  ### Envio automático de e-mails
  No caminho `'/helpdesk/system/insert/'`, o arquivo check_atrasos.php é responsável por enviar e-mails de aviso além de alterar o status de chamados atrasados. É possível utilizar essa função executando o script manualmente ou configurando o crontabs de seu servidor.
  
