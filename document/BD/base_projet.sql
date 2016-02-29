/*==============================================================*/
/* Nom de SGBD :  MySQL 5.0                                     */
/* Date de création :  27/05/2014 15:04:08                      */
/*==============================================================*/


drop table if exists avatar;

drop table if exists debloque;

drop table if exists jeux;

drop table if exists membre;

drop table if exists possede;

/*==============================================================*/
/* Table : avatar                                               */
/*==============================================================*/
create table avatar
(
   idAva                int not null,
   nomAva               longtext,
   jpegAva              longblob,
   prixAva              int,
   primary key (idAva)
);

/*==============================================================*/
/* Table : debloque                                             */
/*==============================================================*/
create table debloque
(
   idMem                int not null,
   idJeu                int not null,
   scoreMax             int,
   primary key (idMem, idJeu)
);

/*==============================================================*/
/* Table : jeux                                                 */
/*==============================================================*/
create table jeux
(
   idJeu                int not null,
   nomJeu               longtext,
   prixJeu              int,
   jpegJeu              longblob,
   primary key (idJeu)
);

/*==============================================================*/
/* Table : membre                                               */
/*==============================================================*/
create table membre
(
   idMem                int not null,
   idAva                int not null,
   pseudoMem            longtext,
   motDePasseMem        longtext,
   mailMem              longtext,
   pointsMem            int,
   gradeMem             int,
   primary key (idMem)
);

/*==============================================================*/
/* Table : possede                                              */
/*==============================================================*/
create table possede
(
   idMem                int not null,
   idAva                int not null,
   primary key (idMem, idAva)
);

alter table debloque add constraint FK_debloque foreign key (idMem)
      references membre (idMem) on delete restrict on update restrict;

alter table debloque add constraint FK_debloque2 foreign key (idJeu)
      references jeux (idJeu) on delete restrict on update restrict;

alter table membre add constraint FK_choisis foreign key (idAva)
      references avatar (idAva) on delete restrict on update restrict;

alter table possede add constraint FK_possede foreign key (idMem)
      references membre (idMem) on delete restrict on update restrict;

alter table possede add constraint FK_possede2 foreign key (idAva)
      references avatar (idAva) on delete restrict on update restrict;

