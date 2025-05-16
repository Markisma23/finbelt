Database Schema (MySQL):
-- Users & Roles
drop table if exists roles;
create table roles (
  id int auto_increment primary key,
  name varchar(50) unique
);

create table users (
  id int auto_increment primary key,
  username varchar(100) unique not null,
  email varchar(150) unique not null,
  password varchar(255) not null,
  role_id int not null,
  created_at datetime default current_timestamp,
  updated_at datetime default current_timestamp on update current_timestamp,
  foreign key(role_id) references roles(id)
);

-- Clients & Admins share users table; super-admin, admin, client roles.

-- Loans
drop table if exists loans;
create table loans (
  id int auto_increment primary key,
  user_id int not null,
  principal decimal(12,2) not null,
  interest_type enum('monthly','weekly') not null,
  rate decimal(5,2) not null,
  term int not null, -- number of weeks or months
  status enum('pending','approved','rejected','active','defaulted','closed') default 'pending',
  collateral_path varchar(255) not null,
  contract_path varchar(255) null,
  created_at datetime default current_timestamp,
  updated_at datetime default current_timestamp on update current_timestamp,
  foreign key(user_id) references users(id)
);

-- Auctions
drop table if exists auctions;
create table auctions (
  id int auto_increment primary key,
  loan_id int not null,
  start_time datetime,
  end_time datetime,
  status enum('open','closed') default 'open',
  created_at datetime default current_timestamp,
  updated_at datetime default current_timestamp on update current_timestamp,
  foreign key(loan_id) references loans(id)
);

-- Bids
drop table if exists bids;
create table bids (
  id int auto_increment primary key,
  auction_id int not null,
  user_id int not null,
  amount decimal(12,2) not null,
  bid_time datetime default current_timestamp,
  foreign key(auction_id) references auctions(id),
  foreign key(user_id) references users(id)
);

-- Notifications
drop table if exists notifications;
create table notifications (
  id int auto_increment primary key,
  user_id int not null,
  type varchar(50),
  payload text,
  sent_at datetime null,
  scheduled_at datetime,
  created_at datetime default current_timestamp,
  foreign key(user_id) references users(id)
);

-- Contracts
drop table if exists contracts;
create table contracts (
  id int auto_increment primary key,
  loan_id int not null,
  file_path varchar(255) not null,
  accepted boolean default false,
  accepted_at datetime null,
  created_at datetime default current_timestamp,
  foreign key(loan_id) references loans(id)
);

-- Languages (translation keys)
drop table if exists translations;
create table translations (
  id int auto_increment primary key,
  lang_code varchar(5) not null,
  `key` varchar(100) not null,
  `value` text,
  unique(lang_code, `key`)
);
