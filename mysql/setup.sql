DROP DATABASE IF EXISTS RCMS;
CREATE DATABASE RCMS;

USE RCMS;

CREATE TABLE um_database
(
    id         INTEGER UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(100)     NOT NULL,
    email      VARCHAR(100)     NOT NULL,
    matrix_no  INTEGER UNSIGNED NOT NULL,
    college_id TINYINT UNSIGNED NOT NULL,
    faculty    VARCHAR(100)     NOT NULL,
    course     VARCHAR(100)     NOT NULL
);
INSERT INTO um_database (name, email, matrix_no, college_id, faculty, course)
VALUES ('Khor Philemon', 'wif180024@siswa.um.edu.my', 17175579, 7,
        'Faculty of Computer Science and Information Technology', 'Software Engineering'),
       ('See Boon Pu', 'wif180067@siswa.um.edu.my', 17076249, 8,
        'Faculty of Computer Science and Information Technology', 'Software Engineering'),
       ('Lee Jih Shian', 'wif180029@siswa.um.edu.my', 17126198, 10,
        'Faculty of Computer Science and Information Technology', 'Software Engineering'),
       ('Lim Jin Wei', 'wif180030@siswa.um.edu.my', 17179635, 2,
        'Faculty of Computer Science and Information Technology', 'Software Engineering'),
       ('Lo Zhi Kiat', 'wif180031@siswa.um.edu.my', 17145459, 3,
        'Faculty of Computer Science and Information Technology', 'Software Engineering'),
       ('Lau Xin Yi', 'wif180028@siswa.um.edu.my', 17066424, 12,
        'Faculty of Computer Science and Information Technology', 'Software Engineering');

########################################################################################################################

CREATE TABLE student
(
    id            INTEGER UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    activated     TINYINT(1)       NOT NULL DEFAULT 0,
    hash          VARCHAR(32)      NOT NULL UNIQUE,
    name          VARCHAR(100)     NOT NULL,
    email         VARCHAR(100)     NOT NULL UNIQUE,
    matrix_no     INTEGER UNSIGNED NOT NULL UNIQUE,
    college_id    TINYINT UNSIGNED NOT NULL,
    faculty       VARCHAR(100)     NOT NULL,
    course        VARCHAR(100)     NOT NULL,
    password      VARCHAR(50),
    room_no       VARCHAR(50),
    nationality   VARCHAR(50),
    phone         VARCHAR(50),
    gender        VARCHAR(50),
    date_of_birth DATE,
    address       VARCHAR(255),
    city          VARCHAR(50),
    state         VARCHAR(50),
    zip           VARCHAR(10)
);
# Test account
# email: test@siswa.um.edu.my, password: test123
INSERT INTO student (activated, hash, name, email, matrix_no, college_id, faculty, course, password)
VALUES (1, 'hash', 'Test account', 'test@siswa.um.edu.my', 123456, 7, 'faculty', 'course',
        'cc03e747a6afbbcbf8be7668acfebee5');

########################################################################################################################

CREATE TABLE activity
(
    id                    INTEGER UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name                  VARCHAR(200)  NOT NULL,
    img                   VARCHAR(2083) NOT NULL,
    venue                 VARCHAR(200)  NOT NULL,
    description           VARCHAR(5000) NOT NULL,
    activity_date         DATETIME      NOT NULL,
    registration_deadline DATETIME      NOT NULL
);

