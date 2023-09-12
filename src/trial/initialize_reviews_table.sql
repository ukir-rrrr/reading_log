CREATE TABLE reviews (
    id INTEGER AUTO_INCREMENT NOT NULL PRIMARY KEY,
    title VARCHAR(255),
    author VARCHAR(100),
    status VARCHAR(10),
    score INTEGER,
    summary VARCHAR(1000),
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) DEFAULT CHARACTER SET=utf8mb4;


//データ追加

INSERT INTO reviews (
    title,
    author,
    status,
    score,
    summary
) VALUES (
    'Momo',
    'Michael Ende',
    'complete',
    5,
    'I love this book since I was little.'
);
