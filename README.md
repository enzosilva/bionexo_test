# Bionexo RPA Test

Teste separado em instruções criadas a partir dos _Command Console_ do Laravel.

As instruções foram as seguintes:
- 1ª: Acessar a página https://testpages.herokuapp.com/styled/tag/table.html e capturar todas as informações exibidas na tabela e armazenar em um banco de dados (ex.: myqsl).
- 2ª: Preencher o formulário através do link https://testpages.herokuapp.com/styled/basic-html-form-test.html e retornar se preenchimento foi ok ou não. ( pode inventar as informações a serem preenchidas).
- 3ª: Baixar o arquivo através do link https://testpages.herokuapp.com/styled/download/download.html pelo botão “Direct Link Download” e salvar no seu disco local e renomear o arquivo para “Teste TKS”.
- 4ª: Realizar o upload do arquivo baixado no item 3 através do link https://testpages.herokuapp.com/styled/file-upload-test.html.
- 5ª: Realizar a leitura do .pdf em anexo - _disponibilizado por email_ - e armazenar em .xlsx ou .csv (fica a critério do candidato).

Sendo assim, por se tratar de automações, descartei a necessidade de criar rotas para devidas funcionalidades e optei por desenvolver comandos.

## Instruções iniciais
Por ter sido desenvolvido em Laravel, o padrão de instalação da aplicação via Composer deve ser realizada ao baixar o projeto:
- `composer install`

Como pedido na Instrução de nº1, fora criadas duas tabelas para armazenar os dados `user` e `amount`. Para criar as tabelas basta rodar o seguinte comando:
- `php artisan migrate`
Lembrando que é necessário ter um banco de dados criado e corretamente configurado a partir do arquivo `.env` da aplicação.

E por falar em `.env`, não se esqueça de apontar a porta em que o WebDriver irá rodar através da constante `WEBDRIVER_PORT` (**4444** é o padrão).

Ainda sobre configurações iniciais, para algumas instruções se faz necessário apontar o diretório de download da máquina através do seguinte arquivo:
- `app/Console/Commands/Instruction/Data/File.php`
Para modificar o caminho do diretório, basta utilizar a função `setDownloadBasePath`. Ex.:
```
<?php

/**
 * @var File $file
 */
public function __construct(private File $file)
{
    $file->setDownloadBasePath('/path/to/directory');
}
```
### Lista dos comandos
Foi desenvolvido um comando para cada instrução:
- `php artisan beecare:instruction1`
- `php artisan beecare:instruction2`
- `php artisan beecare:instruction3`
- `php artisan beecare:instruction4`
- `php artisan beecare:instruction5`

É possível passar como argumento para os comandos qual navegador será manipulado pelo WebDriver (**Chrome** é o padrão). Ex.:
- `php artisan beecare:instruction1 firefox`

#### Agradecimento
Valeu, Bionexo, pela oportunidade de participar desse processo! Foi massa aprender um pouco sobre automações.