INSERT INTO activity (name, img, venue, description, activity_date, registration_deadline)
VALUES ('Seeing The Racial Water', '/assets/img/activities/activity-0004.jpg', 'Hall 3',
        'Our original half - day offering with Dr. DiAngelo is a 3.5 hour workshop that offers the bulk of her research, systemic analysis of White Supremacy, and work around Whiteness and White Fragility. Dr. DiAngelo takes participants through topics including white socialization, systemic racism and the specific ways racism manifests for white progressives.',
        CONCAT(CURRENT_DATE - INTERVAL 20 DAY, ' ', '12:30:00'),
        CONCAT(CURRENT_DATE - INTERVAL 45 DAY, ' ', '00:00:00')),
       ('Run for Beer 2020', '/assets/img/activities/activity-0000.jpg', 'Sports Field',
        'We are the Michigan Brewery Running Series and we run for beer! Join us for Global Running Month!
        Try to hit 100 miles in June and earn some awesome prizes along the way! Contact us at catherine@breweryrunningseries.com , Cheers to beers! We’ll see you Running in the Cloud!',
        CONCAT(CURRENT_DATE, ' ', '08:30:00'), CONCAT(CURRENT_DATE - INTERVAL 15 DAY, ' ', '00:00:00')),
       ('Talk Series: Future of Work', '/assets/img/activities/activity-0001.jpg', 'Exam Hall 2',
        'Together with peers from various companies and excellent speakers such as Rick Fedrizzi, Rachel Gutter and Tim Oldman, we discussed these phases in a series of events. The perfect opportunity to gain insights and be prepared for the future.',
        CONCAT(CURRENT_DATE + INTERVAL 15 DAY, ' ', '16:30:00'),
        CONCAT(CURRENT_DATE + INTERVAL 1 DAY, ' ', '00:00:00')),
       ('XXI Power Systems Computation Conference', '/assets/img/activities/activity-0002.jpg', 'Auditorium',
        'The Power Systems Computation Conference (PSCC) is one of the most outstanding events in the energy systems domain. PSCC addresses theoretical developments and computational aspects with respect to power system applications from micro-grids to mega-grids.',
        CONCAT(CURRENT_DATE + INTERVAL 30 DAY, ' ', '15:30:00'),
        CONCAT(CURRENT_DATE + INTERVAL 15 DAY, ' ', '00:00:00')),
       ('GenComm Project', '/assets/img/activities/activity-0003.jpg', 'Hall A',
        'GenComm''s European partners & Guest Speakers will consider hydrogen in this context, and as the catalyst for driving Europe''s green recovery. They will also consider the hurdles that must be overcome for Hydrogen to become a viable and accessible energy source for all.',
        CONCAT(CURRENT_DATE + INTERVAL 60 DAY, ' ', '17:00:00'),
        CONCAT(CURRENT_DATE + INTERVAL 45 DAY, ' ', '00:00:00'));

########################################################################################################################

CREATE TABLE user_activity
(
    user_id     INTEGER UNSIGNED NOT NULL,
    activity_id INTEGER UNSIGNED NOT NULL,
    created_at  TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id, activity_id),
    FOREIGN KEY (user_id) REFERENCES student (id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (activity_id) REFERENCES activity (id) ON DELETE CASCADE ON UPDATE CASCADE
);
# Register test acc with activity 1 and 2
INSERT INTO user_activity (user_id, activity_id)
VALUES (1, 1),
       (1, 2);

########################################################################################################################

CREATE TABLE food
(
    id    INTEGER UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name  VARCHAR(50)   NOT NULL,
    price DECIMAL(6, 2) NOT NULL CHECK ( price > 0 ),
    img   VARCHAR(2083) NOT NULL
);
INSERT INTO food(name, price, img)
VALUES ('Fried Rice', 5.50, '/assets/img/food/nasigoreng.png'),
       ('Satay', 5.00, '/assets/img/food/satay.png'),
       ('Spaghetti', 4.50, '/assets/img/food/spaghetti.png'),
       ('Tomyam ', 6.50, '/assets/img/food/tomyam.png'),
       ('Chicken Rice ', 4.50, '/assets/img/food/nasiayam.png'),
       ('Iced Milo ', 1.50, '/assets/img/food/milo.png'),
       ('Syrup', 1.20, '/assets/img/food/sirap.png'),
       ('Mango Juice', 2.50, '/assets/img/food/mango.png'),
       ('Teh', 2.00, '/assets/img/food/Teh.png'),
       ('Fried Noodles ', 4.00, '/assets/img/food/friednoodles.png'),
       ('Cake ', 2.50, '/assets/img/food/cake.png'),
       ('Pizza Bread ', 3.50, '/assets/img/food/pizzabread.png');

########################################################################################################################

CREATE TABLE food_date
(
    food_id        INTEGER UNSIGNED NOT NULL,
    available_date DATE             NOT NULL,
    PRIMARY KEY (food_id, available_date),
    FOREIGN KEY (food_id) REFERENCES food (id) ON DELETE CASCADE ON UPDATE CASCADE
);

