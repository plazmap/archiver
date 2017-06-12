create table `user` (
	id integer primary key,
	name varchar(30) not null,
	password varchar(100) not null,
	created_at datetime not null
);

