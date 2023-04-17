CREATE TABLE student (
  id INT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(255),
  course TEXT,
  photo VARCHAR(255),
  phone_number INT,
  created_at DATETIME,
  updated_at DATETIME
);

CREATE TABLE user (
  id INT PRIMARY KEY AUTO_INCREMENT,
  email VARCHAR(255),
  username VARCHAR(255),
  password VARCHAR(255),
  created_at DATETIME,
  updated_at DATETIME
);
