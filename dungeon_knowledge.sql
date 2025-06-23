-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 05, 2025 at 11:41 AM
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
-- Database: `dungeon_knowledge`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`) VALUES
(1, 'admin1', '1234'),
(2, 'admin2', '5678');

-- --------------------------------------------------------

--
-- Table structure for table `answers`
--

CREATE TABLE `answers` (
  `id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `answer_text` text NOT NULL,
  `is_correct` tinyint(1) NOT NULL CHECK (`is_correct` in (0,1))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `answers`
--

INSERT INTO `answers` (`id`, `question_id`, `answer_text`, `is_correct`) VALUES
(1, 1, 'A way to draw using digital art tools', 0),
(2, 1, 'Using technology-based logic to solve problems ✅', 1),
(3, 1, 'Playing video games with friends', 0),
(4, 1, ' Memorizing computer parts', 0),
(5, 2, 'Programming in Python', 0),
(6, 2, 'Doing math using a calculator', 0),
(7, 2, 'Solving problems with methods like decomposition and abstraction', 1),
(8, 2, 'Creating a computer game', 0),
(9, 3, 'Pattern recognition', 0),
(10, 3, 'Decomposition', 0),
(11, 3, 'Guessing the solution', 1),
(12, 3, 'Abstraction', 0),
(13, 4, 'A file on your USB stick', 0),
(14, 4, 'Any machine that uses mechanical parts', 0),
(15, 4, 'A system that processes data using digital technology', 1),
(16, 4, 'A book about computers', 0),
(17, 5, 'Data is meaningful; information is raw', 0),
(18, 5, 'Data is processed; information is always stored', 0),
(19, 5, 'Data is raw; information is processed and meaningful', 1),
(20, 5, 'Data is useless; information is not', 0),
(21, 6, 'Repeating a problem', 0),
(22, 6, 'Ignoring unimportant details to focus on the key parts', 1),
(23, 6, 'Creating errors on purpose', 0),
(24, 6, 'Adding extra information', 0),
(25, 7, 'Writing a new algorithm', 0),
(26, 7, 'Noticing that the school bus is always late on rainy days', 1),
(27, 7, 'Installing new software', 0),
(28, 7, 'Guessing answers on a quiz', 0),
(29, 8, 'A set of random instructions', 0),
(30, 8, 'A programming language', 0),
(31, 8, 'A step-by-step method for solving a problem ', 1),
(32, 8, 'A computer virus', 0),
(33, 9, 'A chart showing Wi-Fi speed', 0),
(34, 9, 'A picture of how a website looks', 0),
(35, 9, 'A diagram showing steps in a process or algorithm', 1),
(36, 9, 'A calendar of tasks', 0),
(37, 10, 'Manually doing calculations', 0),
(38, 10, 'Teaching kids to code', 0),
(39, 10, 'Using systems to perform tasks without human help', 1),
(40, 10, 'Drawing diagrams', 0),
(41, 11, 'It makes us good at memorizing phone numbers', 0),
(42, 11, 'It helps solve real-world problems efficiently using technology', 1),
(43, 11, 'It teaches us how to draw better', 0),
(44, 11, 'It replaces critical thinking entirely', 0),
(45, 12, 'A system that only stores physical files', 0),
(46, 12, 'A type of smartphone', 0),
(47, 12, 'A system that collects, processes, and distributes information ', 1),
(48, 12, 'A tool used only by scientists', 0),
(49, 13, 'Hardware', 0),
(50, 13, 'Software', 0),
(51, 13, 'Electricity ', 1),
(52, 13, 'Data', 0),
(53, 14, 'To play games', 0),
(54, 14, 'To generate random data', 0),
(55, 14, 'To help in making decisions through organized data', 1),
(56, 14, 'To store unused information', 0),
(57, 15, 'Only IT professionals', 0),
(58, 15, 'Anyone who works in a bakery', 0),
(59, 15, 'Only people who write code', 0),
(60, 15, 'People in all industries including business, healthcare, and education', 1),
(61, 16, 'Database ', 1),
(62, 16, 'Microwave', 0),
(63, 16, 'Plastic', 0),
(64, 16, 'Pencil', 0),
(65, 17, 'Repairs hardware', 0),
(66, 17, 'Transports information physically', 0),
(67, 17, 'Directs the hardware to process data', 1),
(68, 17, 'Powers the monitor', 0),
(69, 18, 'A machine that cleans keyboards', 0),
(70, 18, 'An ATM machine that processes withdrawals ', 1),
(71, 18, 'A video editing app', 0),
(72, 18, 'A calculator', 0),
(73, 19, 'Operating system', 0),
(74, 19, 'Transaction Processing System', 0),
(75, 19, 'Decision Support System (DSS)', 1),
(76, 19, 'Gaming console', 0),
(77, 20, 'The study of human biology', 0),
(78, 20, 'Making machines mimic human intelligence', 1),
(79, 20, 'Creating digital artwork', 0),
(80, 20, 'Programming games', 0),
(81, 21, 'Electric fan', 0),
(82, 21, 'Calculator', 0),
(83, 21, 'Voice assistant like Siri or Alexa', 1),
(84, 21, 'Flashlight', 0),
(85, 22, 'To create physical robots', 0),
(86, 22, 'To help machines think and act like humans ', 1),
(87, 22, 'To delete human jobs', 0),
(88, 22, 'To replace computers', 0),
(89, 23, 'A way to teach humans about machines', 0),
(90, 23, 'A method where machines learn from data', 1),
(91, 23, 'Writing code with a machine', 0),
(92, 23, 'Creating websites automatically', 0),
(93, 24, 'Natural Intelligence', 0),
(94, 24, 'Narrow AI', 1),
(95, 24, 'Logical AI', 0),
(96, 24, 'Manual AI', 0),
(97, 25, 'AI programs learn and improve from data', 1),
(98, 25, 'AI needs no computer', 0),
(99, 25, 'Traditional programming is faster than AI', 0),
(100, 25, 'AI only works offline', 0);

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `question_text` text NOT NULL,
  `note` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `quiz_id`, `question_text`, `note`) VALUES