INSERT INTO food_date (food_id, available_date)
VALUES (1, CURRENT_DATE),
       (3, CURRENT_DATE),
       (4, CURRENT_DATE),
       (7, CURRENT_DATE),
       (11, CURRENT_DATE),
       (2, CURRENT_DATE + INTERVAL 1 DAY),
       (4, CURRENT_DATE + INTERVAL 1 DAY),
       (6, CURRENT_DATE + INTERVAL 1 DAY),
       (7, CURRENT_DATE - INTERVAL 1 DAY),
       (9, CURRENT_DATE + INTERVAL 1 DAY),
       (10, CURRENT_DATE - INTERVAL 1 DAY),
       (3, CURRENT_DATE + INTERVAL 2 DAY),
       (4, CURRENT_DATE + INTERVAL 2 DAY),
       (5, CURRENT_DATE + INTERVAL 2 DAY),
       (9, CURRENT_DATE + INTERVAL 2 DAY),
       (2, CURRENT_DATE + INTERVAL 3 DAY),
       (3, CURRENT_DATE + INTERVAL 3 DAY),
       (6, CURRENT_DATE + INTERVAL 3 DAY),
       (8, CURRENT_DATE + INTERVAL 3 DAY),
       (12, CURRENT_DATE + INTERVAL 3 DAY),
       (5, CURRENT_DATE + INTERVAL 4 DAY),
       (6, CURRENT_DATE + INTERVAL 4 DAY),
       (8, CURRENT_DATE + INTERVAL 4 DAY),
       (9, CURRENT_DATE + INTERVAL 4 DAY),
       (10, CURRENT_DATE + INTERVAL 4 DAY),
       (2, CURRENT_DATE + INTERVAL 5 DAY),
       (4, CURRENT_DATE + INTERVAL 5 DAY),
       (5, CURRENT_DATE + INTERVAL 5 DAY),
       (8, CURRENT_DATE + INTERVAL 5 DAY),
       (11, CURRENT_DATE + INTERVAL 5 DAY),
       (12, CURRENT_DATE + INTERVAL 5 DAY),
       (1, CURRENT_DATE + INTERVAL 6 DAY),
       (5, CURRENT_DATE + INTERVAL 6 DAY),
       (6, CURRENT_DATE + INTERVAL 6 DAY),
       (9, CURRENT_DATE + INTERVAL 6 DAY),
       (12, CURRENT_DATE + INTERVAL 6 DAY);

########################################################################################################################

CREATE TABLE food_order
(
    order_date DATE             NOT NULL,
    user_id    INTEGER UNSIGNED NOT NULL,
    food_id    INTEGER UNSIGNED NOT NULL,
    quantity   INTEGER UNSIGNED NOT NULL,
    created_at TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (order_date, user_id, food_id),
    FOREIGN KEY (user_id) REFERENCES student (id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (food_id) REFERENCES food (id) ON DELETE CASCADE ON UPDATE CASCADE
);

INSERT INTO food_order (order_date, user_id, food_id, quantity)
VALUES ('2020-06-21', 1, 1, 2),
       ('2020-06-21', 1, 2, 1),
       ('2020-06-22', 1, 3, 1);

########################################################################################################################

CREATE TABLE issue
(
    id         INTEGER UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id    INTEGER UNSIGNED NOT NULL,
    type       VARCHAR(100)     NOT NULL,
    location   VARCHAR(100)     NOT NULL,
    details    VARCHAR(5000)    NOT NULL,
    img        VARCHAR(2083),
    status     TINYINT UNSIGNED NOT NULL DEFAULT 0,
    created_at TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO issue (user_id, type, location, details, img, status, created_at, updated_at)
VALUES (1, 'Electrical Trip', 'Room', 'short circuit in A202', NULL, 0, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
       (1, 'Electrical Trip', 'Office', 'short circuit', NULL, 0, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
       (1, 'Cleanliness', 'Hall', 'rubbish all over the floor', NULL, 1, '2020-06-11 14:45:33', '2020-06-11 14:45:33'),
       (1, 'Lamp', 'Study Area', 'fuse broken', NULL, 1, '2020-02-29 10:36:22', '2020-02-29 10:36:22'),
       (1, 'Chair', 'Room', 'chair broken in A202', NULL, 1, '2020-06-13 12:24:48', '2020-06-13 12:24:48'),
       (1, 'Toilet Bowl', 'Toilet', 'water pipe leakage', NULL, 2, '2020-06-15 17:54:48', '2020-06-15 17:54:48'),
       (1, 'Cleanliness', 'Cafe', 'mess caused by monkeys', NULL, 2, '2020-03-22 22:14:48', '2020-03-22 22:14:48');

########################################################################################################################

CREATE TABLE accommodation
(
    id              INTEGER UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id         INTEGER UNSIGNED NOT NULL,
    check_in_date   DATE             NOT NULL,
    check_out_date  DATE             NOT NULL,
    college_id      TINYINT UNSIGNED NOT NULL,
    purpose         VARCHAR(10000)   NOT NULL,
    supporting_docs VARCHAR(2083),
    status          TINYINT UNSIGNED NOT NULL DEFAULT 0,
    created_at      TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CHECK ( check_in_date < check_out_date),
    CHECK ( college_id BETWEEN 1 AND 12)
);

INSERT INTO accommodation (user_id, check_in_date, check_out_date, college_id, purpose, supporting_docs)
VALUES (1, '2020-07-10', '2020-07-31', 8, 'Internship', NULL);
INSERT INTO accommodation (user_id, check_in_date, check_out_date, college_id, purpose, supporting_docs, status)
VALUES (1, '2020-07-10', '2020-07-31', 12, 'Play play', NULL, 2);
INSERT INTO accommodation (user_id, check_in_date, check_out_date, college_id, purpose, supporting_docs, status)
VALUES (1, '2020-07-10', '2020-07-31', 12, 'Study', NULL, 1);
