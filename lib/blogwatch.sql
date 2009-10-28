CREATE TABLE elggblogwatch (
  id int(11) NOT NULL auto_increment,
  blog_guid char(10) NOT NULL,
  blog_url text NOT NULL,
  username char(50) NOT NULL,
  updated int(11),
  PRIMARY KEY (id)
);