(1, 1, 'What is digital thinking?', 'Digital thinking is problem solving in the basis of technological logic'),
(2, 1, 'What is computational thinking?', 'Decomposition and abstraction used to solve problems in the basis of digital thinking'),
(3, 1, 'Which of the following is not part of computational thinking?', 'Pattern recognition, decomposition and abstraction are part of computational thinking'),
(4, 1, 'What is a digital system?', 'Digital systems often accept data and process them to the point it outputs usable information'),
(5, 2, 'What is the difference between data and information?', ''),
(6, 2, 'What is abstraction in digital thinking?', 'Abstraction involves focusing on key parts of a problem while ignoring unimportant details'),
(7, 2, 'Which is an example of pattern recognition?', 'Pattern recognition is a computational thinking method used to identify the similarity between problems to look for a solution'),
(8, 3, 'What is an algorithm?', 'Algorithm are used to solve problems step by step'),
(9, 3, 'What is a flowchart?', 'Flowchart is a visual representation of a sequence of steps in an algorithm'),
(10, 3, 'What does automation mean in digital thinking?', ''),
(11, 3, 'Why is digital thinking important today?', ''),
(12, 4, 'What is an information system?', 'Information system is a system that collects data and produces useful and usable information after the not usable and not understandable data collected'),
(13, 4, 'Which of the following is not a component of an information system?', 'Hardware, software, data and people are key components in information systems '),
(14, 4, 'What is the main purpose of an information system?', 'The main purpose of an information system is to produce organized data and information to help with decision making within an organization'),
(15, 4, 'Who uses information systems?', 'Information systems are used by people in all industries'),
(16, 5, 'Which of the following is a component of an information system?', ''),
(17, 5, 'What does software in an information system do?', 'Information systems utilizes its hardware by developing software that directs the hardware to process data'),
(18, 5, 'What is an example of a Transaction Processing System (TPS)?', ''),
(19, 5, 'Which system supports decision making at the management level?', ''),
(20, 6, 'What is Artificial Intelligence (AI)?', 'Artificial intelligence is the mimicking of human intelligence in machines'),
(21, 6, 'Which of the following is an example of AI in daily life?', 'Voice assistants are an example of Artificial Intelligence that uses voice recognition'),
(22, 6, 'What is the goal of AI?', ''),
(23, 7, 'What is Machine Learning (ML)?', ''),
(24, 7, 'Which of the following is a type of AI?', 'Narrow AI, General AI and Strong AI are types of Artificial Intelligence.'),
(25, 7, 'What is the difference between AI and traditional programming?', '');

