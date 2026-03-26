-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 26, 2026 at 01:12 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `incident_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `id_picture` varchar(255) DEFAULT NULL,
  `birthday` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `email`, `username`, `password`, `id_picture`, `birthday`) VALUES
(1, 'admin@berms.gov.ph', 'admin', 'admin123', NULL, NULL),
(2, 'mark@mark.com', 'mark', 'admin123', 'mark_1774181567.png', '2017-06-15');

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `user_name` varchar(255) DEFAULT NULL,
  `user_email` varchar(255) DEFAULT NULL,
  `emergency_type` varchar(50) DEFAULT NULL,
  `details` text DEFAULT NULL,
  `attachment_path` varchar(255) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`id`, `user_id`, `user_name`, `user_email`, `emergency_type`, `details`, `attachment_path`, `status`, `created_at`) VALUES
(1, NULL, 'John Rodriguez', 'john.rodriguez@email.com', 'Fire', 'House fire in Barangay 171. Flames visible from roof. People evacuated.', 'report_uploads/evidence_1.svg', 'Resolved', '2026-03-20 06:23:00'),
(2, NULL, 'Maria Santos', 'maria.santos@email.com', 'Ambulance', 'Medical emergency - chest pain. Patient needs immediate assistance.', 'report_uploads/evidence_2.svg', 'Resolved', '2026-03-20 07:45:00'),
(3, NULL, 'Pedro Reyes', 'pedro.reyes@email.com', 'Police', 'Traffic accident at intersection. Multiple vehicles involved. No injuries reported.', 'report_uploads/evidence_3.svg', 'Pending', '2026-03-20 08:12:00'),
(4, NULL, 'Ana Garcia', 'ana.garcia@email.com', 'Fire', 'Electrical fire in commercial building. Smoke detected on 3rd floor.', 'report_uploads/evidence_4.svg', 'Pending', '2026-03-20 09:30:00'),
(5, NULL, 'Carlos Mendez', 'carlos.mendez@email.com', 'Ambulance', 'Fall injury - elderly patient. Possible broken hip. Needs emergency care.', 'report_uploads/evidence_5.svg', 'In Progress', '2026-03-20 10:02:00'),
(6, NULL, 'Rosa Flores', 'rosa.flores@email.com', 'Police', 'Theft reported at convenience store. Suspect fled on motorcycle.', 'report_uploads/evidence_6.svg', 'Pending', '2026-03-20 10:45:00'),
(7, NULL, 'Luis Ramirez', 'luis.ramirez@email.com', 'Fire', 'Vegetation fire spreading near residential area. Wind speed increasing.', 'report_uploads/evidence_7.svg', 'In Progress', '2026-03-20 11:15:00'),
(8, NULL, 'Elena Cruz', 'elena.cruz@email.com', 'Ambulance', 'Allergic reaction - difficulty breathing. Anaphylaxis suspected. Urgent.', 'report_uploads/evidence_8.svg', 'Resolved', '2026-03-20 11:50:00'),
(9, NULL, 'Miguel Torres', 'miguel.torres@email.com', 'Police', 'Domestic dispute with verbal threats. Concerned neighbor called.', 'report_uploads/evidence_9.svg', 'Pending', '2026-03-20 12:30:00'),
(10, NULL, 'Patricia Ortiz', 'patricia.ortiz@email.com', 'Fire', 'Gas leak detected in apartment complex. Smell of gas reported.', 'report_uploads/evidence_10.svg', 'In Progress', '2026-03-20 13:05:00'),
(11, NULL, 'Diego Herrera', 'diego.herrera@email.com', 'Ambulance', 'Vehicle accident victim - apparent head injury. Unconscious patient.', 'report_uploads/evidence_11.svg', 'Resolved', '2026-03-20 13:45:00'),
(12, NULL, 'Sofia Morales', 'sofia.morales@email.com', 'Police', 'Noise complaint from nightclub. Excessive volume disturbing residents.', 'report_uploads/evidence_12.svg', 'Resolved', '2026-03-20 14:15:00'),
(13, NULL, 'Roberto Luna', 'roberto.luna@email.com', 'Fire', 'Chimney fire with visible flames. Family evacuated safely.', 'report_uploads/evidence_13.svg', 'Pending', '2026-03-20 14:50:00'),
(14, NULL, 'Lucia Campos', 'lucia.campos@email.com', 'Ambulance', 'Diabetic emergency - patient unconscious. Blood sugar crisis.', 'report_uploads/evidence_14.svg', 'In Progress', '2026-03-20 15:20:00'),
(15, NULL, 'Antonio Gutierrez', 'antonio.gutierrez@email.com', 'Police', 'Break-in at residence. Burglar alarm triggered. Police requested.', 'report_uploads/evidence_15.svg', 'Pending', '2026-03-20 16:05:00'),
(16, NULL, 'Carmen Rodriguez', 'carmen.rodriguez@email.com', 'Fire', 'Cooking fire in kitchen. Contained but smoke present throughout house.', 'report_uploads/evidence_16.svg', 'Resolved', '2026-03-20 16:40:00'),
(17, NULL, 'Francisco Medina', 'francisco.medina@email.com', 'Ambulance', 'Child with high fever and seizure. Parents very concerned.', 'report_uploads/evidence_17.svg', 'In Progress', '2026-03-20 17:15:00'),
(18, NULL, 'Isabella Jimenez', 'isabella.jimenez@email.com', 'Police', 'Hit and run incident. Vehicle fled. Victim transported to hospital.', 'report_uploads/evidence_18.svg', 'Pending', '2026-03-20 17:50:00'),
(19, NULL, 'Vicente Solis', 'vicente.solis@email.com', 'Fire', 'Warehouse fire - multiple items burning. Dense smoke spreading.', 'report_uploads/evidence_19.svg', 'In Progress', '2026-03-20 18:25:00'),
(20, NULL, 'Dolores Vargas', 'dolores.vargas@email.com', 'Ambulance', 'Elderly patient fallen. Hip fracture suspected. Pain level very high.', 'report_uploads/evidence_20.svg', 'Resolved', '2026-03-20 19:00:00'),
(21, NULL, 'Jorge Navarro', 'jorge.navarro@email.com', 'Police', 'Street altercation between groups. Potential violence situation.', 'report_uploads/evidence_21.svg', 'Pending', '2026-03-20 19:35:00'),
(22, NULL, 'Mercedes Romero', 'mercedes.romero@email.com', 'Fire', 'Garden shed fire spreading toward main house. Immediate action needed.', 'report_uploads/evidence_22.svg', 'In Progress', '2026-03-20 20:10:00'),
(23, NULL, 'Oscar Castillo', 'oscar.castillo@email.com', 'Ambulance', 'Severe burns from cooking accident. Second-degree burns on hands.', 'report_uploads/evidence_23.svg', 'Resolved', '2026-03-20 20:45:00'),
(24, NULL, 'Matilda Silva', 'matilda.silva@email.com', 'Police', 'Suspicious person loitering near school. Possibly casing for theft.', 'report_uploads/evidence_24.svg', 'Pending', '2026-03-20 21:20:00'),
(25, NULL, 'Raphael Acosta', 'raphael.acosta@email.com', 'Fire', 'Car fire in parking garage. Spreading rapidly. Other vehicles at risk.', 'report_uploads/evidence_25.svg', 'In Progress', '2026-03-20 21:55:00'),
(26, NULL, 'Gloria Estrada', 'gloria.estrada@email.com', 'Ambulance', 'Severe abdominal pain. Possible appendicitis. Patient very distressed.', 'report_uploads/evidence_26.svg', 'Resolved', '2026-03-20 22:30:00'),
(27, NULL, 'Benito Campos', 'benito.campos@email.com', 'Police', 'Vandalism at community center. Windows broken, graffiti present.', 'report_uploads/evidence_27.svg', 'Pending', '2026-03-20 23:05:00'),
(28, NULL, 'Natalia Fuentes', 'natalia.fuentes@email.com', 'Fire', 'Electrical panel fire in office building. Power cut to section.', 'report_uploads/evidence_28.svg', 'In Progress', '2026-03-20 23:40:00'),
(29, NULL, 'Leandro Mora', 'leandro.mora@email.com', 'Ambulance', 'Choking incident - child unable to breathe. Heimlich maneuver attempted.', 'report_uploads/evidence_29.svg', 'Resolved', '2026-03-21 00:15:00'),
(30, NULL, 'Valentina Blanco', 'valentina.blanco@email.com', 'Police', 'Suspicious package found near mall. Bomb squad called for inspection.', 'report_uploads/evidence_30.svg', 'Pending', '2026-03-21 00:50:00'),
(34, NULL, 'Jusuasd', 'consultajustine850@gmail.com', 'Ambulance', 'fas pilay pilay', 'uploads/69bd9a2a3b37c_logo-removebg-preview_imgupscaler.ai_Enhancer_2K.png', 'Pending', '2026-03-20 19:04:10'),
(36, NULL, 'sdsad', 'sad@asdas.com', 'Ambulance', 'safasdsa', 'uploads/reports/69bfe0286b64b_logo-removebg-preview_imgupscaler.ai_Enhancer_2K.png', 'Pending', '2026-03-22 12:27:20');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `firstName` varchar(50) DEFAULT NULL,
  `lastName` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstName`, `lastName`, `email`, `password`) VALUES
(1, 'mark ', 'rivera', 'aiahcamille01@gmail.com', 'Pogiako123');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
