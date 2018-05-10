# helpdesk
Um exemplo de sistema básico de helpdesk utilizando bootstrap, PhP 7 e MySqli.

## PhP
Para criação do sistema foi utilizada a versão 7.2.1 do PhP.

## Bootstrap
Para criação do sistema foi utilizada a versão 4 do bootstrap.

## Instalação
Para fazer uso do sistema
```html
$ git clone https://github.com/mathmed/helpdesk.git
```
Lembrando de adicionar a pasta à algum servidor.

## Como usar

Em seu servidor de banco de dados, importe o arquivo sql localizado em `'helpdesk/sql'`.

Navegue até `'helpdesk/db/config'` e preencha com as informações do seu banco de dados.

Acesse `'helpdesk/index.php'` através de seu servidor. Usuário padrão cadastrado
`Login: admin
  Senha: admin`
  
 ## Configuração de envio de e-mails
  
  Acesse `$ cd 'helpdesk/system/receive'`, no arquivo email.class.php atualizes os campos $email->Username, $mail->Password e $mail->FromName e o corpo das mensagens com suas necessidades.
  
  ### Envio automático de e-mails
  No caminho `'/helpdesk/system/insert/'` existe o arquivo check_atrasos.php responsável por enviar e-mails de aviso caso haja chamados atrasados. Para habilitá-lo é necessário configurar o crontabs de seu servidor.
  
