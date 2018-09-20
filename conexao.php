<?php
    
    //Função que estabelece a conexão com o banco de dados
    function conexaoBD(){
        
        //Conexão com o BANCO DE DADOS
        $host = "localhost";
        $database = "db_contatos2018inf3m"; #NOME DO BANCO
        $user = "root"; #NOME USUARIO
        $password = "bcd127"; #SENHA


        //EXEMPLO DE CONEXAO USANDO A BIBLIOTECA mysql_connect
        //mysql_connect($host, $user, $password)
        //mysql_selectdb($database)

        #PASSANDO AS VÁRIAVES PARA A CONEXÃO COM O BANCO. OBS: NA ORDEM ABAIXO($host, $user, $password, $database)
        //ESABELECE A CONEXÃO COM O BANCO DE DADOS MYSQL, USANDO A BIBLIOTECA MYSQLI
        //IF CASO A CONEXÃO DO BANCO NÃO FOR ESTABELECIDA
        if(!$conexao = mysqli_connect($host, $user, $password, $database)){
            echo("ERRO! Não foi possível fazer a conexão com o banco de dados");
        }
        
        return $conexao;
    }
    
?>