-- --------------------------------------------------------

--
-- Table structure for table `quizzes`
--

CREATE TABLE `quizzes` (
  `id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `quiz_name` text NOT NULL,
  `description` text DEFAULT NULL,
  `wrong_answer_limit` int(11) DEFAULT 3
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quizzes`
--

INSERT INTO `quizzes` (`id`, `subject_id`, `quiz_name`, `description`, `wrong_answer_limit`) VALUES
(1, 1, 'Chapter 1: Fundamentals of Digital Thinking', 'Learn the definition, fundamentals and key aspects of digital thinking', 0),
(2, 1, 'Chapter 2: Data, Information, and Problem Solving', 'Learn the definitions and characteristics of Data, Information and Problem Solving', 5),
(3, 1, 'Chapter 3: Algorithms, Automation, and Applications', 'Learn how digital thinking could be applied on the basis of algorithms, automation and applications', 4),
(4, 2, 'Chapter 1: Introduction to Information Systems', 'Learn the definitions and fundamental aspects in an information system', 0),
(5, 2, 'Chapter 2: Components and Types of Information Systems', 'Learn more about the components and different types of information systems ', 3),
(6, 3, 'Chapter 1: Understanding Artificial Intelligence', 'Learn the definition and fundamentals of Artificial Intelligence', 0),
(7, 3, 'Chapter 2: Types and Techniques of AI', 'Learn the techniques used in AI to help machines learn from data and recognize patterns', 3);

-- --------------------------------------------------------

--
-- Table structure for table `results`
--

CREATE TABLE `results` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `score` varchar(11) DEFAULT NULL,
  `taken_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `results`
--

INSERT INTO `results` (`id`, `user_id`, `quiz_id`, `score`, `taken_at`) VALUES
(30, 1, 1, '4/4', '2025-05-05 17:20:32'),
(31, 1, 4, '4/4', '2025-05-05 17:29:07'),
(32, 1, 4, '4/4', '2025-05-05 17:29:30'),
(33, 1, 6, '3/3', '2025-05-05 17:30:39'),
(34, 1, 6, '3/3', '2025-05-05 17:31:00'),
(35, 2, 1, '4/4', '2025-05-05 17:34:25'),
(36, 2, 1, '4/4', '2025-05-05 17:34:49'),
(37, 2, 4, '4/4', '2025-05-05 17:36:03'),
(38, 2, 6, '3/3', '2025-05-05 17:36:24');

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` int(11) NOT NULL,
  `subject_name` text NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `subject_name`, `description`) VALUES
(1, 'Digital Thinking', 'Learn the art of digital thinking in daily life problem solving'),
(2, 'Information Systems', 'This set introduces the concepts, components, and applications of information systems.'),
(3, 'Introduction to Artificial Intelligence', 'Learn the fundamentals, different types and mechanisms of Artificial Intelligence ');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `avatar_index` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `password`, `avatar_index`) VALUES
(1, 'John Doe', 'user1', '$2y$10$ftOaaNETBmK3J8aRuyZQmOSkwxpBOAaP0hGLshLxn.HmfQr6QuXN2', 1),
(2, 'Alex', 'user2', '$2y$10$kIn4J7.R7bWSNT.ByDICAOh0eTZhci05HPtnlRh7e75wuQA2LAyRa', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`) USING HASH;

--
-- Indexes for table `answers`
--
ALTER TABLE `answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `question_id` (`question_id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quiz_id` (`quiz_id`);

--
-- Indexes for table `quizzes`
--
ALTER TABLE `quizzes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `results`
--
ALTER TABLE `results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `quiz_id` (`quiz_id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`) USING HASH;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `answers`
--
ALTER TABLE `answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `quizzes`
--
ALTER TABLE `quizzes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `results`
--
ALTER TABLE `results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `answers`
--
ALTER TABLE `answers`
  ADD CONSTRAINT `answers_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`);

--
-- Constraints for table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`);

--
-- Constraints for table `quizzes`
--
ALTER TABLE `quizzes`
  ADD CONSTRAINT `quizzes_ibfk_1` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`);

--
-- Constraints for table `results`
--
ALTER TABLE `results`
  ADD CONSTRAINT `results_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `results_ibfk_2` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
