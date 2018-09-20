<?php
    
    //inclui o arquivo q faz a conexao com o banco
    require_once('conexao.php');
    
    //Chama a função que estabelece a conexao com o banco de dados
    $conexao = conexaoBD();

    //Declarando as variaveis para null para não dar erro do tipo  "<br /><b>Notice</b>:  Undefined variable:" no editar
    $nome = null;
    $email = null;
    $telefone = null;
    $celular = null;
    $data_nascimento = null;
    $obs = null;

    $botao = "Inserir"; // INSERIR OU ATUALIZAR ( VALUE DO BOTAO )
    
    //Ativa o uso de variáveis de sessão(Globais)
    session_start();
    
    
        
    if(isset($_POST['btnsalvar']))
    {
        
        #RESGATANDO OS VALUES DO FORMULÁRIO
        $nome = $_POST["txtnome"];
        $email = $_POST["txtemail"];
        $telefone = $_POST["txttelefone"];
        $celular = $_POST["txtcelular"];
        $data_nascimento = $_POST["txtdatanasc"];
        $obs = $_POST["txtobs"];
        
        #TRATAMENTO DA DATA
        //CONVERTER A DATA NO PADRÃO AMERICANO, USAMOS O COMANDO EXPLODE PARA CRIAR ARRAY DE DADOS E MONTAMOS O PADRAO ano-mes-dia para o bd
        $data = explode("/", $data_nascimento);
        $data_nascimento = $data[2]. "-" .$data[1]. "-" .$data[0];
        
        if($_POST['btnsalvar'] == "Inserir"){
            #NUMEROS TIPOS INT NÃO PRECISAM DE ASPAS SIMPLES ('')
            $sql = "INSERT INTO tbl_contatos
                    (nome, email, telefone, celular, DATA_NASCIMENTO, OBS)
                    VALUES (
                    '".$nome."',
                    '".$email."',
                    '".$telefone."',
                    '".$celular."', 
                    '".$data_nascimento."',
                    '".$obs."');
                    ";
        
        }else if($_POST['btnsalvar'] == "Editar"){ #EDITAR
            
            $sql = "UPDATE tbl_contatos SET 
                    nome='".$nome."',
                    telefone='".$telefone."',
                    celular='".$celular."', 
                    email='".$email."',
                    DATA_NASCIMENTO='".$data_nascimento."',
                    OBS='".$obs."'
                
                WHERE codigo=".$_SESSION['codigo'];    
            
        }
        
        #ENVIANDO PARA O BANCO
        mysqli_query($conexao, $sql);
        header('location:index.php');//COMANDO PARA NAO REPETIR UM CONTATO CADASTRADO
        
    }
    
    //VERIFICA A EXISTÊNCIA DA VARIÁVEL MODO
    //A VARIAVEL MODO É ENVIADA PARA A URL ATRAVÉS DO LINK
    // NA TABELA DA CONSULTA, ASSIM COMO O ID DO REGISTRO QUE SERÁ EXCLUIDO OU EDITADO
    if(isset($_GET['modo'])){
        
        //SE MODO FOR EXCLUIR, FAÇA:
        $modo = $_GET['modo'];
        if($modo == 'excluir'){
            
            //VARIAVEL QUE RESGATA A VARIAVEL(ID) DA URL
            $codigo = $_GET['id'];
            
            //COMANDO PARA DELETAR O CONTATO MAIS A CONCATENAÇÃO
            $sql = "DELETE FROM tbl_contatos WHERE codigo=".$codigo;
            
            //EXECUTA NO BANDO DE DADOS O SCRIPT
            mysqli_query($conexao, $sql);
            
            //REDIRECIONA PARA A PÁGINA INICIAL
            header('location:index.php');
        }else if ($modo == 'buscar'){ //****** EDITAR *******
            $botao = "Editar";
            $codigo = $_GET['id'];
            //CRIA UMA VÁRIAVEL DE SESSAO ( GLOBAL) PARA GUARDAR O ID DO REGISTRO QUE SERÁ EDITADO
            $_SESSION['codigo']=$codigo;
            $sql = "SELECT * FROM tbl_contatos WHERE codigo=".$codigo;
            
            //EXECUTA NO BANDO DE DADOS O SCRIPT
            $select = mysqli_query($conexao, $sql);
            if($rsConsulta=mysqli_fetch_array($select)){
                //GUARDANDO EM VARIAVEIS LOCAIS O CONTEUDO QUE O BD RETORNOU NO SELECT
                $nome = $rsConsulta['nome'];
                $email = $rsConsulta['email'];
                $telefone = $rsConsulta['telefone'];
                $celular = $rsConsulta['celular'];
                $data_nascimento = $rsConsulta['DATA_NASCIMENTO'];
                //FUNCAO DATE, REALIZA A FORMATAÇÃO DA DATA EM PADROES DIFERENTES
                //strtotime CONVERTE UMA STRING EM UM TIPO DE DADOS DATA
                $data_nascimento = date("d/m/Y", strtotime($data_nascimento)); 
                
                $obs = $rsConsulta['OBS'];
            }
        }
    }

