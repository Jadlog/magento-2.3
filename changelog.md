# Changelog
Todas as mudanças importantes deste projeto serão documentadas nesse arquivo.

Tipos de mudanças:
- **Adicionado** para novos recursos.
- **Modificado** para alterações em recursos existentes.
- **Obsoleto** para recursos que serão removidos nas próximas versões.
- **Removido** para recursos removidos nesta versão.
- **Corrigido** para qualquer correção de bug.
- **Segurança** em caso de vulnerabilidades.

*Esse formato foi inspirado no modelo do [Keep a Changelog](https://keepachangelog.com/pt-BR/1.0.0/).*

# Versões
<!-- MarkdownTOC -->

- [\[0.0.6\] - 2020-07-23](#006-2020-07-23)
  - [Modificado](#modificado)
- [\[0.0.5\] - 2020-07-15](#005-2020-07-15)
  - [Corrigido](#corrigido)
- [\[0.0.4\] - 2020-03-18](#004-2020-03-18)
  - [Modificado](#modificado_1)
- [\[0.0.3\] - 2019-12-02](#003-2019-12-02)
  - [Adicionado](#adicionado)
  - [Modificado](#modificado_2)
  - [Corrigido](#corrigido_1)
- [\[0.0.2\] - 2019-10-30](#002-2019-10-30)
  - [Adicionado](#adicionado_1)
  - [Modificado](#modificado_3)
  - [Corrigido](#corrigido_2)
- [\[0.0.1\] - 2019-10-14](#001-2019-10-14)
  - [Adicionado](#adicionado_2)
- [*Em progresso*:](#em-progresso)

<!-- /MarkdownTOC -->
<a id="006-2020-07-23"></a>
## [0.0.6] - 2020-07-23

<a id="modificado"></a>
### Modificado
- Cotação de fretes arredondará peso para mínimo de 100g.

<a id="005-2020-07-15"></a>
## [0.0.5] - 2020-07-15

<a id="corrigido"></a>
### Corrigido
- Erro ao carregar PUDOs sem complemento.

<a id="004-2020-03-18"></a>
## [0.0.4] - 2020-03-18

<a id="modificado_1"></a>
### Modificado
- Busca de pontos de retirada utilizando o endereço *http://www.jadlog.com.br/embarcador/api/pickup/pudos* sem a necessidade da **Chave "MyPudo"**.
  
<a id="003-2019-12-02"></a>
## [0.0.3] - 2019-12-02

<a id="adicionado"></a>
### Adicionado
- Tela para envio dos pedidos de coleta (backend).

<a id="modificado_2"></a>
### Modificado
- Tabela *jadlog_sales_order*:
  - Inclusão da coluna *campos_dfe*.
  - Inclusão de índices para as colunas: *pudo_id*, *codigo*, *shipment_id*.
- Ambiente de desenvolvimento:
  - Alterar mapeamento da pasta com código fonte.
  - Habilitar composer na instalação do módulo (antes era no provisionamento da máquina).

<a id="corrigido_1"></a>
### Corrigido
- Recalcular frete utilizando o cep do ponto de retirada (tela "fechar pedido").

<a id="002-2019-10-30"></a>
## [0.0.2] - 2019-10-30

<a id="adicionado_1"></a>
### Adicionado
- Busca de pontos de retirada.
- Cálculo de frete para todos os pontos de retirada.

<a id="modificado_3"></a>
### Modificado
- Melhorias na forma de apresentar os dados do ponto de retirada ao final da compra.

<a id="corrigido_2"></a>
### Corrigido
- Tela para escolha de pontos de retirada exibe os pontos com frete calculado.
- Ambiente de desenvolvimento:
  - Correção script de instalação do magento.
  - Ajustes na documentação.

<a id="001-2019-10-14"></a>
## [0.0.1] - 2019-10-14

<a id="adicionado_2"></a>
### Adicionado
- Licença.
- Ambiente de desenvolvimento.
- Manual de instalaçao e uso.
- Tela de configuração da extensão.
- Métodos de envio: Jadlog Expresso e Jadlog Pickup.
- Tela de escolha de pontos de coleta.

<a id="em-progresso"></a>
## *Em progresso*:
  - Instalação facilitada via composer.
  - Exibição dos mapas das lojas.
  - Rastreamento do pedido.
  - Bug conhecidos:
    - Filtrar os pedidos causa em alguns casos problemas na listagem caso o valor filtrado seja apagado e o filtro aplicado novamente. Solução: recarregar a página (tecla *F5*).
