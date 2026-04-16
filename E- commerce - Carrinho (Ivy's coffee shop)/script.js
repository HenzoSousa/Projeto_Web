

const produtos = [
    { id: 1, nome: "Café Expresso",    apelido: "Expresso",      categoria: "Bebidas", preco: 8.00,  cor: "#4a2c2a", imagem: "imagens/cafe-expresso.png" },
    { id: 2, nome: "Cappuccino",        apelido: "Cappuccino",    categoria: "Bebidas", preco: 12.50, cor: "#7b4f3a", imagem: "imagens/cappuccino.png" },
    { id: 3, nome: "Pão de Queijo",     apelido: "Pão de Queijo", categoria: "Comidas", preco: 6.50,  cor: "#b07d3a", imagem: "imagens/pao-de-queijo.png" },
    { id: 4, nome: "Croissant",         apelido: "Croissant",     categoria: "Comidas", preco: 14.00, cor: "#c8893a", imagem: "imagens/croissant.png" },
    { id: 5, nome: "Bolo de Chocolate", apelido: "Bolo",          categoria: "Comidas", preco: 15.00, cor: "#3b1f1a", imagem: "imagens/bolo-chocolate.png" },
    { id: 6, nome: "Torta de Limão",    apelido: "Torta",         categoria: "Comidas", preco: 18.00, cor: "#5a7a3a", imagem: "imagens/torta-limao.png" }
];

let carrinho = [];


const listaProdutosEl = document.getElementById("lista-produtos"); 
const listaCarrinhoEl = document.getElementById("lista-carrinho"); 
const btnFinalizar    = document.getElementById("btn-limpar");     
const filtroEl        = document.getElementById("filtro");         


function carregarDados() {

    // Tenta buscar o carrinho salvo. Se não existir, retorna null.
    const carrinhoSalvo = localStorage.getItem("carrinhoCaixa");

    if (carrinhoSalvo) {
        // JSON.parse transforma o texto salvo de volta em array JavaScript
        carrinho = JSON.parse(carrinhoSalvo);
    } else {
       
        carrinho = [];
    }
}


function salvarDados() {
    // JSON.stringify transforma o array em texto para poder salvar
    localStorage.setItem("carrinhoCaixa", JSON.stringify(carrinho));
}


function filtrarProdutos(valorFiltro) {

    let filtrados = [];

    switch (valorFiltro) {

        case "bebidas":
            // filter percorre o array e guarda os que passam na condição
            filtrados = produtos.filter(function(p) {
                return p.categoria === "Bebidas";
            });
            break;

        case "comidas":
            filtrados = produtos.filter(function(p) {
                return p.categoria === "Comidas";
            });
            break;

        case "todos":
            // slice() sem argumentos copia o array inteiro
            filtrados = produtos.slice();
            break;
    }

    return filtrados;
}


function listarProdutos() {

    // Limpa os cards que estavam na tela
    listaProdutosEl.innerHTML = "";

    // Pega os produtos filtrados conforme o select
    let produtosParaMostrar = filtrarProdutos(filtroEl.value);

    // forEach passa por cada produto da lista
    produtosParaMostrar.forEach(function(produto) {

        // Cria uma <div> nova para ser o card
        const card = document.createElement("div");
        card.classList.add("card"); // Adiciona a classe CSS "card"

        // innerHTML monta o visual do card com os dados do produto
        
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

        // Coloca o card dentro da grade na página
        listaProdutosEl.appendChild(card);
    });
}


function adicionarAoCarrinho(idProduto) {

    // Procura no array produtos o objeto com o id igual ao clicado
    const produto = produtos.find(function(p) {
        return p.id === idProduto;
    });

    // Variável de controle: começa como false (não encontrado)
    let encontrado = false;

    // Percorre o carrinho procurando se o produto já está lá
    for (let i = 0; i < carrinho.length; i++) {

        if (carrinho[i].id === produto.id) {
            carrinho[i].quantidade = carrinho[i].quantidade + 1; // Aumenta 1 na quantidade
            encontrado = true; // Marca que encontrou
            break; // Para o loop
        }
    }

    // Se não encontrou no carrinho, adiciona como item novo
    if (encontrado === false) {
        carrinho.push({
            id: produto.id,
            nome: produto.nome,
            imagem: produto.imagem, // Guarda também o emoji para mostrar no carrinho
            preco: produto.preco,
            quantidade: 1
        });
    }

    salvarDados();
    atualizarCarrinho();
}


function removerDoCarrinho(idProduto) {

    // findIndex retorna a posição do item no array (ex: posição 0, 1, 2...)
    const posicao = carrinho.findIndex(function(p) {
        return p.id === idProduto;
    });

    // Se encontrou (posicao diferente de -1)...
    if (posicao !== -1) {

        if (carrinho[posicao].quantidade > 1) {
            // Diminui 1 na quantidade
            carrinho[posicao].quantidade = carrinho[posicao].quantidade - 1;
        } else {
            // Remove o item completamente do array
            // splice(posicao, 1) remove 1 elemento na posição indicada
            carrinho.splice(posicao, 1);
        }
    }

    salvarDados();
    atualizarCarrinho();
}

function atualizarCarrinho() {

    // Limpa o que estava na lista do carrinho
    listaCarrinhoEl.innerHTML = "";

    // Se o carrinho estiver vazio...
    if (carrinho.length === 0) {
        listaCarrinhoEl.innerHTML = "<p class='carrinho-vazio'>Seu carrinho está vazio.</p>";
        atualizarTotal(); // Zera o total também
        return; // Para a função aqui
    }

    // Percorre cada item do carrinho e cria o elemento visual
    for (let i = 0; i < carrinho.length; i++) {

        const item = carrinho[i];

        // Calcula preço total deste item: preço x quantidade
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

    // Soma quantidade x preço de cada item
    for (let i = 0; i < carrinho.length; i++) {
        total = total + (carrinho[i].preco * carrinho[i].quantidade);
    }

    const totalValorEl = document.getElementById("total-valor");

    // Atualiza o texto do total na tela
    totalValorEl.textContent = "R$ " + total.toFixed(2);
}


function limparCarrinho() {

    const confirmou = confirm("Deseja finalizar e limpar o pedido?");

    if (confirmou === true) {
        carrinho = [];
        salvarDados();
        atualizarCarrinho();
    }
}


// Escutam ações do usuário e chamam as funções certas


// Quando a página terminar de carregar, executa as 3 funções abaixo
document.addEventListener("DOMContentLoaded", function() {
    carregarDados();     // Recupera carrinho salvo
    listarProdutos();    // Desenha os cards
    atualizarCarrinho(); // Desenha o carrinho
});

// Quando o usuário mudar o                                                                                                                                                                                                                                                           