?>

<html>
	<head>
		<title>
			Conexão com Banco
		</title>
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <link rel="stylesheet" type="text/css" href="css/reset.css">
        <script type="text/javascript" src="js/jquery.js"></script>
        
        <!-- jQuery MODAL -->
        <script>
            /* $(document)ELEMENTO DO HTML */
            //É OBRIGADOTÓRIO A TAG DO jQuery
            $(document).ready(function(){
                
                //Function para abrir a janela Modal
                $(".visualizar").click(function(){//.visualizar é a class da imagem VISUALIZAR
                    //EFEITOS: tootle, slideToogle, slideDown, slideUp, fadeIn, fadeOut
                   $("#container").fadeIn(700); //#container é o ID da DIV do MODAL
                });
            });
            
            //AJAX - Função para receber o ID do registro e descarregar na modal
            function modal(idItem){
                //somente o ajax consegue forçar um post ou get para página sem precisar atualizar a página
                
                $.ajax({
                    type: "POST", //serve para especificar a página
                    url: "modal.php", //serve para especificar a página que sera requisitada
                    data:{idRegistro:idItem}, //serve para criar váriaveis que serão submetidas(GET/POST) para a página requisitada
                    
                    success: function(dados){ //caso toda a requisição seja realizada com exito, então a Function do success será executada e atraves do parametro dados, iremos descarregar na div(modal) o conteudo de dados
                        
                        //Procurar por erros: alert('dados'), e comentar a linha abaixo
                        $('#modal').html(dados);
                    }
                    
                })
                
            }
        
        </script>
        
        
        
        
        
        <!-- VALIDANDO OS CAMPOS DO FORMULÁRIO -->
        <script>
            function Validar(caracter, blockType, campo){
                //INICIA O CAMPO COM A COR BRANCA
                document.getElementById(campo).style="background-color: #fff;";
                if(window.event){
                    //guarda o ascii da letra digitada pelo usuario
                    var letra = caracter.charCode;
                }else{
                    //guarda o ascii da letra digitada pelo usuario
                    var letra = caracter.which;
                }
                
                if(blockType == "number"){
                    //BLOQUEIO DE NÚMEROS
                    if(letra >= 48 && letra <=57){
                        document.getElementById(campo).style="background-color: red;";
                        //CANCELA A AÇÃO DA TECLA
                        return false;
                    }
                        
                }else if (blockType == "caracter"){
                        if(letra < 47 || letra > 57){
                            document.getElementById(campo).style="background-color: red;";
                        //CANCELA A AÇÃO DA TECLA
                            return false;
                        }
                        
                }
               
            }
        </script>
	</head>
	<body>
        <!-- 
            TYPE FORMULÁRIOS EM HTML5:
                tel;
                date;
                month;
                week;
                email;
                range;
                number;
                color;
                url;
        -->
        
        <!-- Codigo para gerar a tela da modal no navegador -->
        <div id="container">
            <div id="modal">
                
            </div>
        </div>
        
        <div class="tela_cadastro">
             CADASTRO DE CONTATOS
        </div>
        <form name="frmcontatos" action="index.php" method="POST">
            <div class="contatos">
                NOME: <input type="text" value="<?php echo($nome);?>" id="nome" name="txtnome" size="40" required placeholder="Digite o nome" onkeypress="return Validar(event, 'number', this.id);"><p>
                EMAIL: <input type="text" value="<?php echo($email);?>" name="txtemail" placeholder="Email" size="40"><p>
                TELEFONE: <input type="text" value="<?php echo($telefone);?>"  id="telefone" name="txttelefone" size="40" placeholder="Telefone" onkeypress="return Validar(event, 'caracter', this.id);"><p>
                CELULAR: <input type="text" value="<?php echo($celular);?>" name="txtcelular" size="40" placeholder="Ex: 011 99999-9999"><p>
                DT.NASC.: <input type="text" value="<?php echo($data_nascimento);?>" name="txtdatanasc" placeholder="DT. NASC" size="40"><p>
                OBS: <input type="text" value="<?php echo($obs);?>" name="txtobs" size="40" placeholder="Observação"><p> 
                
                <input type="submit" name="btnsalvar" value="<?php echo($botao);?>">
                
            </div>
        </form>
            
            <div class="titulo_consulta">
                CONSULTA DE CONTATOS
            </div>
            <div class="div_form">
                <div class="nomes_form">
                    <div class="nomes_consulta">
                        NOME
                    </div>
                    <div class="nomes_consulta">
                        TELEFONE
                    </div>
                    <div class="nomes_consulta">
                       CELULAR   
                    </div>
                    <div class="nomes_consulta">
                        EMAIL
                    </div>
                    <div class="nomes_consulta">
                        OPÇÕES
                    </div>
                </div>
                
                <!-- PEGANDO OS DADOS DO BANCO -->
                <?php
                    $sql = "SELECT * FROM tbl_contatos ORDER BY codigo desc";
                    #EXECUTA UM SCRIPT DO BANCO E GUARDA O RETORNO NA VARIAVEL SELECT
                    $select = mysqli_query($conexao, $sql);
                    
                    ##mysqli_fetch_array
                    while($rsContatos = mysqli_fetch_array($select))//FORMATO ARRAY
                    {
                ?>
                    <div class="nomes_form2 color">

                        <div class="nomes_consulta2">
                            <?php
                                echo($rsContatos['nome'])
                            ?>
                        </div>
                        <div class="nomes_consulta2">
                            <?php
                                echo($rsContatos['telefone'])
                            ?>
                        </div>
                        <div class="nomes_consulta2">
                            <?php
                                echo($rsContatos['celular'])
                            ?>
                        </div>
                        <div class="nomes_consulta2">
                            <?php
                                echo($rsContatos['email'])
                            ?>
                        </div>
                        <div class="nomes_consulta">
                            <div class="img">
                                <a href="index.php?modo=buscar&id=<?php echo($rsContatos['codigo'])?>">
                                    <img src="imagens/editar.png" width="30px"title="Editar Contato" height="30px">
                                </a>
                            </div>
                            <div class="img">
                                <a href="index.php?modo=excluir&id=<?php echo($rsContatos['codigo'])?>"><!-- VARIAVEL MODOO -->
                                    <img src="imagens/excluir.png" width="30px" title="Excluir Contato" height="30px">
                                </a>
                            </div>
                            <div class="img"><!-- **** VISUALIZAR COM AJAX ****) -->
                                <a href="#" class="visualizar" onclick="modal(<?php echo($rsContatos['codigo'])?>)">
                                    <img src="imagens/zoom.png" width="30px" title="Visualizar Contato" height="30px">
                                </a>
                            </div>

                        </div>
                    </div>
                <?php
                    }
                ?>
             <!-- FIM - PEGANDO OS DADOS DO BANCO -->
            </div>
    </body>
</html> 

