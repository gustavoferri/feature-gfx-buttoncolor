# Gfx_ButtonColor – CLI para alteração de cor dos botões por Store View

Este repositório contém um módulo customizado para Adobe Commerce / Magento 2 que permite alterar a cor dos botões de uma store-view específica através de um comando CLI, sem necessidade de conhecimento técnico em Magento ou frontend por parte do cliente.

---

## Objetivo

Permitir que o cliente consiga testar diferentes cores de botões diariamente, sem abrir tickets para o time técnico e sem modificar arquivos de tema.

O cliente informa apenas:
- a cor desejada (HEX)
- o ID da store-view

---

## Solução

A solução segue boas práticas do Adobe Commerce:

- Comando CLI utilizando Symfony Console
- Configuração salva por store-view
- Aplicação da cor via CSS variável
- Nenhuma alteração direta em temas
- Código simples e de fácil manutenção

### Funcionamento

1. O comando valida:
   - formato do HEX
   - existência da store-view
2. A cor é salva em `core_config_data` com escopo store
3. O frontend lê a configuração
4. Uma variável CSS (`--cc-button-bg`) é definida
5. O CSS do módulo aplica a cor aos botões permitidos

---

## Comando disponível

```bash
bin/magento color:change <hex_color> <store_id>

```
## Screenshots

Abaixo estão alguns exemplos visuais do uso do comando e do resultado aplicado no frontend.

### Erro – Store View não encontrada
<img width="531" height="35" alt="store-not-found" src="https://github.com/user-attachments/assets/817b2fb5-5745-4d65-96e7-7c1e01c2c4fa" />

### Sucesso – Comando executado corretamente
<img width="527" height="35" alt="command-success" src="https://github.com/user-attachments/assets/10cb2c58-a00c-4619-b0be-12a045a78cfc" />

### Página Home – Botões com cor preto (#000000)
![print-screen-home](https://github.com/user-attachments/assets/11c8186c-e159-41b5-8db7-36058ee77ce8)

### Página de Produto – Botões com cor preto (#000000)
![product-page](https://github.com/user-attachments/assets/b6e8438c-d2f6-45b2-b2f1-746ed53ec4ee)
