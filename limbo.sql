# limbo_db Lost and Found database system commands
# Authors: Kulvinder Lotay, Artur Barbosa
# v1.0

# check if the limbo_db exists, if not, create the database
create database if not exists limbo_db;
use limbo_db;

# check for users table, if it does not exist, create the table
create table if not exists users(
	id int unsigned auto_increment primary key,
	user_id varchar(20) not null unique,
	first_name varchar(40) not null,
	last_name varchar(40),
	email varchar(60) unique,
	pass varchar(250) not null,
	reg_date datetime not null
);

# populate users table with user admin, password gaze11e
insert into users(user_id, first_name, pass)
values('admin', 'Administrator', 'gaze11e');

# create stuff table
create table if not exists stuff(
	id int auto_increment primary key,
	location_id int not null,
	description varchar(250) not null,
	create_date datetime not null,
	update_date datetime not null,
	room varchar(15),
	owner varchar(30),
	finder varchar(30),
	status set('found','lost','claimed'),
	image_url varchar(500)
);

insert into stuff(location_id, description, create_date, update_date, status, image_url)
values (1, 'Black iPhone 6S with red cover', now(), now(), 'lost', 'http://ecx.images-amazon.com/images/I/61Ux1qTqwGL._SX425_.jpg'),
	(2, 'TI-84 Plus Calculator - black', now(), now(), 'lost', 'http://ll-us-i5.wal.co/dfw/dce07b8c-3034/k2-_888c1ea8-b6ad-449b-8e08-78aea6f0b613.v1.jpg-faf19e87ddcb4d4b27aba82782fd401905627416-optim-450x450.jpg'),
	(3, 'Blue Umbrella', now(), now(), 'lost', 'http://www.umbrellaman.com/images/005open.jpg'),
	(4, 'Macbook Air', now(), now(), 'found', 'http://cdn.cultofmac.com/wp-content/uploads/2014/01/MacBook-Air-vs-MacBook-pro-Retina-2013-sunlight-640x436.jpg'),
	(5, 'Brown/Beige Timberland Wallet with $94', now(), now(), 'found', 'http://www.selltimberlandshoes.com/PicImages/Timberland%20Men\'s%20Earthkeepers%20Wallets/Timberland%20Men\'s%20Pull-Up%20Leather%20Elastic%20Flip%20Wallet/Pull-Up%20Leather%20Elastic%20Flip%20Wallet%20(2).jpg'),
	(12, 'Textbook - PHP and MySQL in Easy Steps', now(), now(), 'found', 'http://ecx.images-amazon.com/images/I/41G1olU0H1L._SX404_BO1,204,203,200_.jpg');

# create locations table
create table locations(
	id int auto_increment primary key,
	create_date datetime not null,
	update_date datetime not null,
	name varchar(50) not null
);

# populate locations table
insert into locations(create_date, update_date, name)
values(now(), now(), "Byrne House"),
	  (now(), now(), "James A. Cannavino Library"),
	  (now(), now(), "Champagnat Hall"),
	  (now(), now(), "Cornell Boathouse"),
	  (now(), now(), "Donnelly Hall"),
	  (now(), now(), "Dyson Center"),
	  (now(), now(), "Fontaine Hall"),
	  (now(), now(), "Foy Townhouses"),
	  (now(), now(), "Fulton Street Townhouses"),
	  (now(), now(), "Lower Fulton Townhouses"),
	  (now(), now(), "Gartland Apartments"),
	  (now(), now(), "Greystone Hall"),
	  (now(), now(), "Hancock Center"),
	  (now(), now(), "Kirk House"),
	  (now(), now(), "Leo Hall"),
	  (now(), now(), "Longview Park"),
	  (now(), now(), "Lowell Thomas Communications Center"),
	  (now(), now(), "Marian Hall"),
	  (now(), now(), "Marist Boathouse"),
	  (now(), now(), "James McCann Recreational Center"),
	  (now(), now(), "Mid-rise Hall"),
	  (now(), now(), "Sheahan Hall"),
	  (now(), now(), "Steel Plant Art Studios and Gallery"),
	  (now(), now(), "Student Center"),
	  (now(), now(), "Rotunda"),
	  (now(), now(), "Lower Townhouses"),
	  (now(), now(), "Lower West Cedar Townhouses"),
	  (now(), now(), "Upper West Cedar Townhouses");
