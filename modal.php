<?php
    require('conexao.php');
    $conexao = conexaoBD();
    $codigo = $_POST['idRegistro'];
    
    $sql = "SELECT * FROM tbl_contatos WHERE codigo=".$codigo;
    
    //RETURNO DO BANCO
    $select = mysqli_query($conexao, $sql);

    if($rs = mysqli_fetch_array($select))
    {
        $nome = $rs['nome'];
        $email = $rs['email'];
        $telefone = $rs['telefone'];
        $celular = $rs['celular'];
        $data_nascimento = $rs['DATA_NASCIMENTO'];
        $obs = $rs['OBS'];
        
    }
?>


<html>
    <head>
        <title> Modal </title>
        <style>
            tr, td{
                
                height: 20px;
                border: 1px solid #238948;
                
                border-radius: 5px;
                font-size: 18px;
                
                font-family: Arial;
                text-align: left;
                padding-top: 15px;
                
            }
            
            a{text-decoration: inherit;}
            .fechar{font-family: Arial;font-size: 25px;}

        </style>
        <script type="text/javascript" src="js/jquery.js"></script>
        
        <!-- SCRIPT PARA FECHAR A JANELA MODAL -->
        <script>
            $(document).ready(function(){
                $('.fechar').click(function(){
                    $('#container').fadeOut(400)
                });
            });
        </script>
        
    </head>
    <body>
        <a href="#" class="fechar"> Fechar </a>
        <table width="700">
            <tr><!-- LINHA 1 -->
                <td>
                    Nome:
                </td>
                
                <td>
                    <?php echo($nome)?>
                </td>
                
            </tr>
            <tr><!-- LINHA 2 -->
                <td>
                    Telefone:
                </td>
                
                <td>
                    <?php echo($telefone)?>
                </td>
                
            </tr>
            <tr><!-- LINHA 3 -->
                <td>
                    Celular:
                </td>
                
                <td>
                    <?php echo($celular)?>
                </td>
                
            </tr>
            <tr><!-- LINHA 4 -->
                <td>
                    Email:
                </td>
                
                <td>
                    <?php echo($email)?>
                </td>
                
            </tr>
            <tr><!-- LINHA 5 -->
                <td>
                    DT.Nascimento:
                </td>
                
                <td>
                    <?php echo($data_nascimento)?>
                </td>
                
            </tr>
            <tr><!-- LINHA 6 -->
                <td>
                    OBS:
                </td>
                
                <td>
                    <?php echo($obs)?>
                </td>
                
            </tr>
        </table>
    </body>
</html>