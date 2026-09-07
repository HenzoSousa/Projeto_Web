create database if not exists bd_mundo character set utf8mb4 collate utf8mb4_unicode_ci;
use bd_mundo;

create table if not exists continentes (
    id int auto_increment primary key,
    nome varchar(100) not null,
    populacao bigint not null,
    area decimal(15,2) not null,
    total_paises int default 0
) engine=innodb;

create table if not exists paises (
    id int auto_increment primary key,
    nome varchar(100) not null,
    continente_id int not null,
    populacao bigint not null,
    area decimal(15,2) not null,
    idioma varchar(100) not null,
    clima varchar(100) not null,
    regime_politico varchar(100) not null,
    moeda varchar(100) not null,
    foreign key (continente_id) references continentes(id) on delete restrict
) engine=innodb;

create table if not exists cidades (
    id int auto_increment primary key,
    nome varchar(100) not null,
    pais_id int not null,
    populacao bigint not null,
    area decimal(15,2) not null,
    clima varchar(100) not null,
    data_fundacao date not null,
    foreign key (pais_id) references paises(id) on delete restrict
) engine=innodb;

create table if not exists governantes (
    id int auto_increment primary key,
    nome varchar(150) not null,
    partido_politico varchar(100) not null,
    data_nascimento date not null,
    idade int not null,
    data_inicio_mandato date not null,
    data_final_mandato date not null,
    pais_id int default null,
    cidade_id int default null,
    foreign key (pais_id) references paises(id) on delete set null,
    foreign key (cidade_id) references cidades(id) on delete set null
) engine=innodb;

create table if not exists usuarios (
    id int auto_increment primary key,
    nome varchar(100) not null,
    login varchar(50) unique not null,
    senha varchar(255) not null,
    primeiro_acesso tinyint(1) default 1,
    tentativas_falhas int default 0,
    bloqueado tinyint(1) default 0,
    data_cadastro datetime default current_timestamp,
    ultimo_acesso datetime null
) engine=innodb;

create table if not exists logs (
    id int auto_increment primary key,
    usuario_id int null,
    acao varchar(50) not null,
    ip varchar(45) not null,
    data_hora datetime default current_timestamp,
    detalhes text,
    foreign key (usuario_id) references usuarios(id) on delete set null
) engine=innodb;

insert into continentes (nome, populacao, area, total_paises) values 
('américa do sul', 430000000, 17840000.00, 1),
('europa', 746000000, 10180000.00, 1)
on duplicate key update id=id;

insert into paises (nome, continente_id, populacao, area, idioma, clima, regime_politico, moeda) values 
('brasil', 1, 214000000, 8515767.00, 'português', 'tropical', 'república presidencialista', 'real'),
('frança', 2, 67000000, 643801.00, 'francês', 'temperado', 'república semipresidencialista', 'euro')
on duplicate key update id=id;

insert into cidades (nome, pais_id, populacao, area, clima, data_fundacao) values 
('são josé dos campos', 1, 730000, 1099.00, 'subtropical', '1767-07-27'),
('paris', 2, 2160000, 105.00, 'temperado', '0250-01-01')
on duplicate key update id=id;

insert into usuarios (nome, login, senha, primeiro_acesso) 
values ('administrador', 'admin', '$2y$10$8pQ7z9vX5kL2mN7pQ9xY5eZ7vX8nM9pQ2wE4rT6yU8iO0pL2kM4n', 1)
on duplicate key update login = login;