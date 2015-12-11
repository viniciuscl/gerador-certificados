# gerador-certificados
Gerador de Certificados em PHP

REQUISITOS
- Ambiente WAMP ou LAMP (Apache / PHP 5 / MySQL)

PENDÊNCIAS
- Interface para Cadastrar/Alterar/Excluir usuários
- Interface para Alterar Configurações do Sistema

OBSERVAÇÕES
- O código de criação do banco de dados está no arquivo "bd.sql"

- Definir o host, usuário, senha e nome do banco de dados no arquivo "banco.php"

- Definir configurações do sistema na tabela "config" do banco de dados, conforme os campos abaixo:
	- Campo "url_download_certificados": URL do seu site que tornará os certificados acessíveis via web.
	- Campo "caminho_armazenar_certificados": caminho do sistema operacional onde os certificados serão armazenados.
	
- Definir usuários que terão acesso ao sistema diretamente no banco de dados (tabela "usuario"), pois ainda não foi criado uma interface para isso.
	- Há um usuário padrão já definido no banco de dados com as seguintes credenciais: Login: admin | Senha: admin
	- No banco de dados, a senha deve ser um HASH MD5
	
- As imagens (cabeçalho,assinatura) para gerar os certificados estão na pasta "resources/images/". Ao alterá-las, o ideal é manter o mesmo tamanho de imagem
	



