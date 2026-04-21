const produtos = [
    { id: 1, nome: "Café Expresso",    apelido: "Expresso",      categoria: "Bebidas", preco: 8.00,  cor: "#4a2c2a", imagem: "imagens/cafe-expresso.png" },
    { id: 2, nome: "Cappuccino",       apelido: "Cappuccino",    categoria: "Bebidas", preco: 12.50, cor: "#7b4f3a", imagem: "imagens/cappuccino.png" },
    { id: 3, nome: "Pão de Queijo",    apelido: "Pão de Queijo", categoria: "Comidas", preco: 6.50,  cor: "#b07d3a", imagem: "imagens/pao-de-queijo.png" },
    { id: 4, nome: "Croissant",        apelido: "Croissant",     categoria: "Comidas", preco: 14.00, cor: "#c8893a", imagem: "imagens/croissant.png" },
    { id: 5, nome: "Bolo de Chocolate",apelido: "Bolo",          categoria: "Comidas", preco: 15.00, cor: "#3b1f1a", imagem: "imagens/bolo-chocolate.png" },
    { id: 6, nome: "Torta de Limão",   apelido: "Torta",         categoria: "Comidas", preco: 18.00, cor: "#5a7a3a", imagem: "imagens/torta-limao.png" }
];

let carrinho = [];

const listaProdutosEl = document.getElementById("lista-produtos"); 
const listaCarrinhoEl = document.getElementById("lista-carrinho"); 
const btnLimpar = document.getElementById("btn-limpar");     
const filtroEl = document.getElementById("filtro"); 
const btnFinalizar = document.getElementById("btn-finalizar");    

// Local Storage

function carregarDados() {
    const carrinhoSalvo = localStorage.getItem("carrinhoCaixa");

    if (carrinhoSalvo) {
        carrinho = JSON.parse(carrinhoSalvo);
    } else {
        carrinho = [];
    }
}

function salvarDados() {
    localStorage.setItem("carrinhoCaixa", JSON.stringify(carrinho));
}


function filtrarProdutos(valorFiltro) {

    let filtrados = [];

    switch (valorFiltro) {
        case "bebidas":
            filtrados = produtos.filter(p => p.categoria === "Bebidas");
            break;

        case "comidas":
            filtrados = produtos.filter(p => p.categoria === "Comidas");
            break;

        case "todos":
            filtrados = produtos.slice();
            break;
    }

    return filtrados;
}


function listarProdutos() {

    listaProdutosEl.innerHTML = "";

    let produtosParaMostrar = filtrarProdutos(filtroEl.value);

    produtosParaMostrar.forEach(function(produto) {

        const card = document.createElement("div");
        card.classList.add("card");

        card.innerHTML = `
            <div class="card-top">
                <img class="card-img" src="${produto.imagem}" alt="${produto.nome}">
            </div>
            <div class="card-bottom">
                <span class="card-nome">${produto.nome}</span>
                <span class="card-categoria">${produto.categoria}</span>
                <div class="card-footer">
                    <span class="card-preco">R$ ${produto.preco.toFixed(2)}</span>
                    <button class="btn-adicionar" onclick="adicionarAoCarrinho(${produto.id})">+ Adicionar</button>
                </div>
            </div>
        `;

        listaProdutosEl.appendChild(card);
    });
}



function adicionarAoCarrinho(idProduto) {

    const produto = produtos.find(p => p.id === idProduto);

    let encontrado = false;

    for (let i = 0; i < carrinho.length; i++) {
        if (carrinho[i].id === produto.id) {
            carrinho[i].quantidade++;
            encontrado = true;
            break;
        }
    }

    if (!encontrado) {
        carrinho.push({
            id: produto.id,
            nome: produto.nome,
            imagem: produto.imagem,
            preco: produto.preco,
            quantidade: 1
        });
    }

    salvarDados();
    atualizarCarrinho();
}

function removerDoCarrinho(idProduto) {

    const posicao = carrinho.findIndex(p => p.id === idProduto);

    if (posicao !== -1) {

        if (carrinho[posicao].quantidade > 1) {
            carrinho[posicao].quantidade--;
        } else {
            carrinho.splice(posicao, 1);
        }
    }

    salvarDados();
    atualizarCarrinho();
}

function limparCarrinho() {
    carrinho = [];
    salvarDados();
    atualizarCarrinho();
}



function atualizarCarrinho() {

    listaCarrinhoEl.innerHTML = "";

    if (carrinho.length === 0) {
        listaCarrinhoEl.innerHTML = "<p class='carrinho-vazio'>Seu carrinho está vazio.</p>";
        atualizarTotal();
        return;
    }

    for (let i = 0; i < carrinho.length; i++) {

        const item = carrinho[i];
        const totalItem = item.preco * item.quantidade;

        const div = document.createElement("div");
        div.classList.add("cart-item");

        div.innerHTML = `
            <div class="item-info">
                <img class="item-img" src="${item.imagem}" alt="${item.nome}">
                <div class="item-texto">
                    <span class="item-nome">${item.nome}</span>
                    <span class="item-calc">${item.quantidade}x R$ ${item.preco.toFixed(2)}</span>
                </div>
            </div>
            <div class="item-price-area">
                <span class="item-total">R$ ${totalItem.toFixed(2)}</span>
                <button class="btn-remover" onclick="removerDoCarrinho(${item.id})">×</button>
            </div>
        `;

        listaCarrinhoEl.appendChild(div);
    }

    atualizarTotal();
}

function atualizarTotal() {

    let total = 0;

    for (let i = 0; i < carrinho.length; i++) {
        total += carrinho[i].preco * carrinho[i].quantidade;
    }

    const totalValorEl = document.getElementById("total-valor");
    totalValorEl.textContent = "R$ " + total.toFixed(2);
}



document.addEventListener("DOMContentLoaded", function() {
    carregarDados();
    listarProdutos();
    atualizarCarrinho();
});

// filtragem dos produtos atraves da listagem deles
if (filtroEl) {
    filtroEl.addEventListener("change", function() {
        listarProdutos();
    });
}

// botão limpar 
if (btnLimpar) {
    btnLimpar.addEventListener("click", function() {
        limparCarrinho();
    });
}