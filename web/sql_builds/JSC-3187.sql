use billing;

CREATE TABLE `billing.ExclusiveProposalMailer` (
  `RECEIVER` int(11) NOT NULL,
  `AGENT_NAME` varchar(50) NOT NULL,
  `AGENT_EMAIL` varchar(50) NOT NULL,
  `TUPLE_ID` varchar(50) DEFAULT NULL,
  `STATUS` enum('N','U','Y','I') DEFAULT 'N',
  `DATE` date NOT NULL,
  `FOLLOWUP_STATUS` varchar(2) DEFAULT 'F0',
  `AGENT_PHONE` varchar(250) NOT NULL,
  UNIQUE KEY `RECEIVER` (`RECEIVER`,`TUPLE_ID`,`DATE`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;