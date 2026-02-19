<<<<<<< HEAD
# 🛍️ Achadinhos da Lucy — Multiplataformas

> Sistema web completo para divulgação e cadastro de produtos das plataformas **Shopee**, **Mercado Livre** e **Amazon**.

---

## 📁 Estrutura de Arquivos

```
📂 Projeto/
├── 📄 achadinhos-lucy.html      → Loja principal (vitrine pública)
├── 📄 login.html                → Tela de autenticação (área admin)
├── 📄 cadastro-produto.html     → Painel de cadastro de produtos
├── 🖼️ 619321799_...n.jpg        → Logo da marca
├── 🖼️ 620096214_...n.png        → Banner da página
└── 📄 README.md                 → Este arquivo
```

> ⚠️ **Importante:** todos os arquivos devem estar na **mesma pasta** para que os links e imagens funcionem corretamente.

---

## 🔄 Fluxo Completo do Sistema

```
┌─────────────────────────────────────────────────┐
│           achadinhos-lucy.html                  │
│              (Loja Pública)                     │
│                                                 │
│  Visitantes veem produtos, filtram e compram    │
│  Botão "🔐 Painel Administrativo"               │
└────────────────────┬────────────────────────────┘
                     │ clica no botão
                     ▼
┌─────────────────────────────────────────────────┐
│                login.html                       │
│           (Autenticação Admin)                  │
│                                                 │
│  Usuário: #############                   │
│  Senha:   ********                           │
└────────────────────┬────────────────────────────┘
                     │ credenciais corretas
                     ▼
┌─────────────────────────────────────────────────┐
│          cadastro-produto.html                  │
│           (Painel Administrativo)               │
│                                                 │
│  Cadastra produtos → salva no localStorage      │
│  Produtos aparecem automaticamente na loja      │
└────────────────────┬────────────────────────────┘
                     │ clica "← Ver Loja"
                     ▼
┌─────────────────────────────────────────────────┐
│           achadinhos-lucy.html                  │
│   Produtos cadastrados exibidos com badge ✨    │
└─────────────────────────────────────────────────┘
```

---

## 📄 Descrição de Cada Arquivo

---

### 1. `achadinhos-lucy.html` — Loja Principal

A **vitrine pública** do projeto. Esta é a página que os visitantes e clientes acessam para ver e comprar produtos.

#### ✅ Funcionalidades
- **Header animado** com logo, nome da marca e badges das plataformas
- **Barra de estatísticas** com total de produtos, plataformas e avaliação
- **Barra de busca** em tempo real — filtra os produtos conforme você digita
- **Filtros por plataforma** — exibe todos, somente Shopee, Mercado Livre ou Amazon
- **Cards de produto** com imagem, nome, categoria, avaliação, preço, desconto e parcelamento
- **Badge ✨ Novo** nos produtos cadastrados pelo painel administrativo
- **Botão WhatsApp** em cada card — abre conversa direto com a Lucy com o nome do produto
- **Botão flutuante** do WhatsApp no canto inferior direito
- **Banner de ofertas** com botão de contato
- **Botão Painel Administrativo** que redireciona para o login
- **Footer** com links e informações de contato

#### 🔌 Integração com API
No início do JavaScript há um bloco `API_CONFIG` onde basta informar a URL da sua API:

```js
const API_CONFIG = {
  baseUrl: 'https://sua-api.com/api/products', // ← altere aqui
  params: { limit: 50, active: true },
  headers: {
    'Content-Type': 'application/json',
    // 'Authorization': 'Bearer SEU_TOKEN'
  }
};
```

**Formato esperado da API** (array de objetos):
```json
[
  {
    "id": "001",
    "title": "Nome do Produto",
    "category": "Eletrônicos",
    "image": "https://url-da-imagem.jpg",
    "platform": "shopee",
    "price": 89.90,
    "originalPrice": 159.90,
    "discount": 44,
    "rating": 4.8,
    "reviews": 1243,
    "installments": "4x R$ 22,47",
    "url": "https://link-do-produto"
  }
]
```

> 💡 Se a API não estiver configurada ou retornar erro, a página exibirá **8 produtos demo** automaticamente.

#### 📦 Prioridade de carregamento dos produtos
```
1º → Produtos cadastrados pelo painel (localStorage)  ← aparecem primeiro com badge ✨ Novo
2º → Produtos da API externa (ou demo se API offline)
```

---

### 2. `login.html` — Tela de Autenticação

Página de **acesso restrito** ao painel administrativo. Nenhum visitante comum passa por ela.

