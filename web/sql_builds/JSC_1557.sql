USE billing;

INSERT INTO billing.SERVICES ('', 'NCP2', 'eAdvantage - 2 months', '', '2', '0', '4000', '89.99', '4000', '89.99', '4000', '89.99', '4000', '89.99', '4000', '89.99', '4000', '89.99', '4000', '89.99', 'Y', NULL , 'PNCP2', 'N', '149', 'N', 'Y', 'Y', 'N', '');
INSERT INTO billing.PACK_COMPONENTS VALUES (NULL, 'PNCP2', 'C2');
INSERT INTO billing.PACK_COMPONENTS VALUES (NULL, 'PNCP2', 'T2');
INSERT INTO billing.PACK_COMPONENTS VALUES (NULL, 'PNCP2', 'R2');
INSERT INTO billing.COMPONENTS VALUES (NULL, 'C2', 'eAdvantage - 2 months', '', 2, 4000, 89.99, 'F,D,N', 'D', 0);
INSERT INTO billing.DIRECT_CALL_COUNT VALUES ('NCP2', 50);
UPDATE billing.SERVICES SET PRICE_RS=ROUND(PRICE_RS_TAX/(1+(15/100)),2); 