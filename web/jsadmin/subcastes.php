<?php

include_once('connect.inc');

$db = connect_db();

// CASE 1: To ADD new Subcastes
if (
    isset($_REQUEST['new_subcaste']) 
    && isset($_REQUEST['div1'])
   ) {

  $new_subcaste = ucwords(trim(stripslashes($_REQUEST['new_subcaste'])));
 
  //Query whether spelling exists
  $sql_select = "SELECT SQL_CACHE SUBCASTE_ID, SORT_BY, LABEL FROM newjs.SUBCASTE_SPELLINGS_MAP WHERE SPELLING='" . $new_subcaste . "'";
  $result_select = mysql_query($sql_select) or logError("Error connecting to MySQL server and fetching already existing subcastes");
  
  //If no spelling for subcaste exists(The subcaste entered will also be a spelling for that subcaste)
  if (0 === mysql_num_rows($result_select)) {
    
    //Get the last subcaste_id value
    $sql_last = "SELECT SQL_CACHE SUBCASTE_ID FROM newjs.SUBCASTE_SPELLINGS_MAP ORDER BY SUBCASTE_ID DESC LIMIT 1";
    $result_last = mysql_query($sql_last) or logError("Error connecting to MySQL server and fetching last subcaste id");
    
    //There are more than 1 result
    if (0 < mysql_num_rows($result_last)) {
      
      $row = mysql_fetch_assoc($result_last);
      $curr_sub_id = ++$row['SUBCASTE_ID']; //increment the last subcaste_id by 1

      $default_related_caste_id = 0; //default related caste id is set as 0

      //Insert into spellings map with appropriate values
      $sql_insert1 = "INSERT INTO newjs.SUBCASTE_SPELLINGS_MAP (SELECT '" 
                      . strtoupper($new_subcaste) . "', '" 
                      . $new_subcaste . "', " 
                      . "max(SUBCASTE_ID) + 1, max(SORT_BY) + 1 FROM newjs.SUBCASTE_SPELLINGS_MAP)";
      $result_insert1 = mysql_query($sql_insert1) or logError("Error connecting to MySQL server and inserting new subcaste into SUBCASTE_SPELLINGS_MAP");
      
      //Select statement to ensure whether subcaste_id does not exist already in caste id map table
      $sql_select2 = "SELECT * FROM newjs.SUBCASTE_CASTE_ID_MAP WHERE SUBCASTE_ID='" 
                      . $curr_sub_id . "'";
      $result_select2 = mysql_query($sql_select2) or logError("Error connecting to MySQL server and retrieving values from SUBCASTE_CASTE_ID_MAP");
      
      //does not exist
      if (0 === mysql_num_rows($result_select2)) {
        $sql_insert2 = "INSERT INTO newjs.SUBCASTE_CASTE_ID_MAP VALUES('"
                        . $curr_sub_id . "', '"
                        . $default_related_caste_id . "')";
        $result_insert2 = mysql_query($sql_insert2) or logError("Error connecting to MySQL server and inserting into SUBCASTE_CASTE_ID_MAP");

        echo $new_subcaste . " subcaste added to database";
      }
      
    } else {
      die("Some problem occurred when retrieving last subcaste id");
    }
  } else {
    echo $new_subcaste . " already exists";
  }
} //CASE 1 ends