#### ✅ Funcionalidades
- Logo com borda giratória animada
- Fundo com bolhas de luz e partículas flutuantes
- Campos de **usuário** e **senha** com validação
- **Botão olho** para mostrar/ocultar a senha
- Tecla **Enter** navega entre campos e confirma o login
- **Mensagens de erro específicas:** usuário errado, senha errada ou ambos
- **Sistema de tentativas:** bloqueia por 30 segundos após 5 erros consecutivos
- **Contador regressivo** durante o bloqueio
- Tela de **sucesso animada** com barra de progresso antes de redirecionar
- Se já estiver logado, redireciona direto para o cadastro sem pedir login novamente

#### 🔐 Credenciais fixas
| Campo   | Valor               |
|---------|---------------------|
| Usuário | `achadinhos_dalucy` |
| Senha   | `salvador91`        |

#### 🗝️ Sessão
Após o login bem-sucedido, salva a chave `lucy_admin_session = "authenticated"` em:
- `localStorage` — persiste ao fechar e reabrir o navegador
- `sessionStorage` — persiste enquanto a aba estiver aberta

---

### 3. `cadastro-produto.html` — Painel Administrativo

Área **protegida por login** para cadastrar novos produtos na loja. Acessível somente após autenticação.

#### 🔒 Proteção de acesso
Ao abrir a página, o JavaScript verifica imediatamente se existe sessão ativa. Caso não exista, redireciona para `login.html` **sem renderizar nada** (o body fica invisível durante a checagem, evitando flashes de conteúdo).

#### ✅ Funcionalidades
- Formulário completo com todos os campos do produto
- **Preview ao vivo** — conforme preenche, o card do produto aparece em tempo real no painel lateral
- **Cálculo automático de desconto** a partir dos preços informados
- **Validação de campos obrigatórios** com mensagens de erro em destaque
- **Verificação de ID duplicado** — impede cadastrar dois produtos com o mesmo ID
- **Lista lateral** de todos os produtos já cadastrados com opção de excluir
- **Toast notifications** de sucesso ou erro
- **Botão 🚪 Sair** — encerra a sessão e volta para o login
- **Botão ← Ver Loja** — navega para a loja sem encerrar a sessão

#### 📋 Campos do formulário
| Campo          | Obrigatório | Descrição                                      |
|----------------|:-----------:|------------------------------------------------|
| `id`           | ✅           | Identificador único do produto                 |
| `title`        | ✅           | Nome completo do produto                       |
| `category`     | ❌           | Categoria (Eletrônicos, Beleza, etc.)          |
| `image`        | ✅           | URL direta da imagem                           |
| `platform`     | ✅           | shopee / mercadolivre / amazon                 |
| `price`        | ✅           | Preço atual em R$                              |
| `originalPrice`| ❌           | Preço original (antes do desconto)             |
| `discount`     | ❌           | % de desconto (calculado automaticamente)      |
| `rating`       | ❌           | Nota de 0 a 5 (ex: 4.8)                       |
| `reviews`      | ❌           | Número de avaliações                           |
| `installments` | ❌           | Texto de parcelamento (ex: 10x R$ 24,90)       |
| `url`          | ✅           | Link direto para o produto na plataforma       |

#### 💾 Onde os dados são salvos
Os produtos cadastrados são salvos no **`localStorage`** do navegador com a chave:
```
achadinhos_lucy_products
```

Isso significa que os dados **ficam salvos no computador** mesmo após fechar o navegador, e são carregados automaticamente toda vez que a loja é aberta.

> ⚠️ Se o cache do navegador for limpo, os produtos cadastrados localmente serão perdidos. Para uma solução permanente, conecte a uma API/banco de dados.

---

## 🎨 Identidade Visual

| Elemento     | Valor                          |
|--------------|-------------------------------|
| Cor principal| `#e85d1a` (laranja)           |
| Cor destaque | `#f5c518` (amarelo)           |
| Cor azul     | `#2563eb`                     |
| Cor escura   | `#1a1a2e`                     |
| Fonte título | **Pacifico** (Google Fonts)   |
| Fonte corpo  | **Nunito** (Google Fonts)     |

---

## 📱 Contato

