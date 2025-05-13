CREATE TABLE users(id INT AUTO_INCREMENT PRIMARY KEY,
email VARCHAR(255) UNIQUE,
password VARCHAR(255),
role ENUM('super_admin','admin','client'),
created_at DATETIME DEFAULT CURRENT_TIMESTAMP);

CREATE TABLE clients(user_id INT PRIMARY KEY,
name VARCHAR(255),
nrc VARCHAR(100),
country VARCHAR(100),
FOREIGN KEY(user_id) REFERENCES users(id));

CREATE TABLE admins (
  user_id INT PRIMARY KEY,
  permissions TEXT,
  FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE loans (
  id INT AUTO_INCREMENT PRIMARY KEY,
  client_id INT,
  amount DECIMAL(15,2),
  term_type ENUM('monthly','weekly'),
  interest_rate DECIMAL(5,2),
  status ENUM('pending','approved','rejected'),
  due_date DATE,
  collateral_path VARCHAR(255),
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (client_id) REFERENCES users(id)
);

CREATE TABLE auctions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  loan_id INT,
  start_date DATETIME,
  end_date DATETIME,
  status ENUM('open','closed'),
  FOREIGN KEY (loan_id) REFERENCES loans(id)
);

CREATE TABLE bids (
  id INT AUTO_INCREMENT PRIMARY KEY,
  auction_id INT,
  client_id INT,
  amount DECIMAL(15,2),
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (auction_id) REFERENCES auctions(id),
  FOREIGN KEY (client_id) REFERENCES users(id)
);