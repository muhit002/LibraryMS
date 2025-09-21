-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 21, 2025 at 04:43 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `library_management_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `admin_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`admin_id`, `username`, `password`) VALUES
(1, 'admin', '$2y$10$ksq.J24zya/QPWndBF1bLOU.X/ge4T6NdGZAZuccYXmJUC44QBFEe');

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `book_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(200) NOT NULL,
  `isbn` varchar(20) NOT NULL,
  `category_id` int(11) NOT NULL,
  `total_copies` int(5) NOT NULL DEFAULT 1,
  `available_copies` int(5) NOT NULL DEFAULT 1,
  `cover_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`book_id`, `title`, `author`, `isbn`, `category_id`, `total_copies`, `available_copies`, `cover_image`) VALUES
(1, 'The Great Gatsby', 'F. Scott Fitzgerald', '978-0743273565', 1, 5, 5, NULL),
(3, 'Sapiens: A Brief History of Humankind', 'Yuval Noah Harari', '978-0062316097', 3, 4, 4, NULL),
(4, 'Clean Code: A Handbook of Agile Software Craftsmanship', 'Robert C. Martin', '978-0132350884', 4, 6, 4, NULL),
(6, 'python', 'muhit', '1234', 4, 10, 9, NULL),
(9, 'To Kill a Mockingbird', 'Harper Lee', '9780446310789', 1, 2, 1, '1757052263_68ba7d67eb5cb_download.jpeg'),
(10, 'The Pragmatic Programmer', 'Andy Hunt and Dave Thomas', '9787121387098', 4, 10, 10, '1757318224_68be8c50314fe_content.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `borrowed_books`
--

CREATE TABLE `borrowed_books` (
  `borrow_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `issue_date` date NOT NULL,
  `due_date` date NOT NULL,
  `return_date` date DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Borrowed'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `borrowed_books`
--

INSERT INTO `borrowed_books` (`borrow_id`, `book_id`, `member_id`, `issue_date`, `due_date`, `return_date`, `status`) VALUES
(3, 6, 4, '2025-09-04', '2025-09-18', '2025-09-04', 'Returned'),
(5, 4, 5, '2025-09-05', '2025-09-19', '2025-09-08', 'Returned'),
(6, 9, 6, '2025-09-05', '2025-09-19', NULL, 'Borrowed'),
(7, 6, 5, '2025-09-08', '2025-09-22', '2025-09-08', 'Returned'),
(8, 6, 5, '2025-09-08', '2025-09-22', NULL, 'Borrowed');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`) VALUES
(4, 'Computer Science'),
(1, 'Fiction'),
(3, 'History'),
(5, 'Research'),
(2, 'Science and Technology');

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `member_id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `registration_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`member_id`, `name`, `email`, `password`, `phone`, `registration_date`) VALUES
(4, 'Abrar Khan Alvi', 'abrar1khan2@gmail.com', '$2y$10$Yjk0NTTeFF4lk6MSvlwoUOltIkuCp6xxzjam7Ao4wNmilCvJYaxSS', '01816249770', '2025-09-04'),
(5, 'Abdullah Al Muhit', 'muhit@gmail.com', '$2y$10$Kx.KeK5uAC/5TfYIrplGh.rtqwhz7t7JAig2vEprGqWVmuOPQHkaC', '01748596010', '2025-09-04'),
(6, 'user12', 'ab@gmail.com', '$2y$10$a14j.5Zj66GORgaoAK1RqOynFDkFkCfThoek8H/XWzGknRwYgWLny', '01748596010', '2025-09-05'),
(8, 'abdullah al muhit', 'abdullha@gmail.com', '$2y$10$Z9Lr90YiedmqP8Me7HhstO6.yxR8n.NOi2j9iQg7ENhop1PcfVrHS', '01748596010', '2025-09-21'),
(9, 'sabbir', 'sabbir@gmail.com', '$2y$10$ezP5J70SnMIrcUXhXV28Jexy1KYrg.PRHFHh0AbR9A5vWTR3m5ARe', '123456789', '2025-09-21');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`book_id`),
  ADD UNIQUE KEY `isbn` (`isbn`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `borrowed_books`
--
ALTER TABLE `borrowed_books`
  ADD PRIMARY KEY (`borrow_id`),
  ADD KEY `book_id` (`book_id`),
  ADD KEY `member_id` (`member_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `category_name` (`category_name`);

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`member_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `book_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `borrowed_books`
--
ALTER TABLE `borrowed_books`
  MODIFY `borrow_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `member_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `books_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON UPDATE CASCADE;

--
-- Constraints for table `borrowed_books`
--
ALTER TABLE `borrowed_books`
  ADD CONSTRAINT `borrowed_books_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `books` (`book_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `borrowed_books_ibfk_2` FOREIGN KEY (`member_id`) REFERENCES `members` (`member_id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
