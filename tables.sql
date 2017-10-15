#	Zemljotres SQL database tables
#	Author: Veselin Grcić

create table posts (
	id bigint auto_increment,
	heading varchar(100) not null,
	content text not null,
	sequence bigint
	primary key(id);
);

create table members (
	id int auto_increment,
	first varchar(20) not null,
	last varchar(20) not null,
	instrument varchar(50),
	active boolean default true,
	photo varchar(100),
	bio text,
	primary key(id)
);

create table albums(
	id int auto_increment,
	name varchar(50) not null,
	year int,
	photo varchar(50),
	description varchar(100),
	info text,
	primary key(id)
);

create table tracks (
	id int auto_increment,
	name varchar(50) not null,
	audio varchar(50),
	sequence int,
	album_id int,
	lyrics text,
	video varchar(50),
	primary key(id),
	foreign key(album_id) references albums(id)
);

create table galleries (
	id int auto_increment,
	name varchar(100) not null,
	primary key(id)
);

create table images (
	id int auto_increment,
	gallery_id int,
	primary key (id),
	foreign key (gallery_id) references galleries (id),
);