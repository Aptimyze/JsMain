USE newjs;
UPDATE `COMMUNITY_PAGES_MAPPING` set CONTENT = 'Baidyas are the worshippers of Goddess Kalika and according to the caste system, they are Bengali Hindus, who are originally Brahmins and are known for their exceptional skills in Ayurveda. The ancestors of the Baidya community were originally the physicians and it is an intellect community of West Bengal. The Baidya matrimonial follows the pure Bengali rituals and customs. In the Baidya matrimony, the weddings are arranged within the same caste, but not within the same gotra.' WHERE URL LIKE '%/bengali-baidya-matrimony-matrimonials%';
update  `COMMUNITY_PAGES_MAPPING` set MAPPED_VALUE='252' WHERE  `MAPPED_LABEL` LIKE '%Baidya%';