| Canal     | Informação                                                          |
|-----------|---------------------------------------------------------------------|
| WhatsApp  | [(82) 99812-0711](https://wa.me/5582998120711)                      |
| Shopee    | Configurável no footer da loja                                      |
| Mercado Livre | Configurável no footer da loja                                  |
| Amazon    | Configurável no footer da loja                                      |

---

## 🚀 Como Usar

### Passo 1 — Preparar os arquivos
Coloque todos os arquivos na **mesma pasta** no seu computador:
```
📂 MinhaLoja/
├── achadinhos-lucy.html
├── login.html
├── cadastro-produto.html
├── 619321799_...n.jpg   (logo)
└── 620096214_...n.png   (banner)
```

### Passo 2 — Abrir a loja
Dê duplo clique em **`achadinhos-lucy.html`** para abrir no navegador.

### Passo 3 — Cadastrar um produto
1. Clique em **🔐 Painel Administrativo** na barra superior da loja
2. Faça login com as credenciais
3. Preencha o formulário e clique em **✅ Cadastrar Produto**
4. O produto aparece instantaneamente na loja com o badge **✨ Novo**

### Passo 4 — Conectar sua API (opcional)
Abra `achadinhos-lucy.html` em um editor de texto e localize:
```js
baseUrl: 'https://sua-api.com/api/products',
```
Substitua pela URL real da sua API.

---

## 🔮 Possibilidades de Evolução Futura

- [ ] Conectar a um banco de dados real (Firebase, Supabase, etc.)
- [ ] Sistema de edição de produtos já cadastrados
- [ ] Upload de imagens diretamente do computador
- [ ] Página de detalhes de cada produto
- [ ] Sistema de cupons e promoções
- [ ] Painel com gráficos de produtos mais clicados
- [ ] Login com autenticação segura via JWT/OAuth
- [ ] Versão mobile como PWA (Progressive Web App)
- [ ] Integração com APIs oficiais da Shopee e Mercado Livre

---

## ⚙️ Tecnologias Utilizadas

| Tecnologia       | Uso                                      |
|------------------|------------------------------------------|
| HTML5            | Estrutura das páginas                    |
| CSS3             | Estilização, animações e responsividade  |
| JavaScript (ES6) | Lógica, filtros, autenticação e storage  |
| localStorage     | Persistência dos produtos cadastrados    |
| sessionStorage   | Controle de sessão do login              |
| Google Fonts     | Fontes Pacifico e Nunito                 |
| WhatsApp API     | Links diretos para conversa              |

> Nenhuma biblioteca externa foi utilizada. O projeto roda **100% no navegador**, sem servidor ou instalação.

---

=======
# 🛍️ Achadinhos da Lucy — Multiplataformas

> Sistema web completo para divulgação e cadastro de produtos das plataformas **Shopee**, **Mercado Livre** e **Amazon**.

---

## 📁 Estrutura de Arquivos

```
📂 Projeto/
├── 📄 achadinhos-lucy.html      → Loja principal (vitrine pública)
├── 📄 login.html                → Tela de autenticação (área admin)
├── 📄 cadastro-produto.html     → Painel de cadastro de produtos
├── 🖼️ 619321799_...n.jpg        → Logo da marca
├── 🖼️ 620096214_...n.png        → Banner da página
└── 📄 README.md                 → Este arquivo
```

> ⚠️ **Importante:** todos os arquivos devem estar na **mesma pasta** para que os links e imagens funcionem corretamente.

---

## 🔄 Fluxo Completo do Sistema

```
┌─────────────────────────────────────────────────┐
│           achadinhos-lucy.html                  │
│              (Loja Pública)                     │
│                                                 │
│  Visitantes veem produtos, filtram e compram    │
│  Botão "🔐 Painel Administrativo"               │
└────────────────────┬────────────────────────────┘
                     │ clica no botão
                     ▼
┌─────────────────────────────────────────────────┐
│                login.html                       │
│           (Autenticação Admin)                  │
│                                                 │
│  Usuário: #############                   │
│  Senha:   ********                           │
└────────────────────┬────────────────────────────┘
                     │ credenciais corretas
                     ▼
┌─────────────────────────────────────────────────┐
│          cadastro-produto.html                  │
│           (Painel Administrativo)               │
│                                                 │
│  Cadastra produtos → salva no localStorage      │
│  Produtos aparecem automaticamente na loja      │
└────────────────────┬────────────────────────────┘
                     │ clica "← Ver Loja"
                     ▼
┌─────────────────────────────────────────────────┐
│           achadinhos-lucy.html                  │
│   Produtos cadastrados exibidos com badge ✨    │
└─────────────────────────────────────────────────┘
```

---

## 📄 Descrição de Cada Arquivo

---

### 1. `achadinhos-lucy.html` — Loja Principal

A **vitrine pública** do projeto. Esta é a página que os visitantes e clientes acessam para ver e comprar produtos.

#### ✅ Funcionalidades
- **Header animado** com logo, nome da marca e badges das plataformas
- **Barra de estatísticas** com total de produtos, plataformas e avaliação
- **Barra de busca** em tempo real — filtra os produtos conforme você digita
- **Filtros por plataforma** — exibe todos, somente Shopee, Mercado Livre ou Amazon
- **Cards de produto** com imagem, nome, categoria, avaliação, preço, desconto e parcelamento
- **Badge ✨ Novo** nos produtos cadastrados pelo painel administrativo
- **Botão WhatsApp** em cada card — abre conversa direto com a Lucy com o nome do produto
- **Botão flutuante** do WhatsApp no canto inferior direito
- **Banner de ofertas** com botão de contato
- **Botão Painel Administrativo** que redireciona para o login
- **Footer** com links e informações de contato

#### 🔌 Integração com API
No início do JavaScript há um bloco `API_CONFIG` onde basta informar a URL da sua API:

```js
const API_CONFIG = {
  baseUrl: 'https://sua-api.com/api/products', // ← altere aqui
  params: { limit: 50, active: true },
  headers: {
    'Content-Type': 'application/json',
    // 'Authorization': 'Bearer SEU_TOKEN'
  }
};
```

**Formato esperado da API** (array de objetos):
```json
[
  {
    "id": "001",
    "title": "Nome do Produto",
    "category": "Eletrônicos",
    "image": "https://url-da-imagem.jpg",
    "platform": "shopee",
    "price": 89.90,
    "originalPrice": 159.90,
    "discount": 44,
    "rating": 4.8,
    "reviews": 1243,
    "installments": "4x R$ 22,47",
    "url": "https://link-do-produto"
  }
]
```

> 💡 Se a API não estiver configurada ou retornar erro, a página exibirá **8 produtos demo** automaticamente.

#### 📦 Prioridade de carregamento dos produtos
```
1º → Produtos cadastrados pelo painel (localStorage)  ← aparecem primeiro com badge ✨ Novo
2º → Produtos da API externa (ou demo se API offline)
```

---

### 2. `login.html` — Tela de Autenticação

Página de **acesso restrito** ao painel administrativo. Nenhum visitante comum passa por ela.

#### ✅ Funcionalidades
- Logo com borda giratória animada
- Fundo com bolhas de luz e partículas flutuantes
- Campos de **usuário** e **senha** com validação
- **Botão olho** para mostrar/ocultar a senha
- Tecla **Enter** navega entre campos e confirma o login
- **Mensagens de erro específicas:** usuário errado, senha errada ou ambos
- **Sistema de tentativas:** bloqueia por 30 segundos após 5 erros consecutivos
- **Contador regressivo** durante o bloqueio
- Tela de **sucesso animada** com barra de progresso antes de redirecionar
- Se já estiver logado, redireciona direto para o cadastro sem pedir login novamente

#### 🔐 Credenciais fixas
| Campo   | Valor               |
|---------|---------------------|
| Usuário | `achadinhos_dalucy` |
| Senha   | `salvador91`        |

#### 🗝️ Sessão
Após o login bem-sucedido, salva a chave `lucy_admin_session = "authenticated"` em:
- `localStorage` — persiste ao fechar e reabrir o navegador
- `sessionStorage` — persiste enquanto a aba estiver aberta

---

### 3. `cadastro-produto.html` — Painel Administrativo

Área **protegida por login** para cadastrar novos produtos na loja. Acessível somente após autenticação.

#### 🔒 Proteção de acesso
Ao abrir a página, o JavaScript verifica imediatamente se existe sessão ativa. Caso não exista, redireciona para `login.html` **sem renderizar nada** (o body fica invisível durante a checagem, evitando flashes de conteúdo).

#### ✅ Funcionalidades
- Formulário completo com todos os campos do produto
- **Preview ao vivo** — conforme preenche, o card do produto aparece em tempo real no painel lateral
- **Cálculo automático de desconto** a partir dos preços informados
- **Validação de campos obrigatórios** com mensagens de erro em destaque
- **Verificação de ID duplicado** — impede cadastrar dois produtos com o mesmo ID
- **Lista lateral** de todos os produtos já cadastrados com opção de excluir
- **Toast notifications** de sucesso ou erro
- **Botão 🚪 Sair** — encerra a sessão e volta para o login
- **Botão ← Ver Loja** — navega para a loja sem encerrar a sessão

#### 📋 Campos do formulário
| Campo          | Obrigatório | Descrição                                      |
|----------------|:-----------:|------------------------------------------------|
| `id`           | ✅           | Identificador único do produto                 |
| `title`        | ✅           | Nome completo do produto                       |
| `category`     | ❌           | Categoria (Eletrônicos, Beleza, etc.)          |
| `image`        | ✅           | URL direta da imagem                           |
| `platform`     | ✅           | shopee / mercadolivre / amazon                 |
| `price`        | ✅           | Preço atual em R$                              |
| `originalPrice`| ❌           | Preço original (antes do desconto)             |
| `discount`     | ❌           | % de desconto (calculado automaticamente)      |
| `rating`       | ❌           | Nota de 0 a 5 (ex: 4.8)                       |
| `reviews`      | ❌           | Número de avaliações                           |
| `installments` | ❌           | Texto de parcelamento (ex: 10x R$ 24,90)       |
| `url`          | ✅           | Link direto para o produto na plataforma       |

#### 💾 Onde os dados são salvos
Os produtos cadastrados são salvos no **`localStorage`** do navegador com a chave:
```
achadinhos_lucy_products
```

Isso significa que os dados **ficam salvos no computador** mesmo após fechar o navegador, e são carregados automaticamente toda vez que a loja é aberta.

> ⚠️ Se o cache do navegador for limpo, os produtos cadastrados localmente serão perdidos. Para uma solução permanente, conecte a uma API/banco de dados.

---

## 🎨 Identidade Visual

| Elemento     | Valor                          |
|--------------|-------------------------------|
| Cor principal| `#e85d1a` (laranja)           |
| Cor destaque | `#f5c518` (amarelo)           |
| Cor azul     | `#2563eb`                     |
| Cor escura   | `#1a1a2e`                     |
| Fonte título | **Pacifico** (Google Fonts)   |
| Fonte corpo  | **Nunito** (Google Fonts)     |

---

## 📱 Contato

| Canal     | Informação                                                          |
|-----------|---------------------------------------------------------------------|
| WhatsApp  | [(82) 99812-0711](https://wa.me/5582998120711)                      |
| Shopee    | Configurável no footer da loja                                      |
| Mercado Livre | Configurável no footer da loja                                  |
| Amazon    | Configurável no footer da loja                                      |

---

## 🚀 Como Usar

### Passo 1 — Preparar os arquivos
Coloque todos os arquivos na **mesma pasta** no seu computador:
```
📂 MinhaLoja/
├── achadinhos-lucy.html
├── login.html
├── cadastro-produto.html
├── 619321799_...n.jpg   (logo)
└── 620096214_...n.png   (banner)
```

### Passo 2 — Abrir a loja
Dê duplo clique em **`achadinhos-lucy.html`** para abrir no navegador.

### Passo 3 — Cadastrar um produto
1. Clique em **🔐 Painel Administrativo** na barra superior da loja
2. Faça login com as credenciais
3. Preencha o formulário e clique em **✅ Cadastrar Produto**
4. O produto aparece instantaneamente na loja com o badge **✨ Novo**

### Passo 4 — Conectar sua API (opcional)
Abra `achadinhos-lucy.html` em um editor de texto e localize:
```js
baseUrl: 'https://sua-api.com/api/products',
```
Substitua pela URL real da sua API.

---

## 🔮 Possibilidades de Evolução Futura

- [ ] Conectar a um banco de dados real (Firebase, Supabase, etc.)
- [ ] Sistema de edição de produtos já cadastrados
- [ ] Upload de imagens diretamente do computador
- [ ] Página de detalhes de cada produto
- [ ] Sistema de cupons e promoções
- [ ] Painel com gráficos de produtos mais clicados
- [ ] Login com autenticação segura via JWT/OAuth
- [ ] Versão mobile como PWA (Progressive Web App)
- [ ] Integração com APIs oficiais da Shopee e Mercado Livre

---

## ⚙️ Tecnologias Utilizadas

| Tecnologia       | Uso                                      |
|------------------|------------------------------------------|
| HTML5            | Estrutura das páginas                    |
| CSS3             | Estilização, animações e responsividade  |
| JavaScript (ES6) | Lógica, filtros, autenticação e storage  |
| localStorage     | Persistência dos produtos cadastrados    |
| sessionStorage   | Controle de sessão do login              |
| Google Fonts     | Fontes Pacifico e Nunito                 |
| WhatsApp API     | Links diretos para conversa              |

> Nenhuma biblioteca externa foi utilizada. O projeto roda **100% no navegador**, sem servidor ou instalação.

---

>>>>>>> 05c17e974ed82686ed1612707809daf60d2c183e
*Desenvolvido com ❤️ para Achadinhos da Lucy · Multiplataformas*# achados_da_lucy
# Achadinhos_da_lucy
