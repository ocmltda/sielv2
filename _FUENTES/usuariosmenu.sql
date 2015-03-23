drop table if exists users;

drop table if exists percat;

drop table if exists perfil;

drop table if exists menu;

drop table if exists empresa;


/*==============================================================*/
/* Table: empresa                                               */
/*==============================================================*/
create table empresa
(
   emp_id               int not null auto_increment,
   emp_nombre           varchar(64) not null,
   primary key (emp_id)
);

/*==============================================================*/
/* Table: menu                                                  */
/*==============================================================*/
create table menu
(
   men_id               int not null auto_increment,
   men_nombre           varchar(64) not null,
   primary key (men_id)
);

/*==============================================================*/
/* Table: perfil                                                */
/*==============================================================*/
create table perfil
(
   per_id               int not null auto_increment,
   per_nombre           varchar(32) not null,
   primary key (per_id)
);

/*==============================================================*/
/* Table: percat                                                */
/*==============================================================*/
create table percat
(
   pcat_id              int not null auto_increment,
   per_id               int,
   men_id               int,
   primary key (pcat_id)
);

/*==============================================================*/
/* Table: users                                                 */
/*==============================================================*/
create table users
(
   usu_id               int not null auto_increment,
   emp_id               int,
   per_id               int,
   usu_nombre           varchar(64) not null,
   usu_login            varchar(16) not null,
   usu_pass             varchar(16) not null,
   usu_vigente          bool not null,
   primary key (usu_id)
);

alter table percat add constraint FK_men_pcat foreign key (men_id)
      references menu (men_id) on delete restrict on update restrict;

alter table percat add constraint FK_per_pcat foreign key (per_id)
      references perfil (per_id) on delete restrict on update restrict;

alter table users add constraint FK_emp_usu foreign key (emp_id)
      references empresa (emp_id) on delete restrict on update restrict;

alter table users add constraint FK_per_usu foreign key (per_id)
      references perfil (per_id) on delete restrict on update restrict;
