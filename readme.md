# Desafio Técnico — Processamento de Municípios

A proposta é ler um arquivo CSV contendo nomes de municípios, enriquecer essas informações utilizando a API do IBGE, gerar um novo arquivo consolidado e, a partir dele, calcular estatísticas solicitadas no desafio.

O foco da solução foi criar um fluxo simples, confiável e fácil de entender.

---

## 🧩 Como a solução funciona (visão geral)

1. O sistema lê o arquivo `input.csv`
2. Para cada município:
   - consulta a API do IBGE
   - tenta identificar o município correto
   - classifica o resultado como:
     - `OK`
     - `NAO_ENCONTRADO`
     - `ERRO_API`
3. Gera o arquivo `resultado.csv` com os dados enriquecidos
4. A partir desse arquivo, calcula as estatísticas e gera o `stats.json`

---

## ▶️ Como executar o projeto

Requisitos:
- PHP 7.4 ou superior

Passos:

```bash
php main.php
