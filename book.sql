-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主机： 127.0.0.1
-- 生成日期： 2024-12-30 07:50:44
-- 服务器版本： 9.0.1
-- PHP 版本： 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 数据库： `book`
--

-- --------------------------------------------------------

--
-- 表的结构 `book`
--

CREATE TABLE `book` (
  `BookID` int NOT NULL,
  `BookName` varchar(100) NOT NULL,
  `Author` varchar(100) NOT NULL,
  `PublisherID` int DEFAULT NULL,
  `CategoryID` int DEFAULT NULL,
  `Price` decimal(10,2) NOT NULL,
  `Stock` int NOT NULL DEFAULT '0',
  `Description` text,
  `ImageURL` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- 转存表中的数据 `book`
--

INSERT INTO `book` (`BookID`, `BookName`, `Author`, `PublisherID`, `CategoryID`, `Price`, `Stock`, `Description`, `ImageURL`) VALUES
(1, '深入理解计算机系统', 'Randal E. Bryant', 3, 1, 139.00, 45, '本书从程序员的视角详细阐述计算机系统的本质概念，并展示这些概念如何实实在在地影响应用程序的正确性、性能和实用性。', 'uploads/books/676d6bf4470c0.jpg'),
(2, '算法导论', 'Thomas H.Cormen', 3, 1, 128.00, 30, '本书提供了对当代计算机算法研究的一个全面、综合性的介绍。', 'uploads/books/676d6befdf363.png'),
(4, '三体', '刘慈欣', 1, 2, 23.00, 199, '科幻小说史诗巨著，讲述人类文明与三体文明的正面碰撞。', 'uploads/books/676d6be363628.jpg'),
(5, 'JavaScript高级程序设计', 'Nicholas C.Zakas', 4, 1, 99.00, 25, 'JavaScript领域的经典之作，涵盖JavaScript编程的方方面面。', 'uploads/books/676d6bdacfd19.jpg'),
(6, '人类简史', '尤瓦尔·赫拉利', 2, 4, 68.00, 87, '从认知革命、农业革命、科学革命到人工智能革命，重新审视人类发展的历程。', 'uploads/books/676d6bd02fdb0.jpg'),
(7, '红楼梦', '曹雪芹', 1, 2, 50.00, 100, '中国古典小说巅峰之作，描写贾宝玉与林黛玉的爱情悲剧。', 'uploads/books/676d67e41334e.jpg'),
(8, '西游记', '吴承恩', 1, 2, 45.00, 98, '讲述唐僧师徒四人西天取经的神话故事。', 'uploads/books/676d67dd4b976.jpg'),
(9, '三国演义', '罗贯中', 1, 2, 48.00, 98, '以三国时期为背景，描绘了众多英雄人物的斗争与智慧。', 'uploads/books/676d67d79738c.jpg'),
(10, '水浒传', '施耐庵', 1, 2, 47.00, 96, '描述了108位好汉在梁山泊聚义的故事。', 'uploads/books/676d67d177ad5.jpg'),
(13, 'dldl', '111', NULL, 19, 2.00, 455, '11', 'uploads/books/67723e2242916.jpg');

-- --------------------------------------------------------

--
-- 表的结构 `cart`
--

CREATE TABLE `cart` (
  `CartID` int NOT NULL,
  `UserID` int NOT NULL,
  `BookID` varchar(50) NOT NULL,
  `Quantity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- 表的结构 `category`
--

CREATE TABLE `category` (
  `CategoryID` int NOT NULL,
  `CategoryName` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- 转存表中的数据 `category`
--

INSERT INTO `category` (`CategoryID`, `CategoryName`) VALUES
(1, '计算机/网络'),
(2, '文学小说'),
(3, '经济管理'),
(4, '科学技术'),
(15, '计算机/网络'),
(16, '文学小说'),
(17, '经济管理'),
(18, '科学技术'),
(19, '计算机/网络'),
(20, '文学小说'),
(21, '经济管理'),
(22, '科学技术');

-- --------------------------------------------------------

--
-- 表的结构 `order`
--

CREATE TABLE `order` (
  `OrderID` int NOT NULL,
  `UserID` int NOT NULL,
  `TotalAmount` decimal(10,2) NOT NULL,
  `ShippingAddress` text NOT NULL,
  `Phone` varchar(20) NOT NULL,
  `OrderTime` datetime NOT NULL,
  `Status` enum('pending','paid','shipped','completed','cancelled') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- 转存表中的数据 `order`
--

INSERT INTO `order` (`OrderID`, `UserID`, `TotalAmount`, `ShippingAddress`, `Phone`, `OrderTime`, `Status`) VALUES
(1, 8, 0.00, '123', '123', '2024-12-25 13:34:21', 'completed'),
(2, 8, 406.00, '北京市海淀区', '13800138000', '2024-12-25 16:37:33', 'completed'),
(3, 8, 92.00, '111', '111', '2024-12-26 22:39:27', 'completed'),
(4, 8, 68.00, '1', '1', '2024-12-27 00:00:27', 'completed'),
(5, 13, 188.00, '12', '12', '2024-12-27 13:35:44', 'completed'),
(6, 8, 834.00, '1', '1', '2024-12-30 14:25:14', 'cancelled'),
(7, 8, 48.00, '111', '123', '2024-12-30 14:27:33', 'completed'),
(8, 8, 48.00, '111', '123', '2024-12-30 14:30:25', 'completed');

-- --------------------------------------------------------

--
-- 表的结构 `order_detail`
--

CREATE TABLE `order_detail` (
  `OrderDetailID` int NOT NULL,
  `OrderID` int NOT NULL,
  `BookID` int NOT NULL,
  `Quantity` int NOT NULL,
  `Price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- 转存表中的数据 `order_detail`
--

INSERT INTO `order_detail` (`OrderDetailID`, `OrderID`, `BookID`, `Quantity`, `Price`) VALUES
(1, 1, 5, 5, 99.00),
(2, 1, 6, 3, 68.00),
(3, 2, 1, 2, 139.00),
(4, 2, 2, 1, 128.00),
(5, 3, 10, 1, 47.00),
(6, 3, 8, 1, 45.00),
(7, 4, 4, 1, 23.00),
(8, 4, 8, 1, 45.00),
(9, 5, 10, 4, 47.00),
(10, 6, 1, 6, 139.00),
(11, 7, 9, 1, 48.00),
(12, 8, 9, 1, 48.00);

-- --------------------------------------------------------

--
-- 表的结构 `publisher`
--

CREATE TABLE `publisher` (
  `PublisherID` int NOT NULL,
  `PublisherName` varchar(100) DEFAULT NULL,
  `Address` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- 转存表中的数据 `publisher`
--

INSERT INTO `publisher` (`PublisherID`, `PublisherName`, `Address`) VALUES
(1, '人民文学出版社', '北京市朝阳区潘家园东里7号'),
(2, '商务印书馆', '北京市东城区王府井大街36号'),
(3, '机械工业出版社', '北京市西城区百万庄大街22号'),
(4, '电子工业出版社', '北京市海淀区万寿路173号'),
(7, '人民文学出版社', '北京市朝阳区潘家园东里7号'),
(8, '商务印书馆', '北京市东城区王府井大街36号'),
(9, '机械工业出版社', '北京市西城区百万庄大街22号'),
(10, '电子工业出版社', '北京市海淀区万寿路173号'),
(11, '人民文学出版社', '北京市朝阳区潘家园东里7号'),
(12, '商务印书馆', '北京市东城区王府井大街36号'),
(13, '机械工业出版社', '北京市西城区百万庄大街22号'),
(14, '电子工业出版社', '北京市海淀区万寿路173号');

-- --------------------------------------------------------

--
-- 表的结构 `stock_record`
--

CREATE TABLE `stock_record` (
  `RecordID` int NOT NULL,
  `BookID` int NOT NULL,
  `Type` enum('in','out') NOT NULL,
  `Quantity` int NOT NULL,
  `Remark` text,
  `OperatorID` int NOT NULL,
  `RecordTime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- 转存表中的数据 `stock_record`
--

INSERT INTO `stock_record` (`RecordID`, `BookID`, `Type`, `Quantity`, `Remark`, `OperatorID`, `RecordTime`) VALUES
(1, 1, 'in', 1, '', 3, '2024-12-25 13:42:03'),
(2, 6, 'in', 30, 'test', 3, '2024-12-26 23:17:49'),
(3, 5, 'out', 10, '', 3, '2024-12-26 23:18:47'),
(5, 10, 'in', 1, '', 3, '2024-12-27 00:06:18'),
(6, 13, 'in', 222, '222', 3, '2024-12-30 14:31:05');

--
-- 触发器 `stock_record`
--
DELIMITER $$
CREATE TRIGGER `after_stock_record_insert` AFTER INSERT ON `stock_record` FOR EACH ROW BEGIN
    IF NEW.Type = 'in' THEN
        UPDATE book
        SET Stock = Stock + NEW.Quantity
        WHERE BookID = NEW.BookID;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- 表的结构 `user`
--

CREATE TABLE `user` (
  `UserID` int NOT NULL,
  `Username` varchar(50) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Address` varchar(200) DEFAULT NULL,
  `ContactInfo` varchar(200) DEFAULT NULL,
  `RegistrationDate` datetime DEFAULT CURRENT_TIMESTAMP,
  `Role` varchar(50) NOT NULL DEFAULT 'customer',
  `RegisterTime` datetime DEFAULT CURRENT_TIMESTAMP,
  `Phone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- 转存表中的数据 `user`
--

INSERT INTO `user` (`UserID`, `Username`, `Password`, `Email`, `Address`, `ContactInfo`, `RegistrationDate`, `Role`, `RegisterTime`, `Phone`) VALUES
(3, 'root', '$2y$10$2x.AL9S4vDbhZMSjOYSPUujbG8eCkj48mOv/Zgk4tJrBeii2srbmm', '123@qq.com', NULL, NULL, '2024-12-25 12:01:51', 'admin', '2024-12-25 14:07:56', NULL),
(4, 'admin', '$2y$10$2x.AL9S4vDbhZMSjOYSPUujbG8eCkj48mOv/Zgk4tJrBeii2srbmm', 'admin@example.com', NULL, NULL, '2024-12-25 12:11:05', 'admin', '2024-12-25 14:07:56', NULL),
(8, 'user', '$2y$10$2x.AL9S4vDbhZMSjOYSPUujbG8eCkj48mOv/Zgk4tJrBeii2srbmm', 'user@example.com', NULL, NULL, '2024-12-25 12:12:09', 'customer', '2024-12-25 14:07:56', NULL),
(9, 'test', '123456', 'test@example.com', NULL, NULL, '2024-12-25 16:51:56', 'customer', '2024-12-25 16:51:56', NULL),
(11, 'admin123', '$2y$10$X2q2iXbtO5v4/skE1VO10ukskOO31Vw4nnYj49558jHW0m5j9zgoK', 'admin123@qq.com', 'admin123', '111', '2024-12-26 20:19:07', 'customer', '2024-12-26 20:19:07', NULL),
(13, 'user1', '$2y$10$zJE/D3/gnIOxVsPux.RyYOQCK4RALT6ukJY5be5hzJaRRnR2x9vhC', '1233@qq.com', '', '111', '2024-12-27 13:35:18', 'customer', '2024-12-27 13:35:18', NULL);

--
-- 转储表的索引
--

--
-- 表的索引 `book`
--
ALTER TABLE `book`
  ADD PRIMARY KEY (`BookID`),
  ADD KEY `PublisherID` (`PublisherID`),
  ADD KEY `CategoryID` (`CategoryID`);

--
-- 表的索引 `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`CartID`),
  ADD KEY `UserID` (`UserID`),
  ADD KEY `BookID` (`BookID`);

--
-- 表的索引 `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`CategoryID`);

--
-- 表的索引 `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`OrderID`),
  ADD KEY `UserID` (`UserID`);

--
-- 表的索引 `order_detail`
--
ALTER TABLE `order_detail`
  ADD PRIMARY KEY (`OrderDetailID`),
  ADD KEY `OrderID` (`OrderID`),
  ADD KEY `BookID` (`BookID`);

--
-- 表的索引 `publisher`
--
ALTER TABLE `publisher`
  ADD PRIMARY KEY (`PublisherID`);

--
-- 表的索引 `stock_record`
--
ALTER TABLE `stock_record`
  ADD PRIMARY KEY (`RecordID`),
  ADD KEY `BookID` (`BookID`),
  ADD KEY `OperatorID` (`OperatorID`);

--
-- 表的索引 `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `Username` (`Username`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `book`
--
ALTER TABLE `book`
  MODIFY `BookID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- 使用表AUTO_INCREMENT `cart`
--
ALTER TABLE `cart`
  MODIFY `CartID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- 使用表AUTO_INCREMENT `category`
--
ALTER TABLE `category`
  MODIFY `CategoryID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- 使用表AUTO_INCREMENT `order`
--
ALTER TABLE `order`
  MODIFY `OrderID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- 使用表AUTO_INCREMENT `order_detail`
--
ALTER TABLE `order_detail`
  MODIFY `OrderDetailID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- 使用表AUTO_INCREMENT `publisher`
--
ALTER TABLE `publisher`
  MODIFY `PublisherID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- 使用表AUTO_INCREMENT `stock_record`
--
ALTER TABLE `stock_record`
  MODIFY `RecordID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- 使用表AUTO_INCREMENT `user`
--
ALTER TABLE `user`
  MODIFY `UserID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- 限制导出的表
--

--
-- 限制表 `book`
--
ALTER TABLE `book`
  ADD CONSTRAINT `book_ibfk_1` FOREIGN KEY (`PublisherID`) REFERENCES `publisher` (`PublisherID`),
  ADD CONSTRAINT `book_ibfk_2` FOREIGN KEY (`CategoryID`) REFERENCES `category` (`CategoryID`);

--
-- 限制表 `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `order_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `user` (`UserID`);

--
-- 限制表 `order_detail`
--
ALTER TABLE `order_detail`
  ADD CONSTRAINT `order_detail_ibfk_1` FOREIGN KEY (`OrderID`) REFERENCES `order` (`OrderID`),
  ADD CONSTRAINT `order_detail_ibfk_2` FOREIGN KEY (`BookID`) REFERENCES `book` (`BookID`);

--
-- 限制表 `stock_record`
--
ALTER TABLE `stock_record`
  ADD CONSTRAINT `stock_record_ibfk_1` FOREIGN KEY (`BookID`) REFERENCES `book` (`BookID`),
  ADD CONSTRAINT `stock_record_ibfk_2` FOREIGN KEY (`OperatorID`) REFERENCES `user` (`UserID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
