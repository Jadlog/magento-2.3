[//]: # (To view this file use: python -m pip install --user grip; python -m grip -b "README.md")
[//]: # (https://github.com/settings/tokens)
[//]: # (vim ~/.grip/settings.py)
[//]: # (PASSWORD = 'YOUR-ACCESS-TOKEN')
[//]: # (https://github.com/naokazuterada/MarkdownTOC)
[//]: # (Many thanks to silentcast for animated gif generation: ppa:sethj/silentcast)

# Extensão de Frete Jadlog - Magento 2.3
## Ambiente para desenvolvimento

<!-- MarkdownTOC -->

- [Requisitos](#requisitos)
  - [Vagrant](#vagrant)
  - [Chaves de acesso a loja Magento 2](#chaves-de-acesso-a-loja-magento-2)
  - [Conta de email](#conta-de-email)
  - [Arquivo *config.yml*](#arquivo-configyml)
- [Instalação](#instalacao)
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
O sistema será capaz de enviar emails utilizando um provedor externo. Essa funcionalidade é atendida pelo pacote *sSMTP* (vide arquivo *ssmtp_config.sh*). 
A configuração é feita na seção *ssmtp* do arquivo *config.yml*; Sugerimos a utilização do *gmail* e o arquivo de configuração de exemplo *config.sample.yml* já possui exemplo de uso. 

<a id="arquivo-configyml"></a>
### Arquivo *config.yml*
Segue exemplo de configuração:

```
    define: ubuntu-magento-dev

    virtualbox_name: ubuntu-magento-dev

    virtualbox_memory: 2048 #lower memory may cause deploy of sample data to fail

    hostname: magento.dev.local

    root_db_password: mypass

    private_network_ip: "192.168.50.5"

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

<a id="desenvolvimento"></a>
## Desenvolvimento
* [Jadlog](http://www.jadlog.com.br) - *Uma empresa DPDgroup*
