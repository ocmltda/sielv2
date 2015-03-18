drop table if exists alertas;

drop table if exists informes;

drop table if exists tipos_acciones;

/*==============================================================*/
/* Table: alertas                                               */
/*==============================================================*/
create table alertas
(
   id                   int not null auto_increment,
   fecha                date not null,
   hora                 time not null,
   locales_id           int not null,
   coordenadas          varchar(45) not null,
   incidencia           varchar(64) not null,
   comentarios          text,
   tiposacciones_id     smallint,
   fotografia           varchar(128) not null,
   primary key (id)
);

/*==============================================================*/
/* Table: informes                                              */
/*==============================================================*/
create table informes
(
   informes_id          int not null auto_increment,
   nombre               varchar(64) not null,
   periodo              date not null,
   fecha_publicacion    date not null,
   estado               smallint not null,
   primary key (informes_id)
);

/*==============================================================*/
/* Table: tipos_acciones                                        */
/*==============================================================*/
create table tipos_acciones
(
   tipos_acciones_id    int not null auto_increment,
   accion               varchar(32) not null,
   primary key (tipos_acciones_id)
);