//CASE 2: To update Spelling Lists for a particular subcaste
else if (
            isset($_REQUEST['new_spelling']) 
            && isset($_REQUEST['for_subcaste']) 
            && isset($_REQUEST['div2'])
          ) {
  
  $for_subcaste = trim(stripslashes($_REQUEST['for_subcaste']));
  $new_spelling = trim(stripslashes($_REQUEST['new_spelling']));
  $flag = 0;
  $sort_by = 0;
  $subcaste_id = 0;
  
  //Query to check whether subcaste already exists
  $sql_select = "SELECT SQL_CACHE * FROM newjs.SUBCASTE_SPELLINGS_MAP WHERE LABEL='" 
                 . $for_subcaste . "'";
  $result_select = mysql_query($sql_select) or logError("Error connecting to MySQL server and fetching already existing Spellings");
  
  //perform insert when subcaste exists and the spelling doesn't
  if (0 !== mysql_num_rows($result_select)) {
    
    while ($row = mysql_fetch_assoc($result_select)) {
      
      if (strcasecmp(trim($row['SPELLING']), $new_spelling) != 0) {
        
        $subcaste_id = $row['SUBCASTE_ID'];
        $sort_by = $row['SORT_BY'];
        ++$flag; //set flag for indicating that insertion needs to be performed
        continue;
      } else {
        
        $flag = 0;
        break;
      }
    }
    if ($flag) {
      
      //insert into spellings map if the spelling doesn't exists
      $sql_insert = "INSERT INTO newjs.SUBCASTE_SPELLINGS_MAP VALUES('" 
        . strtoupper($new_spelling) . "', '" 
        . ucwords($for_subcaste) . "', '" 
        . $subcaste_id . "', '"
        . $sort_by . "')";
      $result_insert = mysql_query($sql_insert);
      
      echo $new_spelling . ' added to the list of spellings for subcaste ' . $for_subcaste;
      $flag = 0;
    } else {
      echo $new_spelling . ' spelling already exists for subcaste ' . $for_subcaste;
    }
  } else {
    echo $for_subcaste . " subcaste does not exist in our database. Please add it first.";
  }
} //CASE 2 ends

// CASE 3: ADD Related Caste ID for a subcaste
else if (
            isset($_REQUEST['add_related_caste_id']) 
            && isset($_REQUEST['for_subcaste']) 
            && isset($_REQUEST['div3_a'])
          ) {
  
  $for_subcaste = trim(stripslashes($_REQUEST['for_subcaste']));
  $add_related_caste_id = $_REQUEST['add_related_caste_id'];
  
  //Query to find the subcaste id for entered subcaste.
  $sql_select1 = "SELECT * FROM newjs.SUBCASTE_SPELLINGS_MAP WHERE SPELLING='"
                 . $for_subcaste . "'";
  $result_select1 = mysql_query($sql_select1) or logError("Error connecting to MySQL server: Add Related Caste ID");
  
  //subcaste exists in database
  if (0 < mysql_num_rows($result_select1)) {
    
    $row = mysql_fetch_assoc($result_select1);
    $subcaste_id = $row['SUBCASTE_ID'];
    
    //Query to check whether related caste id exists for subcaste id
    $sql_select2 = "SELECT * FROM newjs.SUBCASTE_CASTE_ID_MAP WHERE SUBCASTE_ID='"
                    . $subcaste_id
                    . "' AND RELATED_CASTE_ID='"
                    . $add_related_caste_id . "'";
    $result_select2 = mysql_query($sql_select2) or logError("Error connecting to MySQL server: Checking for duplicate entry in SUBCASTE_CASTE_ID_MAP");
    
    //no entry for related caste id for subcaste
    if (0 === mysql_num_rows($result_select2)) {
      //INSERT NOW
      $sql_insert = "INSERT INTO newjs.SUBCASTE_CASTE_ID_MAP VALUES('"
                     . $subcaste_id . "', '"
                     . $add_related_caste_id . "')";
      $result_insert = mysql_query($sql_insert) or logError("Error connecting to MySQL server: Inserting record in SUBCASTE_CASTE_ID_MAP");
      
      echo "Related caste ID " . $add_related_caste_id . " added for " . $for_subcaste;
    } else {
      echo "Related Caste ID: " . $add_related_caste_id . " for subcaste: " . $for_subcaste . " already exists";
    }
  } else {
    echo $for_subcaste . " subcaste does not exist in our database. Please add it first.";
  }
} //CASE 3 ends

