drop table if exists empusu;

drop table if exists menu;

drop table if exists percat;

/*==============================================================*/
/* Table: empusu                                                */
/*==============================================================*/
create table empusu
(
   emu_id               int not null auto_increment,
   usuarios_id          smallint not null,
   clientes_id          smallint not null,
   primary key (emu_id)
);

/*==============================================================*/
/* Table: menu                                                  */
/*==============================================================*/
create table menu
(
   men_id               int not null auto_increment,
   men_nombre           varchar(64) not null,
   men_orden            smallint not null,
   primary key (men_id)
);

/*==============================================================*/
/* Table: percat                                                */
/*==============================================================*/
create table percat
(
   pcat_id              int not null auto_increment,
   men_id               int,
   tipos_usuarios_id    smallint not null,
   primary key (pcat_id)
);

alter table percat add constraint FK_men_pcat foreign key (men_id)
      references menu (men_id) on delete restrict on update restrict;
