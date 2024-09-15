
<h1>Pendências :</h1>

rota :carteiras/{id}/edit
    
    Depois que gerar uma carteira pdf para ele ele Mudar de Gerar PDF para Gerar Novamente um PDF -> 0%

caso tenha vencido, a cobrança, você deve gerar uma nova. -> (TESTAR)

--------------------
<strong>Gerar PDF:</strong><br>
Ele so deixará o cliente Gerar o PDF se a ultima cobrança daquela carteira seja RECEIVED.

<strong>Processo de Geração da Carteira PDF</strong> 
    
    1 - Pegar todos os dados do estudate para gerar, imagens,cpf ...
    2 - Salvar PDF, e salvar o link na carteira

Na tela de edit, devemos mostrar um card com o pdf disponível
<br>

Na Visualização do Estudante na tela students/{id}/edit ou /show :

    1 - Um botão para atualizar as informações do usuário com base na api da faculdade - pendente
--------------------
<h1>Configuração da plataforma :</h1> - 0 %
    
    Aqui ele pode alterar o valor da cobrança, url do front, logo do front
--------------------
<h1>Fluxo de Cadastro :</h1>
    
    1 - Criar a tabela steps
    2 - Criar a tablea step_registers
--------------------
<h1>Criar tela de profile do admin e mudar senha :</h1>

    1 - Mudar somente imagem e logo
--------------------
<h1>Criar tela de funcionários:</h1>

    1 - Mudar somente imagem e logo
--------------------
<h1> Rever upload e consumo de imagens :</h1>

    1 - 
    2 - Criar a tablea step_registers


# back-end-carteira


<h1>Para rodar o websocket</h1>
    
    Personalize o APP_KEY APP_ID APP_SECRET

    docker run -p 6001:6001 -p 9601:9601 -e SOKETI_DEFAULT_APP_KEY=6e1911549c4e -e SOKETI_DEFAULT_APP_SECRET=ikwhrxtoc22vboqhtun0 -e SOKETI_DEFAULT_APP_ID=242124 quay.io/soketi/soketi:1.4-16-debian

<h1>Para rodar gerador de carteira</h1>
    
    docker run -p 1458:1458 --name gerar-carteira luska066/gerar-carteira-pdf
