[//]: # (To view this file use: python -m pip install --user grip; python -m grip -b "README.md")
[//]: # (https://github.com/settings/tokens)
[//]: # (vim ~/.grip/settings.py)
[//]: # (PASSWORD = 'YOUR-ACCESS-TOKEN')
[//]: # (https://github.com/naokazuterada/MarkdownTOC)
[//]: # (Many thanks to peek for animated gif generation: https://github.com/phw/peek)

# Extensão de Frete Jadlog - Magento 2.3
## Ambiente para desenvolvimento

<!-- MarkdownTOC -->

- [Requisitos](#requisitos)
  - [Vagrant](#vagrant)
  - [Chaves de acesso a loja Magento 2](#chaves-de-acesso-a-loja-magento-2)
  - [Conta de email](#conta-de-email)
  - [Arquivo *config.yml*](#arquivo-configyml)
- [Instalação](#instalacao)
- [Solução de problemas](#solucao-de-problemas)
  - [Site ficou sem os dados de exemplo \(*sample data*\)](#site-ficou-sem-os-dados-de-exemplo-sample-data)
  - [Erro 500 com log do Apache *PHP Fatal error:  require\(\): Failed opening required '/var/www/html/vendor/composer/../jadlog/embarcador/registration.php'*](#erro-500-com-log-do-apache-php-fatal-error-require-failed-opening-required-varwwwhtmlvendorcomposerjadlogembarcadorregistrationphp)
- [Notas](#notas)
  - [Git no Windows](#git-no-windows)
  - [Scripts para gerenciar o módulo na máquina virtual](#scripts-para-gerenciar-o-modulo-na-maquina-virtual)
- [Desenvolvimento](#desenvolvimento)

<!-- /MarkdownTOC -->

<a id="requisitos"></a>
## Requisitos

<a id="vagrant"></a>
### Vagrant
O ambiente de desenvolvimento utiliza *Vagrant* para gerenciar a máquina virtual com o software necessário.

Instale o *Vagrant* de acordo com seu ambiente de desenvolvimento. Recomendamos o uso do *VirtualBox* como software de virtualização.

<a id="chaves-de-acesso-a-loja-magento-2"></a>
### Chaves de acesso a loja Magento 2
Você deve criar as chaves de acesso a loja do Magento 2 no endereço https://marketplace.magento.com/customer/accessKeys/.

<a id="conta-de-email"></a>
### Conta de email
O sistema será capaz de enviar emails utilizando um provedor externo. Essa funcionalidade é atendida pelo pacote *sSMTP* (vide arquivo *ssmtp_config.sh*). A configuração é feita na seção *ssmtp* do arquivo *config.yml*; Sugerimos a utilização do *gmail* e o arquivo de configuração de exemplo *config.sample.yml* já possui exemplo de uso.

<a id="arquivo-configyml"></a>
### Arquivo *config.yml*
Segue exemplo de configuração:

```
    define: ubuntu-magento-dev

    virtualbox_name: ubuntu-magento-dev

    virtualbox_memory: 2048 #lower memory may cause deploy of sample data to fail

    hostname: magento.dev.local

    root_db_password: mypass

    #if you want DHCP:
    #private_network_ip: false #for dhcp
    #OR if you want an specific IP:
    private_network_ip: "192.168.50.5" #specific IP

    #if you want ssh on virtual machine on a diffetent port uncomment below
    #ssh_host_port: 2200

    synced_folders:
      nfs: true
      default_disabled: true
      folders:
        - ori: "../src/"
          dst: "/src/"
        - ori: "../dev/"
          dst: "/development/"

    ssmtp:
      test_recipient: youremail@yourdomain.com
      conf:
        root: google_account_to_send_email@gmail.com
        mailhub: smtp.gmail.com:587
        auth_user: google_account_to_send_email
        auth_pass: your_secure_password
        use_starttls: "YES"
        rewrite_domain: gmail.com
        from_line_override: "yes"
      revaliases: root:google_account_to_send_email@gmail.com:smtp.gmail.com:587

    magento2_install:
      access_key:
        #https://marketplace.magento.com/customer/accessKeys/
        public: your_public_key
        private: your_private_key
      admin_firstname: admin
      admin_lastname: admin
      admin_email: youremail@yourdomain.com
      admin_user: admin
      admin_password: admin123
      language: pt_BR
      currency: BRL
      timezone: America/Sao_Paulo

```


<a id="instalacao"></a>
## Instalação
Abaixo seguem os passos gerais para instalação do ambiente de desenvolvimento.

1. Instale o *Vagrant*. Site oficial: [vagrantup.com](http://vagrantup.com/).

2. Instale o *VirtualBox*. Site oficial [virtualbox.org](http://www.virtualbox.org/).

3. Caso utilize hospedeiro *Linux* ou *MacOS* você pode habilitar a opção de pastas sincronizadas utilizando *nfs* na secão *synced_folders* do arquivo de configuração. O *NFS* pode ser instalado conforme o sistema operacional hospedeiro (*e.g. nfs-utils*). Lembre-se de configurar o servidor nfs para aceitar a versão de *nfs* utilizada pelo *Vagrant*, e.g */etc/nfs.conf*:

```
    [nfsd]
     vers3=y
     udp=y
```

4. Copie o arquivo *config.sample.yml* para *config.yml*, faça os ajustes necessários para seu ambiente e, em seguida, inicie o Vagrant:

```
$ vagrant up
```

* Durante a instalação será enviado um email de teste para a conta configurada em *test_recipient* de acordo com os dados informados no arquivo *config.yml* na seção *ssmtp*. O assunto do email será algo similar a *"This is a test message from root@\<hostname\> on \<date\>"*.

5. Acesse o site em [magento.dev.local:8000](http://magento.dev.local:8000/) e verifique as configurações do Apache e do PHP. *Caso tenha alterado o hostname no arquivo de configuração deve utilizar a URL correpondente.*

6. O ambiente de desenvolvimento Magento já deve estar disponível em [magento.dev.local](http://magento.dev.local/). O *backend* pode ser acessado em [magento.dev.local/admin](http://magento.dev.local/admin/).

<a id="solucao-de-problemas"></a>
## Solução de problemas

<a id="site-ficou-sem-os-dados-de-exemplo-sample-data"></a>
### Site ficou sem os dados de exemplo (*sample data*)
Algumas vezes o *composer* não consegue baixar todos os arquivos necessários. Caso isso ocorra, será exibido um erro do tipo:
```
The "https://packagist.org/p/symfony/property-access%249847e6b4fcbdf1a1c484598185a2f246e0c0bb7ab796836c2eb98ed925f08d95.json" file could not be downloaded (HTTP/1.1 404 Not Found)
```

Para resolver deve-se acessar a máquina virtual (*vagrant ssh*) e executar:
```
vagrant@magento:~$ composer clear-cache
vagrant@magento:~$ composer -vvv
vagrant@magento:~$ /var/www/html/bin/magento sampledata:deploy
vagrant@magento:~$ /var/www/html/bin/magento setup:upgrade

```

<a id="erro-500-com-log-do-apache-php-fatal-error-require-failed-opening-required-varwwwhtmlvendorcomposerjadlogembarcadorregistrationphp"></a>
### Erro 500 com log do Apache *PHP Fatal error:  require(): Failed opening required '/var/www/html/vendor/composer/../jadlog/embarcador/registration.php'*

Ocasionalmente a máquina virtual pode não conseguir montar a pasta /var/www/html/extensions/Jadlog_Embarcador. Caso isso aconteça, além do log do Apache informar o erro acima, também ocorrerão avisos ao tentar compilar o módulo como *PHP Warning:  require(/var/www/html/vendor/composer/../jadlog/embarcador/registration.php):*. Para corrigir basta remapear as pastas com o código fonte do módulo:

```
sudo systemctl restart var-www-html-extensions-Jadlog_Embarcador.automount
```

<a id="notas"></a>
## Notas

<a id="git-no-windows"></a>
### Git no Windows
Instalar com a opção "**Checkout as-is, commit Unix-style line endings**".
![Install Git on Windows - EOL Config](install_git_on_windows_eol_config.png?raw=true)

<a id="scripts-para-gerenciar-o-modulo-na-maquina-virtual"></a>
### Scripts para gerenciar o módulo na máquina virtual
***Para que esses scripts funcionem, os mapeamentos das pastas devem estar conforme o exemplo de configuração***:
```
      folders:
        - ori: "../src/"
          dst: "/src/"
        - ori: "../dev/"
          dst: "/development/"
```
A utilização desses scripts é feita diretamente no *shell* da máquina virtual, acessível pelo comando:
```
$ vagrant ssh
```
Todos os scripts estão na pasta */development/magemodule/* da máquina virtual.

- install_module.sh

  Instala o módulo *Jadlog_Embarcador* na pasta de instalação do Magento da máquina virtual. Uso:
  ```
  vagrant@magento:~$ /development/magemodule/install_module.sh

  ```

- enable_module.sh

  Habilita o módulo *Jadlog_Embarcador* (uma vez já instalado). Uso:
  ```
  vagrant@magento:~$ /development/magemodule/enable_module.sh

  ```

- cache_clean.sh

  Limpa o cache do Magento e opcionalmente recompila/atualiza os módulos. Uso:
  ```
  vagrant@magento:~$ /development/magemodule/cache_clean.sh [compile] [upgrade]

  ```

- disable_module.sh

  Desabilita o módulo *Jadlog_Embarcador*. Uso:
  ```
  vagrant@magento:~$ /development/magemodule/disable_module.sh

  ```

- uninstall_module.sh

  Desinstala o módulo *Jadlog_Embarcador*. Uso:
  ```
  vagrant@magento:~$ /development/magemodule/uninstall_module.sh

  ```

<a id="desenvolvimento"></a>
## Desenvolvimento
* [Jadlog](http://www.jadlog.com.br) - *Uma empresa DPDgroup*
