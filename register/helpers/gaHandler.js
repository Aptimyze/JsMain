import GA from '../../common/components/GA';
import {getItem} from '../services/localStorage';
export function errorGATracking(page, error) {
    let GAObject = new GA();
    // console.log('ga', "E", "jsms", "regErrorTracking" + page, error);
    GAObject.regTrackGA("E", "jsms", "regErrorTracking" + page, error);
    GAObject.regTrackGA("E", "jsms", "regErrorTracking_R" + page, error);
}

export function s6IncompleteHandeler() {
    let GAObject = new GA();
    if (getItem('incomplete') == 1) {
        // console.log('ga compreg', "P", `JSMS_REG_S6_Incomplete`);
        GAObject.regTrackGA("P", `JSMS_REG_S6_Incomplete`);
        GAObject.regTrackGA("P", `JSMS_REG_S6_Incomplete_R`);
    } else {
        // console.log('ga abtmefamily', "P", `JSMS_REG_S6`);
        GAObject.regTrackGA("P", `JSMS_REG_S6`);
        GAObject.regTrackGA("P", `JSMS_REG_S6_R`);
    }
}