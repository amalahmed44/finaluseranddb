-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3308
-- Generation Time: Aug 30, 2024 at 12:19 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bookstore`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `book_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `is_featured` enum('yes','no') NOT NULL,
  `stock` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`book_id`, `title`, `author`, `category`, `price`, `image`, `description`, `is_featured`, `stock`) VALUES
(7, 'Ababil', 'Ahmed Al-Hamdan', 'Fantasy', 10.00, 'ababel.jpg', 'Ababil, is the first part of the Mud and Fire series consisting of three books, written by the novelist Ahmed Al Hamdan. The novel revolves around the fairy Jumana, who married Bahr after she gave up her supernatural powers and her family in order to marry him. But she discovers that her husband, Bahar, works for a terrorist organization that is hostile to her family! She tries to get him out of it by all means. But Bahar is threatened with the destruction of his family if he tries to rebel.\r\n\r\nThe novel within its pages contains a lot of plot and clarity of narration, to make it the distinguished novel that you must read now.', 'yes', 100),
(8, 'Al-Jassasa', 'Ahmed Al-Hamdan', 'Fantasy', 10.00, 'jas.jpeg', 'Al-Jassasah novel is the second part of the Mud and Fire series or Ababil novel by Ahmed Al Hamdan. The novel adds a large dose of fun and suspense. The style of the novel is very beautiful and completely different from the previous novel, although it is complementary to it. It revolves around the hero Wasef and the wars he wages, and in the end he loses, to turn into a stage of revenge and overcoming obstacles.\r\n\r\nIt features a detailed, consistent narrative and some fast-paced action, with a host of new characters, stories, and events', 'yes', 100),
(12, 'It Starts With Us', 'Colleen Hoover', 'Romance', 15.00, 'starts.jpg', 'It Starts with Us is a romance novel by Colleen Hoover, published by Atria Books on October 18, 2022.[1] It is the sequel to her 2016 best-selling novel It Ends with Us.[2] The sequel was first announced in February 2022.[3] It became Simon & Schuster\'s most pre-ordered book of all time.[4] Hoover wrote the novel as a \"thank you\" to fans of the first novel.[5]', 'yes', 100);

-- --------------------------------------------------------

--
-- Table structure for table `books_sold`
--

CREATE TABLE `books_sold` (
  `id` int(11) NOT NULL,
  `order_item_id` int(11) DEFAULT NULL,
  `book_id` int(11) DEFAULT NULL,
  `actual_stock` int(11) DEFAULT NULL,
  `quantity_ordered` int(11) DEFAULT NULL,
  `remaining_amount` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books_sold`
--

INSERT INTO `books_sold` (`id`, `order_item_id`, `book_id`, `actual_stock`, `quantity_ordered`, `remaining_amount`, `created_at`) VALUES
(10, NULL, 7, 100, 1, 99, '2024-08-28 19:29:09'),
(11, NULL, 8, 100, 5, 95, '2024-08-30 09:30:35');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `region` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `payment_method` enum('Zaad','Edahab') NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `reference_number` varchar(255) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `name`, `region`, `phone`, `address`, `payment_method`, `amount`, `reference_number`, `user_id`, `created_at`) VALUES
(27, 'uu', 'uu', '87654', 'jmnhbg', 'Zaad', 12.00, 'jmnhbgty67', 3, '2024-08-28 18:57:43'),
(28, 'hfgc', 'jgf', '87654', 'ghf150', 'Zaad', 10.00, 'bvcdrf5', 1, '2024-08-30 09:30:04'),
(29, 'kujyhdf', 'jhgc', '98765', 'kjhgf', 'Zaad', 20.00, 'ugjj6', 1, '2024-08-30 09:38:26');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `status` enum('pending','approved','delivered') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_item_id`, `order_id`, `book_id`, `quantity`, `price`, `total`, `status`, `created_at`) VALUES
(25, 27, 7, 1, 10.00, 10.00, 'delivered', '2024-08-28 18:57:43'),
(26, 28, 8, 5, 10.00, 20.00, 'delivered', '2024-08-30 09:30:04'),
(27, 29, 7, 1, 10.00, 10.00, 'approved', '2024-08-30 09:38:26'),
(28, 29, 8, 1, 10.00, 10.00, 'approved', '2024-08-30 09:38:26');

--
-- Triggers `order_items`
--
DELIMITER $$
CREATE TRIGGER `after_order_delivered` AFTER UPDATE ON `order_items` FOR EACH ROW BEGIN
    DECLARE current_stock INT;
    DECLARE total_quantity_ordered INT;

    IF NEW.status = 'delivered' THEN
        -- Get the current stock of the book from the books table
        SELECT stock INTO current_stock
        FROM books
        WHERE book_id = NEW.book_id
        LIMIT 1;

        -- Calculate total quantity ordered for the book
        SELECT IFNULL(SUM(quantity_ordered), 0) INTO total_quantity_ordered
        FROM books_sold
        WHERE book_id = NEW.book_id;

        -- Check if the book already exists in books_sold
        IF EXISTS (SELECT 1 FROM books_sold WHERE book_id = NEW.book_id) THEN
            -- Update the existing record
            UPDATE books_sold
            SET quantity_ordered = total_quantity_ordered + NEW.quantity,
                remaining_amount = current_stock - (total_quantity_ordered + NEW.quantity)
            WHERE book_id = NEW.book_id;
        ELSE
            -- Insert a new record in books_sold
            INSERT INTO books_sold (book_id, actual_stock, quantity_ordered, remaining_amount)
            VALUES (NEW.book_id, 
                    current_stock, 
                    NEW.quantity, 
                    current_stock - NEW.quantity);
        END IF;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`) VALUES
(1, 'amal', '$2y$10$YHysCXqkUCUkZ/L2skxpQeNmgc508BXe62kAAvXZjLXu08RvbPvji'),
(3, 'ubah', '$2y$10$X3vbvKsulArXNBALl6gdiOtrejFQlV/2vQ1tVACi.2d4fkjYPx91q');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`book_id`);

--
-- Indexes for table `books_sold`
--
ALTER TABLE `books_sold`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_item_id` (`order_item_id`),
  ADD KEY `book_id` (`book_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `book_id` (`book_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `book_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `books_sold`
--
ALTER TABLE `books_sold`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `books_sold`
--
ALTER TABLE `books_sold`
  ADD CONSTRAINT `books_sold_ibfk_1` FOREIGN KEY (`order_item_id`) REFERENCES `order_items` (`order_item_id`),
  ADD CONSTRAINT `books_sold_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`book_id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`book_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
