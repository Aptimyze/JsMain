use sugarcrm;

UPDATE leads_cstm SET source_c='7' WHERE source_c='3';

UPDATE leads_cstm SET source_c='9' WHERE source_c='8';

UPDATE leads_cstm SET source_c='9' WHERE source_c='11';

use sugarcrm_housekeeping;

UPDATE connected_leads_cstm SET source_c='7' WHERE source_c='3';

UPDATE connected_leads_cstm SET source_c='9' WHERE source_c='8';

UPDATE connected_leads_cstm SET source_c='9' WHERE source_c='11';

UPDATE inactive_leads_cstm SET source_c='7' WHERE source_c='3';

UPDATE inactive_leads_cstm SET source_c='9' WHERE source_c='8';

UPDATE inactive_leads_cstm SET source_c='9' WHERE source_c='11';
