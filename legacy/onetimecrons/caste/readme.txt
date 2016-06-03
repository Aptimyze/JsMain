STEPS TO POPULATE CASTE_REVAMP TABLE
1) populateCasteTable.php
2) modifyMerges.php
3) modifyDeletes.php
4) performSorting.php

STEPS TO POPULATE CASTE_GROUP_MAPPING TABLE
1) populateMappingTable.php

INPUT FILE FOR CASTE_REVAMP TABLE
1) hindu_castes.csv
2) sikh_castes.txt
3) caste_group.csv

INPUT FILE FOR MERGING DATA
1) merges.csv

INPUT FILE FOR DELETE DATA
1) delete.csv

INPUT FILE FOR MAPPING DATA
1) mapping.csv

STEPS TO POPULATE CASTE_GROUP_CACHE
1) displayCasteGroups.php

CASTE_GROUP_CACHE CREATE IN FILE NAMED = caste_group_mapping.php

STEPS TO MAKE UPDATES IN OVERALL TABLES
1) overall_updates.php (Call the required function with parameters)
2) update_functions.php (All functions are defined here)

INPUT FILE FOR OVERALL UPDATES FOR CASTE VALUES
1) overall_updates.csv

STEPS TO MAP NEW CASTE VALUES TO MTONGUES
1) overall_updates.php (run this file with filename as new_caste_mtongue_mapping.csv and call function update_caste_mtongue_mapping)

INPUT FILE TO MAP NEW CASTE VALUES TO MTONGUES
1) new_caste_mtongue_mapping.csv
