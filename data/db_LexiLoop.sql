CREATE DATABASE IF NOT EXISTS db_LexiLoop;

use db_LexiLoop;
-- DROP TABLE IF EXISTS Topics;
DROP TABLE IF EXISTS Users;

-- Bảng 5.1. Bảng Users
create table Users 
(
	userID int primary key auto_increment,
	username varchar(50) unique not null,
	email varchar(50) unique not null,
	password_hash varchar(50) not null,
	full_name varchar(100) null,
	avatar_url varchar(250) null,
	role enum('user','admin') default 'user',
	status enum('active','locked') default 'active',
	created_at datetime DEFAULT  CURRENT_TIMESTAMP(),
	update_at datetime DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP ,
	daily_reminder_enabled tinyint default 1,
	reminder_time time default "20:00:00",
	daily_target_words int default 20
);

-- Bảng 5.2. Topics
create table Topics 
(
	topicID int primary key auto_increment,
	topicName varchar(100) not null,
	topicDescription text null,
	created_by int null,
	topicCreated_at DATETIME default now(),
	foreign key users(created_by) references Users(userID) ON DELETE SET NULL
);

-- Bảng 5.3.Vocabulary
CREATE TABLE vocabulary (
    id              INT             PRIMARY KEY AUTO_INCREMENT,
    topic_id        INT             NOT NULL,
    word            VARCHAR(100)    NOT NULL,
    pronunciation    VARCHAR(100)    NULL,
    part_of_speech  VARCHAR(30)     NULL,
    meaning         TEXT            NOT NULL,
    example_sentence TEXT           NULL,
    created_by      INT             NULL,
    created_at      DATETIME        DEFAULT NOW(),
    audio_url       VARCHAR(255)    NULL,
    FOREIGN KEY (topic_id)   REFERENCES Topics(topicID) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES Users(userID)   ON DELETE SET NULL
);

-- 5.4. Bảng vocabulary_images
CREATE TABLE vocabulary_images (
    id              INT             PRIMARY KEY AUTO_INCREMENT,
    vocabulary_id   INT             NOT NULL,
    image_url       VARCHAR(255)    NOT NULL,
    uploaded_by     INT             NULL,
    uploaded_at     DATETIME        DEFAULT NOW(),
    FOREIGN KEY (vocabulary_id) REFERENCES vocabulary(id) ON DELETE CASCADE,
    FOREIGN KEY (uploaded_by)   REFERENCES Users(userID)  ON DELETE SET NULL
);

-- 5.5. Bảng user_vocab_progress
CREATE TABLE user_vocab_progress (
    id                  INT             PRIMARY KEY AUTO_INCREMENT,
    user_id             INT             NOT NULL,
    vocabulary_id       INT             NOT NULL,
    status              ENUM('new','learning','mastered') DEFAULT 'new',
    ease_factor         FLOAT           DEFAULT 2.5,
    interval_days       INT             DEFAULT 0,
    repetitions         INT             DEFAULT 0,
    next_review_date    DATE            NULL,
    last_reviewed_at    DATETIME        NULL,
    last_quality_rating TINYINT         NULL CHECK (last_quality_rating BETWEEN 0 AND 5),
    UNIQUE (user_id, vocabulary_id),
    FOREIGN KEY (user_id)       REFERENCES Users(userID)      ON DELETE CASCADE,
    FOREIGN KEY (vocabulary_id) REFERENCES vocabulary(id)     ON DELETE CASCADE
);

-- 5.6. Bảng review_logs
CREATE TABLE review_logs (
    id                  INT         PRIMARY KEY AUTO_INCREMENT,
    progress_id         INT         NOT NULL,
    review_date         DATE        NOT NULL,
    quality_rating      TINYINT     NOT NULL CHECK (quality_rating BETWEEN 0 AND 5),
    response_time_ms    INT         NULL,
    FOREIGN KEY (progress_id) REFERENCES user_vocab_progress(id) ON DELETE CASCADE
);

-- 5.7. Bảng favorites
CREATE TABLE favorites (
    id              INT         PRIMARY KEY AUTO_INCREMENT,
    user_id         INT         NOT NULL,
    vocabulary_id   INT         NOT NULL,
    created_at      DATETIME    DEFAULT NOW(),
    UNIQUE (user_id, vocabulary_id),
    FOREIGN KEY (user_id)       REFERENCES Users(userID)  ON DELETE CASCADE,
    FOREIGN KEY (vocabulary_id) REFERENCES vocabulary(id) ON DELETE CASCADE
);

-- 5.8. Bảng learning_sessions
CREATE TABLE learning_sessions (
    id                  INT         PRIMARY KEY AUTO_INCREMENT,
    user_id             INT         NOT NULL,
    session_date        DATE        NOT NULL,
    words_studied       INT         DEFAULT 0,
    duration_seconds    INT         DEFAULT 0,
    FOREIGN KEY (user_id) REFERENCES Users(userID) ON DELETE CASCADE
);

-- 5.9. Bảng user_login_sessions
CREATE TABLE user_login_sessions (
    id              INT             PRIMARY KEY AUTO_INCREMENT,
    user_id         INT             NOT NULL,
    session_token   VARCHAR(255)    NOT NULL UNIQUE,
    ip_address      VARCHAR(45)     NULL,
    user_agent      VARCHAR(255)    NULL,
    created_at      DATETIME        DEFAULT NOW(),
    expires_at      DATETIME        NOT NULL,
    FOREIGN KEY (user_id) REFERENCES Users(userID) ON DELETE CASCADE
);

-- 5.10. Bảng password_resets
CREATE TABLE password_resets (
    id          INT             PRIMARY KEY AUTO_INCREMENT,
    email       VARCHAR(100)    NOT NULL,
    otp_code    VARCHAR(6)      NOT NULL,
    expires_at  DATETIME        NOT NULL,
    created_at  DATETIME        DEFAULT NOW()
);

