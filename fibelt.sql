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
  loan_id INT NOT NULL,
  item_name VARCHAR(255),
  image_path VARCHAR(255),
  start_date DATETIME,
  end_date DATETIME,
  status ENUM('open','closed') DEFAULT 'open',
  FOREIGN KEY (loan_id) REFERENCES loans(id)
);

CREATE TABLE bids (
  id INT AUTO_INCREMENT PRIMARY KEY,
  auction_id INT NOT NULL,
  client_id INT NOT NULL,
  amount DECIMAL(15,2),
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (auction_id) REFERENCES auctions(id),
  FOREIGN KEY (client_id) REFERENCES users(id)
);

CREATE TABLE contracts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  loan_id INT NOT NULL,
  path VARCHAR(255) NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  approved_at DATETIME NULL,
  FOREIGN KEY (loan_id) REFERENCES loans(id)
);

CREATE VIEW loan_summary AS
SELECT
  COUNT(*) AS total_loans,
  SUM(CASE WHEN status='approved' THEN 1 ELSE 0 END) AS approved_loans,
  SUM(CASE WHEN status='pending'  THEN 1 ELSE 0 END) AS pending_loans,
  SUM(CASE WHEN status='rejected' THEN 1 ELSE 0 END) AS rejected_loans,
  SUM(CASE WHEN status='liquidated' THEN 1 ELSE 0 END) AS liquidated_loans,
  SUM(amount * interest_rate) AS total_interest_expected
FROM loans;
