create database moviePass;
use moviePass;
#drop database moviePass;

create table direcciones(
	idDireccion int not null auto_increment,
    provincia varchar(60),
    ciudad varchar(40),
    calle varchar (40),
    altura int,
    constraint pkIdDireccion primary key (idDireccion)
);
create table usuarios(
	idUsuario int not null auto_increment,
    nombre varchar (30),
    apellido varchar (30),
    dni int,
    email varchar (45) unique,
    contrasenia varchar (40),
    rol varchar(8),
    baja boolean,
    constraint pkIdUsuario primary key (idUsuario)
);
create table tarjetasCredito(
	idTarjetaCredito int not null auto_increment,
    nombreCompania varchar(20),
    nroTarjeta bigint unique,
    codigoSeguridad int,
    constraint pkIdTarjetaCredito primary key (idTarjetaCredito)
);
create table cines( 
	idCine int not null auto_increment,
	idDireccion int,
    nombre varchar(40),
    capacidadTotal int,
    precioEntrada int,
    baja boolean,
	constraint pkIdCine primary key (idCine),
    constraint fkIdDireccion foreign key (idDireccion) references direcciones(idDireccion)
);
create table salas(
	idSala int not null auto_increment,
    idCine int,
    nombre varchar(40),
    precio int,
	capacidadButacas int,
    baja boolean,
    constraint pkIdSala primary key (idSala),
    constraint fkIdCine foreign key (idCine) references cines(idCine)
);
create table proyecciones(
	idProyeccion int not null auto_increment,
    idSala int,
	idPelicula int,
	asientosDisponibles int,
    asientosOcupados int,
	fecha date,
    horario varchar (6),
    baja boolean,
    constraint pkIdProyeccion primary key (idProyeccion),
    constraint fk_IdSala foreign key (idSala) references salas(idSala)
);
create table compras(
	idCompra int not null auto_increment,
    idTarjetaCredito int,
    idCliente int,
    cantEntradas int,
    descuento float,
    fecha date,
    total int,
    baja boolean,
    constraint pkIdCompra primary key (idCompra),
    constraint fkIdTarjetaCredito foreign key (idTarjetaCredito) references tarjetasCredito (idTarjetaCredito),
    constraint fkIdCliente foreign key (idCliente) references clientes (idCliente)
);
create table entradas(
	idEntrada int not null auto_increment,
    idProyeccion int,
    idCliente int,
    idCompra int,
    codigoQr varchar(400),
    baja boolean,
    constraint pkIdEntrada primary key (idEntrada),
    constraint fkIdProyeccion foreign key (idProyeccion) references proyecciones(idProyeccion),
	constraint fkIdCompra foreign key (idCompra) references compras (idCompra),
	constraint fkIdCliente foreign key (idCliente) references clientes (idCliente)
);
create table pagoTC(
    idPagoTC int not null auto_increment,
    idCompra int,
    codigoAut int,
    fecha date,
    total float,
    baja boolean,
    constraint pkIdPago primary key (idPagoTC),
    constraint fkIdCompra foreign key (idCompra) references compras (idCompra)
);

insert into usuarios (nombre, apellido, dni, email, contrasenia, rol, baja) values ('Federico', 'Alesandro', 42454677, 'fede.alesandro@gmail.com', 'fedecapo', 'admin', 0);
insert into usuarios (nombre, apellido, dni, email, contrasenia, rol, baja) values ('Fabio', 'Laguna', 38057871, 'fabiolaguna.94@gmail.com', 'fabiocapo', 'cliente', 0);
insert into tarjetasCredito (nombreCompania, nroTarjeta, codigoSeguridad) values ('Visa', '1111111111111111', '111'), ('MasterCard', '2222222222222222', '222');

/*
select *
from usuarios;

select *
from entradas;

select *
from cines as c
join direcciones as d
on c.idDireccion = d.idDireccion;

select *
from proyecciones;
#update cines set baja=0 where idCine=2;
SELECT idTarjetaCredito FROM tarjetasCredito where nroTarjeta = 1111111111111111;

select e.idProyeccion, c.cantEntradas, c.total 
from entradas e 
join compras c
on e.idCompra=c.idCompra
order by idProyeccion;
select * 
from pagoTC
order by idPagoTC;
select *
from compras
order by idCompra;*/

#update entradas set codigoQR='<img src="https://chart.googleapis.com/chart?chs=70x70&cht=qr&chl=Puede ingresar al cine" title="Acceso al cine"/>' where idEntrada=1