//CASE 4: REMOVE Related Caste ID for a subcaste
else if (
            isset($_REQUEST['rm_related_caste_id']) 
            && isset($_REQUEST['for_subcaste']) 
            && isset($_REQUEST['div3_r'])
          ) {
  
  $for_subcaste = trim(stripslashes($_REQUEST['for_subcaste']));
  $remove_related_caste_id = $_REQUEST['rm_related_caste_id'];
  
  //Query to find the subcaste id for entered subcaste
  $sql_select1 = "SELECT * FROM newjs.SUBCASTE_SPELLINGS_MAP WHERE SPELLING='"
                  . $for_subcaste . "'";
  $result_select1 = mysql_query($sql_select1) or logError("Error connecting to MySQL server: Remove Related Caste ID");
  
  //subcaste exists
  if (0 < mysql_num_rows($result_select1)) {
    
    $row = mysql_fetch_assoc($result_select1);
    $subcaste_id = $row['SUBCASTE_ID'];
    
    //Query to check whether related caste id exists for subcaste
    $sql_select2 = "SELECT * FROM newjs.SUBCASTE_CASTE_ID_MAP WHERE SUBCASTE_ID='"
                    . $subcaste_id
                    . "' AND RELATED_CASTE_ID='"
                    . $remove_related_caste_id . "'";
    $result_select2 = mysql_query($sql_select2) or logError("Error connecting to MySQL server: Remove Related Caste ID, checking for existence");
    
    //Exactly one record
    if (1 === mysql_num_rows($result_select2)) {
      
      //Delete entry for related caste id for a subcaste id.
      $sql_delete = "DELETE FROM newjs.SUBCASTE_CASTE_ID_MAP WHERE SUBCASTE_ID='"
                     . $subcaste_id
                     . "' AND RELATED_CASTE_ID='"
                     . $remove_related_caste_id . "'";
      $result_delete = mysql_query($sql_delete) or logError("Error connecting to MySQL server: Remove Related Caste ID, error in deleting");
      
      echo "Related Caste ID: " . $remove_related_caste_id . " deleted for subcaste " . $for_subcaste;
    } else {
      echo "SUBCASTE_CASTE_ID_MAP: Unspecified number of rows in the output " . mysql_num_rows($result_select2);
    }
  } else {
    echo $for_subcaste . " subcaste does not exist in our databse. Please add it first.";
  }
} //CASE 4 ends

//CASE 5: Update Rank Order of a subcaste.
else if (
            isset($_REQUEST['div4']) 
            && isset($_REQUEST['for_subcaste']) 
            && isset($_REQUEST['update_order'])
          ) {
  
  $for_subcaste = trim(stripslashes($_REQUEST['for_subcaste']));
  $new_order = $_REQUEST['update_order'];
  
  //Query to check subcaste exists for entered subcaste
  $sql_select = "SELECT * FROM newjs.SUBCASTE_SPELLINGS_MAP WHERE SPELLING='"
                 . $for_subcaste . "'";
  $result_select = mysql_query($sql_select) or logError("Error connecting to MySQL server: Fetch subcaste rank order");
  
  //subcaste exists
  if (0 < mysql_num_rows($result_select)) {
    
    $row = mysql_fetch_assoc($result_select);
    $label = $row['LABEL'];
    
    //Query to find all subcastes >= new rank order so that they can be incremented by 1
    $sql_select2 = "SELECT * FROM newjs.SUBCASTE_SPELLINGS_MAP WHERE SORT_BY >='"
                    . $new_order . "'";
    $result_select2 = mysql_query($sql_select2) or logError("Error: Incrementing other SORT BYs to avoid confusion");

    //there are some rows.
    if (0 < mysql_num_rows($result_select2)) {
      while ($row1 = mysql_fetch_assoc($result_select2)) {
        
        //For each result in the result set.

        $new_rank = $row1['SORT_BY'] + 1; //increment current sort_by by 1
        $label1 = $row1['LABEL'];
        
        //Query to update rank order of subcastes having rank order >= new rank order
        $sql_update1 = "UPDATE newjs.SUBCASTE_SPELLINGS_MAP SET SORT_BY='"
                       . $new_rank 
                       . "' WHERE LABEL='"
                       . $label1 . "'";
        $result_update1 = mysql_query($sql_update1) or logError("Some crap you wrote");
      }
    }

    //Finally update the rank order of the subcaste in question
    $sql_update = "UPDATE newjs.SUBCASTE_SPELLINGS_MAP SET SORT_BY='"
                   . $new_order
                   . "' WHERE LABEL='"
                   . $label . "'";
    $result_update = mysql_query($sql_update) or logError("Error connecting to MySQL server: Update subcaste rank order");
    
    echo "New rank order for " . $for_subcaste . " set to " . $new_order;
  } else {
    echo $for_subcaste . " subcaste doesn't exist in our database. Please add it first";
  }
} //CASE 5 ends
